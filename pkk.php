<?php
// تحديد ملف النص
$file = 'brho.txt';

// إذا كان يوجد طلب لتعديل النص
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = $_POST['content'];
    // حفظ التعديلات في الملف
    file_put_contents($file, $content);
    
    // بعد حفظ التعديلات، إعادة التوجيه إلى الصفحة نفسها في وضع العرض
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// قراءة محتوى الملف
$content = file_get_contents($file);

// حالة التعديل (إذا كان المستخدم قد ضغط على زر التعديل)
$isEditing = isset($_GET['edit']) && $_GET['edit'] == 'true';

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>عرض وتعديل الملف</title>
    <style>
        .content {
            font-family: Arial, sans-serif;
            font-size: 16px;
            line-height: 1.6;
            white-space: pre-wrap; /* للحفاظ على تنسيق الأسطر */
        }
        textarea {
            width: 100%;
            height: 200px;
            font-size: 16px;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <h1>عرض وتعديل محتوى الملف</h1>

    <!-- إذا كان في وضع التعديل، نعرض textarea لتعديل النص -->
    <?php if ($isEditing): ?>
        <form method="POST">
            <textarea name="content"><?php echo htmlspecialchars($content); ?></textarea><br>
            <button type="submit">حفظ التعديلات</button>
        </form>
    <?php else: ?>
        <!-- عرض النص بشكل منسق إذا لم يكن في وضع التعديل -->
        <div class="content">
            <?php echo nl2br(htmlspecialchars($content)); ?>
        </div>
        <!-- زر التعديل لتحويل الصفحة إلى وضع التعديل -->
        <a href="?edit=true"><button>تعديل النص</button></a>
    <?php endif; ?>
</body>
</html>