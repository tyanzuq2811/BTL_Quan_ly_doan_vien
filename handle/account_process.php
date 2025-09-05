<?php
require_once __DIR__ . '/../functions/account_functions.php';

// Lấy action
$action = '';
if (isset($_GET['action'])) {
    $action = $_GET['action'];
} elseif (isset($_POST['action'])) {
    $action = $_POST['action'];
}

switch ($action) {
    case 'create':
        handleCreateAccount();
        break;
    case 'edit':
        handleEditAccount();
        break;
    case 'delete':
        handleDeleteAccount();
        break;
    case '':
}

// Lấy tất cả tài khoản
function handleGetAllAccounts($keyword = '') {
    return getAllAccounts($keyword);
}

// Lấy tài khoản theo ID
function handleGetAccountById($id) {
    return getAccountById($id);
}

/**
 * Xử lý thêm tài khoản
 */
function handleCreateAccount() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: ../views/account/create_account.php?error=Invalid method");
        exit();
    }

    if (!isset($_POST['doan_vien_id']) || !isset($_POST['ten_dang_nhap']) || !isset($_POST['mat_khau'])) {
        header("Location: ../views/account/create_account.php?error=Missing required information");
        exit();
    }

    $doan_vien_id = $_POST['doan_vien_id'];
    $ten_dang_nhap = trim($_POST['ten_dang_nhap']);
    $mat_khau = $_POST['mat_khau'];
    $vai_tro = $_POST['vai_tro'] ?? 'DoanVien';
    $trang_thai = $_POST['trang_thai'] ?? 'Hoạt động';

    if (empty($doan_vien_id) || empty($ten_dang_nhap) || empty($mat_khau)) {
        header("Location: ../views/account/create_account.php?error=Please fill in all required fields");
        exit();
    }

    $result = addAccount($doan_vien_id, $ten_dang_nhap, $mat_khau, $vai_tro, $trang_thai);

    if ($result) {
        header("Location: ../views/account.php?success=Add account successfully");
    } else {
        header("Location: ../views/account/create_account.php?error=Failed to add account");
    }
    exit();
}

/**
 * Xử lý cập nhật tài khoản
 */
function handleEditAccount() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: ../views/account.php?error=Invalid method");
        exit();
    }

    if (!isset($_POST['id']) || !isset($_POST['doan_vien_id']) || !isset($_POST['ten_dang_nhap']) || !isset($_POST['mat_khau'])) {
        header("Location: ../views/account.php?error=Missing required information");
        exit();
    }

    $id = $_POST['id'];
    $doan_vien_id = $_POST['doan_vien_id'];
    $ten_dang_nhap = trim($_POST['ten_dang_nhap']);
    $mat_khau = $_POST['mat_khau'];
    $vai_tro = $_POST['vai_tro'] ?? 'DoanVien';
    $trang_thai = $_POST['trang_thai'] ?? 'Hoạt động';

    if (empty($doan_vien_id) || empty($ten_dang_nhap) || empty($mat_khau)) {
        header("Location: ../views/account/edit_account.php?id=" . $id . "&error=Please fill in all required fields");
        exit();
    }

    $result = updateAccount($id, $doan_vien_id, $ten_dang_nhap, $mat_khau, $vai_tro, $trang_thai);

    if ($result) {
        header("Location: ../views/account.php?success=Update account successfully");
    } else {
        header("Location: ../views/account/edit_account.php?id=" . $id . "&error=Failed to update account");
    }
    exit();
}

/**
 * Xử lý xóa tài khoản
 */
function handleDeleteAccount() {
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        header("Location: ../views/account.php?error=Invalid method");
        exit();
    }

    if (!isset($_GET['id']) || empty($_GET['id'])) {
        header("Location: ../views/account.php?error=Account ID not found");
        exit();
    }

    $id = $_GET['id'];
    if (!is_numeric($id)) {
        header("Location: ../views/account.php?error=Invalid account ID");
        exit();
    }

    $result = deleteAccount($id);

    if ($result) {
        header("Location: ../views/account.php?success=Delete account successfully");
    } else {
        header("Location: ../views/account.php?error=Failed to delete account");
    }
    exit();
}