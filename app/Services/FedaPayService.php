<?php

namespace App\Services;

use FedaPay\FedaPay;
use FedaPay\Transaction;

class FedaPayService
{
    public function __construct()
    {
        FedaPay::setApiKey(config('services.fedapay.secret_key'));
        FedaPay::setEnvironment(config('services.fedapay.sandbox') ? 'sandbox' : 'live');
    }

    public function createTransaction(array $data): Transaction
    {
        return Transaction::create([
            'description'  => $data['description'],
            'amount'       => $data['amount'], // en XOF, entier
            'currency'     => ['iso' => 'XOF'],
            'callback_url' => route('fedapay.callback'),
            'customer'     => [
                'firstname' => $data['firstname'] ?? '',
                'lastname'  => $data['lastname'] ?? '',
                'email'     => $data['email'] ?? '',
            ],
        ]);
    }

    public function generatePaymentUrl(Transaction $transaction): string
    {
        $token = $transaction->generateToken();
        return $token->url;
    }
}