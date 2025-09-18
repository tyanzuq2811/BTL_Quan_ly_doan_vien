<?php
require_once __DIR__ . '/../functions/youth_union_team_functions.php';

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
        handleAddTeam();
        break;
    case 'edit':
        handleEditTeam();
        break;
    case 'delete':
        handleDeleteTeam();
        break;
    default:
        $_SESSION['error'] = 'Hành động không hợp lệ';
        header("Location: /BTL/views/youth_union_team.php");
        exit();
}

/**
 * Handle adding a new team
 */
function handleAddTeam() {
    $ten = filter_input(INPUT_POST, 'ten', FILTER_SANITIZE_STRING) ?? '';
    $lien_chi_id = filter_input(INPUT_POST, 'lien_chi_id', FILTER_VALIDATE_INT) ?? 0;
    $ngay_thanh_lap = filter_input(INPUT_POST, 'ngay_thanh_lap', FILTER_SANITIZE_STRING) ?? null;
    $trang_thai = filter_input(INPUT_POST, 'trang_thai', FILTER_SANITIZE_STRING) ?? 'Hoạt động';

    if (empty($ten) || $lien_chi_id <= 0) {
        $_SESSION['error'] = 'Dữ liệu không hợp lệ';
        header("Location: /BTL/views/youth_union_team_create.php");
        exit();
    }

    if (addTeam($ten, $lien_chi_id, $ngay_thanh_lap, $trang_thai)) {
        $_SESSION['success'] = 'Thêm chi đoàn thành công';
        header("Location: /BTL/views/youth_union_team.php");
        exit();
    } else {
        $_SESSION['error'] = 'Thêm chi đoàn thất bại';
        header("Location: /BTL/views/youth_union_team_create.php");
        exit();
    }
}

/**
 * Handle editing a team
 */
function handleEditTeam() {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT) ?? 0;
    $ten = filter_input(INPUT_POST, 'ten', FILTER_SANITIZE_STRING) ?? '';
    $lien_chi_id = filter_input(INPUT_POST, 'lien_chi_id', FILTER_VALIDATE_INT) ?? 0;
    $ngay_thanh_lap = filter_input(INPUT_POST, 'ngay_thanh_lap', FILTER_SANITIZE_STRING) ?? null;
    $trang_thai = filter_input(INPUT_POST, 'trang_thai', FILTER_SANITIZE_STRING) ?? 'Hoạt động';

    if ($id <= 0 || empty($ten) || $lien_chi_id <= 0) {
        $_SESSION['error'] = 'Dữ liệu không hợp lệ';
        header("Location: /BTL/views/youth_union_team_edit.php?id=$id");
        exit();
    }

    if (updateTeam($id, $ten, $lien_chi_id, $ngay_thanh_lap, $trang_thai)) {
        $_SESSION['success'] = 'Sửa chi đoàn thành công';
        header("Location: /BTL/views/youth_union_team.php");
        exit();
    } else {
        $_SESSION['error'] = 'Sửa chi đoàn thất bại';
        header("Location: /BTL/views/youth_union_team_edit.php?id=$id");
        exit();
    }
}

/**
 * Handle deleting a team
 */
function handleDeleteTeam() {
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?? 0;

    if ($id <= 0) {
        $_SESSION['error'] = 'ID không hợp lệ';
        header("Location: /BTL/views/youth_union_team.php");
        exit();
    }

    if (deleteTeam($id)) {
        $_SESSION['success'] = 'Xóa chi đoàn thành công';
        header("Location: /BTL/views/youth_union_team.php");
        exit();
    } else {
        $_SESSION['error'] = 'Xóa chi đoàn thất bại';
        header("Location: /BTL/views/youth_union_team.php");
        exit();
    }
}
?>