<?php
session_start();
require_once __DIR__ . '/../functions/db_connection.php'; 
require_once __DIR__ . '/../functions/account_functions.php';

$action = filter_input(INPUT_GET, 'action', FILTER_DEFAULT) ?? '';

function handleAddAccount() {
    $doan_vien_id = filter_input(INPUT_POST, 'doan_vien_id', FILTER_VALIDATE_INT) ?? null;
    $ten_dang_nhap = trim(filter_input(INPUT_POST, 'ten_dang_nhap', FILTER_DEFAULT) ?? '');
    $mat_khau = trim(filter_input(INPUT_POST, 'mat_khau', FILTER_DEFAULT) ?? '');
    $vai_tro = trim(filter_input(INPUT_POST, 'vai_tro', FILTER_DEFAULT) ?? '');
    $trang_thai = trim(filter_input(INPUT_POST, 'trang_thai', FILTER_DEFAULT) ?? '');

    if (usernameExists($ten_dang_nhap)) {
        $_SESSION['error'] = 'Tên đăng nhập đã tồn tại. Vui lòng chọn tên khác.';
        header('Location: /BTL/views/account/account_create.php');
        exit();
    }

    if (empty($mat_khau)) {
        $_SESSION['error'] = 'Mật khẩu không được để trống.';
        header('Location: /BTL/views/account/account_create.php');
        exit();
    }

    if (addAccount($doan_vien_id, $ten_dang_nhap, $mat_khau, $vai_tro, $trang_thai)) {
        $_SESSION['success'] = 'Thêm tài khoản thành công.';
    } else {
        $_SESSION['error'] = 'Thêm tài khoản thất bại. Vui lòng thử lại.';
    }
    header('Location: /BTL/views/account.php');
    exit();
}

function handleEditAccount() {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT) ?? 0;
    $doan_vien_id = filter_input(INPUT_POST, 'doan_vien_id', FILTER_VALIDATE_INT) ?? null;
    $ten_dang_nhap = trim(filter_input(INPUT_POST, 'ten_dang_nhap', FILTER_DEFAULT) ?? '');
    $mat_khau = trim(filter_input(INPUT_POST, 'mat_khau', FILTER_DEFAULT) ?? '');
    $vai_tro = trim(filter_input(INPUT_POST, 'vai_tro', FILTER_DEFAULT) ?? '');
    $trang_thai = trim(filter_input(INPUT_POST, 'trang_thai', FILTER_DEFAULT) ?? '');

    $current_account = getAccountById($id);
    $new_password = !empty($mat_khau) ? $mat_khau : null; // Chỉ sử dụng mật khẩu mới nếu có

    if (usernameExists($ten_dang_nhap) && $current_account['ten_dang_nhap'] !== $ten_dang_nhap) {
        $_SESSION['error'] = 'Tên đăng nhập đã tồn tại. Vui lòng chọn tên khác.';
        header('Location: /BTL/views/account/account_edit.php?id=' . $id);
        exit();
    }

    if (updateAccount($id, $doan_vien_id, $ten_dang_nhap, $new_password, $vai_tro, $trang_thai)) {
        $_SESSION['success'] = 'Cập nhật tài khoản thành công.';
    } else {
        $_SESSION['error'] = 'Cập nhật tài khoản thất bại. Vui lòng thử lại.';
    }
    header('Location: /BTL/views/account.php');
    exit();
}

function handleDeleteAccount() {
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?? 0;
    if (deleteAccount($id)) {
        $_SESSION['success'] = 'Xóa tài khoản thành công.';
    } else {
        $_SESSION['error'] = 'Xóa tài khoản thất bại. Vui lòng thử lại.';
    }
    header('Location: /BTL/views/account.php');
    exit();
}

switch ($action) {
    case 'add':
        handleAddAccount();
        break;
    case 'edit':
        handleEditAccount();
        break;
    case 'delete':
        handleDeleteAccount();
        break;
    default:
        header('Location: /BTL/views/account.php');
        exit();
}
?>