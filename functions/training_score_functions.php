<?php
require_once 'db_connection.php';

/**
 * Lấy tất cả điểm rèn luyện
 */
function getAllTrainingScores($keyword = '') {
    $conn = getDbConnection();
    $keyword = "%" . $keyword . "%";

    $sql = "SELECT drl.*, dv.ho_ten AS member_name FROM diem_ren_luyen drl 
            LEFT JOIN doan_vien dv ON drl.doan_vien_id = dv.id 
            WHERE 1=1";
    if (!empty($keyword)) {
        $sql .= " AND (dv.ho_ten LIKE ? OR drl.nam_hoc LIKE ?)";
    }
    $sql .= " ORDER BY drl.id ASC";

    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        if (!empty($keyword)) {
            mysqli_stmt_bind_param($stmt, "ss", $keyword, $keyword);
        }
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $trainingScores = [];
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $trainingScores[] = $row;
            }
        }

        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return $trainingScores;
    }
    mysqli_close($conn);
    return [];
}

/**
 * Lấy điểm rèn luyện theo ID
 */
function getTrainingScoreById($id) {
    $conn = getDbConnection();
    $sql = "SELECT * FROM diem_ren_luyen WHERE id = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $trainingScore = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            return $trainingScore;
        }
        mysqli_stmt_close($stmt);
    }
    mysqli_close($conn);
    return null;
}

/**
 * Thêm điểm rèn luyện
 */
function addTrainingScore($doan_vien_id, $nam_hoc, $hoc_ky, $diem, $xep_loai = null, $ghi_chu = null) {
    $conn = getDbConnection();
    $sql = "INSERT INTO diem_ren_luyen (doan_vien_id, nam_hoc, hoc_ky, diem, xep_loai, ghi_chu, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, NOW())";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        if (empty($xep_loai)) $xep_loai = null;
        if (empty($ghi_chu)) $ghi_chu = null;
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
 * Cập nhật điểm rèn luyện
 */
function updateTrainingScore($id, $doan_vien_id, $nam_hoc, $hoc_ky, $diem, $xep_loai = null, $ghi_chu = null) {
    $conn = getDbConnection();
    $sql = "UPDATE diem_ren_luyen 
            SET doan_vien_id = ?, nam_hoc = ?, hoc_ky = ?, diem = ?, xep_loai = ?, ghi_chu = ?
            WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        if (empty($xep_loai)) $xep_loai = null;
        if (empty($ghi_chu)) $ghi_chu = null;
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
 * Xóa điểm rèn luyện
 */
function deleteTrainingScore($id) {
    $conn = getDbConnection();
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