<?php
require_once __DIR__ . '/../functions/event_participation_functions.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING) ?? '';

switch ($action) {
    case 'add':
        handleAddParticipation();
        break;
    case 'edit':
        handleEditParticipation();
        break;
    case 'delete':
        handleDeleteParticipation();
        break;
    default:
        $_SESSION['error'] = 'Hành động không hợp lệ';
        header("Location: /BTL/views/event_participation.php");
        exit();
}

function handleAddParticipation() {
    $su_kien_id = filter_input(INPUT_POST, 'su_kien_id', FILTER_VALIDATE_INT) ?? 0;
    $doan_vien_id = filter_input(INPUT_POST, 'doan_vien_id', FILTER_VALIDATE_INT) ?? 0;
    $trang_thai = filter_input(INPUT_POST, 'trang_thai', FILTER_SANITIZE_STRING) ?? 'Đã đăng ký';

    if ($su_kien_id <= 0 || $doan_vien_id <= 0) {
        $_SESSION['error'] = 'Dữ liệu không hợp lệ';
        header("Location: /BTL/views/event_participation_create.php");
        exit();
    }

    if (addParticipation($su_kien_id, $doan_vien_id, $trang_thai)) {
        $_SESSION['success'] = 'Thêm tham gia sự kiện thành công';
        header("Location: /BTL/views/event_participation.php");
        exit();
    } else {
        $_SESSION['error'] = 'Thêm tham gia sự kiện thất bại. Có thể bản ghi đã tồn tại.';
        header("Location: /BTL/views/event_participation_create.php");
        exit();
    }
}

function handleEditParticipation() {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT) ?? 0;
    $su_kien_id = filter_input(INPUT_POST, 'su_kien_id', FILTER_VALIDATE_INT) ?? 0;
    $doan_vien_id = filter_input(INPUT_POST, 'doan_vien_id', FILTER_VALIDATE_INT) ?? 0;
    $trang_thai = filter_input(INPUT_POST, 'trang_thai', FILTER_SANITIZE_STRING) ?? 'Đã đăng ký';

    if ($id <= 0 || $su_kien_id <= 0 || $doan_vien_id <= 0) {
        $_SESSION['error'] = 'Dữ liệu không hợp lệ';
        header("Location: /BTL/views/event_participation_edit.php?id=$id");
        exit();
    }

    if (updateParticipation($id, $su_kien_id, $doan_vien_id, $trang_thai)) {
        $_SESSION['success'] = 'Sửa tham gia sự kiện thành công';
        header("Location: /BTL/views/event_participation.php");
        exit();
    } else {
        $_SESSION['error'] = 'Sửa tham gia sự kiện thất bại. Có thể bản ghi đã tồn tại.';
        header("Location: /BTL/views/event_participation_edit.php?id=$id");
        exit();
    }
}

function handleDeleteParticipation() {
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?? 0;

    if ($id <= 0) {
        $_SESSION['error'] = 'ID không hợp lệ';
        header("Location: /BTL/views/event_participation.php");
        exit();
    }

    if (deleteParticipation($id)) {
        $_SESSION['success'] = 'Xóa tham gia sự kiện thành công';
        header("Location: /BTL/views/event_participation.php");
        exit();
    } else {
        $_SESSION['error'] = 'Xóa tham gia sự kiện thất bại';
        header("Location: /BTL/views/event_participation.php");
        exit();
    }
}
?>