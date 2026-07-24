<?php
// login.php - معالجة تسجيل الدخول
require_once 'config.php';
require_once 'session.php';
redirectIfLoggedIn();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $_SESSION['login_error'] = 'الرجاء ملء جميع الحقول';
        header('Location: index.php');
        exit;
    }

    $stmt = $pdo->prepare("SELECT id, fullname, password, status FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        if ($user['status'] === 'inactive') {
            $_SESSION['login_error'] = 'الحساب معطل، يرجى التواصل مع الدعم';
            header('Location: index.php');
            exit;
        }
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_fullname'] = $user['fullname'];
        header('Location: home.php');
        exit;
    } else {
        $_SESSION['login_error'] = 'البريد الإلكتروني أو كلمة المرور غير صحيحة';
        header('Location: index.php');
        exit;
    }
} else {
    header('Location: index.php');
    exit;
}
?>