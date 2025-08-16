<!DOCTYPE html>
<html>
<head>
    <title>MiniStore - Orders</title>
    <link rel="stylesheet" href="/css/main.css">
</head>
<body>
    <h1>Your Orders</h1>
    
    <div>
        <h2>Order #<?= $order->getId() ?></h2>
        <p>Date: <?= $order->getCreatedAt()->format('Y-m-d H:i:s') ?></p>
        <p>Status: <?= $order->getStatus() ?></p>
        <p>Total: $<?= number_format($order->getTotal(), 2) ?></p>
        
        <h3>Products:</h3>
        <ul>
            <?php foreach ($order->getProducts() as $item): ?>
            <li>
                <?= $item['quantity'] ?> x <?= htmlspecialchars($item['product']->getName()) ?>
                ($<?= number_format($item['product']->getPrice(), 2) ?> each)
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>