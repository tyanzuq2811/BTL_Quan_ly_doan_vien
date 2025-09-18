<?php
session_start();
require_once '../functions/db_connection.php';
require_once '../functions/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $conn = getDbConnection();
    $ten_dang_nhap = $_POST['ten_dang_nhap'] ?? '';
    $mat_khau = $_POST['mat_khau'] ?? '';

    if (empty($ten_dang_nhap) || empty($mat_khau)) {
        $_SESSION['error'] = 'Vui lòng nhập đầy đủ tên đăng nhập và mật khẩu!';
        header('Location: ../authentication_login.php');
        exit();
    }

    $user = authenticateUser($conn, $ten_dang_nhap, $mat_khau);
    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['ten_dang_nhap'] = $user['ten_dang_nhap'];
        $_SESSION['vai_tro'] = $user['vai_tro'];
        $_SESSION['success'] = 'Đăng nhập thành công!';
        mysqli_close($conn);
        header('Location: ../views/dashboard.php');
        exit();
    }

    $_SESSION['error'] = 'Tên đăng nhập hoặc mật khẩu không đúng!';
    mysqli_close($conn);
    header('Location: ../authentication_login.php');
    exit();
}
?>