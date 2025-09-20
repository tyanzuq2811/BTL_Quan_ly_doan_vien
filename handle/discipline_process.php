<?php
require_once __DIR__ . '/../functions/discipline_functions.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING) ?? '';

switch ($action) {
    case 'add':
        handleAddDiscipline();
        break;
    case 'edit':
        handleEditDiscipline();
        break;
    case 'delete':
        handleDeleteDiscipline();
        break;
    default:
        $_SESSION['error'] = 'Hành động không hợp lệ';
        header("Location: /BTL/views/discipline.php");
        exit();
}

function handleAddDiscipline() {
    $doan_vien_id = filter_input(INPUT_POST, 'doan_vien_id', FILTER_VALIDATE_INT) ?? 0;
    $loai_id = filter_input(INPUT_POST, 'loai_id', FILTER_VALIDATE_INT) ?? 0;
    $ngay_quyet_dinh = filter_input(INPUT_POST, 'ngay_quyet_dinh', FILTER_SANITIZE_STRING) ?? '';
    $noi_dung = filter_input(INPUT_POST, 'noi_dung', FILTER_SANITIZE_STRING) ?? null;

    if ($doan_vien_id <= 0 || $loai_id <= 0 || empty($ngay_quyet_dinh)) {
        $_SESSION['error'] = 'Dữ liệu không hợp lệ';
        header("Location: /BTL/views/discipline_create.php");
        exit();
    }

    if (addDiscipline($doan_vien_id, $loai_id, $ngay_quyet_dinh, $noi_dung)) {
        $_SESSION['success'] = 'Thêm kỷ luật thành công';
        header("Location: /BTL/views/discipline.php");
        exit();
    } else {
        $_SESSION['error'] = 'Thêm kỷ luật thất bại';
        header("Location: /BTL/views/discipline_create.php");
        exit();
    }
}

function handleEditDiscipline() {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT) ?? 0;
    $doan_vien_id = filter_input(INPUT_POST, 'doan_vien_id', FILTER_VALIDATE_INT) ?? 0;
    $loai_id = filter_input(INPUT_POST, 'loai_id', FILTER_VALIDATE_INT) ?? 0;
    $ngay_quyet_dinh = filter_input(INPUT_POST, 'ngay_quyet_dinh', FILTER_SANITIZE_STRING) ?? '';
    $noi_dung = filter_input(INPUT_POST, 'noi_dung', FILTER_SANITIZE_STRING) ?? null;

    if ($id <= 0 || $doan_vien_id <= 0 || $loai_id <= 0 || empty($ngay_quyet_dinh)) {
        $_SESSION['error'] = 'Dữ liệu không hợp lệ';
        header("Location: /BTL/views/discipline_edit.php?id=$id");
        exit();
    }

    if (updateDiscipline($id, $doan_vien_id, $loai_id, $ngay_quyet_dinh, $noi_dung)) {
        $_SESSION['success'] = 'Sửa kỷ luật thành công';
        header("Location: /BTL/views/discipline.php");
        exit();
    } else {
        $_SESSION['error'] = 'Sửa kỷ luật thất bại';
        header("Location: /BTL/views/discipline_edit.php?id=$id");
        exit();
    }
}

function handleDeleteDiscipline() {
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?? 0;

    if ($id <= 0) {
        $_SESSION['error'] = 'ID không hợp lệ';
        header("Location: /BTL/views/discipline.php");
        exit();
    }

    if (deleteDiscipline($id)) {
        $_SESSION['success'] = 'Xóa kỷ luật thành công';
        header("Location: /BTL/views/discipline.php");
        exit();
    } else {
        $_SESSION['error'] = 'Xóa kỷ luật thất bại';
        header("Location: /BTL/views/discipline.php");
        exit();
    }
}
?>