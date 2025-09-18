<?php
require_once __DIR__ . '/db_connection.php';

/**
 * Get all chapters, optionally filtered by search term
 */
function getAllChapters($search = '') {
    $conn = getDbConnection();
    if (!$conn) {
        return [];
    }
    $sql = "SELECT id, ten, doan_truong_id, ngay_thanh_lap, trang_thai
            FROM lien_chi_doan
            WHERE ten LIKE ? OR doan_truong_id LIKE ?
            ORDER BY id";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        $searchTerm = '%' . mysqli_real_escape_string($conn, $search) . '%';
        mysqli_stmt_bind_param($stmt, "ss", $searchTerm, $searchTerm);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $chapters = [];
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $chapters[] = $row;
            }
        }
        mysqli_stmt_close($stmt);
        mysqli_free_result($result);
    }
    mysqli_close($conn);
    return $chapters;
}

/**
 * Add a new chapter
 */
function addChapter($ten, $doan_truong_id, $ngay_thanh_lap = null, $trang_thai = 'Hoạt động') {
    $conn = getDbConnection();
    if (!$conn) {
        return false;
    }
    $ten = mysqli_real_escape_string($conn, $ten);
    $doan_truong_id = mysqli_real_escape_string($conn, $doan_truong_id);
    $sql = "INSERT INTO lien_chi_doan (ten, doan_truong_id, ngay_thanh_lap, trang_thai) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        $ngay_thanh_lap = empty($ngay_thanh_lap) ? null : $ngay_thanh_lap;
        mysqli_stmt_bind_param($stmt, "ssss", $ten, $doan_truong_id, $ngay_thanh_lap, $trang_thai);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return $success;
    }
    mysqli_close($conn);
    return false;
}

/**
 * Get chapter by ID
 */
function getChapterById($id) {
    $conn = getDbConnection();
    if (!$conn) {
        return null;
    }
    $sql = "SELECT id, ten, doan_truong_id, ngay_thanh_lap, trang_thai FROM lien_chi_doan WHERE id = ? LIMIT 1";
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
 * Update a chapter
 */
function updateChapter($id, $ten, $doan_truong_id, $ngay_thanh_lap = null, $trang_thai = 'Hoạt động') {
    $conn = getDbConnection();
    if (!$conn) {
        return false;
    }
    $ten = mysqli_real_escape_string($conn, $ten);
    $doan_truong_id = mysqli_real_escape_string($conn, $doan_truong_id);
    $sql = "UPDATE lien_chi_doan SET ten = ?, doan_truong_id = ?, ngay_thanh_lap = ?, trang_thai = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        $ngay_thanh_lap = empty($ngay_thanh_lap) ? null : $ngay_thanh_lap;
        mysqli_stmt_bind_param($stmt, "ssssi", $ten, $doan_truong_id, $ngay_thanh_lap, $trang_thai, $id);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return $success;
    }
    mysqli_close($conn);
    return false;
}

/**
 * Delete a chapter
 */
function deleteChapter($id) {
    $conn = getDbConnection();
    if (!$conn) {
        return false;
    }
    $sql = "DELETE FROM lien_chi_doan WHERE id = ?";
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