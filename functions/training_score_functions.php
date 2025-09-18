<?php
require_once __DIR__ . '/db_connection.php';

/**
 * Get all training score records with member names, optionally filtered by search term
 */
function getAllScores($search = '') {
    $conn = getDbConnection();
    if (!$conn) {
        return [];
    }
    $sql = "SELECT drl.id, drl.doan_vien_id, dv.ho_ten AS doan_vien_ho_ten, drl.nam_hoc, drl.hoc_ky, drl.diem, drl.xep_loai, drl.ghi_chu
            FROM diem_ren_luyen drl
            JOIN doan_vien dv ON drl.doan_vien_id = dv.id
            WHERE dv.ho_ten LIKE ? OR drl.nam_hoc LIKE ?
            ORDER BY drl.id";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        $searchTerm = '%' . mysqli_real_escape_string($conn, $search) . '%';
        mysqli_stmt_bind_param($stmt, "ss", $searchTerm, $searchTerm);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $scores = [];
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $scores[] = $row;
            }
        }
        mysqli_stmt_close($stmt);
        mysqli_free_result($result);
    }
    mysqli_close($conn);
    return $scores;
}

/**
 * Add a new training score record
 */
function addScore($doan_vien_id, $nam_hoc, $hoc_ky, $diem, $xep_loai, $ghi_chu) {
    $conn = getDbConnection();
    if (!$conn) {
        return false;
    }
    $nam_hoc = mysqli_real_escape_string($conn, $nam_hoc);
    $sql = "INSERT INTO diem_ren_luyen (doan_vien_id, nam_hoc, hoc_ky, diem, xep_loai, ghi_chu) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        $ghi_chu = $ghi_chu ?: null;
        mysqli_stmt_bind_param($stmt, "ississ", $doan_vien_id, $nam_hoc, $hoc_ky, $diem, $xep_loai, $ghi_chu);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return $success;
    }
    mysqli_close($conn);
    return false;
}

/**
 * Get training score record by ID
 */
function getScoreById($id) {
    $conn = getDbConnection();
    if (!$conn) {
        return null;
    }
    $sql = "SELECT id, doan_vien_id, nam_hoc, hoc_ky, diem, xep_loai, ghi_chu 
            FROM diem_ren_luyen WHERE id = ? LIMIT 1";
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
 * Update a training score record
 */
function updateScore($id, $doan_vien_id, $nam_hoc, $hoc_ky, $diem, $xep_loai, $ghi_chu) {
    $conn = getDbConnection();
    if (!$conn) {
        return false;
    }
    $nam_hoc = mysqli_real_escape_string($conn, $nam_hoc);
    $sql = "UPDATE diem_ren_luyen SET doan_vien_id = ?, nam_hoc = ?, hoc_ky = ?, diem = ?, xep_loai = ?, ghi_chu = ? 
            WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        $ghi_chu = $ghi_chu ?: null;
        mysqli_stmt_bind_param($stmt, "ississi", $doan_vien_id, $nam_hoc, $hoc_ky, $diem, $xep_loai, $ghi_chu, $id);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return $success;
    }
    mysqli_close($conn);
    return false;
}

/**
 * Delete a training score record
 */
function deleteScore($id) {
    $conn = getDbConnection();
    if (!$conn) {
        return false;
    }
    $sql = "DELETE FROM diem_ren_luyen WHERE id = ?";
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
 * Calculate rank based on score
 */
function calculateRank($diem) {
    if ($diem >= 90) return 'Xuất sắc';
    if ($diem >= 80) return 'Tốt';
    if ($diem >= 70) return 'Khá';
    if ($diem >= 50) return 'Trung bình';
    return 'Yếu';
}
?>