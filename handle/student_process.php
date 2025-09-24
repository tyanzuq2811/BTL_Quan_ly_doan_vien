<?php
require_once(__DIR__ . "/../functions/db_connection.php");
require_once(__DIR__ . "/../functions/student_functions.php");
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // Cập nhật hồ sơ
    if ($action === 'update_profile') {
        $id = intval($_POST['student_id']);
        $email = $_POST['email'];
        $phone = $_POST['so_dien_thoai'];
        $address = $_POST['dia_chi'];

        if (updateStudentProfile($id, $email, $phone, $address)) {
            $_SESSION['success'] = "✅ Cập nhật hồ sơ thành công!";
        } else {
            $_SESSION['error'] = "❌ Có lỗi khi cập nhật hồ sơ!";
        }
        header("Location: ../views/student/profile.php");
        exit;
    }

    // Đăng ký sự kiện
    if ($action === 'register_event') {
        $studentId = intval($_POST['student_id']);
        $eventId   = intval($_POST['event_id']);

        $message = registerEvent($studentId, $eventId);
        $_SESSION['success'] = $message; // có thể là báo đã đăng ký hoặc thành công

        header("Location: ../views/student/events.php");
        exit;
    }

    // Hủy đăng ký sự kiện
    if ($action === 'cancel_event') {
        $studentId = intval($_POST['student_id']);
        $eventId   = intval($_POST['event_id']);

        $message = cancelEvent($studentId, $eventId);
        $_SESSION['success'] = $message;

        header("Location: ../views/student/events.php");
        exit;
    }
}
?>
