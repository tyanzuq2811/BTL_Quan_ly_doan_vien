<?php
require_once __DIR__ . '/../functions/member_functions.php';

// Lấy action
$action = '';
if (isset($_GET['action'])) {
    $action = $_GET['action'];
} elseif (isset($_POST['action'])) {
    $action = $_POST['action'];
}

switch ($action) {
    case 'create':
        handleCreateMember();
        break;
    case 'edit':
        handleEditMember();
        break;
    case 'delete':
        handleDeleteMember();
        break;
    case '':
}

// Lấy tất cả đoàn viên
function handleGetAllMembers($keyword = '') {
    return getAllMembers($keyword);
}

// Lấy đoàn viên theo ID
function handleGetMemberById($id) {
    return getMemberById($id);
}

/**
 * Xử lý thêm đoàn viên
 */
function handleCreateMember() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: ../views/member.php?error=Phương thức không hợp lệ");
        exit();
    }

    if (!isset($_POST['mssv']) || !isset($_POST['ho_ten']) || !isset($_POST['ngay_sinh']) || !isset($_POST['gioi_tinh'])) {
        header("Location: ../views/create_member.php?error=Thiếu thông tin cần thiết");
        exit();
    }

    $mssv = trim($_POST['mssv']);
    $ho_ten = trim($_POST['ho_ten']);
    $ngay_sinh = $_POST['ngay_sinh'];
    $gioi_tinh = $_POST['gioi_tinh'];
    $email = $_POST['email'] ?? null;
    $so_dien_thoai = $_POST['so_dien_thoai'] ?? null;
    $dia_chi = $_POST['dia_chi'] ?? null;
    $ngay_vao_doan = $_POST['ngay_vao_doan'] ?? null;

    if (empty($mssv) || empty($ho_ten) || empty($ngay_sinh) || empty($gioi_tinh)) {
        header("Location: ../views/create_member.php?error=Vui lòng điền đầy đủ thông tin");
        exit();
    }

    $result = addMember($mssv, $ho_ten, $ngay_sinh, $gioi_tinh, $email, $so_dien_thoai, $dia_chi, $ngay_vao_doan);

    if ($result) {
        header("Location: ../views/member.php?success=Thêm đoàn viên thành công");
    } else {
        header("Location: ../views/create_member.php?error=Thêm đoàn viên thất bại");
    }
    exit();
}

/**
 * Xử lý cập nhật đoàn viên
 */
function handleEditMember() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: ../views/member.php?error=Phương thức không hợp lệ");
        exit();
    }

    if (!isset($_POST['id']) || !isset($_POST['mssv']) || !isset($_POST['ho_ten']) || !isset($_POST['ngay_sinh']) || !isset($_POST['gioi_tinh'])) {
        header("Location: ../views/member.php?error=Thiếu thông tin cần thiết");
        exit();
    }

    $id = $_POST['id'];
    $mssv = trim($_POST['mssv']);
    $ho_ten = trim($_POST['ho_ten']);
    $ngay_sinh = $_POST['ngay_sinh'];
    $gioi_tinh = $_POST['gioi_tinh'];
    $email = $_POST['email'] ?? null;
    $so_dien_thoai = $_POST['so_dien_thoai'] ?? null;
    $dia_chi = $_POST['dia_chi'] ?? null;
    $ngay_vao_doan = $_POST['ngay_vao_doan'] ?? null;

    if (empty($mssv) || empty($ho_ten) || empty($ngay_sinh) || empty($gioi_tinh)) {
        header("Location: ../views/edit_member.php?id=" . $id . "&error=Vui lòng điền đầy đủ thông tin");
        exit();
    }

    $result = updateMember($id, $mssv, $ho_ten, $ngay_sinh, $gioi_tinh, $email, $so_dien_thoai, $dia_chi, $ngay_vao_doan);

    if ($result) {
        header("Location: ../views/member.php?success=Cập nhật đoàn viên thành công");
    } else {
        header("Location: ../views/edit_member.php?id=" . $id . "&error=Cập nhật đoàn viên thất bại");
    }
    exit();
}

/**
 * Xử lý xóa đoàn viên
 */
function handleDeleteMember() {
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        header("Location: ../views/member.php?error=Phương thức không hợp lệ");
        exit();
    }

    if (!isset($_GET['id']) || empty($_GET['id'])) {
        header("Location: ../views/member.php?error=Không tìm thấy ID đoàn viên");
        exit();
    }

    $id = $_GET['id'];
    if (!is_numeric($id)) {
        header("Location: ../views/member.php?error=ID đoàn viên không hợp lệ");
        exit();
    }

    $result = deleteMember($id);

    if ($result) {
        header("Location: ../views/member.php?success=Xóa đoàn viên thành công");
    } else {
        header("Location: ../views/member.php?error=Xóa đoàn viên thất bại");
    }
    exit();
}

function countMembers($keyword = '') {
    global $conn; // Giả sử $conn là kết nối MySQL từ member_functions.php
    $sql = "SELECT COUNT(*) as total FROM doan_vien WHERE 1=1";
    if (!empty($keyword)) {
        $sql .= " AND (mssv LIKE ? OR ho_ten LIKE ?)";
    }
    $stmt = $conn->prepare($sql);
    if (!empty($keyword)) {
        $search = "%$keyword%";
        $stmt->bind_param("ss", $search, $search);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['total'];
}
