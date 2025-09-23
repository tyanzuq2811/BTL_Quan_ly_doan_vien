<?php
require_once __DIR__ . '/../functions/db_connection.php'; 
require_once __DIR__ . '/../functions/member_functions.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

$action = filter_input(INPUT_GET, 'action', FILTER_DEFAULT) ?? '';

switch ($action) {
    case 'add':
        handleAddMember();
        break;
    case 'edit':
        handleEditMember();
        break;
    case 'delete':
        handleDeleteMember();
        break;
    default:
        $_SESSION['error'] = 'Hành động không hợp lệ';
        header("Location: /BTL/views/member.php");
        exit();
}

function handleAddMember() {
    $mssv = trim(filter_input(INPUT_POST, 'mssv', FILTER_DEFAULT) ?? '');
    $ho_ten = trim(filter_input(INPUT_POST, 'ho_ten', FILTER_DEFAULT) ?? '');
    $ngay_sinh = trim(filter_input(INPUT_POST, 'ngay_sinh', FILTER_DEFAULT) ?? '');
    $gioi_tinh = trim(filter_input(INPUT_POST, 'gioi_tinh', FILTER_DEFAULT) ?? '');
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL) ?? null;
    $so_dien_thoai = trim(filter_input(INPUT_POST, 'so_dien_thoai', FILTER_DEFAULT) ?? null);
    $dia_chi = trim(filter_input(INPUT_POST, 'dia_chi', FILTER_DEFAULT) ?? null);
    $ngay_vao_doan = trim(filter_input(INPUT_POST, 'ngay_vao_doan', FILTER_DEFAULT) ?? null);
    $chi_doan_id = filter_input(INPUT_POST, 'chi_doan_id', FILTER_VALIDATE_INT) ?: null;
    $trang_thai = trim(filter_input(INPUT_POST, 'trang_thai', FILTER_DEFAULT) ?? 'Hoạt động');

    if (empty($mssv) || empty($ho_ten) || empty($ngay_sinh) || empty($gioi_tinh)) {
        $_SESSION['error'] = 'Dữ liệu không hợp lệ';
        header("Location: /BTL/views/member/member_create.php");
        exit();
    }

    if (mssvExists($mssv)) {
        $_SESSION['error'] = 'Mã số sinh viên đã tồn tại. Vui lòng chọn MSSV khác.';
        header("Location: /BTL/views/member/member_create.php");
        exit();
    }

    if (addMember($mssv, $ho_ten, $ngay_sinh, $gioi_tinh, $email, $so_dien_thoai, $dia_chi, $ngay_vao_doan, $chi_doan_id, $trang_thai)) {
        $_SESSION['success'] = 'Thêm đoàn viên thành công';
        header("Location: /BTL/views/member.php");
        exit();
    } else {
        $_SESSION['error'] = 'Thêm đoàn viên thất bại';
        header("Location: /BTL/views/member/member_create.php");
        exit();
    }
}

function handleEditMember() {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT) ?? 0;
    $mssv = trim(filter_input(INPUT_POST, 'mssv', FILTER_DEFAULT) ?? '');
    $ho_ten = trim(filter_input(INPUT_POST, 'ho_ten', FILTER_DEFAULT) ?? '');
    $ngay_sinh = trim(filter_input(INPUT_POST, 'ngay_sinh', FILTER_DEFAULT) ?? '');
    $gioi_tinh = trim(filter_input(INPUT_POST, 'gioi_tinh', FILTER_DEFAULT) ?? '');
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL) ?? null;
    $so_dien_thoai = trim(filter_input(INPUT_POST, 'so_dien_thoai', FILTER_DEFAULT) ?? null);
    $dia_chi = trim(filter_input(INPUT_POST, 'dia_chi', FILTER_DEFAULT) ?? null);
    $ngay_vao_doan = trim(filter_input(INPUT_POST, 'ngay_vao_doan', FILTER_DEFAULT) ?? null);
    $chi_doan_id = filter_input(INPUT_POST, 'chi_doan_id', FILTER_VALIDATE_INT) ?: null;
    $trang_thai = trim(filter_input(INPUT_POST, 'trang_thai', FILTER_DEFAULT) ?? 'Hoạt động');

    if ($id <= 0 || empty($mssv) || empty($ho_ten) || empty($ngay_sinh) || empty($gioi_tinh)) {
        $_SESSION['error'] = 'Dữ liệu không hợp lệ';
        header("Location: /BTL/views/member/member_edit.php?id=$id");
        exit();
    }

    $current_member = getMemberById($id);
    if (mssvExists($mssv) && $current_member['mssv'] !== $mssv) {
        $_SESSION['error'] = 'Mã số sinh viên đã tồn tại. Vui lòng chọn MSSV khác.';
        header("Location: /BTL/views/member/member_edit.php?id=$id");
        exit();
    }

    if (updateMember($id, $mssv, $ho_ten, $ngay_sinh, $gioi_tinh, $email, $so_dien_thoai, $dia_chi, $ngay_vao_doan, $chi_doan_id, $trang_thai)) {
        $_SESSION['success'] = 'Sửa đoàn viên thành công';
        header("Location: /BTL/views/member.php");
        exit();
    } else {
        $_SESSION['error'] = 'Sửa đoàn viên thất bại';
        header("Location: /BTL/views/member.php?id=$id");
        exit();
    }
}

function handleDeleteMember() {
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?? 0;

    if ($id <= 0) {
        $_SESSION['error'] = 'ID không hợp lệ';
        header("Location: /BTL/views/member.php");
        exit();
    }

    if (deleteMember($id)) {
        $_SESSION['success'] = 'Xóa đoàn viên thành công';
        header("Location: /BTL/views/member.php");
        exit();
    } else {
        $_SESSION['error'] = 'Xóa đoàn viên thất bại';
        header("Location: /BTL/views/member.php");
        exit();
    }
}

function mssvExists($mssv) {
    $conn = getDbConnection();
    $stmt = $conn->prepare("SELECT COUNT(*) FROM doan_vien WHERE mssv = ?");
    $stmt->bind_param("s", $mssv);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();
    $conn->close();
    return $count > 0;
}
?>