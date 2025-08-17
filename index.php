<?php
require __DIR__ . '/vendor/autoload.php';

use MiniStore\Modules\Core\Config;
use MiniStore\Modules\Products\Product;
use MiniStore\Modules\Users\Customer;
use MiniStore\Modules\Users\Admin;
use MiniStore\Modules\Orders\Order;
use MiniStore\Modules\Payments\PayPal;
use MiniStore\Modules\Payments\Stripe;
use MiniStore\Modules\Payments\PaymentProcessor;

Config::load(__DIR__.'/config.php');

// Create products
$product1 = new Product(1, 'Laptop', 999.99, 10, 'High performance laptop');
$product2 = new Product(2, 'Smartphone', 697.99, 20, 'Latest model smartphone');
$product3 = new Product(3, 'Headphones', 85.99, 50, 'Noise cancelling headphones');

// Create a customer
$customer1 = new Customer(1, 'Salah Ali', 'salah@gmail.com', password_hash('123456', PASSWORD_BCRYPT), '123 Main St, City','1234567890');
// $customer2 = new Customer(2,'Sami Ali', 'sami@gmail.com', password_hash('password123', PASSWORD_BCRYPT),'123 Main St, City','1234567890');

// Create an order
$order = new Order(1001, $customer1);
$order->addProduct($product1, 1);
$order->addProduct($product2, 2);
$order->addProduct($product3, 1);

// Calculate total with discount
$total = $order->calculateTotal(true);
// echo "Order Total: $" . number_format($total, 2) . "<br>";


// Choose a payment gateway (polymorphism)
$gatewayName = $_GET['gateway'] ?? 'stripe';
$gateway = $gatewayName === 'stripe' ? new Stripe('32453334') : new PayPal('merchant@gmail.com', 'password');

// Process payment
$paymentProcessor = new PaymentProcessor($gateway);
$paymentResult = $paymentProcessor->processOrderPayment($order);

$method=[

    $gatewayName =>$paymentProcessor
];

$results=[];

foreach ($method as $methodName => $processor) {
    $results[$methodName] = $processor->processOrderPayment($order);
}

// Create an admin (just for demonstration)
$admin = new Admin(1,'Admin User','admin@gmail.com',password_hash('admin123', PASSWORD_BCRYPT),3);


$totals= $order->returnCalculateTotal(true);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>MiniStore Demo</title>
    <link rel="stylesheet" href="public/css/main.css">
</head>
<body>
    <main class="container">
        <h1>MiniStore - PHP OOP Demo</h1>
        
        <!-- Customer section -->
        <section class="box">
            <h2>Customer</h2>
            <p><strong>Name:</strong> <?= htmlspecialchars($customer1->getName()) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($customer1->getEmail()) ?></p>
            <p><strong>Admin Role:</strong> <?= htmlspecialchars($admin->getRole()) ?></p>
        </section>

        <!-- Products section -->
        <section class="box">
            <h2>Products (Current Stock)</h2>
            <ul>
                <li><?= htmlspecialchars($product1->getName()) ?> — Stock: <?= $product1->getStock(); ?></li>
                <li><?= htmlspecialchars($product2->getName()) ?> — Stock: <?= $product2->getStock(); ?></li>
                <li><?= htmlspecialchars($product3->getName()) ?> — Stock: <?= $product3->getStock(); ?></li>
            </ul>
        </section>
    
        <!-- Order section -->
        <section class="box">
            <h2>Order #<?= $order->getId(); ?> <small class="status <?= strtolower($order->getStatus()); ?>">(<?= $order->getStatus(); ?>)</small></h2>
            <table>
                <thead>
                    <tr><th>Product</th><th>Qty</th><th>Unit Price</th><th> Total</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($order->getProducts() as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['product']->getName()); ?></td>
                        <td><?= $item['quantity']; ?></td>
                        <td><?= number_format($item['product']->getPrice(), 2) . 'USD'; ?></td>
                        <td><?= number_format($item['product']->getPrice() * $item['quantity'], 2) . 'USD'; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr><th colspan="3" class="right">Total Before Tax & Discount</th><td><?= number_format($totals['totalBeforeTaxDiscount'], 2) . 'USD'; ?></td></tr>
                    <tr><th colspan="3" class="right">Discount (<?= $totals['discount_percent']; ?>%)</th><td><?= number_format($totals['discount_value'], 2) . ' USD'; ?></td></tr>
                    <tr><th colspan="3" class="right">Total After Discount (<?= $totals['discount_percent']; ?>%)</th><td><?= number_format($totals['totalAfterDiscount'], 2) . ' USD'; ?></td></tr>
                    <tr><th colspan="3" class="right">Tax (<?= $totals['tax_rate'] * 100; ?>%)</th><td><?= number_format($totals['tax'], 2) . ' USD' ; ?></td></tr>
                    <tr><th colspan="3" class="right">Total</th><td><strong><?= number_format($totals['total'], 2) . 'USD'; ?></strong></td></tr>
                </tfoot>
            </table>
        </section>

        <!-- Payment section -->
        <section class="box">
            <h2>Payment Result:</h2>
            <?=print_r($paymentResult);?> 
            <p>Order Status:<?= htmlspecialchars($order->getStatus()) ?></p>
            <table>
                <thead>
                    <tr>
                        <th>Payment Way</th>
                        <th>Process No</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $method => $result): ?>
                    <tr> 
                        <td><?= htmlspecialchars($method) ?></td>
                        <td><?= htmlspecialchars($result['transaction_id']) ?></td>
                        <td><?= number_format($result['amount'], 2) ?> USD</td>
                        <td class="<?= $result['success'] ? 'success' : 'error' ?>">
                            <?= $result['success'] ? 'success' : 'Failed' ?>
                        </td>
                        <td><?= date('Y-m-d H:i:s') ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table> 

            <div class="actions">
                <a href="?gateway=paypal" class="btn">Pay with PayPal</a>  /
                <a href="?gateway=stripe" class="btn">Pay with Stripe</a>
            </div>
            <br>
            <div class="alert ">Payment successful via <?= htmlspecialchars($gatewayName); ?>!</div>
        
        </section>
    </main>
</body>
</html>