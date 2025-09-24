<?php
require_once __DIR__ . '/db_connection.php';

function getAllAccounts($search = '', $limit = 10, $offset = 0) {
    $conn = getDbConnection();
    if (!$conn) {
        return [];
    }
    $sql = "SELECT t.id, d.ho_ten, t.ten_dang_nhap, t.vai_tro, t.trang_thai
            FROM tai_khoan t
            LEFT JOIN doan_vien d ON t.doan_vien_id = d.id
            WHERE t.ten_dang_nhap LIKE ?
            ORDER BY t.id
            LIMIT ? OFFSET ?";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        $searchTerm = '%' . mysqli_real_escape_string($conn, $search) . '%';
        mysqli_stmt_bind_param($stmt, "sii", $searchTerm, $limit, $offset);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $accounts = [];
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $accounts[] = $row;
            }
        }
        mysqli_stmt_close($stmt);
        mysqli_free_result($result);
    }
    mysqli_close($conn);
    return $accounts;
}

function getTotalAccounts($search = '') {
    $conn = getDbConnection();
    if (!$conn) {
        return 0;
    }
    $sql = "SELECT COUNT(*) AS total
            FROM tai_khoan
            WHERE ten_dang_nhap LIKE ?";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        $searchTerm = '%' . mysqli_real_escape_string($conn, $search) . '%';
        mysqli_stmt_bind_param($stmt, "s", $searchTerm);
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

function addAccount($doan_vien_id, $ten_dang_nhap, $mat_khau, $vai_tro = 'DoanVien', $trang_thai = 'Hoạt động') {
    $conn = getDbConnection();
    if (!$conn) {
        return false;
    }

    if (!empty($doan_vien_id)) {
        $checkSql = "SELECT COUNT(*) as cnt FROM doan_vien WHERE id = ?";
        $checkStmt = mysqli_prepare($conn, $checkSql);
        mysqli_stmt_bind_param($checkStmt, "i", $doan_vien_id);
        mysqli_stmt_execute($checkStmt);
        $result = mysqli_stmt_get_result($checkStmt);
        $row = mysqli_fetch_assoc($result);
        mysqli_stmt_close($checkStmt);

        if ($row['cnt'] == 0) {
            mysqli_close($conn);
            return false; 
        }
    }

    $ten_dang_nhap = mysqli_real_escape_string($conn, $ten_dang_nhap);
    $hashed_password = password_hash($mat_khau, PASSWORD_DEFAULT);
    $sql = "INSERT INTO tai_khoan (doan_vien_id, ten_dang_nhap, mat_khau, vai_tro, trang_thai) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        $doan_vien_id = empty($doan_vien_id) ? null : $doan_vien_id;
        mysqli_stmt_bind_param($stmt, "issss", $doan_vien_id, $ten_dang_nhap, $hashed_password, $vai_tro, $trang_thai);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return $success;
    }
    mysqli_close($conn);
    return false;
}

function getAccountById($id) {
    $conn = getDbConnection();
    if (!$conn) {
        return null;
    }
    $sql = "SELECT t.id, d.ho_ten, t.ten_dang_nhap, t.vai_tro, t.trang_thai
            FROM tai_khoan t
            LEFT JOIN doan_vien d ON t.doan_vien_id = d.id
            WHERE t.id = ? LIMIT 1";
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

function updateAccount($id, $doan_vien_id, $ten_dang_nhap, $mat_khau, $vai_tro = 'DoanVien', $trang_thai = 'Hoạt động') {
    $conn = getDbConnection();
    if (!$conn) {
        return false;
    }

    if (!empty($doan_vien_id)) {
        $checkSql = "SELECT COUNT(*) as cnt FROM doan_vien WHERE id = ?";
        $checkStmt = mysqli_prepare($conn, $checkSql);
        mysqli_stmt_bind_param($checkStmt, "i", $doan_vien_id);
        mysqli_stmt_execute($checkStmt);
        $result = mysqli_stmt_get_result($checkStmt);
        $row = mysqli_fetch_assoc($result);
        mysqli_stmt_close($checkStmt);

        if ($row['cnt'] == 0) {
            mysqli_close($conn);
            return false; 
        }
    }

    $ten_dang_nhap = mysqli_real_escape_string($conn, $ten_dang_nhap);
    $hashed_password = !empty($mat_khau) ? password_hash($mat_khau, PASSWORD_DEFAULT) : null;
    $sql = "UPDATE tai_khoan SET doan_vien_id = ?, ten_dang_nhap = ?, mat_khau = ?, vai_tro = ?, trang_thai = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        $doan_vien_id = empty($doan_vien_id) ? null : $doan_vien_id;
        $mat_khau = $hashed_password ?: null; 
        mysqli_stmt_bind_param($stmt, "issssi", $doan_vien_id, $ten_dang_nhap, $mat_khau, $vai_tro, $trang_thai, $id);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return $success;
    }
    mysqli_close($conn);
    return false;
}

function deleteAccount($id) {
    $conn = getDbConnection();
    if (!$conn) {
        return false;
    }
    $sql = "DELETE FROM tai_khoan WHERE id = ?";
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

function usernameExists($username) {
    $conn = getDbConnection();  
    $stmt = $conn->prepare("SELECT COUNT(*) FROM tai_khoan WHERE ten_dang_nhap = ?");
    if ($stmt === false) {
        die("Lỗi prepare: " . $conn->error);
    }
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();
    $conn->close(); 
    return $count > 0;
}
?>