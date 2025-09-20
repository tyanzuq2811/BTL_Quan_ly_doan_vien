<?php
require_once __DIR__ . '/db_connection.php';
require_once __DIR__ . '/member_functions.php';

function getAllChapters($search = '', $limit = 10, $offset = 0) {
    $conn = getDbConnection();
    if (!$conn) {
        return [];
    }
    $sql = "SELECT lcd.id, lcd.ten, dv.ho_ten AS doan_truong_ho_ten, lcd.ngay_thanh_lap, lcd.trang_thai
            FROM lien_chi_doan lcd
            LEFT JOIN doan_vien dv ON lcd.doan_truong_id = dv.id
            WHERE lcd.ten LIKE ? OR dv.ho_ten LIKE ?
            ORDER BY lcd.id ASC
            LIMIT ? OFFSET ?";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        $searchTerm = '%' . mysqli_real_escape_string($conn, $search) . '%';
        mysqli_stmt_bind_param($stmt, "ssii", $searchTerm, $searchTerm, $limit, $offset);
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

function getTotalChapters($search = '') {
    $conn = getDbConnection();
    if (!$conn) {
        return 0;
    }
    $sql = "SELECT COUNT(*) AS total
            FROM lien_chi_doan lcd
            LEFT JOIN doan_vien dv ON lcd.doan_truong_id = dv.id
            WHERE lcd.ten LIKE ? OR dv.ho_ten LIKE ?";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        $searchTerm = '%' . mysqli_real_escape_string($conn, $search) . '%';
        mysqli_stmt_bind_param($stmt, "ss", $searchTerm, $searchTerm);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        $total = $row['total'];
        mysqli_stmt_close($stmt);
        mysqli_free_result($result);
    } else {
        $total = 0;
    }
    mysqli_close($conn);
    return $total;
}

function addChapter($ten, $doan_truong_id, $ngay_thanh_lap = null, $trang_thai = 'Hoạt động') {
    $conn = getDbConnection();
    if (!$conn) {
        return false;
    }
    $ten = mysqli_real_escape_string($conn, $ten);
    $sql = "INSERT INTO lien_chi_doan (ten, doan_truong_id, ngay_thanh_lap, trang_thai) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        $ngay_thanh_lap = empty($ngay_thanh_lap) ? null : $ngay_thanh_lap;
        $doan_truong_id = empty($doan_truong_id) ? null : $doan_truong_id;
        mysqli_stmt_bind_param($stmt, "siss", $ten, $doan_truong_id, $ngay_thanh_lap, $trang_thai);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return $success;
    }
    mysqli_close($conn);
    return false;
}

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

function updateChapter($id, $ten, $doan_truong_id, $ngay_thanh_lap = null, $trang_thai = 'Hoạt động') {
    $conn = getDbConnection();
    if (!$conn) {
        return false;
    }
    $ten = mysqli_real_escape_string($conn, $ten);
    $sql = "UPDATE lien_chi_doan SET ten = ?, doan_truong_id = ?, ngay_thanh_lap = ?, trang_thai = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        $ngay_thanh_lap = empty($ngay_thanh_lap) ? null : $ngay_thanh_lap;
        $doan_truong_id = empty($doan_truong_id) ? null : $doan_truong_id;
        mysqli_stmt_bind_param($stmt, "sissi", $ten, $doan_truong_id, $ngay_thanh_lap, $trang_thai, $id);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return $success;
    }
    mysqli_close($conn);
    return false;
}

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