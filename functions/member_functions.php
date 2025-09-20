<?php
require_once __DIR__ . '/db_connection.php';

function getAllMembers($search = '', $limit = 10, $offset = 0) {
    $conn = getDbConnection();
    if (!$conn) {
        return [];
    }
    $sql = "SELECT dv.id, dv.mssv, dv.ho_ten, dv.ngay_sinh, dv.gioi_tinh, dv.email, dv.so_dien_thoai, dv.dia_chi, 
                   dv.ngay_vao_doan, cd.ten AS chi_doan_ten, dv.trang_thai
            FROM doan_vien dv
            LEFT JOIN chi_doan cd ON dv.chi_doan_id = cd.id
            WHERE dv.mssv LIKE ? OR dv.ho_ten LIKE ? OR dv.email LIKE ?
            ORDER BY dv.id ASC
            LIMIT ? OFFSET ?";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        $searchTerm = '%' . mysqli_real_escape_string($conn, $search) . '%';
        mysqli_stmt_bind_param($stmt, "sssii", $searchTerm, $searchTerm, $searchTerm, $limit, $offset);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $members = [];
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $members[] = $row;
            }
        }
        mysqli_stmt_close($stmt);
        mysqli_free_result($result);
    }
    mysqli_close($conn);
    return $members;
}

function getTotalMembers($search = '') {
    $conn = getDbConnection();
    if (!$conn) {
        return 0;
    }
    $sql = "SELECT COUNT(*) AS total
            FROM doan_vien dv
            WHERE dv.mssv LIKE ? OR dv.ho_ten LIKE ? OR dv.email LIKE ?";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        $searchTerm = '%' . mysqli_real_escape_string($conn, $search) . '%';
        mysqli_stmt_bind_param($stmt, "sss", $searchTerm, $searchTerm, $searchTerm);
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

function addMember($mssv, $ho_ten, $ngay_sinh, $gioi_tinh, $email, $so_dien_thoai, $dia_chi, $ngay_vao_doan, $chi_doan_id, $trang_thai) {
    $conn = getDbConnection();
    if (!$conn) {
        return false;
    }
    $mssv = mysqli_real_escape_string($conn, $mssv);
    $ho_ten = mysqli_real_escape_string($conn, $ho_ten);
    $email = $email ? mysqli_real_escape_string($conn, $email) : null;
    $so_dien_thoai = $so_dien_thoai ? mysqli_real_escape_string($conn, $so_dien_thoai) : null;
    $dia_chi = $dia_chi ? mysqli_real_escape_string($conn, $dia_chi) : null;
    $ngay_vao_doan = $ngay_vao_doan ? mysqli_real_escape_string($conn, $ngay_vao_doan) : null;
    $sql = "INSERT INTO doan_vien (mssv, ho_ten, ngay_sinh, gioi_tinh, email, so_dien_thoai, dia_chi, ngay_vao_doan, chi_doan_id, trang_thai) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssssssssis", $mssv, $ho_ten, $ngay_sinh, $gioi_tinh, $email, $so_dien_thoai, $dia_chi, $ngay_vao_doan, $chi_doan_id, $trang_thai);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return $success;
    }
    mysqli_close($conn);
    return false;
}

function getMemberById($id) {
    $conn = getDbConnection();
    if (!$conn) {
        return null;
    }
    $sql = "SELECT id, mssv, ho_ten, ngay_sinh, gioi_tinh, email, so_dien_thoai, dia_chi, ngay_vao_doan, chi_doan_id, trang_thai 
            FROM doan_vien WHERE id = ? LIMIT 1";
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

function updateMember($id, $mssv, $ho_ten, $ngay_sinh, $gioi_tinh, $email, $so_dien_thoai, $dia_chi, $ngay_vao_doan, $chi_doan_id, $trang_thai) {
    $conn = getDbConnection();
    if (!$conn) {
        return false;
    }
    $mssv = mysqli_real_escape_string($conn, $mssv);
    $ho_ten = mysqli_real_escape_string($conn, $ho_ten);
    $email = $email ? mysqli_real_escape_string($conn, $email) : null;
    $so_dien_thoai = $so_dien_thoai ? mysqli_real_escape_string($conn, $so_dien_thoai) : null;
    $dia_chi = $dia_chi ? mysqli_real_escape_string($conn, $dia_chi) : null;
    $ngay_vao_doan = $ngay_vao_doan ? mysqli_real_escape_string($conn, $ngay_vao_doan) : null;
    $sql = "UPDATE doan_vien SET mssv = ?, ho_ten = ?, ngay_sinh = ?, gioi_tinh = ?, email = ?, so_dien_thoai = ?, 
            dia_chi = ?, ngay_vao_doan = ?, chi_doan_id = ?, trang_thai = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssssssssisi", $mssv, $ho_ten, $ngay_sinh, $gioi_tinh, $email, $so_dien_thoai, $dia_chi, $ngay_vao_doan, $chi_doan_id, $trang_thai, $id);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return $success;
    }
    mysqli_close($conn);
    return false;
}

function deleteMember($id) {
    $conn = getDbConnection();
    if (!$conn) {
        return false;
    }
    $sql = "DELETE FROM doan_vien WHERE id = ?";
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