<?php
require_once(__DIR__ . "/db_connection.php");  

/**
 * Kết nối CSDL
 */
$conn = getDbConnection();

/**
 * Lấy thông tin đoàn viên theo user_id (account_id)
 */
function getStudentByAccount($account_id) {
    global $conn;
    $sql = "SELECT dv.*, cd.ten AS chi_doan
            FROM tai_khoan tk
            INNER JOIN doan_vien dv ON tk.doan_vien_id = dv.id
            LEFT JOIN chi_doan cd ON dv.chi_doan_id = cd.id
            WHERE tk.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $account_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

/**
 * Cập nhật hồ sơ cá nhân
 */
function updateStudentProfile($id, $email, $phone, $address) {
    global $conn;
    $sql = "UPDATE doan_vien SET email=?, so_dien_thoai=?, dia_chi=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $email, $phone, $address, $id);
    return $stmt->execute();
}

/**
 * Lấy đoàn phí theo user_id (chính là doan_vien_id)
 */
function getStudentUnionFees($user_id) {
    global $conn;
    $sql = "SELECT * FROM doan_phi WHERE doan_vien_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

/**
 * Lấy điểm rèn luyện
 */
function getStudentScores($user_id) {
    global $conn;
    $sql = "SELECT * FROM diem_ren_luyen WHERE doan_vien_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

/**
 * Lấy danh sách tất cả sự kiện
 */
function getAllEvents() {
    global $conn;
    $sql = "SELECT * FROM su_kien ORDER BY ngay_to_chuc ASC";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

/**
 * Lấy sự kiện đã đăng ký
 */
function getStudentEvents($user_id) {
    global $conn;
    $sql = "SELECT sk.id, sk.ten_su_kien, sk.ngay_to_chuc, tg.trang_thai
            FROM su_kien sk
            JOIN tham_gia_su_kien tg ON sk.id = tg.su_kien_id
            WHERE tg.doan_vien_id = ?
              AND tg.trang_thai = 'Đã đăng ký'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

/**
 * Lấy khen thưởng
 */
function getStudentAwards($user_id) {
    global $conn;
    $sql = "SELECT * FROM khen_thuong WHERE doan_vien_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

/**
 * Lấy kỷ luật
 */
function getStudentDisciplines($user_id) {
    global $conn;
    $sql = "SELECT * FROM ky_luat WHERE doan_vien_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

/**
 * Đăng ký sự kiện
 */
function registerEvent($user_id, $event_id) {
    global $conn;

    // Kiểm tra xem đã đăng ký chưa
    $check = $conn->prepare("SELECT id FROM tham_gia_su_kien WHERE doan_vien_id = ? AND su_kien_id = ?");
    $check->bind_param("ii", $user_id, $event_id);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        return "⚠️ Bạn đã đăng ký sự kiện này rồi!";
    }

    // Nếu chưa đăng ký thì thêm mới
    $sql = "INSERT INTO tham_gia_su_kien (doan_vien_id, su_kien_id, trang_thai) VALUES (?, ?, 'Đã đăng ký')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $event_id);

    if ($stmt->execute()) {
        return "✅ Đăng ký sự kiện thành công!";
    } else {
        return "❌ Có lỗi xảy ra khi đăng ký.";
    }
}

/**
 * Hủy đăng ký sự kiện
 */
function cancelEvent($user_id, $event_id) {
    global $conn;

    $sql = "DELETE FROM tham_gia_su_kien WHERE doan_vien_id = ? AND su_kien_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $event_id);

    if ($stmt->execute() && $stmt->affected_rows > 0) {
        return "✅ Hủy đăng ký sự kiện thành công!";
    } else {
        return "⚠️ Bạn chưa đăng ký sự kiện này hoặc đã hủy trước đó.";
    }
}
?>
