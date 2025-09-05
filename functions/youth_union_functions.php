<?php
require_once 'db_connection.php';

/**
 * Lấy tất cả tổ chức đoàn
 */
function getAllYouthUnions($keyword = '') {
    $conn = getDbConnection();
    $keyword = "%" . $keyword . "%";

    $sql = "SELECT t1.id, t1.ten, t1.loai, t1.cap_tren_id, t1.ngay_thanh_lap, t1.trang_thai, t1.created_at,
                   t2.ten AS parent_name
            FROM to_chuc_doan t1
            LEFT JOIN to_chuc_doan t2 ON t1.cap_tren_id = t2.id
            WHERE t1.ten LIKE ?
            ORDER BY t1.id ASC";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $keyword);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $unions = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $unions[] = $row;
        }
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $unions;
}

/**
 * Lấy tổ chức đoàn theo ID
 */
function getYouthUnionById($id) {
    $conn = getDbConnection();
    $sql = "SELECT * FROM to_chuc_doan WHERE id = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $union = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            return $union;
        }
        mysqli_stmt_close($stmt);
    }
    mysqli_close($conn);
    return null;
}

/**
 * Thêm tổ chức đoàn
 */
function addYouthUnion($ten, $loai, $cap_tren_id = null, $ngay_thanh_lap = null) {
    $conn = getDbConnection();
    $sql = "INSERT INTO to_chuc_doan (ten, loai, cap_tren_id, ngay_thanh_lap, trang_thai, created_at) 
            VALUES (?, ?, ?, ?, 'Hoạt động', NOW())";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        // Nếu ngay_thanh_lap rỗng, gán NULL
        if (empty($ngay_thanh_lap)) {
            $ngay_thanh_lap = null;
        }
        mysqli_stmt_bind_param($stmt, "ssis", $ten, $loai, $cap_tren_id, $ngay_thanh_lap);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return $success;
    }
    mysqli_close($conn);
    return false;
}

/**
 * Cập nhật tổ chức đoàn
 */
function updateYouthUnion($id, $ten, $loai, $cap_tren_id = null, $ngay_thanh_lap = null) {
    $conn = getDbConnection();
    $sql = "UPDATE to_chuc_doan 
            SET ten = ?, loai = ?, cap_tren_id = ?, ngay_thanh_lap = ?
            WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        // Nếu ngay_thanh_lap rỗng, gán NULL
        if (empty($ngay_thanh_lap)) {
            $ngay_thanh_lap = null;
        }
        mysqli_stmt_bind_param($stmt, "ssisi", $ten, $loai, $cap_tren_id, $ngay_thanh_lap, $id);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return $success;
    }
    mysqli_close($conn);
    return false;
}

/**
 * Xóa tổ chức đoàn
 */
function deleteYouthUnion($id) {
    $conn = getDbConnection();
    $sql = "DELETE FROM to_chuc_doan WHERE id = ?";
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