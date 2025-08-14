<?php
namespace MiniStore\Modules\Payments;

interface PaymentGateway
{
    public function processPayment($amount);
    public function verifyPayment($transactionId);
}