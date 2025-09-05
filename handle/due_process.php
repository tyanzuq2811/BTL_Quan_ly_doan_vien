<?php
require_once __DIR__ . '/../functions/due_functions.php';

// Lấy action
$action = '';
if (isset($_GET['action'])) {
    $action = $_GET['action'];
} elseif (isset($_POST['action'])) {
    $action = $_POST['action'];
}

switch ($action) {
    case 'create':
        handleCreateDues();
        break;
    case 'edit':
        handleEditDues();
        break;
    case 'delete':
        handleDeleteDues();
        break;
    case '':
}

// Lấy tất cả khoản đoàn phí
function handleGetAllDues($keyword = '') {
    return getAllDues($keyword);
}

// Lấy khoản đoàn phí theo ID
function handleGetDuesById($id) {
    return getDuesById($id);
}

/**
 * Xử lý thêm khoản đoàn phí
 */
function handleCreateDues() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: ../views/due/create_due.php?error=Invalid method");
        exit();
    }

    if (!isset($_POST['doan_vien_id']) || !isset($_POST['loai_doan_phi_id']) || !isset($_POST['nam_hoc']) || !isset($_POST['hoc_ky']) || !isset($_POST['so_tien'])) {
        header("Location: ../views/due/create_due.php?error=Missing required information");
        exit();
    }

    $doan_vien_id = $_POST['doan_vien_id'];
    $loai_doan_phi_id = $_POST['loai_doan_phi_id'];
    $nam_hoc = trim($_POST['nam_hoc']);
    $hoc_ky = $_POST['hoc_ky'];
    $so_tien = floatval($_POST['so_tien']);
    $ngay_nop = $_POST['ngay_nop'] ?? null;
    $trang_thai = $_POST['trang_thai'] ?? 'Chưa nộp';

    if (empty($doan_vien_id) || empty($loai_doan_phi_id) || empty($nam_hoc) || empty($hoc_ky) || $so_tien <= 0) {
        header("Location: ../views/due/create_due.php?error=Please fill in all required fields correctly");
        exit();
    }

    $result = addDues($doan_vien_id, $loai_doan_phi_id, $nam_hoc, $hoc_ky, $so_tien, $ngay_nop, $trang_thai);

    if ($result) {
        header("Location: ../views/due/dues.php?success=Add dues successfully");
    } else {
        header("Location: ../views/due/create_due.php?error=Failed to add dues");
    }
    exit();
}

/**
 * Xử lý cập nhật khoản đoàn phí
 */
function handleEditDues() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: ../views/due/dues.php?error=Invalid method");
        exit();
    }

    if (!isset($_POST['id']) || !isset($_POST['doan_vien_id']) || !isset($_POST['loai_doan_phi_id']) || !isset($_POST['nam_hoc']) || !isset($_POST['hoc_ky']) || !isset($_POST['so_tien'])) {
        header("Location: ../views/due/dues.php?error=Missing required information");
        exit();
    }

    $id = $_POST['id'];
    $doan_vien_id = $_POST['doan_vien_id'];
    $loai_doan_phi_id = $_POST['loai_doan_phi_id'];
    $nam_hoc = trim($_POST['nam_hoc']);
    $hoc_ky = $_POST['hoc_ky'];
    $so_tien = floatval($_POST['so_tien']);
    $ngay_nop = $_POST['ngay_nop'] ?? null;
    $trang_thai = $_POST['trang_thai'] ?? 'Chưa nộp';

    if (empty($doan_vien_id) || empty($loai_doan_phi_id) || empty($nam_hoc) || empty($hoc_ky) || $so_tien <= 0) {
        header("Location: ../views/due/edit_due.php?id=" . $id . "&error=Please fill in all required fields correctly");
        exit();
    }

    $result = updateDues($id, $doan_vien_id, $loai_doan_phi_id, $nam_hoc, $hoc_ky, $so_tien, $ngay_nop, $trang_thai);

    if ($result) {
        header("Location: ../views/due/dues.php?success=Update dues successfully");
    } else {
        header("Location: ../views/due/edit_due.php?id=" . $id . "&error=Failed to update dues");
    }
    exit();
}

/**
 * Xử lý xóa khoản đoàn phí
 */
function handleDeleteDues() {
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        header("Location: ../views/due/dues.php?error=Invalid method");
        exit();
    }

    if (!isset($_GET['id']) || empty($_GET['id'])) {
        header("Location: ../views/due/dues.php?error=Member ID not found");
        exit();
    }

    $id = $_GET['id'];
    if (!is_numeric($id)) {
        header("Location: ../views/due/dues.php?error=Invalid member ID");
        exit();
    }

    $result = deleteDues($id);

    if ($result) {
        header("Location: ../views/due/dues.php?success=Delete dues successfully");
    } else {
        header("Location: ../views/due/dues.php?error=Failed to delete dues");
    }
    exit();
}