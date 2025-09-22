<?php
session_start();
require_once '../functions/db_connection.php';
require_once '../functions/auth.php';

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_unset(); 
    session_destroy(); 
    header('Location: ../authentication_login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $conn = getDbConnection();
    $ten_dang_nhap = filter_input(INPUT_POST, 'ten_dang_nhap', FILTER_DEFAULT) ?? '';
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

        switch ($user['vai_tro']) {
            case 'Admin':
                header('Location: ../views/admin_dashboard.php');
                break;
            case 'DoanTruong':
                header('Location: ../views/dashboard.php');
                break;
            case 'DoanVien':
                header('Location: ../views/doanvien_dashboard.php');
                break;
            default:
                $_SESSION['error'] = 'Vai trò không hợp lệ!';
                header('Location: ../authentication_login.php');
                break;
        }
        mysqli_close($conn);
        exit();
    }

    $_SESSION['error'] = 'Tên đăng nhập hoặc mật khẩu không đúng!';
    mysqli_close($conn);
    header('Location: ../authentication_login.php');
    exit();
}
?>