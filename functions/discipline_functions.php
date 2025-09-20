<?php
require_once __DIR__ . '/db_connection.php';
require_once __DIR__ . '/member_functions.php';

function getAllDisciplines($search = '', $limit = 10, $offset = 0) {
    $conn = getDbConnection();
    if (!$conn) {
        return [];
    }
    $sql = "SELECT kl.id, kl.doan_vien_id, kl.loai_id, kl.ngay_quyet_dinh, kl.noi_dung, 
                   dv.ho_ten AS doan_vien_ho_ten, lkl.ten AS loai_ten, lkl.mo_ta AS loai_mo_ta
            FROM ky_luat kl
            JOIN doan_vien dv ON kl.doan_vien_id = dv.id
            JOIN loai_ky_luat lkl ON kl.loai_id = lkl.id
            WHERE dv.ho_ten LIKE ? OR lkl.ten LIKE ?
            ORDER BY kl.id ASC
            LIMIT ? OFFSET ?";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        $searchTerm = '%' . mysqli_real_escape_string($conn, $search) . '%';
        mysqli_stmt_bind_param($stmt, "ssii", $searchTerm, $searchTerm, $limit, $offset);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $disciplines = [];
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $disciplines[] = $row;
            }
        }
        mysqli_stmt_close($stmt);
        mysqli_free_result($result);
    }
    mysqli_close($conn);
    return $disciplines;
}

function getTotalDisciplines($search = '') {
    $conn = getDbConnection();
    if (!$conn) {
        return 0;
    }
    $sql = "SELECT COUNT(*) AS total
            FROM ky_luat kl
            JOIN doan_vien dv ON kl.doan_vien_id = dv.id
            JOIN loai_ky_luat lkl ON kl.loai_id = lkl.id
            WHERE dv.ho_ten LIKE ? OR lkl.ten LIKE ?";
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

function getAllDisciplineTypes() {
    $conn = getDbConnection();
    if (!$conn) {
        return [];
    }
    $sql = "SELECT id, ten, mo_ta FROM loai_ky_luat ORDER BY ten";
    $result = mysqli_query($conn, $sql);
    $discipline_types = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $discipline_types[] = $row;
        }
    }
    mysqli_free_result($result);
    mysqli_close($conn);
    return $discipline_types;
}

function addDiscipline($doan_vien_id, $loai_id, $ngay_quyet_dinh, $noi_dung) {
    $conn = getDbConnection();
    if (!$conn) {
        return false;
    }
    $noi_dung = $noi_dung ? mysqli_real_escape_string($conn, $noi_dung) : null;
    $sql = "INSERT INTO ky_luat (doan_vien_id, loai_id, ngay_quyet_dinh, noi_dung) 
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

function getDisciplineById($id) {
    $conn = getDbConnection();
    if (!$conn) {
        return null;
    }
    $sql = "SELECT kl.id, kl.doan_vien_id, kl.loai_id, kl.ngay_quyet_dinh, kl.noi_dung, lkl.mo_ta AS loai_mo_ta
            FROM ky_luat kl
            JOIN loai_ky_luat lkl ON kl.loai_id = lkl.id
            WHERE kl.id = ? LIMIT 1";
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

function updateDiscipline($id, $doan_vien_id, $loai_id, $ngay_quyet_dinh, $noi_dung) {
    $conn = getDbConnection();
    if (!$conn) {
        return false;
    }
    $noi_dung = $noi_dung ? mysqli_real_escape_string($conn, $noi_dung) : null;
    $sql = "UPDATE ky_luat SET doan_vien_id = ?, loai_id = ?, ngay_quyet_dinh = ?, noi_dung = ? 
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

function deleteDiscipline($id) {
    $conn = getDbConnection();
    if (!$conn) {
        return false;
    }
    $sql = "DELETE FROM ky_luat WHERE id = ?";
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