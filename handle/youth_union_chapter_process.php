<?php
require_once __DIR__ . '/../functions/youth_union_chapter_functions.php';

$action = $_GET['action'] ?? $_POST['action'] ?? '';

switch ($action) {
    case 'create':
        handleCreateChapter();
        break;
    case 'edit':
        handleEditChapter();
        break;
    case 'delete':
        handleDeleteChapter();
        break;
}

function handleGetAllChapters() {
    return getAllChapters();
}

function handleCreateChapter() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $ten = trim($_POST['ten']);
        $doan_truong_id = intval($_POST['doan_truong_id']);
        $ngay_thanh_lap = !empty($_POST['ngay_thanh_lap']) ? $_POST['ngay_thanh_lap'] : null;
        $trang_thai = $_POST['trang_thai'] ?? 'Hoạt động';

        if (empty($ten) || empty($doan_truong_id)) {
            header("Location: ../views/youth_union_chapter/youth_union_chapter_create.php?error=Vui lòng nhập đầy đủ thông tin");
            exit();
        }
        if (addChapter($ten, $doan_truong_id, $ngay_thanh_lap, $trang_thai)) {
            header("Location: ../views/youth_union_chapter.php?success=Thêm liên chi đoàn thành công");
        } else {
            header("Location: ../views/youth_union_chapter/youth_union_chapter_create.php?error=Thêm liên chi đoàn thất bại");
        }
        exit();
    }
}

function handleEditChapter() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = intval($_POST['id']);
        $ten = trim($_POST['ten']);
        $doan_truong_id = intval($_POST['doan_truong_id']);
        $ngay_thanh_lap = !empty($_POST['ngay_thanh_lap']) ? $_POST['ngay_thanh_lap'] : null;
        $trang_thai = $_POST['trang_thai'] ?? 'Hoạt động';

        if (updateChapter($id, $ten, $doan_truong_id, $ngay_thanh_lap, $trang_thai)) {
            header("Location: ../views/youth_union_chapter.php?success=Cập nhật liên chi đoàn thành công");
        } else {
            header("Location: ../views/youth_union_chapter/youth_union_chapter_edit.php?id=$id&error=Cập nhật thất bại");
        }
        exit();
    }
}

function handleDeleteChapter() {
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);
        if (deleteChapter($id)) {
            header("Location: ../views/youth_union_chapter.php?success=Xóa liên chi đoàn thành công");
        } else {
            header("Location: ../views/youth_union_chapter.php?error=Xóa liên chi đoàn thất bại");
        }
        exit();
    }
}


