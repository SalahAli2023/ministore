<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/config.php';
use MiniStore\Modules\Users\{Admin,Customer};
use MiniStore\Modules\Payments\{Paypal, Stripe};

require_once 'src/Modules/Core/Config.php';
require_once 'src/Modules/Core/Logger.php';

// Autoload classes
// spl_autoload_register(function ($class) {
//     $file = str_replace('\\', '/', $class) . '.php';
    
//     if (file_exists($file)) {
//         require $file;
//     }
// });

// spl_autoload_register(function ($class) {
    
//     if (file_exists($file)) {
//         require $file;
//     } else {
//         // throw new Exception("Class {$class} not found in {$file}");
//     }
// });

// Load configuration
\MiniStore\Modules\Core\Config::load(__DIR__.'/config.php');

// Create products
$product1 = new \MiniStore\Modules\Products\Product(1, 'Laptop', 999.99, 10, 'High performance laptop');
$product2 = new \MiniStore\Modules\Products\Product(2, 'Smartphone', 599.99, 20, 'Latest model smartphone');
$product3 = new \src\Modules\Products\Product(3, 'Headphones', 99.99, 50, 'Noise cancelling headphones');

// Create a customer
$customer = new \MiniStore\Modules\Users\Customer(
    1, 
    'John Doe', 
    'john@example.com', 
    password_hash('password123', PASSWORD_BCRYPT),
    '123 Main St, City',
    '1234567890'
);

// Create an order
$order = new \MiniStore\Modules\Orders\Order(1001, $customer);
$order->addProduct($product1, 1);
$order->addProduct($product2, 2);
$order->addProduct($product3, 1);

// Calculate total with discount
$total = $order->calculateTotal(true);
echo "Order Total: $" . number_format($total, 2) . "\n";

// Process payment
$paypal = new \MiniStore\Modules\Payments\PayPal('merchant@example.com', 'password');
$paymentProcessor = new \MiniStore\Modules\Payments\PaymentProcessor($paypal);
$paymentResult = $paymentProcessor->processOrderPayment($order);

echo "Payment Result:\n";
print_r($paymentResult);

// Display order status
echo "Order Status: " . $order->getStatus() . "\n";

// Create an admin (just for demonstration)
$admin = new \MiniStore\Modules\Users\Admin(
    1,
    'Admin User',
    'admin@example.com',
    password_hash('admin123', PASSWORD_BCRYPT),
    3
);

echo "Admin Role: " . $admin->getRole() . "\n";