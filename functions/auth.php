<?php
/**
 * Kiểm tra user đã đăng nhập chưa
 */
function checkLogin($redirectPath = '../index.php') {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id']) || !isset($_SESSION['ten_dang_nhap'])) {
        $_SESSION['error'] = 'Bạn cần đăng nhập để truy cập trang này!';
        header('Location: ' . $redirectPath);
        exit();
    }
}

/**
 * Đăng xuất
 */
function logout($redirectPath = '../index.php') {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    session_unset();
    session_destroy();

    session_start();
    $_SESSION['success'] = 'Đăng xuất thành công!';

    header('Location: ' . $redirectPath);
    exit();
}

/**
 * Lấy user hiện tại
 */
function getCurrentUser() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (isset($_SESSION['user_id']) && isset($_SESSION['ten_dang_nhap'])) {
        return [
            'id' => $_SESSION['user_id'],
            'ten_dang_nhap' => $_SESSION['ten_dang_nhap']
        ];
    }

    return null;
}

/**
 * Kiểm tra login (trả về true/false)
 */
function isLoggedIn() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    return isset($_SESSION['user_id']) && isset($_SESSION['ten_dang_nhap']);
}

/**
 * Xác thực user (không dùng password_hash)
 */
function authenticateUser($conn, $ten_dang_nhap, $mat_khau) {
    $sql = "SELECT id, ten_dang_nhap 
            FROM tai_khoan 
            WHERE ten_dang_nhap = ? 
              AND mat_khau = SHA2(?, 256)
            LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) return false;

    mysqli_stmt_bind_param($stmt, "ss", $ten_dang_nhap, $mat_khau);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
        return $user;
    }

    if ($stmt) mysqli_stmt_close($stmt);
    return false;
}

