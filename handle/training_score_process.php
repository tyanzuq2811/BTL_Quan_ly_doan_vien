<?php
require_once __DIR__ . '/../functions/training_score_functions.php';

// Lấy action
$action = '';
if (isset($_GET['action'])) {
    $action = $_GET['action'];
} elseif (isset($_POST['action'])) {
    $action = $_POST['action'];
}

switch ($action) {
    case 'create':
        handleCreateTrainingScore();
        break;
    case 'edit':
        handleEditTrainingScore();
        break;
    case 'delete':
        handleDeleteTrainingScore();
        break;
    case '':
}

// Lấy tất cả điểm rèn luyện
function handleGetAllTrainingScores($keyword = '') {
    return getAllTrainingScores($keyword);
}

// Lấy điểm rèn luyện theo ID
function handleGetTrainingScoreById($id) {
    return getTrainingScoreById($id);
}

/**
 * Xử lý thêm điểm rèn luyện
 */
function handleCreateTrainingScore() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: ../views/training_score/create_training_score.php?error=Invalid method");
        exit();
    }

    if (!isset($_POST['doan_vien_id']) || !isset($_POST['nam_hoc']) || !isset($_POST['hoc_ky']) || !isset($_POST['diem'])) {
        header("Location: ../views/training_score/create_training_score.php?error=Missing required information");
        exit();
    }

    $doan_vien_id = $_POST['doan_vien_id'];
    $nam_hoc = trim($_POST['nam_hoc']);
    $hoc_ky = $_POST['hoc_ky'];
    $diem = intval($_POST['diem']);
    $xep_loai = $_POST['xep_loai'] ?? null;
    $ghi_chu = $_POST['ghi_chu'] ?? null;

    if (empty($doan_vien_id) || empty($nam_hoc) || empty($hoc_ky) || $diem < 0 || $diem > 100) {
        header("Location: ../views/training_score/create_training_score.php?error=Please fill in all required fields correctly (score 0-100)");
        exit();
    }

    $result = addTrainingScore($doan_vien_id, $nam_hoc, $hoc_ky, $diem, $xep_loai, $ghi_chu);

    if ($result) {
        header("Location: ../views/training_score.php?success=Add training score successfully");
    } else {
        header("Location: ../views/training_score/create_training_score.php?error=Failed to add training score");
    }
    exit();
}

/**
 * Xử lý cập nhật điểm rèn luyện
 */
function handleEditTrainingScore() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: ../views/training_score.php?error=Invalid method");
        exit();
    }

    if (!isset($_POST['id']) || !isset($_POST['doan_vien_id']) || !isset($_POST['nam_hoc']) || !isset($_POST['hoc_ky']) || !isset($_POST['diem'])) {
        header("Location: ../views/training_score.php?error=Missing required information");
        exit();
    }

    $id = $_POST['id'];
    $doan_vien_id = $_POST['doan_vien_id'];
    $nam_hoc = trim($_POST['nam_hoc']);
    $hoc_ky = $_POST['hoc_ky'];
    $diem = intval($_POST['diem']);
    $xep_loai = $_POST['xep_loai'] ?? null;
    $ghi_chu = $_POST['ghi_chu'] ?? null;

    if (empty($doan_vien_id) || empty($nam_hoc) || empty($hoc_ky) || $diem < 0 || $diem > 100) {
        header("Location: ../views/training_score/edit_training_score.php?id=" . $id . "&error=Please fill in all required fields correctly (score 0-100)");
        exit();
    }

    $result = updateTrainingScore($id, $doan_vien_id, $nam_hoc, $hoc_ky, $diem, $xep_loai, $ghi_chu);

    if ($result) {
        header("Location: ../views/training_score.php?success=Update training score successfully");
    } else {
        header("Location: ../views/training_score/edit_training_score.php?id=" . $id . "&error=Failed to update training score");
    }
    exit();
}

/**
 * Xử lý xóa điểm rèn luyện
 */
function handleDeleteTrainingScore() {
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        header("Location: ../views/training_score.php?error=Invalid method");
        exit();
    }

    if (!isset($_GET['id']) || empty($_GET['id'])) {
        header("Location: ../views/training_score.php?error=Training score ID not found");
        exit();
    }

    $id = $_GET['id'];
    if (!is_numeric($id)) {
        header("Location: ../views/training_score.php?error=Invalid training score ID");
        exit();
    }

    $result = deleteTrainingScore($id);

    if ($result) {
        header("Location: ../views/training_score.php?success=Delete training score successfully");
    } else {
        header("Location: ../views/training_score.php?error=Failed to delete training score");
    }
    exit();
}