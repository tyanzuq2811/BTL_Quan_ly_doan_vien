<?php
require_once __DIR__ . '/../functions/youth_union_chapter_functions.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

$action = filter_input(INPUT_GET, 'action', FILTER_DEFAULT) ?? '';

switch ($action) {
    case 'add':
        handleAddChapter();
        break;
    case 'edit':
        handleEditChapter();
        break;
    case 'delete':
        handleDeleteChapter();
        break;
    default:
        $_SESSION['error'] = 'Hành động không hợp lệ';
        header("Location: /BTL/views/youth_union_chapter.php");
        exit();
}

function handleAddChapter() {
    $ten = filter_input(INPUT_POST, 'ten', FILTER_DEFAULT) ?? '';
    $doan_truong_id = filter_input(INPUT_POST, 'doan_truong_id', FILTER_VALIDATE_INT) ?? null;
    $ngay_thanh_lap = filter_input(INPUT_POST, 'ngay_thanh_lap', FILTER_DEFAULT) ?? null;
    $trang_thai = filter_input(INPUT_POST, 'trang_thai', FILTER_DEFAULT) ?? 'Hoạt động';

    if (empty($ten)) {
        $_SESSION['error'] = 'Dữ liệu không hợp lệ';
        header("Location: /BTL/views/youth_union_chapter/youth_union_chapter_create.php");
        exit();
    }

    if (addChapter($ten, $doan_truong_id, $ngay_thanh_lap, $trang_thai)) {
        $_SESSION['success'] = 'Thêm liên chi đoàn thành công';
        header("Location: /BTL/views/youth_union_chapter.php");
        exit();
    } else {
        $_SESSION['error'] = 'Thêm liên chi đoàn thất bại';
        header("Location: /BTL/views/youth_union_chapter/youth_union_chapter_create.php");
        exit();
    }
}

function handleEditChapter() {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT) ?? 0;
    $ten = filter_input(INPUT_POST, 'ten', FILTER_DEFAULT) ?? '';
    $doan_truong_id = filter_input(INPUT_POST, 'doan_truong_id', FILTER_VALIDATE_INT) ?? null;
    $ngay_thanh_lap = filter_input(INPUT_POST, 'ngay_thanh_lap', FILTER_DEFAULT) ?? null;
    $trang_thai = filter_input(INPUT_POST, 'trang_thai', FILTER_DEFAULT) ?? 'Hoạt động';

    if ($id <= 0 || empty($ten)) {
        $_SESSION['error'] = 'Dữ liệu không hợp lệ';
        header("Location: /BTL/views/youth_union_chapter/youth_union_chapter_edit.php?id=$id");
        exit();
    }

    if (updateChapter($id, $ten, $doan_truong_id, $ngay_thanh_lap, $trang_thai)) {
        $_SESSION['success'] = 'Sửa liên chi đoàn thành công';
        header("Location: /BTL/views/youth_union_chapter.php");
        exit();
    } else {
        $_SESSION['error'] = 'Sửa liên chi đoàn thất bại';
        header("Location: /BTL/views/youth_union_chapter/youth_union_chapter_edit.php?id=$id");
        exit();
    }
}

function handleDeleteChapter() {
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?? 0;

    if ($id <= 0) {
        $_SESSION['error'] = 'ID không hợp lệ';
        header("Location: /BTL/views/youth_union_chapter.php");
        exit();
    }

    if (deleteChapter($id)) {
        $_SESSION['success'] = 'Xóa liên chi đoàn thành công';
        header("Location: /BTL/views/youth_union_chapter.php");
        exit();
    } else {
        $_SESSION['error'] = 'Xóa liên chi đoàn thất bại';
        header("Location: /BTL/views/youth_union_chapter.php");
        exit();
    }
}
?>