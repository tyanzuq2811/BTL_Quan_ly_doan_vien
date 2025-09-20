<?php
require_once __DIR__ . '/db_connection.php';
require_once __DIR__ . '/member_functions.php';

function getAllAwards($search = '', $limit = 10, $offset = 0) {
    $conn = getDbConnection();
    if (!$conn) {
        return [];
    }
    $sql = "SELECT kt.id, kt.doan_vien_id, kt.loai_id, kt.ngay_quyet_dinh, kt.noi_dung, 
                   dv.ho_ten AS doan_vien_ho_ten, lkt.ten AS loai_ten, lkt.mo_ta AS loai_mo_ta
            FROM khen_thuong kt
            JOIN doan_vien dv ON kt.doan_vien_id = dv.id
            JOIN loai_khen_thuong lkt ON kt.loai_id = lkt.id
            WHERE dv.ho_ten LIKE ? OR lkt.ten LIKE ?
            ORDER BY kt.id ASC
            LIMIT ? OFFSET ?";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        $searchTerm = '%' . mysqli_real_escape_string($conn, $search) . '%';
        mysqli_stmt_bind_param($stmt, "ssii", $searchTerm, $searchTerm, $limit, $offset);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $awards = [];
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $awards[] = $row;
            }
        }
        mysqli_stmt_close($stmt);
        mysqli_free_result($result);
    }
    mysqli_close($conn);
    return $awards;
}

function getTotalAwards($search = '') {
    $conn = getDbConnection();
    if (!$conn) {
        return 0;
    }
    $sql = "SELECT COUNT(*) AS total
            FROM khen_thuong kt
            JOIN doan_vien dv ON kt.doan_vien_id = dv.id
            JOIN loai_khen_thuong lkt ON kt.loai_id = lkt.id
            WHERE dv.ho_ten LIKE ? OR lkt.ten LIKE ?";
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

function getAllAwardTypes() {
    $conn = getDbConnection();
    if (!$conn) {
        return [];
    }
    $sql = "SELECT id, ten, mo_ta FROM loai_khen_thuong ORDER BY ten";
    $result = mysqli_query($conn, $sql);
    $award_types = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $award_types[] = $row;
        }
    }
    mysqli_free_result($result);
    mysqli_close($conn);
    return $award_types;
}

function addAward($doan_vien_id, $loai_id, $ngay_quyet_dinh, $noi_dung) {
    $conn = getDbConnection();
    if (!$conn) {
        return false;
    }
    $noi_dung = $noi_dung ? mysqli_real_escape_string($conn, $noi_dung) : null;
    $sql = "INSERT INTO khen_thuong (doan_vien_id, loai_id, ngay_quyet_dinh, noi_dung) 
            VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "iiss", $doan_vien_id, $loai_id, $ngay_quyet_dinh, $noi_dung);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return $success;
    }
    mysqli_close($conn);
    return false;
}

function getAwardById($id) {
    $conn = getDbConnection();
    if (!$conn) {
        return null;
    }
    $sql = "SELECT kt.id, kt.doan_vien_id, kt.loai_id, kt.ngay_quyet_dinh, kt.noi_dung, lkt.mo_ta AS loai_mo_ta
            FROM khen_thuong kt
            JOIN loai_khen_thuong lkt ON kt.loai_id = lkt.id
            WHERE kt.id = ? LIMIT 1";
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

function updateAward($id, $doan_vien_id, $loai_id, $ngay_quyet_dinh, $noi_dung) {
    $conn = getDbConnection();
    if (!$conn) {
        return false;
    }
    $noi_dung = $noi_dung ? mysqli_real_escape_string($conn, $noi_dung) : null;
    $sql = "UPDATE khen_thuong SET doan_vien_id = ?, loai_id = ?, ngay_quyet_dinh = ?, noi_dung = ? 
            WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "iissi", $doan_vien_id, $loai_id, $ngay_quyet_dinh, $noi_dung, $id);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return $success;
    }
    mysqli_close($conn);
    return false;
}

function deleteAward($id) {
    $conn = getDbConnection();
    if (!$conn) {
        return false;
    }
    $sql = "DELETE FROM khen_thuong WHERE id = ?";
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