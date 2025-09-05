<?php
require_once __DIR__ . '/../functions/youth_union_functions.php';

// Lấy action
$action = '';
if (isset($_GET['action'])) {
    $action = $_GET['action'];
} elseif (isset($_POST['action'])) {
    $action = $_POST['action'];
}

switch ($action) {
    case 'create':
        handleCreateYouthUnion();
        break;
    case 'edit':
        handleEditYouthUnion();
        break;
    case 'delete':
        handleDeleteYouthUnion();
        break;
}

// Lấy tất cả tổ chức đoàn
function handleGetAllYouthUnions($keyword = '') {
    return getAllYouthUnions($keyword);
}

// Lấy tổ chức đoàn theo ID
function handleGetYouthUnionById($id) {
    return getYouthUnionById($id);
}

/**
 * Xử lý thêm tổ chức đoàn
 */
function handleCreateYouthUnion() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: ../views/youth_union.php?error=Phương thức không hợp lệ");
        exit();
    }

    if (!isset($_POST['id']) || !isset($_POST['ten']) || !isset($_POST['loai'])) {
        header("Location: ../views/youth_union.php?error=Thiếu thông tin cần thiết");
        exit();
    }

    $id = $_POST['id'];
    $ten = trim($_POST['ten']);
    $loai = trim($_POST['loai']);
    $cap_tren_id = $_POST['cap_tren_id'] ?? null;
    $ngay_thanh_lap = $_POST['ngay_thanh_lap'] ?? null;

    if (empty($ten) || empty($loai)) {
        header("Location: ../views/youth_union/edit_youth_union.php?id=" . $id . "&error=Vui lòng điền đầy đủ thông tin");
        exit();
    }

    $result = updateYouthUnion($id, $ten, $loai, $cap_tren_id, $ngay_thanh_lap);

    if ($result) {
        $_SESSION['success_message'] = "Cập nhật tổ chức đoàn thành công"; // Lưu thông báo vào session
        header("Location: ../views/youth_union.php"); // Chuyển hướng không tham số
    } else {
        header("Location: ../views/youth_union/edit_youth_union.php?id=" . $id . "&error=Cập nhật tổ chức đoàn thất bại");
    }
    exit();
}

/**
 * Xử lý cập nhật tổ chức đoàn
 */
function handleEditYouthUnion() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: ../views/youth_union.php?error=Phương thức không hợp lệ");
        exit();
    }

    if (!isset($_POST['id']) || !isset($_POST['ten']) || !isset($_POST['loai'])) {
        header("Location: ../views/youth_union.php?error=Thiếu thông tin cần thiết");
        exit();
    }

    $id = $_POST['id'];
    $ten = trim($_POST['ten']);
    $loai = $_POST['loai'];
    $cap_tren_id = $_POST['cap_tren_id'] ?? null;
    $ngay_thanh_lap = $_POST['ngay_thanh_lap'] ?? null;

    if (empty($ten) || empty($loai)) {
        header("Location: ../views/youth_union/edit_youth_union.php?id=" . $id . "&error=Vui lòng điền đầy đủ thông tin");
        exit();
    }

    $result = updateYouthUnion($id, $ten, $loai, $cap_tren_id, $ngay_thanh_lap);

    if ($result) {
        header("Location: ../views/youth_union.php?success=Cập nhật tổ chức đoàn thành công");
    } else {
        header("Location: ../views/youth_union/edit_youth_union.php?id=" . $id . "&error=Cập nhật tổ chức đoàn thất bại");
    }
    exit();
}

/**
 * Xử lý xóa tổ chức đoàn
 */
function handleDeleteYouthUnion() {
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        header("Location: ../views/youth_union.php?error=Phương thức không hợp lệ");
        exit();
    }

    if (!isset($_GET['id']) || empty($_GET['id'])) {
        header("Location: ../views/youth_union.php?error=Không tìm thấy ID tổ chức đoàn");
        exit();
    }

    $id = $_GET['id'];
    if (!is_numeric($id)) {
        header("Location: ../views/youth_union.php?error=ID tổ chức đoàn không hợp lệ");
        exit();
    }

    $result = deleteYouthUnion($id);

    if ($result) {
        header("Location: ../views/youth_union.php?success=Xóa tổ chức đoàn thành công");
    } else {
        header("Location: ../views/youth_union.php?error=Xóa tổ chức đoàn thất bại");
    }
    exit();
}