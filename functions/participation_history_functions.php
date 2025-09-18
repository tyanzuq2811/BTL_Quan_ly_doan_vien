<?php
require_once __DIR__ . '/db_connection.php';

/**
 * Get all participation history records with member and team names, optionally filtered by search term
 */
function getAllHistories($search = '') {
    $conn = getDbConnection();
    if (!$conn) {
        return [];
    }
    $sql = "SELECT lstg.id, lstg.doan_vien_id, dv.ho_ten AS doan_vien_ho_ten, lstg.chi_doan_id, cd.ten AS chi_doan_ten, 
                   lstg.ngay_bat_dau, lstg.ngay_ket_thuc, lstg.trang_thai
            FROM lich_su_tham_gia lstg
            JOIN doan_vien dv ON lstg.doan_vien_id = dv.id
            JOIN chi_doan cd ON lstg.chi_doan_id = cd.id
            WHERE dv.ho_ten LIKE ? OR cd.ten LIKE ?
            ORDER BY lstg.id";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        $searchTerm = '%' . mysqli_real_escape_string($conn, $search) . '%';
        mysqli_stmt_bind_param($stmt, "ss", $searchTerm, $searchTerm);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $histories = [];
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $histories[] = $row;
            }
        }
        mysqli_stmt_close($stmt);
        mysqli_free_result($result);
    }
    mysqli_close($conn);
    return $histories;
}

/**
 * Add a new participation history record
 */
function addHistory($doan_vien_id, $chi_doan_id, $ngay_bat_dau, $ngay_ket_thuc, $trang_thai) {
    $conn = getDbConnection();
    if (!$conn) {
        return false;
    }
    $sql = "INSERT INTO lich_su_tham_gia (doan_vien_id, chi_doan_id, ngay_bat_dau, ngay_ket_thuc, trang_thai) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        $ngay_ket_thuc = $ngay_ket_thuc ?: null;
        mysqli_stmt_bind_param($stmt, "iisss", $doan_vien_id, $chi_doan_id, $ngay_bat_dau, $ngay_ket_thuc, $trang_thai);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return $success;
    }
    mysqli_close($conn);
    return false;
}

/**
 * Get participation history record by ID
 */
function getHistoryById($id) {
    $conn = getDbConnection();
    if (!$conn) {
        return null;
    }
    $sql = "SELECT id, doan_vien_id, chi_doan_id, ngay_bat_dau, ngay_ket_thuc, trang_thai 
            FROM lich_su_tham_gia WHERE id = ? LIMIT 1";
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
 * Update a participation history record
 */
function updateHistory($id, $doan_vien_id, $chi_doan_id, $ngay_bat_dau, $ngay_ket_thuc, $trang_thai) {
    $conn = getDbConnection();
    if (!$conn) {
        return false;
    }
    $sql = "UPDATE lich_su_tham_gia SET doan_vien_id = ?, chi_doan_id = ?, ngay_bat_dau = ?, ngay_ket_thuc = ?, trang_thai = ? 
            WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        $ngay_ket_thuc = $ngay_ket_thuc ?: null;
        mysqli_stmt_bind_param($stmt, "iisssi", $doan_vien_id, $chi_doan_id, $ngay_bat_dau, $ngay_ket_thuc, $trang_thai, $id);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return $success;
    }
    mysqli_close($conn);
    return false;
}

/**
 * Delete a participation history record
 */
function deleteHistory($id) {
    $conn = getDbConnection();
    if (!$conn) {
        return false;
    }
    $sql = "DELETE FROM lich_su_tham_gia WHERE id = ?";
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
?>