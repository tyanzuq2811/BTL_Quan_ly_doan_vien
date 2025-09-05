<h2 align="center">
    <a href="https://dainam.edu.vn/vi/khoa-cong-nghe-thong-tin">
    🎓 Faculty of Information Technology (DaiNam University)
    </a>
</h2>
<h2 align="center">
    Youth Union Member Management
</h2>
<div align="center">
    <p align="center">
        <img src="docs/logo/aiotlab_logo.png" alt="AIoTLab Logo" width="170"/>
        <img src="docs/logo/fitdnu_logo.png" alt="AIoTLab Logo" width="180"/>
        <img src="docs/logo/dnu_logo.png" alt="DaiNam University Logo" width="200"/>
    </p>

[![AIoTLab](https://img.shields.io/badge/AIoTLab-green?style=for-the-badge)](https://www.facebook.com/DNUAIoTLab)
[![Faculty of Information Technology](https://img.shields.io/badge/Faculty%20of%20Information%20Technology-blue?style=for-the-badge)](https://dainam.edu.vn/vi/khoa-cong-nghe-thong-tin)
[![DaiNam University](https://img.shields.io/badge/DaiNam%20University-orange?style=for-the-badge)](https://dainam.edu.vn)

</div>
 
## 📖 1. Giới thiệu
Quản lý đoàn viên trong trường Đại học

## 🔧 2. Các công nghệ được sử dụng
<div align="center">

### Hệ điều hành
![macOS](https://img.shields.io/badge/macOS-000000?style=for-the-badge&logo=macos&logoColor=F0F0F0)
[![Windows](https://img.shields.io/badge/Windows-0078D6?style=for-the-badge&logo=windows&logoColor=white)](https://www.microsoft.com/en-us/windows/)
[![Ubuntu](https://img.shields.io/badge/Ubuntu-E95420?style=for-the-badge&logo=ubuntu&logoColor=white)](https://ubuntu.com/)

### Công nghệ chính
[![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
[![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)](#)
[![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)](#)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white)](https://getbootstrap.com/)

### Web Server & Database
[![Apache](https://img.shields.io/badge/Apache-D22128?style=for-the-badge&logo=apache&logoColor=white)](https://httpd.apache.org/)
[![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com/) 
[![XAMPP](https://img.shields.io/badge/XAMPP-FB7A24?style=for-the-badge&logo=xampp&logoColor=white)](https://www.apachefriends.org/)

### Database Management Tools
[![MySQL Workbench](https://img.shields.io/badge/MySQL_Workbench-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://dev.mysql.com/downloads/workbench/)
</div>
# Quản lý Đoàn Viên - Hệ thống Quản lý Đoàn Thanh Niên

Chào mừng bạn đến với **Hệ thống Quản lý Đoàn Viên**, một ứng dụng web được phát triển để hỗ trợ quản lý thông tin đoàn viên, điểm rèn luyện, đoàn phí, và tài khoản trong tổ chức Đoàn Thanh Niên. Dự án được xây dựng bằng PHP với cơ sở dữ liệu MySQL, chạy trên môi trường XAMPP, và cung cấp giao diện thân thiện để quản lý các hoạt động đoàn.

## 🚀 3. Các project đã thực hiện dựa trên Platform

Một số project sinh viên đã thực hiện:
- #### [Khoá 15](./docs/projects/K15/README.md)
- #### [Khoá 16]() (Coming soon)
##⚙️ 4. Cài đặt (XAMPP)
___________________________________________________
###4.1. Cài đặt công cụ, môi trường

Tải và cài đặt XAMPP:
👉 https://www.apachefriends.org/download.html

(khuyến nghị bản XAMPP với PHP 8.x)

Cài đặt Visual Studio Code và các extension:

PHP Intelephense

MySQL

Prettier – Code Formatter

###4.2. Tải project

Clone project về thư mục htdocs của XAMPP (ví dụ ổ C):

cd C:\xampp\htdocs
git clone https://gitlab.com/username/qlsv-doanvien.git


Truy cập project qua đường dẫn:
👉 http://localhost/index.php

###4.3. Setup database

Mở XAMPP Control Panel, start Apache và MySQL.

Truy cập MySQL WorkBench

Tạo database:

CREATE DATABASE IF NOT EXISTS quan_ly_doan_vien
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

###4.4. Setup tham số kết nối

Mở file db_connection trong project, chỉnh thông tin DB:

<?php
    function getDbConnection() {
        $servername = "localhost";
        $username = "root";
        $password = "Dung@28112005";
        $dbname = "quan_ly_doan_vien";
        $port = 3306;
        $conn = mysqli_connect($servername, $username, $password, $dbname, $port);
        if (!$conn) {
            die("Kết nối database thất bại: " . mysqli_connect_error());
        }
        mysqli_set_charset($conn, "utf8");
        return $conn;
    }
?>
###4.5. Chạy hệ thống

Mở XAMPP Control Panel → Start Apache và MySQL.

Truy cập hệ thống:
👉 http://localhost/index.php

###4.6. Đăng nhập lần đầu

Hệ thống có thể cấp tài khoản admin.

Admin đăng nhập xong có thể:

Tạo thông tin tổ chức đoàn (Đoàn trường, Liên chi, Chi đoàn).

Thêm đoàn viên và cấp tài khoản.

Quản lý phân quyền theo cấp.

    
