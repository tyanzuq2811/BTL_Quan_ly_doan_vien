<?php
require_once __DIR__ . '/db_connection.php';

/**
 * Get all teams with chapter names, optionally filtered by search term
 */
function getAllTeams($search = '') {
    $conn = getDbConnection();
    if (!$conn) {
        return [];
    }
    $sql = "SELECT cd.id, cd.ten, lcd.ten AS lien_chi_doan, cd.ngay_thanh_lap, cd.trang_thai
            FROM chi_doan cd
            LEFT JOIN lien_chi_doan lcd ON cd.lien_chi_id = lcd.id
            WHERE cd.ten LIKE ? OR lcd.ten LIKE ?
            ORDER BY cd.id";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        $searchTerm = '%' . mysqli_real_escape_string($conn, $search) . '%';
        mysqli_stmt_bind_param($stmt, "ss", $searchTerm, $searchTerm);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $teams = [];
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $teams[] = $row;
            }
        }
        mysqli_stmt_close($stmt);
        mysqli_free_result($result);
    }
    mysqli_close($conn);
    return $teams;
}

/**
 * Add a new team
 */
function addTeam($ten, $lien_chi_id, $ngay_thanh_lap = null, $trang_thai = 'Hoạt động') {
    $conn = getDbConnection();
    if (!$conn) {
        return false;
    }
    $ten = mysqli_real_escape_string($conn, $ten);
    $sql = "INSERT INTO chi_doan (ten, lien_chi_id, ngay_thanh_lap, trang_thai) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        $ngay_thanh_lap = empty($ngay_thanh_lap) ? null : $ngay_thanh_lap;
        mysqli_stmt_bind_param($stmt, "siss", $ten, $lien_chi_id, $ngay_thanh_lap, $trang_thai);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return $success;
    }
    mysqli_close($conn);
    return false;
}

/**
 * Get team by ID
 */
function getTeamById($id) {
    $conn = getDbConnection();
    if (!$conn) {
        return null;
    }
    $sql = "SELECT id, ten, lien_chi_id, ngay_thanh_lap, trang_thai FROM chi_doan WHERE id = ? LIMIT 1";
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
 * Update a team
 */
function updateTeam($id, $ten, $lien_chi_id, $ngay_thanh_lap = null, $trang_thai = 'Hoạt động') {
    $conn = getDbConnection();
    if (!$conn) {
        return false;
    }
    $ten = mysqli_real_escape_string($conn, $ten);
    $sql = "UPDATE chi_doan SET ten = ?, lien_chi_id = ?, ngay_thanh_lap = ?, trang_thai = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        $ngay_thanh_lap = empty($ngay_thanh_lap) ? null : $ngay_thanh_lap;
        mysqli_stmt_bind_param($stmt, "sissi", $ten, $lien_chi_id, $ngay_thanh_lap, $trang_thai, $id);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return $success;
    }
    mysqli_close($conn);
    return false;
}

/**
 * Delete a team
 */
function deleteTeam($id) {
    $conn = getDbConnection();
    if (!$conn) {
        return false;
    }
    $sql = "DELETE FROM chi_doan WHERE id = ?";
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