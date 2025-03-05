<?php
require 'config.php'; // استدعاء إعدادات الاتصال

// استعلام لاسترجاع البيانات من الجدول
$query = "SELECT id, name, price FROM products LIMIT 10";
$stmt = $pdo->prepare($query);
$stmt->execute();
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>قائمة المنتجات</title>
    <style>
        body { font-family: Arial, sans-serif; direction: rtl; text-align: center; }
        table { width: 80%; margin: 20px auto; border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 10px; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>

    <h2>قائمة المنتجات</h2>

    <?php if (count($products) > 0): ?>
        <table>
            <tr>
                <th>المعرف</th>
                <th>اسم المنتج</th>
                <th>السعر</th>
            </tr>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?= htmlspecialchars($product['id']) ?></td>
                    <td><?= htmlspecialchars($product['name']) ?></td>
                    <td><?= htmlspecialchars($product['price']) ?> ريال</td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>لا توجد منتجات متاحة.</p>
    <?php endif; ?>

</body>
</html>
