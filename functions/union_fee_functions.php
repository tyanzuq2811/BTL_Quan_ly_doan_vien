<?php
require_once __DIR__ . '/db_connection.php';

/**
 * Get all union fee records with member and fee type names, optionally filtered by search term
 */
function getAllFees($search = '') {
    $conn = getDbConnection();
    if (!$conn) {
        return [];
    }
    $sql = "SELECT dp.id, dp.doan_vien_id, dv.ho_ten AS doan_vien_ho_ten, dp.loai_doan_phi_id, ldp.ten AS loai_doan_phi_ten,
                   dp.nam_hoc, dp.so_tien, dp.ngay_nop, dp.trang_thai
            FROM doan_phi dp
            JOIN doan_vien dv ON dp.doan_vien_id = dv.id
            JOIN loai_doan_phi ldp ON dp.loai_doan_phi_id = ldp.id
            WHERE dv.ho_ten LIKE ? OR ldp.ten LIKE ?
            ORDER BY dp.id";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        $searchTerm = '%' . mysqli_real_escape_string($conn, $search) . '%';
        mysqli_stmt_bind_param($stmt, "ss", $searchTerm, $searchTerm);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $fees = [];
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $fees[] = $row;
            }
        }
        mysqli_stmt_close($stmt);
        mysqli_free_result($result);
    }
    mysqli_close($conn);
    return $fees;
}

/**
 * Add a new union fee record
 */
function addFee($doan_vien_id, $loai_doan_phi_id, $nam_hoc, $so_tien, $ngay_nop, $trang_thai) {
    $conn = getDbConnection();
    if (!$conn) {
        return false;
    }
    $nam_hoc = mysqli_real_escape_string($conn, $nam_hoc);
    $sql = "INSERT INTO doan_phi (doan_vien_id, loai_doan_phi_id, nam_hoc, so_tien, ngay_nop, trang_thai) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        $ngay_nop = $ngay_nop ?: null;
        mysqli_stmt_bind_param($stmt, "iisdss", $doan_vien_id, $loai_doan_phi_id, $nam_hoc, $so_tien, $ngay_nop, $trang_thai);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return $success;
    }
    mysqli_close($conn);
    return false;
}

/**
 * Get union fee record by ID
 */
function getFeeById($id) {
    $conn = getDbConnection();
    if (!$conn) {
        return null;
    }
    $sql = "SELECT id, doan_vien_id, loai_doan_phi_id, nam_hoc, so_tien, ngay_nop, trang_thai 
            FROM doan_phi WHERE id = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return $row;
    }
    mysqli_close($conn);
    return null;
}

/**
 * Update a union fee record
 */
function updateFee($id, $doan_vien_id, $loai_doan_phi_id, $nam_hoc, $so_tien, $ngay_nop, $trang_thai) {
    $conn = getDbConnection();
    if (!$conn) {
        return false;
    }
    $nam_hoc = mysqli_real_escape_string($conn, $nam_hoc);
    $sql = "UPDATE doan_phi SET doan_vien_id = ?, loai_doan_phi_id = ?, nam_hoc = ?, so_tien = ?, ngay_nop = ?, trang_thai = ? 
            WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        $ngay_nop = $ngay_nop ?: null;
        mysqli_stmt_bind_param($stmt, "iisdssi", $doan_vien_id, $loai_doan_phi_id, $nam_hoc, $so_tien, $ngay_nop, $trang_thai, $id);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return $success;
    }
    mysqli_close($conn);
    return false;
}

/**
 * Delete a union fee record
 */
function deleteFee($id) {
    $conn = getDbConnection();
    if (!$conn) {
        return false;
    }
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
 * Get all union fee types
 */
function getAllFeeTypes() {
    $conn = getDbConnection();
    if (!$conn) {
        return [];
    }
    $sql = "SELECT id, ten, so_tien FROM loai_doan_phi ORDER BY ten";
    $result = mysqli_query($conn, $sql);
    $fee_types = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $fee_types[] = $row;
        }
    }
    mysqli_free_result($result);
    mysqli_close($conn);
    return $fee_types;
}
?>