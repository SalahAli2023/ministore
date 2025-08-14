<?php
namespace MiniStore\Modules\Payments;

use MiniStore\Modules\Orders\Order;
use MiniStore\Traits\Loggable;

class PaymentProcessor
{
    use Loggable;

    private $paymentGateway;

    public function __construct(PaymentGateway $paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;
    }

    public function processOrderPayment(Order $order)
    {
        $total = $order->calculateTotal(true); // Apply discount
        $result = $this->paymentGateway->processPayment($total);
        
        if ($result['success']) {
            $order->setStatus('paid');
            $this->logAction("Order {$order->getId()} payment successful via {$result['gateway']}");
        } else {
            $order->setStatus('payment_failed');
            $this->logAction("Order {$order->getId()} payment failed");
        }

        return $result;
    }
}