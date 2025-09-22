<?php
require_once __DIR__ . '/../functions/award_functions.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

$action = filter_input(INPUT_GET, 'action', FILTER_DEFAULT) ?? '';

switch ($action) {
    case 'add':
        handleAddAward();
        break;
    case 'edit':
        handleEditAward();
        break;
    case 'delete':
        handleDeleteAward();
        break;
    default:
        $_SESSION['error'] = 'Hành động không hợp lệ';
        header("Location: /BTL/views/award.php");
        exit();
}

function handleAddAward() {
    $doan_vien_id = filter_input(INPUT_POST, 'doan_vien_id', FILTER_DEFAULT) ?? 0;
    $loai_id = filter_input(INPUT_POST, 'loai_id', FILTER_DEFAULT) ?? 0;
    $ngay_quyet_dinh = filter_input(INPUT_POST, 'ngay_quyet_dinh', FILTER_DEFAULT) ?? '';
    $noi_dung = filter_input(INPUT_POST, 'noi_dung', FILTER_DEFAULT) ?? null;

    if ($doan_vien_id <= 0 || $loai_id <= 0 || empty($ngay_quyet_dinh)) {
        $_SESSION['error'] = 'Dữ liệu không hợp lệ';
        header("Location: /BTL/views/award/award_create.php");
        exit();
    }

    if (addAward($doan_vien_id, $loai_id, $ngay_quyet_dinh, $noi_dung)) {
        $_SESSION['success'] = 'Thêm khen thưởng thành công';
        header("Location: /BTL/views/award.php");
        exit();
    } else {
        $_SESSION['error'] = 'Thêm khen thưởng thất bại';
        header("Location: /BTL/views/award/award_create.php");
        exit();
    }
}

function handleEditAward() {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT) ?? 0;
    $doan_vien_id = filter_input(INPUT_POST, 'doan_vien_id', FILTER_VALIDATE_INT) ?? 0;
    $loai_id = filter_input(INPUT_POST, 'loai_id', FILTER_VALIDATE_INT) ?? 0;
    $ngay_quyet_dinh = filter_input(INPUT_POST, 'ngay_quyet_dinh', FILTER_DEFAULT) ?? '';
    $noi_dung = filter_input(INPUT_POST, 'noi_dung', FILTER_DEFAULT) ?? null;

    if ($id <= 0 || $doan_vien_id <= 0 || $loai_id <= 0 || empty($ngay_quyet_dinh)) {
        $_SESSION['error'] = 'Dữ liệu không hợp lệ';
        header("Location: /BTL/views/award/award_edit.php?id=$id");
        exit();
    }

    if (updateAward($id, $doan_vien_id, $loai_id, $ngay_quyet_dinh, $noi_dung)) {
        $_SESSION['success'] = 'Sửa khen thưởng thành công';
        header("Location: /BTL/views/award.php");
        exit();
    } else {
        $_SESSION['error'] = 'Sửa khen thưởng thất bại';
        header("Location: /BTL/views/award/award_edit.php?id=$id");
        exit();
    }
}

function handleDeleteAward() {
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?? 0;

    if ($id <= 0) {
        $_SESSION['error'] = 'ID không hợp lệ';
        header("Location: /BTL/views/award.php");
        exit();
    }

    if (deleteAward($id)) {
        $_SESSION['success'] = 'Xóa khen thưởng thành công';
        header("Location: /BTL/views/award.php");
        exit();
    } else {
        $_SESSION['error'] = 'Xóa khen thưởng thất bại';
        header("Location: /BTL/views/award.php");
        exit();
    }
}
?>