<?php
require_once 'db_connection.php';

/**
 * Lấy tất cả đoàn viên
 */
function getAllMembers($keyword = '') {
    $conn = getDbConnection();
    $keyword = "%" . $keyword . "%";

    $sql = "SELECT * FROM doan_vien 
            WHERE mssv LIKE ? OR ho_ten LIKE ? 
            ORDER BY id ASC";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $keyword, $keyword);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $members = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $members[] = $row;
        }
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $members;
}

/**
 * Lấy đoàn viên theo ID
 */
function getMemberById($id) {
    $conn = getDbConnection();
    $sql = "SELECT * FROM doan_vien WHERE id = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $member = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            return $member;
        }
        mysqli_stmt_close($stmt);
    }
    mysqli_close($conn);
    return null;
}

/**
 * Thêm đoàn viên
 */
function addMember($mssv, $ho_ten, $ngay_sinh, $gioi_tinh, $email = null, $so_dien_thoai = null, $dia_chi = null, $ngay_vao_doan = null) {
    $conn = getDbConnection();
    $sql = "INSERT INTO doan_vien (mssv, ho_ten, ngay_sinh, gioi_tinh, email, so_dien_thoai, dia_chi, ngay_vao_doan, trang_thai, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Hoạt động', NOW())";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        if (empty($email)) $email = null;
        if (empty($so_dien_thoai)) $so_dien_thoai = null;
        if (empty($dia_chi)) $dia_chi = null;
        if (empty($ngay_vao_doan)) $ngay_vao_doan = null;
        mysqli_stmt_bind_param($stmt, "ssssssss", $mssv, $ho_ten, $ngay_sinh, $gioi_tinh, $email, $so_dien_thoai, $dia_chi, $ngay_vao_doan);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return $success;
    }
    mysqli_close($conn);
    return false;
}

/**
 * Cập nhật đoàn viên
 */
function updateMember($id, $mssv, $ho_ten, $ngay_sinh, $gioi_tinh, $email = null, $so_dien_thoai = null, $dia_chi = null, $ngay_vao_doan = null) {
    $conn = getDbConnection();
    $sql = "UPDATE doan_vien 
            SET mssv = ?, ho_ten = ?, ngay_sinh = ?, gioi_tinh = ?, email = ?, so_dien_thoai = ?, dia_chi = ?, ngay_vao_doan = ?
            WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        if (empty($email)) $email = null;
        if (empty($so_dien_thoai)) $so_dien_thoai = null;
        if (empty($dia_chi)) $dia_chi = null;
        if (empty($ngay_vao_doan)) $ngay_vao_doan = null;
        mysqli_stmt_bind_param($stmt, "ssssssssi", $mssv, $ho_ten, $ngay_sinh, $gioi_tinh, $email, $so_dien_thoai, $dia_chi, $ngay_vao_doan, $id);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return $success;
    }
    mysqli_close($conn);
    return false;
}

/**
 * Xóa đoàn viên
 */
function deleteMember($id) {
    $conn = getDbConnection();
    $sql = "DELETE FROM doan_vien WHERE id = ?";
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