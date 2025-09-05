<?php
require_once 'db_connection.php';

/**
 * Thêm đoàn phí
 */
function addDues($doan_vien_id, $loai_doan_phi_id, $nam_hoc, $hoc_ky, $so_tien, $ngay_nop = null, $trang_thai = 'Chưa nộp') {
    $conn = getDbConnection();
    $sql = "INSERT INTO doan_phi (doan_vien_id, loai_doan_phi_id, nam_hoc, hoc_ky, so_tien, ngay_nop, trang_thai)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "iissdss", $doan_vien_id, $loai_doan_phi_id, $nam_hoc, $hoc_ky, $so_tien, $ngay_nop, $trang_thai);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return $success;
    }
    mysqli_close($conn);
    return false;
}

/**
 * Cập nhật đoàn phí
 */
function updateDues($id, $doan_vien_id, $loai_doan_phi_id, $nam_hoc, $hoc_ky, $so_tien, $ngay_nop = null, $trang_thai = 'Chưa nộp') {
    $conn = getDbConnection();
    $sql = "UPDATE doan_phi 
            SET doan_vien_id = ?, loai_doan_phi_id = ?, nam_hoc = ?, hoc_ky = ?, so_tien = ?, ngay_nop = ?, trang_thai = ?
            WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "iissdssi", $doan_vien_id, $loai_doan_phi_id, $nam_hoc, $hoc_ky, $so_tien, $ngay_nop, $trang_thai, $id);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return $success;
    }
    mysqli_close($conn);
    return false;
}

/**
 * Xóa đoàn phí
 */
function deleteDues($id) {
    $conn = getDbConnection();
    $sql = "DELETE FROM doan_phi WHERE id = ?";
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
 * Lấy đoàn phí theo ID
 */
function getDuesById($id) {
    $conn = getDbConnection();
    $sql = "SELECT * FROM doan_phi WHERE id = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            $dues = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            return $dues;
        }
        mysqli_stmt_close($stmt);
    }
    mysqli_close($conn);
    return null;
}

/**
 * Lấy tất cả đoàn phí (có tìm kiếm)
 */
function getAllDues($keyword = '') {
    $conn = getDbConnection();
    $keyword = "%" . $keyword . "%";

    $sql = "SELECT dp.*, dv.ho_ten AS member_name 
            FROM doan_phi dp 
            LEFT JOIN doan_vien dv ON dp.doan_vien_id = dv.id
            WHERE dv.ho_ten LIKE ? OR dp.nam_hoc LIKE ?
            ORDER BY dp.id ASC";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $keyword, $keyword);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $dues = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $dues[] = $row;
        }
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $dues;
}
