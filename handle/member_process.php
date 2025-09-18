<?php
require_once __DIR__ . '/../functions/member_functions.php';

// Enable error reporting for development (remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start session for consistent error handling
session_start();

// Determine action
$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING) ?? '';

switch ($action) {
    case 'add':
        handleAddMember();
        break;
    case 'edit':
        handleEditMember();
        break;
    case 'delete':
        handleDeleteMember();
        break;
    default:
        $_SESSION['error'] = 'Hành động không hợp lệ';
        header("Location: /BTL/views/doan_vien.php");
        exit();
}

/**
 * Handle adding a new member
 */
function handleAddMember() {
    $mssv = filter_input(INPUT_POST, 'mssv', FILTER_SANITIZE_STRING) ?? '';
    $ho_ten = filter_input(INPUT_POST, 'ho_ten', FILTER_SANITIZE_STRING) ?? '';
    $ngay_sinh = filter_input(INPUT_POST, 'ngay_sinh', FILTER_SANITIZE_STRING) ?? '';
    $gioi_tinh = filter_input(INPUT_POST, 'gioi_tinh', FILTER_SANITIZE_STRING) ?? '';
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL) ?? null;
    $so_dien_thoai = filter_input(INPUT_POST, 'so_dien_thoai', FILTER_SANITIZE_STRING) ?? null;
    $dia_chi = filter_input(INPUT_POST, 'dia_chi', FILTER_SANITIZE_STRING) ?? null;
    $ngay_vao_doan = filter_input(INPUT_POST, 'ngay_vao_doan', FILTER_SANITIZE_STRING) ?? null;
    $chi_doan_id = filter_input(INPUT_POST, 'chi_doan_id', FILTER_VALIDATE_INT) ?: null;
    $trang_thai = filter_input(INPUT_POST, 'trang_thai', FILTER_SANITIZE_STRING) ?? 'Hoạt động';

    if (empty($mssv) || empty($ho_ten) || empty($ngay_sinh) || empty($gioi_tinh)) {
        $_SESSION['error'] = 'Dữ liệu không hợp lệ';
        header("Location: /BTL/views/doan_vien_create.php");
        exit();
    }

    if (addMember($mssv, $ho_ten, $ngay_sinh, $gioi_tinh, $email, $so_dien_thoai, $dia_chi, $ngay_vao_doan, $chi_doan_id, $trang_thai)) {
        $_SESSION['success'] = 'Thêm đoàn viên thành công';
        header("Location: /BTL/views/doan_vien.php");
        exit();
    } else {
        $_SESSION['error'] = 'Thêm đoàn viên thất bại';
        header("Location: /BTL/views/doan_vien_create.php");
        exit();
    }
}

/**
 * Handle editing a member
 */
function handleEditMember() {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT) ?? 0;
    $mssv = filter_input(INPUT_POST, 'mssv', FILTER_SANITIZE_STRING) ?? '';
    $ho_ten = filter_input(INPUT_POST, 'ho_ten', FILTER_SANITIZE_STRING) ?? '';
    $ngay_sinh = filter_input(INPUT_POST, 'ngay_sinh', FILTER_SANITIZE_STRING) ?? '';
    $gioi_tinh = filter_input(INPUT_POST, 'gioi_tinh', FILTER_SANITIZE_STRING) ?? '';
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL) ?? null;
    $so_dien_thoai = filter_input(INPUT_POST, 'so_dien_thoai', FILTER_SANITIZE_STRING) ?? null;
    $dia_chi = filter_input(INPUT_POST, 'dia_chi', FILTER_SANITIZE_STRING) ?? null;
    $ngay_vao_doan = filter_input(INPUT_POST, 'ngay_vao_doan', FILTER_SANITIZE_STRING) ?? null;
    $chi_doan_id = filter_input(INPUT_POST, 'chi_doan_id', FILTER_VALIDATE_INT) ?: null;
    $trang_thai = filter_input(INPUT_POST, 'trang_thai', FILTER_SANITIZE_STRING) ?? 'Hoạt động';

    if ($id <= 0 || empty($mssv) || empty($ho_ten) || empty($ngay_sinh) || empty($gioi_tinh)) {
        $_SESSION['error'] = 'Dữ liệu không hợp lệ';
        header("Location: /BTL/views/doan_vien_edit.php?id=$id");
        exit();
    }

    if (updateMember($id, $mssv, $ho_ten, $ngay_sinh, $gioi_tinh, $email, $so_dien_thoai, $dia_chi, $ngay_vao_doan, $chi_doan_id, $trang_thai)) {
        $_SESSION['success'] = 'Sửa đoàn viên thành công';
        header("Location: /BTL/views/doan_vien.php");
        exit();
    } else {
        $_SESSION['error'] = 'Sửa đoàn viên thất bại';
        header("Location: /BTL/views/doan_vien_edit.php?id=$id");
        exit();
    }
}

/**
 * Handle deleting a member
 */
function handleDeleteMember() {
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?? 0;

    if ($id <= 0) {
        $_SESSION['error'] = 'ID không hợp lệ';
        header("Location: /BTL/views/doan_vien.php");
        exit();
    }

    if (deleteMember($id)) {
        $_SESSION['success'] = 'Xóa đoàn viên thành công';
        header("Location: /BTL/views/doan_vien.php");
        exit();
    } else {
        $_SESSION['error'] = 'Xóa đoàn viên thất bại';
        header("Location: /BTL/views/doan_vien.php");
        exit();
    }
}
?>