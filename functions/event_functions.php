<?php
require_once __DIR__ . '/db_connection.php';
require_once __DIR__ . '/youth_union_team_functions.php';
require_once __DIR__ . '/youth_union_chapter_functions.php';

function getAllEvents($search = '', $limit = 10, $offset = 0) {
    $conn = getDbConnection();
    if (!$conn) {
        return [];
    }
    $sql = "SELECT sk.id, sk.ten_su_kien, sk.mo_ta, sk.ngay_to_chuc, sk.cap_to_chuc, sk.cap_id, sk.trang_thai,
                   CASE 
                       WHEN sk.cap_to_chuc = 'DoanTruong' THEN 'Đoàn Trường'
                       WHEN sk.cap_to_chuc = 'LienChi' THEN lc.ten
                       WHEN sk.cap_to_chuc = 'ChiDoan' THEN cd.ten
                   END AS cap_ten
            FROM su_kien sk
            LEFT JOIN lien_chi_doan lc ON sk.cap_to_chuc = 'LienChi' AND sk.cap_id = lc.id
            LEFT JOIN chi_doan cd ON sk.cap_to_chuc = 'ChiDoan' AND sk.cap_id = cd.id
            WHERE sk.ten_su_kien LIKE ? OR YEAR(sk.ngay_to_chuc) LIKE ?
            ORDER BY sk.ngay_to_chuc DESC
            LIMIT ? OFFSET ?";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        $searchTerm = '%' . mysqli_real_escape_string($conn, $search) . '%';
        mysqli_stmt_bind_param($stmt, "ssii", $searchTerm, $searchTerm, $limit, $offset);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $events = [];
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $events[] = $row;
            }
        }
        mysqli_stmt_close($stmt);
        mysqli_free_result($result);
    }
    mysqli_close($conn);
    return $events;
}

function getTotalEvents($search = '') {
    $conn = getDbConnection();
    if (!$conn) {
        return 0;
    }
    $sql = "SELECT COUNT(*) AS total
            FROM su_kien sk
            WHERE sk.ten_su_kien LIKE ? OR YEAR(sk.ngay_to_chuc) LIKE ?";
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

function addEvent($ten_su_kien, $mo_ta, $ngay_to_chuc, $cap_to_chuc, $cap_id, $trang_thai) {
    $conn = getDbConnection();
    if (!$conn) {
        return false;
    }
    $ten_su_kien = mysqli_real_escape_string($conn, $ten_su_kien);
    $mo_ta = $mo_ta ? mysqli_real_escape_string($conn, $mo_ta) : null;
    $sql = "INSERT INTO su_kien (ten_su_kien, mo_ta, ngay_to_chuc, cap_to_chuc, cap_id, trang_thai) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssssis", $ten_su_kien, $mo_ta, $ngay_to_chuc, $cap_to_chuc, $cap_id, $trang_thai);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return $success;
    }
    mysqli_close($conn);
    return false;
}

function getEventById($id) {
    $conn = getDbConnection();
    if (!$conn) {
        return null;
    }
    $sql = "SELECT id, ten_su_kien, mo_ta, ngay_to_chuc, cap_to_chuc, cap_id, trang_thai 
            FROM su_kien WHERE id = ? LIMIT 1";
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

function updateEvent($id, $ten_su_kien, $mo_ta, $ngay_to_chuc, $cap_to_chuc, $cap_id, $trang_thai) {
    $conn = getDbConnection();
    if (!$conn) {
        return false;
    }
    $ten_su_kien = mysqli_real_escape_string($conn, $ten_su_kien);
    $mo_ta = $mo_ta ? mysqli_real_escape_string($conn, $mo_ta) : null;
    $sql = "UPDATE su_kien SET ten_su_kien = ?, mo_ta = ?, ngay_to_chuc = ?, cap_to_chuc = ?, cap_id = ?, trang_thai = ? 
            WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssssisi", $ten_su_kien, $mo_ta, $ngay_to_chuc, $cap_to_chuc, $cap_id, $trang_thai, $id);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return $success;
    }
    mysqli_close($conn);
    return false;
}

function deleteEvent($id) {
    $conn = getDbConnection();
    if (!$conn) {
        return false;
    }
    $sql = "DELETE FROM su_kien WHERE id = ?";
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