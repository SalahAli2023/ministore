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
            $order->setStatus($order::STATUS_PAID);
            $this->logAction("Order {$order->getId()} payment successful via {$result['gateway']}");
        } else {
            $order->setStatus($order::STATUS_FAILED);
            $this->logAction("Order {$order->getId()} payment failed");
        }

        return $result;
    }
}