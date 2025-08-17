<?php
namespace MiniStore\Modules\Payments;

use MiniStore\Traits\Loggable;

class Stripe implements PaymentGateway
{
    use Loggable;

    private $apiKey;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function processPayment($amount)
    {
        // Simulate payment processing
        $transactionId = 'STRIPE-' . uniqid();
        $this->logAction("Stripe payment processed: $transactionId for amount $amount");
        
        return [
            'gateway' => 'Stripe',            
            'transaction_id' => $transactionId,
            'amount' => $amount,
            'success' => true,
        ];
    }

    public function verifyPayment($transactionId)
    {
        // Simulate payment verification
        $this->logAction("Stripe payment verified: $transactionId");
        return strpos($transactionId, 'STRIPE-') === 0;
    }
}