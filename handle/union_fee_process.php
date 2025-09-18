<?php
require_once __DIR__ . '/../functions/union_fee_functions.php';

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
        handleAddFee();
        break;
    case 'edit':
        handleEditFee();
        break;
    case 'delete':
        handleDeleteFee();
        break;
    default:
        $_SESSION['error'] = 'Hành động không hợp lệ';
        header("Location: /BTL/views/union_fee.php");
        exit();
}

/**
 * Handle adding a new union fee record
 */
function handleAddFee() {
    $doan_vien_id = filter_input(INPUT_POST, 'doan_vien_id', FILTER_VALIDATE_INT) ?? 0;
    $loai_doan_phi_id = filter_input(INPUT_POST, 'loai_doan_phi_id', FILTER_VALIDATE_INT) ?? 0;
    $nam_hoc = filter_input(INPUT_POST, 'nam_hoc', FILTER_SANITIZE_STRING) ?? '';
    $so_tien = filter_input(INPUT_POST, 'so_tien', FILTER_VALIDATE_FLOAT) ?? 0;
    $ngay_nop = filter_input(INPUT_POST, 'ngay_nop', FILTER_SANITIZE_STRING) ?? null;
    $trang_thai = filter_input(INPUT_POST, 'trang_thai', FILTER_SANITIZE_STRING) ?? 'Chưa nộp';

    if ($doan_vien_id <= 0 || $loai_doan_phi_id <= 0 || empty($nam_hoc) || $so_tien <= 0) {
        $_SESSION['error'] = 'Dữ liệu không hợp lệ';
        header("Location: /BTL/views/union_fee_create.php");
        exit();
    }

    if (addFee($doan_vien_id, $loai_doan_phi_id, $nam_hoc, $so_tien, $ngay_nop, $trang_thai)) {
        $_SESSION['success'] = 'Thêm đoàn phí thành công';
        header("Location: /BTL/views/union_fee.php");
        exit();
    } else {
        $_SESSION['error'] = 'Thêm đoàn phí thất bại';
        header("Location: /BTL/views/union_fee_create.php");
        exit();
    }
}

/**
 * Handle editing a union fee record
 */
function handleEditFee() {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT) ?? 0;
    $doan_vien_id = filter_input(INPUT_POST, 'doan_vien_id', FILTER_VALIDATE_INT) ?? 0;
    $loai_doan_phi_id = filter_input(INPUT_POST, 'loai_doan_phi_id', FILTER_VALIDATE_INT) ?? 0;
    $nam_hoc = filter_input(INPUT_POST, 'nam_hoc', FILTER_SANITIZE_STRING) ?? '';
    $so_tien = filter_input(INPUT_POST, 'so_tien', FILTER_VALIDATE_FLOAT) ?? 0;
    $ngay_nop = filter_input(INPUT_POST, 'ngay_nop', FILTER_SANITIZE_STRING) ?? null;
    $trang_thai = filter_input(INPUT_POST, 'trang_thai', FILTER_SANITIZE_STRING) ?? 'Chưa nộp';

    if ($id <= 0 || $doan_vien_id <= 0 || $loai_doan_phi_id <= 0 || empty($nam_hoc) || $so_tien <= 0) {
        $_SESSION['error'] = 'Dữ liệu không hợp lệ';
        header("Location: /BTL/views/union_fee_edit.php?id=$id");
        exit();
    }

    if (updateFee($id, $doan_vien_id, $loai_doan_phi_id, $nam_hoc, $so_tien, $ngay_nop, $trang_thai)) {
        $_SESSION['success'] = 'Sửa đoàn phí thành công';
        header("Location: /BTL/views/union_fee.php");
        exit();
    } else {
        $_SESSION['error'] = 'Sửa đoàn phí thất bại';
        header("Location: /BTL/views/union_fee_edit.php?id=$id");
        exit();
    }
}

/**
 * Handle deleting a union fee record
 */
function handleDeleteFee() {
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?? 0;

    if ($id <= 0) {
        $_SESSION['error'] = 'ID không hợp lệ';
        header("Location: /BTL/views/union_fee.php");
        exit();
    }

    if (deleteFee($id)) {
        $_SESSION['success'] = 'Xóa đoàn phí thành công';
        header("Location: /BTL/views/union_fee.php");
        exit();
    } else {
        $_SESSION['error'] = 'Xóa đoàn phí thất bại';
        header("Location: /BTL/views/union_fee.php");
        exit();
    }
}
?>