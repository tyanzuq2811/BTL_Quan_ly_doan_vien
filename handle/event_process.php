<?php
require_once __DIR__ . '/../functions/event_functions.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING) ?? '';

switch ($action) {
    case 'add':
        handleAddEvent();
        break;
    case 'edit':
        handleEditEvent();
        break;
    case 'delete':
        handleDeleteEvent();
        break;
    default:
        $_SESSION['error'] = 'Hành động không hợp lệ';
        header("Location: /BTL/views/event.php");
        exit();
}

function handleAddEvent() {
    $ten_su_kien = filter_input(INPUT_POST, 'ten_su_kien', FILTER_SANITIZE_STRING) ?? '';
    $mo_ta = filter_input(INPUT_POST, 'mo_ta', FILTER_SANITIZE_STRING) ?? null;
    $ngay_to_chuc = filter_input(INPUT_POST, 'ngay_to_chuc', FILTER_SANITIZE_STRING) ?? '';
    $cap_to_chuc = filter_input(INPUT_POST, 'cap_to_chuc', FILTER_SANITIZE_STRING) ?? '';
    $cap_id = filter_input(INPUT_POST, 'cap_id', FILTER_VALIDATE_INT) ?? 0;
    $trang_thai = filter_input(INPUT_POST, 'trang_thai', FILTER_SANITIZE_STRING) ?? 'Sắp diễn ra';

    if (empty($ten_su_kien) || empty($ngay_to_chuc) || empty($cap_to_chuc) || $cap_id <= 0) {
        $_SESSION['error'] = 'Dữ liệu không hợp lệ';
        header("Location: /BTL/views/event_create.php");
        exit();
    }

    if (addEvent($ten_su_kien, $mo_ta, $ngay_to_chuc, $cap_to_chuc, $cap_id, $trang_thai)) {
        $_SESSION['success'] = 'Thêm sự kiện thành công';
        header("Location: /BTL/views/event.php");
        exit();
    } else {
        $_SESSION['error'] = 'Thêm sự kiện thất bại';
        header("Location: /BTL/views/event_create.php");
        exit();
    }
}

function handleEditEvent() {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT) ?? 0;
    $ten_su_kien = filter_input(INPUT_POST, 'ten_su_kien', FILTER_SANITIZE_STRING) ?? '';
    $mo_ta = filter_input(INPUT_POST, 'mo_ta', FILTER_SANITIZE_STRING) ?? null;
    $ngay_to_chuc = filter_input(INPUT_POST, 'ngay_to_chuc', FILTER_SANITIZE_STRING) ?? '';
    $cap_to_chuc = filter_input(INPUT_POST, 'cap_to_chuc', FILTER_SANITIZE_STRING) ?? '';
    $cap_id = filter_input(INPUT_POST, 'cap_id', FILTER_VALIDATE_INT) ?? 0;
    $trang_thai = filter_input(INPUT_POST, 'trang_thai', FILTER_SANITIZE_STRING) ?? 'Sắp diễn ra';

    if ($id <= 0 || empty($ten_su_kien) || empty($ngay_to_chuc) || empty($cap_to_chuc) || $cap_id <= 0) {
        $_SESSION['error'] = 'Dữ liệu không hợp lệ';
        header("Location: /BTL/views/event_edit.php?id=$id");
        exit();
    }

    if (updateEvent($id, $ten_su_kien, $mo_ta, $ngay_to_chuc, $cap_to_chuc, $cap_id, $trang_thai)) {
        $_SESSION['success'] = 'Sửa sự kiện thành công';
        header("Location: /BTL/views/event.php");
        exit();
    } else {
        $_SESSION['error'] = 'Sửa sự kiện thất bại';
        header("Location: /BTL/views/event_edit.php?id=$id");
        exit();
    }
}

function handleDeleteEvent() {
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?? 0;

    if ($id <= 0) {
        $_SESSION['error'] = 'ID không hợp lệ';
        header("Location: /BTL/views/event.php");
        exit();
    }

    if (deleteEvent($id)) {
        $_SESSION['success'] = 'Xóa sự kiện thành công';
        header("Location: /BTL/views/event.php");
        exit();
    } else {
        $_SESSION['error'] = 'Xóa sự kiện thất bại';
        header("Location: /BTL/views/event.php");
        exit();
    }
}
?>