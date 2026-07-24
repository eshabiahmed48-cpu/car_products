<?php
// register.php - معالجة إنشاء حساب جديد مع تصحيح الأخطاء
require_once 'config.php';
require_once 'session.php';
redirectIfLoggedIn();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';
    $phone = trim($_POST['phone'] ?? '');
    $errors = [];

    // التحقق من صحة المدخلات
    if (strlen($fullname) < 3) $errors[] = 'الاسم يجب أن يكون 3 أحرف على الأقل';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'بريد إلكتروني غير صحيح';
    if (strlen($password) < 6) $errors[] = 'كلمة المرور يجب أن تكون 6 أحرف على الأقل';
    if ($password !== $confirm) $errors[] = 'كلمة المرور غير متطابقة';
    if ($phone && !preg_match('/^[0-9+\-\s]+$/', $phone)) $errors[] = 'رقم الهاتف غير صحيح';

    if (empty($errors)) {
        // التحقق من عدم تكرار البريد
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors[] = 'البريد الإلكتروني مسجل مسبقاً';
        }
    }

    if (!empty($errors)) {
        $_SESSION['register_errors'] = $errors;
        header('Location: index.php');
        exit;
    }

    // تشفير كلمة المرور
    $hashed = password_hash($password, PASSWORD_DEFAULT);

    // إدراج المستخدم مع التحقق من الأخطاء
    try {
        $stmt = $pdo->prepare("INSERT INTO users (fullname, email, password, phone) VALUES (?, ?, ?, ?)");
        $result = $stmt->execute([$fullname, $email, $hashed, $phone]);

        if ($result) {
            $user_id = $pdo->lastInsertId();
            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_fullname'] = $fullname;
            header('Location: home.php');
            exit;
        } else {
            // إذا فشل التنفيذ بدون استثناء
            $_SESSION['register_errors'] = ['حدث خطأ غير معروف أثناء التسجيل'];
            header('Location: index.php');
            exit;
        }
    } catch (PDOException $e) {
        // عرض الخطأ للمطور (يمكنك إخفاؤه في الإنتاج)
        die('خطأ في قاعدة البيانات: ' . $e->getMessage());
    }
} else {
    header('Location: index.php');
    exit;
}
?>