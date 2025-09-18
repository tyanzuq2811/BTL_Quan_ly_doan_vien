<?php
require_once __DIR__ . '/../functions/training_score_functions.php';

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
        handleAddScore();
        break;
    case 'edit':
        handleEditScore();
        break;
    case 'delete':
        handleDeleteScore();
        break;
    default:
        $_SESSION['error'] = 'Hành động không hợp lệ';
        header("Location: /BTL/views/training_score.php");
        exit();
}

/**
 * Handle adding a new training score record
 */
function handleAddScore() {
    $doan_vien_id = filter_input(INPUT_POST, 'doan_vien_id', FILTER_VALIDATE_INT) ?? 0;
    $nam_hoc = filter_input(INPUT_POST, 'nam_hoc', FILTER_SANITIZE_STRING) ?? '';
    $hoc_ky = filter_input(INPUT_POST, 'hoc_ky', FILTER_SANITIZE_STRING) ?? '';
    $diem = filter_input(INPUT_POST, 'diem', FILTER_VALIDATE_INT) ?? 0;
    $xep_loai = filter_input(INPUT_POST, 'xep_loai', FILTER_SANITIZE_STRING) ?? '';
    $ghi_chu = filter_input(INPUT_POST, 'ghi_chu', FILTER_SANITIZE_STRING) ?? null;

    if ($doan_vien_id <= 0 || empty($nam_hoc) || empty($hoc_ky) || $diem < 0 || $diem > 100 || empty($xep_loai)) {
        $_SESSION['error'] = 'Dữ liệu không hợp lệ';
        header("Location: /BTL/views/training_score_create.php");
        exit();
    }

    if (addScore($doan_vien_id, $nam_hoc, $hoc_ky, $diem, $xep_loai, $ghi_chu)) {
        $_SESSION['success'] = 'Thêm điểm rèn luyện thành công';
        header("Location: /BTL/views/training_score.php");
        exit();
    } else {
        $_SESSION['error'] = 'Thêm điểm rèn luyện thất bại. Có thể điểm cho học kỳ này đã tồn tại.';
        header("Location: /BTL/views/training_score_create.php");
        exit();
    }
}

/**
 * Handle editing a training score record
 */
function handleEditScore() {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT) ?? 0;
    $doan_vien_id = filter_input(INPUT_POST, 'doan_vien_id', FILTER_VALIDATE_INT) ?? 0;
    $nam_hoc = filter_input(INPUT_POST, 'nam_hoc', FILTER_SANITIZE_STRING) ?? '';
    $hoc_ky = filter_input(INPUT_POST, 'hoc_ky', FILTER_SANITIZE_STRING) ?? '';
    $diem = filter_input(INPUT_POST, 'diem', FILTER_VALIDATE_INT) ?? 0;
    $xep_loai = filter_input(INPUT_POST, 'xep_loai', FILTER_SANITIZE_STRING) ?? '';
    $ghi_chu = filter_input(INPUT_POST, 'ghi_chu', FILTER_SANITIZE_STRING) ?? null;

    if ($id <= 0 || $doan_vien_id <= 0 || empty($nam_hoc) || empty($hoc_ky) || $diem < 0 || $diem > 100 || empty($xep_loai)) {
        $_SESSION['error'] = 'Dữ liệu không hợp lệ';
        header("Location: /BTL/views/training_score_edit.php?id=$id");
        exit();
    }

    if (updateScore($id, $doan_vien_id, $nam_hoc, $hoc_ky, $diem, $xep_loai, $ghi_chu)) {
        $_SESSION['success'] = 'Sửa điểm rèn luyện thành công';
        header("Location: /BTL/views/training_score.php");
        exit();
    } else {
        $_SESSION['error'] = 'Sửa điểm rèn luyện thất bại. Có thể điểm cho học kỳ này đã tồn tại cho đoàn viên khác.';
        header("Location: /BTL/views/training_score_edit.php?id=$id");
        exit();
    }
}

/**
 * Handle deleting a training score record
 */
function handleDeleteScore() {
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?? 0;

    if ($id <= 0) {
        $_SESSION['error'] = 'ID không hợp lệ';
        header("Location: /BTL/views/training_score.php");
        exit();
    }

    if (deleteScore($id)) {
        $_SESSION['success'] = 'Xóa điểm rèn luyện thành công';
        header("Location: /BTL/views/training_score.php");
        exit();
    } else {
        $_SESSION['error'] = 'Xóa điểm rèn luyện thất bại';
        header("Location: /BTL/views/training_score.php");
        exit();
    }
}
?>