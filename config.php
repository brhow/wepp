<?php
// إعدادات الاتصال بقاعدة البيانات
$host = "sql311.infinityfree.com"; // عنوان السيرفر (الدومين أو الـ IP)
$dbname = "if0_38279630_brhopp"; // اسم قاعدة البيانات
$username = "if0_38279630"; // اسم المستخدم
$password = "Brho12345";
$port = 3306; // المنفذ (افتراضي 3306 لـ MySQL)

try {
    // إنشاء الاتصال باستخدام PDO
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, 
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    // جلب البيانات من الجدول
    $stmt = $pdo->query("SELECT id, name, price FROM products LIMIT 10");

    // عرض البيانات
    while ($row = $stmt->fetch()) {
        echo "ID: " . $row['id'] . "<br>";
        echo "الاسم: " . $row['name'] . "<br>";
        echo "السعر: " . $row['price'] . "<hr>";
    }

} catch (PDOException $e) {
    die("فشل الاتصال: " . $e->getMessage());
}
?>
