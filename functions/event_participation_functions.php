<?php
require_once __DIR__ . '/db_connection.php';
require_once __DIR__ . '/event_functions.php';
require_once __DIR__ . '/member_functions.php';

function getAllParticipations($search = '', $limit = 10, $offset = 0) {
    $conn = getDbConnection();
    if (!$conn) {
        return [];
    }
    $sql = "SELECT tgs.id, tgs.su_kien_id, tgs.doan_vien_id, tgs.trang_thai, sk.ten_su_kien, dv.ho_ten AS doan_vien_ho_ten
            FROM tham_gia_su_kien tgs
            JOIN su_kien sk ON tgs.su_kien_id = sk.id
            JOIN doan_vien dv ON tgs.doan_vien_id = dv.id
            WHERE sk.ten_su_kien LIKE ? OR dv.ho_ten LIKE ?
            ORDER BY tgs.id ASC
            LIMIT ? OFFSET ?";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        $searchTerm = '%' . mysqli_real_escape_string($conn, $search) . '%';
        mysqli_stmt_bind_param($stmt, "ssii", $searchTerm, $searchTerm, $limit, $offset);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $participations = [];
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $participations[] = $row;
            }
        }
        mysqli_stmt_close($stmt);
        mysqli_free_result($result);
    }
    mysqli_close($conn);
    return $participations;
}

function getTotalParticipations($search = '') {
    $conn = getDbConnection();
    if (!$conn) {
        return 0;
    }
    $sql = "SELECT COUNT(*) AS total
            FROM tham_gia_su_kien tgs
            JOIN su_kien sk ON tgs.su_kien_id = sk.id
            JOIN doan_vien dv ON tgs.doan_vien_id = dv.id
            WHERE sk.ten_su_kien LIKE ? OR dv.ho_ten LIKE ?";
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

function addParticipation($su_kien_id, $doan_vien_id, $trang_thai) {
    $conn = getDbConnection();
    if (!$conn) {
        return false;
    }
    $sql = "INSERT INTO tham_gia_su_kien (su_kien_id, doan_vien_id, trang_thai) 
            VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "iis", $su_kien_id, $doan_vien_id, $trang_thai);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return $success;
    }
    mysqli_close($conn);
    return false;
}

function getParticipationById($id) {
    $conn = getDbConnection();
    if (!$conn) {
        return null;
    }
    $sql = "SELECT id, su_kien_id, doan_vien_id, trang_thai 
            FROM tham_gia_su_kien WHERE id = ? LIMIT 1";
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

function updateParticipation($id, $su_kien_id, $doan_vien_id, $trang_thai) {
    $conn = getDbConnection();
    if (!$conn) {
        return false;
    }
    $sql = "UPDATE tham_gia_su_kien SET su_kien_id = ?, doan_vien_id = ?, trang_thai = ? 
            WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "iisi", $su_kien_id, $doan_vien_id, $trang_thai, $id);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return $success;
    }
    mysqli_close($conn);
    return false;
}

function deleteParticipation($id) {
    $conn = getDbConnection();
    if (!$conn) {
        return false;
    }
    $sql = "DELETE FROM tham_gia_su_kien WHERE id = ?";
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