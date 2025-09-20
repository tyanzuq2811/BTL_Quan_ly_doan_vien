<?php
require_once __DIR__ . '/db_connection.php';
require_once __DIR__ . '/member_functions.php';
require_once __DIR__ . '/youth_union_chapter_functions.php';
require_once __DIR__ . '/youth_union_team_functions.php';

function getAllNotifications($search = '', $limit = 10, $offset = 0) {
    $conn = getDbConnection();
    if (!$conn) {
        return [];
    }
    $sql = "SELECT tb.id, tb.tieu_de, tb.noi_dung, tb.cap_to_chuc, tb.cap_id, tb.nguoi_gui, tb.ngay_gui, 
                   dv.ho_ten AS nguoi_gui_ho_ten,
                   CASE 
                       WHEN tb.cap_to_chuc = 'DoanTruong' THEN 'Đoàn trường'
                       WHEN tb.cap_to_chuc = 'LienChi' THEN lc.ten
                       WHEN tb.cap_to_chuc = 'ChiDoan' THEN cd.ten
                   END AS ten_to_chuc
            FROM thong_bao tb
            LEFT JOIN doan_vien dv ON tb.nguoi_gui = dv.id
            LEFT JOIN lien_chi_doan lc ON tb.cap_to_chuc = 'LienChi' AND tb.cap_id = lc.id
            LEFT JOIN chi_doan cd ON tb.cap_to_chuc = 'ChiDoan' AND tb.cap_id = cd.id
            WHERE tb.tieu_de LIKE ? OR dv.ho_ten LIKE ?
            ORDER BY tb.id ASC
            LIMIT ? OFFSET ?";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        $searchTerm = '%' . mysqli_real_escape_string($conn, $search) . '%';
        mysqli_stmt_bind_param($stmt, "ssii", $searchTerm, $searchTerm, $limit, $offset);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $notifications = [];
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $notifications[] = $row;
            }
        }
        mysqli_stmt_close($stmt);
        mysqli_free_result($result);
    }
    mysqli_close($conn);
    return $notifications;
}

function getTotalNotifications($search = '') {
    $conn = getDbConnection();
    if (!$conn) {
        return 0;
    }
    $sql = "SELECT COUNT(*) AS total
            FROM thong_bao tb
            LEFT JOIN doan_vien dv ON tb.nguoi_gui = dv.id
            WHERE tb.tieu_de LIKE ? OR dv.ho_ten LIKE ?";
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

function addNotification($tieu_de, $noi_dung, $cap_to_chuc, $cap_id, $nguoi_gui) {
    $conn = getDbConnection();
    if (!$conn) {
        return false;
    }
    $tieu_de = mysqli_real_escape_string($conn, $tieu_de);
    $noi_dung = mysqli_real_escape_string($conn, $noi_dung);
    $sql = "INSERT INTO thong_bao (tieu_de, noi_dung, cap_to_chuc, cap_id, nguoi_gui) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        $nguoi_gui = $nguoi_gui ?: null;
        mysqli_stmt_bind_param($stmt, "sssii", $tieu_de, $noi_dung, $cap_to_chuc, $cap_id, $nguoi_gui);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return $success;
    }
    mysqli_close($conn);
    return false;
}

function getNotificationById($id) {
    $conn = getDbConnection();
    if (!$conn) {
        return null;
    }
    $sql = "SELECT tb.id, tb.tieu_de, tb.noi_dung, tb.cap_to_chuc, tb.cap_id, tb.nguoi_gui, tb.ngay_gui
            FROM thong_bao tb
            WHERE tb.id = ? LIMIT 1";
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

function updateNotification($id, $tieu_de, $noi_dung, $cap_to_chuc, $cap_id, $nguoi_gui) {
    $conn = getDbConnection();
    if (!$conn) {
        return false;
    }
    $tieu_de = mysqli_real_escape_string($conn, $tieu_de);
    $noi_dung = mysqli_real_escape_string($conn, $noi_dung);
    $sql = "UPDATE thong_bao SET tieu_de = ?, noi_dung = ?, cap_to_chuc = ?, cap_id = ?, nguoi_gui = ? 
            WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        $nguoi_gui = $nguoi_gui ?: null;
        mysqli_stmt_bind_param($stmt, "sssiii", $tieu_de, $noi_dung, $cap_to_chuc, $cap_id, $nguoi_gui, $id);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return $success;
    }
    mysqli_close($conn);
    return false;
}

function deleteNotification($id) {
    $conn = getDbConnection();
    if (!$conn) {
        return false;
    }
    $sql = "DELETE FROM thong_bao WHERE id = ?";
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

function validateCapId($cap_to_chuc, $cap_id) {
    $conn = getDbConnection();
    if (!$conn) {
        return false;
    }
    if ($cap_to_chuc === 'DoanTruong') {
        return $cap_id === 1; 
    } elseif ($cap_to_chuc === 'LienChi') {
        $sql = "SELECT id FROM lien_chi_doan WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $cap_id);
    } elseif ($cap_to_chuc === 'ChiDoan') {
        $sql = "SELECT id FROM chi_doan WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $cap_id);
    } else {
        mysqli_close($conn);
        return false;
    }
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $exists = mysqli_num_rows($result) > 0;
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $exists;
}
?>