<!DOCTYPE html>
<html>
<head>
    <title>MiniStore - Products</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <h1>Our Products</h1>
    
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Price</th>
            <th>Stock</th>
        </tr>
        <?php foreach ($products as $product): ?>
        <tr>
            <td><?= htmlspecialchars($product->getId()) ?></td>
            <td><?= htmlspecialchars($product->getName()) ?></td>
            <td>$<?= number_format($product->getPrice(), 2) ?></td>
            <td><?= $product->getStock() ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>