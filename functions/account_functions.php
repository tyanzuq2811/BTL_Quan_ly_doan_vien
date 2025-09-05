<?php
require_once 'db_connection.php';

/**
 * Thêm tài khoản
 */
function addAccount($doan_vien_id, $ten_dang_nhap, $mat_khau, $vai_tro = 'DoanVien', $trang_thai = 'Hoạt động') {
    $conn = getDbConnection();
    $sql = "INSERT INTO tai_khoan (doan_vien_id, ten_dang_nhap, mat_khau, vai_tro, trang_thai) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        $mat_khau_hashed = password_hash($mat_khau, PASSWORD_DEFAULT);
        mysqli_stmt_bind_param($stmt, "issss", $doan_vien_id, $ten_dang_nhap, $mat_khau_hashed, $vai_tro, $trang_thai);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return $success;
    }
    mysqli_close($conn);
    return false;
}

/**
 * Cập nhật tài khoản
 */
function updateAccount($id, $doan_vien_id, $ten_dang_nhap, $mat_khau, $vai_tro = 'DoanVien', $trang_thai = 'Hoạt động') {
    $conn = getDbConnection();
    $sql = "UPDATE tai_khoan 
            SET doan_vien_id = ?, ten_dang_nhap = ?, mat_khau = ?, vai_tro = ?, trang_thai = ? 
            WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        $mat_khau_hashed = password_hash($mat_khau, PASSWORD_DEFAULT);
        mysqli_stmt_bind_param($stmt, "issssi", $doan_vien_id, $ten_dang_nhap, $mat_khau_hashed, $vai_tro, $trang_thai, $id);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return $success;
    }
    mysqli_close($conn);
    return false;
}

/**
 * Xóa tài khoản
 */
function deleteAccount($id) {
    $conn = getDbConnection();
    $sql = "DELETE FROM tai_khoan WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return $success;
    }
    mysqli_close($conn);
    return false;
}

/**
 * Lấy tài khoản theo ID
 */
function getAccountById($id) {
    $conn = getDbConnection();
    $sql = "SELECT * FROM tai_khoan WHERE id = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            $account = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            return $account;
        }
        mysqli_stmt_close($stmt);
    }
    mysqli_close($conn);
    return null;
}

/**
 * Lấy tất cả tài khoản (có tìm kiếm)
 */
function getAllAccounts($keyword = '') {
    $conn = getDbConnection();
    $keyword = "%" . $keyword . "%";

    $sql = "SELECT tk.*, dv.ho_ten AS member_name 
            FROM tai_khoan tk 
            LEFT JOIN doan_vien dv ON tk.doan_vien_id = dv.id
            WHERE dv.ho_ten LIKE ? OR tk.ten_dang_nhap LIKE ?
            ORDER BY tk.id ASC";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $keyword, $keyword);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $accounts = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $accounts[] = $row;
        }
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $accounts;
}
