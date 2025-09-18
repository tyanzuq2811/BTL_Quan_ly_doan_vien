<?php
require_once __DIR__ . '/../functions/participation_history_functions.php';

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
        handleAddHistory();
        break;
    case 'edit':
        handleEditHistory();
        break;
    case 'delete':
        handleDeleteHistory();
        break;
    default:
        $_SESSION['error'] = 'Hành động không hợp lệ';
        header("Location: /BTL/views/participation_history.php");
        exit();
}

/**
 * Handle adding a new history record
 */
function handleAddHistory() {
    $doan_vien_id = filter_input(INPUT_POST, 'doan_vien_id', FILTER_VALIDATE_INT) ?? 0;
    $chi_doan_id = filter_input(INPUT_POST, 'chi_doan_id', FILTER_VALIDATE_INT) ?? 0;
    $ngay_bat_dau = filter_input(INPUT_POST, 'ngay_bat_dau', FILTER_SANITIZE_STRING) ?? '';
    $ngay_ket_thuc = filter_input(INPUT_POST, 'ngay_ket_thuc', FILTER_SANITIZE_STRING) ?? null;
    $trang_thai = filter_input(INPUT_POST, 'trang_thai', FILTER_SANITIZE_STRING) ?? 'Đang sinh hoạt';

    if ($doan_vien_id <= 0 || $chi_doan_id <= 0 || empty($ngay_bat_dau)) {
        $_SESSION['error'] = 'Dữ liệu không hợp lệ';
        header("Location: /BTL/views/participation_history_create.php");
        exit();
    }

    if (addHistory($doan_vien_id, $chi_doan_id, $ngay_bat_dau, $ngay_ket_thuc, $trang_thai)) {
        $_SESSION['success'] = 'Thêm lịch sử tham gia thành công';
        header("Location: /BTL/views/participation_history.php");
        exit();
    } else {
        $_SESSION['error'] = 'Thêm lịch sử tham gia thất bại';
        header("Location: /BTL/views/participation_history_create.php");
        exit();
    }
}

/**
 * Handle editing a history record
 */
function handleEditHistory() {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT) ?? 0;
    $doan_vien_id = filter_input(INPUT_POST, 'doan_vien_id', FILTER_VALIDATE_INT) ?? 0;
    $chi_doan_id = filter_input(INPUT_POST, 'chi_doan_id', FILTER_VALIDATE_INT) ?? 0;
    $ngay_bat_dau = filter_input(INPUT_POST, 'ngay_bat_dau', FILTER_SANITIZE_STRING) ?? '';
    $ngay_ket_thuc = filter_input(INPUT_POST, 'ngay_ket_thuc', FILTER_SANITIZE_STRING) ?? null;
    $trang_thai = filter_input(INPUT_POST, 'trang_thai', FILTER_SANITIZE_STRING) ?? 'Đang sinh hoạt';

    if ($id <= 0 || $doan_vien_id <= 0 || $chi_doan_id <= 0 || empty($ngay_bat_dau)) {
        $_SESSION['error'] = 'Dữ liệu không hợp lệ';
        header("Location: /BTL/views/participation_history_edit.php?id=$id");
        exit();
    }

    if (updateHistory($id, $doan_vien_id, $chi_doan_id, $ngay_bat_dau, $ngay_ket_thuc, $trang_thai)) {
        $_SESSION['success'] = 'Sửa lịch sử tham gia thành công';
        header("Location: /BTL/views/participation_history.php");
        exit();
    } else {
        $_SESSION['error'] = 'Sửa lịch sử tham gia thất bại';
        header("Location: /BTL/views/participation_history_edit.php?id=$id");
        exit();
    }
}

/**
 * Handle deleting a history record
 */
function handleDeleteHistory() {
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?? 0;

    if ($id <= 0) {
        $_SESSION['error'] = 'ID không hợp lệ';
        header("Location: /BTL/views/participation_history.php");
        exit();
    }

    if (deleteHistory($id)) {
        $_SESSION['success'] = 'Xóa lịch sử tham gia thành công';
        header("Location: /BTL/views/participation_history.php");
        exit();
    } else {
        $_SESSION['error'] = 'Xóa lịch sử tham gia thất bại';
        header("Location: /BTL/views/participation_history.php");
        exit();
    }
}
?>