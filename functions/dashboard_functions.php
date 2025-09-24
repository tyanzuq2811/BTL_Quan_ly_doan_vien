<?php
require_once __DIR__ . '/db_connection.php';

function getMemberStatistics() {
    $conn = getDbConnection();
    $data = [];

    try {
        $result = mysqli_query($conn, "SELECT COUNT(*) as total FROM doan_vien");
        $data['total_members'] = mysqli_fetch_assoc($result)['total'];

        $result = mysqli_query($conn, "SELECT COUNT(*) as total FROM chi_doan");
        $data['total_branches'] = mysqli_fetch_assoc($result)['total'];

        $result = mysqli_query($conn, "SELECT COUNT(*) as total FROM su_kien");
        $data['total_events'] = mysqli_fetch_assoc($result)['total'];

        $result = mysqli_query($conn, "SELECT COUNT(*) as total FROM doan_phi WHERE trang_thai = 'Chưa nộp'");
        $data['unpaid_fees'] = mysqli_fetch_assoc($result)['total'];

        $members_query = "
            SELECT 
                dv.mssv,
                dv.ho_ten,
                dv.email,
                cd.ten as chi_doan,
                COALESCE(drl.diem, 0) as diem,
                COALESCE(drl.xep_loai, 'Chưa đánh giá') as xep_loai,
                COALESCE(dp.trang_thai, 'Chưa có') as trang_thai_doan_phi
            FROM doan_vien dv
            LEFT JOIN chi_doan cd ON dv.chi_doan_id = cd.id
            LEFT JOIN diem_ren_luyen drl ON dv.id = drl.doan_vien_id
            LEFT JOIN doan_phi dp ON dv.id = dp.doan_vien_id
            ORDER BY dv.mssv
        ";
        $data['members'] = mysqli_query($conn, $members_query);

        $branch_stats_query = "
            SELECT 
                cd.ten,
                COUNT(dv.id) as so_thanh_vien,
                ROUND((COUNT(dv.id) * 100.0 / (SELECT COUNT(*) FROM doan_vien)), 1) as ti_le
            FROM chi_doan cd
            LEFT JOIN doan_vien dv ON cd.id = dv.chi_doan_id
            GROUP BY cd.id, cd.ten
            ORDER BY so_thanh_vien DESC
        ";
        $data['branch_stats'] = mysqli_query($conn, $branch_stats_query);

        $events_query = "
            SELECT 
                ten_su_kien,
                mo_ta,
                DATE_FORMAT(ngay_to_chuc, '%d/%m/%Y') as ngay_to_chuc_formatted,
                cap_to_chuc
            FROM su_kien 
            ORDER BY ngay_to_chuc DESC 
            LIMIT 3
        ";
        $data['recent_events'] = mysqli_query($conn, $events_query);

        $notifications_query = "
            SELECT 
                tb.tieu_de,
                tb.noi_dung,
                tb.cap_to_chuc,
                dv.ho_ten as nguoi_gui
            FROM thong_bao tb
            LEFT JOIN doan_vien dv ON tb.nguoi_gui = dv.id
            ORDER BY tb.id DESC
            LIMIT 2
        ";
        $data['recent_notifications'] = mysqli_query($conn, $notifications_query);

        return $data;
    } catch (Exception $e) {
        throw new Exception("Lỗi: " . $e->getMessage());
    } finally {
        if ($conn) mysqli_close($conn);
    }
}
?>