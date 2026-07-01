<?php
namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CoursePayment;
use App\Models\Enrollment;
use App\Models\KkiapayPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Kkiapay\Kkiapay;

class PaymentController extends Controller
{
    const SUPER_ADMIN_MOMO = '0159290652';
    const ADMIN_MOMO       = '0149518565';

    protected function kkiapayClient(): Kkiapay
    {
        return new Kkiapay(
            config('services.kkiapay.public_key'),
            config('services.kkiapay.private_key'),
            config('services.kkiapay.secret_key'),
            config('services.kkiapay.sandbox', true)
        );
    }

    /**
     * Page de paiement Kkiapay pour un cours
     */
public function show(Course $course)
{
    $user = Auth::user();

    $enrolled = Enrollment::where('user_id', $user->id)
        ->where('course_id', $course->id)->first();
    if ($enrolled) {
        return redirect()->route('course.display', $course->slug)
            ->with('success', 'Vous êtes déjà inscrit à ce cours !');
    }

    $amount = $course->offer_price ?? $course->regular_price;

    $paiement = KkiapayPayment::create([
        'user_id'   => $user->id,
        'course_id' => $course->id,
        'montant'   => $amount,
        'statut'    => 'EN_ATTENTE',
    ]);

    return view('student.payment_kkiapay', [
        'course'           => $course,
        'montant'          => (int) $amount,
        'referenceInterne' => $paiement->reference_interne,
        'kkiapayPublicKey' => config('services.kkiapay.public_key'),
        'kkiapaySandbox'   => config('services.kkiapay.sandbox', true) ? 'true' : 'false',
    ]);
}

    /**
     * Vérification du paiement Kkiapay (appelée par le frontend React)
     */
   public function store(Request $request, Course $course)
{
    $valide = $request->validate([
        'transaction_id'    => 'required|string',
        'reference_interne' => 'required|uuid',
    ]);

    $paiement = KkiapayPayment::where('reference_interne', $valide['reference_interne'])
        ->where('user_id', Auth::id())
        ->firstOrFail();

    if ($paiement->statut === 'REUSSI') {
        return response()->json(['statut' => 'deja_confirme']);
    }

    try {
        $transaction = $this->kkiapayClient()->verifyTransaction($valide['transaction_id']);
    } catch (\Exception $e) {
        Log::error('Erreur vérification Kkiapay', ['erreur' => $e->getMessage()]);
        return response()->json(['statut' => 'erreur', 'detail' => $e->getMessage()], 502);
    }

    $t = (array) $transaction;
    $montantAttendu = (int) $paiement->montant;
    $montantRecu    = $t['amount'] ?? null;
    $statutKkiapay  = $t['status'] ?? null;

    if ($statutKkiapay === 'SUCCESS' && $montantRecu == $montantAttendu) {
        $paiement->marquerReussi($t);

        $split = CoursePayment::calculateSplit($paiement->montant);
        CoursePayment::create([
            'user_id'            => Auth::id(),
            'course_id'          => $paiement->course_id,
            'amount'             => $paiement->montant,
            'amount_super_admin' => $split['amount_super_admin'],
            'amount_professor'   => $split['amount_professor'],
            'amount_admin'       => $split['amount_admin'],
            'payment_method'     => 'kkiapay',
            'momo_number'        => $paiement->numero_telephone ?? '',
            'receipt_file'       => '',
            'status'             => 'validated',
        ]);

        Enrollment::firstOrCreate([
            'user_id'   => Auth::id(),
            'course_id' => $paiement->course_id,
        ], ['status' => 1]);

        return response()->json(['statut' => 'succes']);
    }

    $paiement->marquerEchoue($t);
    return response()->json([
        'statut' => 'echec',
        'raison' => $t['reason'] ?? 'Montant ou statut invalide',
    ], 400);
}


/**
 * Initie un paiement FedaPay et redirige vers leur page de paiement
 */
public function fedapayInitier(Request $request, Course $course)
{
    $user   = Auth::user();
    $amount = $course->offer_price ?? $course->regular_price;

    $paiement = KkiapayPayment::create([
        'user_id'   => $user->id,
        'course_id' => $course->id,
        'montant'   => $amount,
        'statut'    => 'EN_ATTENTE',
    ]);

    \FedaPay\FedaPay::setApiKey(config('services.fedapay.secret_key'));
    \FedaPay\FedaPay::setEnvironment(
        config('services.fedapay.sandbox') ? 'sandbox' : 'live'
    );

    $transaction = \FedaPay\Transaction::create([
        'description' => 'Paiement du cours : ' . $course->title,
        'amount'      => (int) $amount,
        'currency'    => ['iso' => 'XOF'],
        'callback_url' => route('payment.fedapay.callback', [
            'course'    => $course->id,
            'reference' => $paiement->reference_interne,
        ]),
        'customer' => [
            'firstname' => $user->firstname ?? $user->name ?? 'Apprenant',
            'lastname'  => $user->lastname ?? '',
            'email'     => $user->email,
        ],
    ]);

    $token = $transaction->generateToken();

    return redirect($token->url);
}

/**
 * Callback FedaPay — appelé après le paiement (succès ou échec)
 */
public function fedapayCallback(Request $request, Course $course)
{
    $referenceInterne = $request->query('reference');
    $transactionId    = $request->query('id');

    $paiement = KkiapayPayment::where('reference_interne', $referenceInterne)
        ->firstOrFail();

    if (!$transactionId) {
        return redirect()->route('course.display', $course->slug)
            ->with('error', 'Paiement annulé ou échoué.');
    }

    \FedaPay\FedaPay::setApiKey(config('services.fedapay.secret_key'));
    \FedaPay\FedaPay::setEnvironment(
        config('services.fedapay.sandbox') ? 'sandbox' : 'live'
    );

    try {
        $transaction = \FedaPay\Transaction::retrieve($transactionId);
    } catch (\Exception $e) {
        Log::error('Erreur FedaPay callback', ['erreur' => $e->getMessage()]);
        return redirect()->route('course.display', $course->slug)
            ->with('error', 'Erreur lors de la vérification du paiement.');
    }

    if ($transaction->status === 'approved' && $paiement->statut !== 'REUSSI') {
        $transactionArray = [
            'transactionId' => (string) $transaction->id,
            'status'        => 'SUCCESS',
            'amount'        => $transaction->amount,
            'source'        => 'fedapay',
        ];

        $paiement->marquerReussi($transactionArray);

        $split = CoursePayment::calculateSplit($paiement->montant);
        CoursePayment::create([
            'user_id'            => $paiement->user_id,
            'course_id'          => $paiement->course_id,
            'amount'             => $paiement->montant,
            'amount_super_admin' => $split['amount_super_admin'],
            'amount_professor'   => $split['amount_professor'],
            'amount_admin'       => $split['amount_admin'],
            'payment_method'     => 'fedapay',
            'momo_number'        => '',
            'receipt_file'       => '',
            'status'             => 'validated',
        ]);

        Enrollment::firstOrCreate([
            'user_id'   => $paiement->user_id,
            'course_id' => $paiement->course_id,
        ], ['status' => 1]);

        return redirect()->route('course.display', $course->slug)
            ->with('success', 'Paiement FedaPay confirmé ! Accès au cours débloqué.');
    }

    return redirect()->route('course.display', $course->slug)
        ->with('error', 'Paiement non complété. Statut : ' . $transaction->status);
}

    /**
     * Page de retour après paiement
     */
    public function retour()
    {
        return Inertia::render('Paiements/Retour');
    }

    /**
     * [ADMIN] Liste des paiements — inchangé
     */
    public function index()
    {
        $payments = CoursePayment::with(['user', 'course', 'course.user'])
            ->latest()->get();
        return view('admin.payment.index', compact('payments'));
    }

    /**
     * [ADMIN] Approuver un paiement — inchangé
     */
    public function approuver(Request $request, CoursePayment $payment)
    {
        Enrollment::firstOrCreate([
            'user_id'   => $payment->user_id,
            'course_id' => $payment->course_id,
        ], ['status' => 1]);

        $payment->update([
            'status'       => 'validated',
            'validated_at' => now(),
            'admin_note'   => $request->admin_note,
        ]);

        return redirect()->route('payments.index')
            ->with('success', 'Paiement validé ! L\'apprenant a accès au cours.');
    }

    /**
     * [ADMIN] Rejeter un paiement — inchangé
     */
    public function reject(Request $request, CoursePayment $payment)
    {
        $payment->update([
            'status'     => 'rejected',
            'admin_note' => $request->admin_note ?? 'Paiement rejeté.',
        ]);

        return redirect()->route('payments.index')
            ->with('success', 'Paiement rejeté.');
    }
}