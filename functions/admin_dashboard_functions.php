<?php
require_once __DIR__ . '/db_connection.php';

function getAccountStatistics() {
    $conn = getDbConnection();
    $data = [];

    try {
        $result = mysqli_query($conn, "SELECT COUNT(*) as total FROM tai_khoan");
        $data['total_accounts'] = mysqli_fetch_assoc($result)['total'];

        $result = mysqli_query($conn, "SELECT vai_tro, COUNT(*) as count FROM tai_khoan GROUP BY vai_tro");
        $data['role_stats'] = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data['role_stats'][$row['vai_tro']] = $row['count'];
        }

        $result = mysqli_query($conn, "SELECT trang_thai, COUNT(*) as count FROM tai_khoan GROUP BY trang_thai");
        $data['status_stats'] = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data['status_stats'][$row['trang_thai']] = $row['count'];
        }

        $accounts_query = "
            SELECT 
                tk.ten_dang_nhap,
                tk.vai_tro,
                tk.trang_thai,
                dv.ho_ten,
                dv.mssv,
                dv.email
            FROM tai_khoan tk
            LEFT JOIN doan_vien dv ON tk.doan_vien_id = dv.id
            ORDER BY tk.id DESC
            LIMIT 10
        ";
        $data['accounts'] = mysqli_query($conn, $accounts_query);

        $role_details_query = "
            SELECT 
                vai_tro,
                COUNT(*) as so_luong,
                ROUND((COUNT(*) * 100.0 / ?), 1) as ti_le
            FROM tai_khoan 
            GROUP BY vai_tro
            ORDER BY so_luong DESC
        ";
        $stmt = mysqli_prepare($conn, $role_details_query);
        mysqli_stmt_bind_param($stmt, "i", $data['total_accounts']);
        mysqli_stmt_execute($stmt);
        $data['role_details'] = mysqli_stmt_get_result($stmt);

        $system_events_query = "
            SELECT 
                'Hệ thống' as ten_su_kien,
                CONCAT('Tài khoản mới: ', ten_dang_nhap) as mo_ta,
                DATE_FORMAT(CURDATE(), '%d/%m/%Y') as ngay_to_chuc_formatted,
                vai_tro as cap_to_chuc
            FROM tai_khoan 
            ORDER BY id DESC 
            LIMIT 3
        ";
        $data['recent_system_events'] = mysqli_query($conn, $system_events_query);

        $notifications_query = "
            SELECT 
                CONCAT('Thống kê: ', vai_tro) as tieu_de,
                CONCAT('Số lượng tài khoản: ', COUNT(*), ' (', ROUND((COUNT(*) * 100.0 / ?), 1), '%)') as noi_dung,
                'Hệ thống' as cap_to_chuc,
                'Admin' as nguoi_gui
            FROM tai_khoan 
            GROUP BY vai_tro
            ORDER BY COUNT(*) DESC
            LIMIT 2
        ";
        $stmt = mysqli_prepare($conn, $notifications_query);
        mysqli_stmt_bind_param($stmt, "i", $data['total_accounts']);
        mysqli_stmt_execute($stmt);
        $data['recent_notifications'] = mysqli_stmt_get_result($stmt);

        return $data;
    } catch (Exception $e) {
        throw new Exception("Lỗi: " . $e->getMessage());
    } finally {
        if ($conn) mysqli_close($conn);
    }
}
?>