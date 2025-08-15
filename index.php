<?php
require __DIR__ . '/vendor/autoload.php';

use MiniStore\Modules\Core\Config;
use MiniStore\Modules\Products\Product;
use MiniStore\Modules\Users\Customer;
use MiniStore\Modules\Users\Admin;
use MiniStore\Modules\Orders\Order;
use MiniStore\Modules\Payments\PayPal;
use MiniStore\Modules\Payments\PaymentProcessor;

Config::load(__DIR__.'/config.php');

// Create products
$product1 = new Product(1, 'Laptop', 999.99, 10, 'High performance laptop');
$product2 = new Product(2, 'Smartphone', 697.99, 20, 'Latest model smartphone');
$product3 = new Product(3, 'Headphones', 85.99, 50, 'Noise cancelling headphones');

// Create a customer
$customer1 = new Customer(1, 'Salah Ali', 'salah@gmail.com', password_hash('123456', PASSWORD_BCRYPT), '123 Main St, City','1234567890');
// $customer2 = new Customer(2,'Sami Ali', 'sami@gmail.com', password_hash('password123', PASSWORD_BCRYPT),'123 Main St, City','1234567890');

echo "<h1>Welcome to MiniStore</h1>";
echo "<h3>Products:</h3>";
foreach ([$product1, $product2,$product3] as $product) {
    echo "<p>{$product->getName()} - {$product->getPrice()} USD</p>";
}

// Create an order
$order = new Order(1001, $customer1);
$order->addProduct($product1, 1);
$order->addProduct($product2, 2);
$order->addProduct($product3, 1);

// Calculate total with discount
$total = $order->calculateTotal(true);
echo "Order Total: $" . number_format($total, 2) . "<br>";

// Process payment
$paypal = new PayPal('merchant@gmail.com', 'password');
$paymentProcessor = new PaymentProcessor($paypal);
$paymentResult = $paymentProcessor->processOrderPayment($order);

echo "Payment Result:<br>";
print_r($paymentResult);

// Display order status
echo "Order Status: " . $order->getStatus() . "<br>";

// Create an admin (just for demonstration)
$admin = new Admin(1,'Admin User','admin@gmail.com',password_hash('admin123', PASSWORD_BCRYPT),3);

echo "Admin Role: " . $admin->getRole() . "<br>";