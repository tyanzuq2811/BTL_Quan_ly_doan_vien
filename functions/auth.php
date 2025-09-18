<?php
/**
 * Hàm kiểm tra xem user đã đăng nhập chưa
 * Nếu chưa đăng nhập, chuyển hướng về trang login
 * 
 * @param string $redirectPath Đường dẫn để chuyển hướng về trang login
 */
function checkLogin($redirectPath = '../authentication_login.php') {
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
 * Hàm đăng xuất user
 * Xóa tất cả session và chuyển hướng về trang login
 * 
 * @param string $redirectPath Đường dẫn để chuyển hướng sau khi logout
 */
function logout($redirectPath = '../authentication_login.php') {
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
 * Hàm lấy thông tin user hiện tại
 * 
 * @return array|null Trả về thông tin user nếu đã đăng nhập, null nếu chưa
 */
function getCurrentUser() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    if (isset($_SESSION['user_id']) && isset($_SESSION['ten_dang_nhap'])) {
        return [
            'id' => $_SESSION['user_id'],
            'ten_dang_nhap' => $_SESSION['ten_dang_nhap'],
            'vai_tro' => $_SESSION['vai_tro'] ?? null
        ];
    }
    
    return null;
}

/**
 * Hàm kiểm tra xem user đã đăng nhập chưa (không redirect)
 * 
 * @return bool True nếu đã đăng nhập, False nếu chưa
 */
function isLoggedIn() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    return isset($_SESSION['user_id']) && isset($_SESSION['ten_dang_nhap']);
}

/**
 * Hàm xác thực đăng nhập
 * @param mysqli $conn
 * @param string $ten_dang_nhap
 * @param string $mat_khau
 * @return array|false Trả về thông tin user nếu đúng, false nếu sai
 */
function authenticateUser($conn, $ten_dang_nhap, $mat_khau) {
    $sql = "SELECT id, ten_dang_nhap, mat_khau, vai_tro 
            FROM tai_khoan 
            WHERE ten_dang_nhap = ? AND trang_thai = 'Hoạt động' 
            LIMIT 1";
    
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) return false;

    mysqli_stmt_bind_param($stmt, "s", $ten_dang_nhap);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        // ✅ So sánh mật khẩu thường (plain text)
        if ($mat_khau === $user['mat_khau']) {
            mysqli_stmt_close($stmt);
            return $user;
        }
    }

    mysqli_stmt_close($stmt);
    return false;
}
?>
