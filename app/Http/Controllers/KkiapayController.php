<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\KkiapayPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Kkiapay\Kkiapay;

class KkiapayController extends Controller
{
    protected function kkiapayClient(): Kkiapay
    {
        return new Kkiapay(
            config('services.kkiapay.public_key'),
            config('services.kkiapay.private_key'),
            config('services.kkiapay.secret_key'),
            config('services.kkiapay.sandbox', true)
        );
    }

public function page(Course $course)
{
    $prix = $course->offer_price ?? $course->regular_price;

    $paiement = KkiapayPayment::create([
        'user_id' => Auth::id(),
        'course_id' => $course->id,
        'montant' => $prix,
        'statut' => 'EN_ATTENTE',
    ]);

    return Inertia::render('Paiements/Page', [
        'course' => $course,
        'montant' => (int) $prix,
        'referenceInterne' => $paiement->reference_interne,
        'kkiapayPublicKey' => config('services.kkiapay.public_key'),
        'kkiapaySandbox' => (bool) config('services.kkiapay.sandbox', true),
    ]);
}

    public function confirmer(Request $request)
    {
        $valide = $request->validate([
            'transaction_id' => 'required|string',
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
            Log::error('Erreur de vérification Kkiapay', ['erreur' => $e->getMessage()]);
            return response()->json(['statut' => 'erreur', 'detail' => $e->getMessage()], 502);
        }

        $transactionArray = (array) $transaction;

        $montantAttendu = (int) $paiement->montant;
        $montantRecu = $transactionArray['amount'] ?? null;
        $statutKkiapay = $transactionArray['status'] ?? null;

        if ($statutKkiapay === 'SUCCESS' && $montantRecu == $montantAttendu) {
            $paiement->marquerReussi($transactionArray);

            \App\Models\Enrollment::firstOrCreate([
                'user_id' => Auth::id(),
                'course_id' => $paiement->course_id,
            ]);

            return response()->json(['statut' => 'succes']);
        }

        $paiement->marquerEchoue($transactionArray);
        return response()->json([
            'statut' => 'echec',
            'raison' => $transactionArray['reason'] ?? 'Montant ou statut invalide',
        ], 400);
    }

    public function webhook(Request $request)
    {
        $secretRecu = $request->header('x-kkiapay-secret', '');
        $secretAttendu = config('services.kkiapay.webhook_secret');

        if (!hash_equals($secretAttendu, $secretRecu)) {
            Log::warning('Webhook Kkiapay refusé : signature invalide');
            return response()->json(['erreur' => 'signature invalide'], 401);
        }

        $payload = $request->json()->all();
        $transactionId = $payload['transactionId'] ?? null;
        $event = $payload['event'] ?? null;

        if (!$transactionId) {
            return response()->json(['erreur' => 'transactionId manquant'], 400);
        }

        $paiement = KkiapayPayment::where('reference_kkiapay', $transactionId)->first();

        if (!$paiement) {
            Log::info('Webhook reçu pour une transaction inconnue', ['id' => $transactionId]);
            return response('OK', 200);
        }

        if ($event === 'transaction.success' && $paiement->statut !== 'REUSSI') {
            $paiement->marquerReussi($payload);
        } elseif ($event === 'transaction.failed') {
            $paiement->marquerEchoue($payload);
        }

        return response('OK', 200);
    }

    public function retour()
    {
        return Inertia::render('Paiements/Retour');
    }
}