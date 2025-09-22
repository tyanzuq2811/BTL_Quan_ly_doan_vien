<?php
require_once __DIR__ . '/../functions/notification_functions.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING) ?? '';

switch ($action) {
    case 'add':
        handleAddNotification();
        break;
    case 'edit':
        handleEditNotification();
        break;
    case 'delete':
        handleDeleteNotification();
        break;
    default:
        $_SESSION['error'] = 'Hành động không hợp lệ';
        header("Location: /BTL/views/notification.php");
        exit();
}

function handleAddNotification() {
    $tieu_de = filter_input(INPUT_POST, 'tieu_de', FILTER_SANITIZE_STRING) ?? '';
    $noi_dung = filter_input(INPUT_POST, 'noi_dung', FILTER_SANITIZE_STRING) ?? '';
    $cap_to_chuc = filter_input(INPUT_POST, 'cap_to_chuc', FILTER_SANITIZE_STRING) ?? '';
    $cap_id = filter_input(INPUT_POST, 'cap_id', FILTER_VALIDATE_INT) ?? 0;
    $nguoi_gui = filter_input(INPUT_POST, 'nguoi_gui', FILTER_VALIDATE_INT) ?? null;

    if (empty($tieu_de) || empty($noi_dung) || empty($cap_to_chuc) || $cap_id <= 0) {
        $_SESSION['error'] = 'Dữ liệu không hợp lệ';
        header("Location: /BTL/views/notification/notification_create.php");
        exit();
    }

    if (!in_array($cap_to_chuc, ['DoanTruong', 'LienChi', 'ChiDoan'])) {
        $_SESSION['error'] = 'Cấp tổ chức không hợp lệ';
        header("Location: /BTL/views/notification/notification_create.php");
        exit();
    }

    if (addNotification($tieu_de, $noi_dung, $cap_to_chuc, $cap_id, $nguoi_gui)) {
        $_SESSION['success'] = 'Thêm thông báo thành công';
        header("Location: /BTL/views/notification.php");
        exit();
    } else {
        $_SESSION['error'] = 'Thêm thông báo thất bại';
        header("Location: /BTL/views/notification/notification_create.php");
        exit();
    }
}

function handleEditNotification() {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT) ?? 0;
    $tieu_de = filter_input(INPUT_POST, 'tieu_de', FILTER_SANITIZE_STRING) ?? '';
    $noi_dung = filter_input(INPUT_POST, 'noi_dung', FILTER_SANITIZE_STRING) ?? '';
    $cap_to_chuc = filter_input(INPUT_POST, 'cap_to_chuc', FILTER_SANITIZE_STRING) ?? '';
    $cap_id = filter_input(INPUT_POST, 'cap_id', FILTER_VALIDATE_INT) ?? 0;
    $nguoi_gui = filter_input(INPUT_POST, 'nguoi_gui', FILTER_VALIDATE_INT) ?? null;

    if ($id <= 0 || empty($tieu_de) || empty($noi_dung) || empty($cap_to_chuc) || $cap_id <= 0) {
        $_SESSION['error'] = 'Dữ liệu không hợp lệ';
        header("Location: /BTL/views/notification/notification_edit.php?id=$id");
        exit();
    }

    if (!in_array($cap_to_chuc, ['DoanTruong', 'LienChi', 'ChiDoan'])) {
        $_SESSION['error'] = 'Cấp tổ chức không hợp lệ';
        header("Location: /BTL/views/notification/notification_edit.php?id=$id");
        exit();
    }

    if (updateNotification($id, $tieu_de, $noi_dung, $cap_to_chuc, $cap_id, $nguoi_gui)) {
        $_SESSION['success'] = 'Sửa thông báo thành công';
        header("Location: /BTL/views/notification.php");
        exit();
    } else {
        $_SESSION['error'] = 'Sửa thông báo thất bại';
        header("Location: /BTL/views/notification/notification_edit.php?id=$id");
        exit();
    }
}

function handleDeleteNotification() {
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?? 0;

    if ($id <= 0) {
        $_SESSION['error'] = 'ID không hợp lệ';
        header("Location: /BTL/views/notification.php");
        exit();
    }

    if (deleteNotification($id)) {
        $_SESSION['success'] = 'Xóa thông báo thành công';
        header("Location: /BTL/views/notification.php");
        exit();
    } else {
        $_SESSION['error'] = 'Xóa thông báo thất bại';
        header("Location: /BTL/views/notification.php");
        exit();
    }
}
?>