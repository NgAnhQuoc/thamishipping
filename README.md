# Hướng dẫn cấu hình Source Code WordPress lên Hosting

Dưới đây là các bước chi tiết và ngắn gọn để cấu hình source code website lên môi trường hosting.

## Bước 1: Tải source code lên Hosting
1. Đăng nhập vào trang quản trị hosting (cPanel, DirectAdmin, v.v.).
2. Mở **File Manager** (Quản lý tệp), truy cập vào thư mục **`public_html`** (hoặc thư mục gốc của tên miền).
3. Tải file source code (`.zip`) mà bạn đang có lên thư mục này và **Extract** (Giải nén).

## Bước 2: Tạo Cơ sở dữ liệu (Database) mới
1. Trên hosting, tìm mục **MySQL Databases** (Cơ sở dữ liệu MySQL).
2. Tạo một **Database** mới.
3. Tạo một **Database User** mới cùng với mật khẩu.
4. Thêm User vừa tạo vào Database và cấp toàn quyền (**All Privileges**).
5. **Ghi lại 3 thông tin**: Tên Database, Tên User và Mật khẩu để dùng cho bước sau.

## Bước 3: Import (Nhập) Database
1. Mở công cụ **phpMyAdmin** trên hosting.
2. Chọn Database mới tạo ở Bước 2.
3. Nhấn vào tab **Import** (Nhập).
4. Chọn file **`thamishipping.sql`** từ máy tính và bấm **Go** (Thực hiện) để nhập dữ liệu.

## Bước 4: Chỉnh sửa cấu hình `wp-config.php`
1. Quay lại **File Manager**, tìm file **`wp-config.php`** và chọn **Edit** (Chỉnh sửa).
2. Tìm đến các dòng cấu hình Database (khoảng dòng 23-29) và thay đổi bằng thông tin đã ghi lại ở Bước 2:
   ```php
   /** The name of the database for WordPress */
   define( 'DB_NAME', 'tên_database_mới_tạo_trên_hosting' );

   /** Database username */
   define( 'DB_USER', 'tên_user_mới_tạo_trên_hosting' );

   /** Database password */
   define( 'DB_PASSWORD', 'mật_khẩu_của_user_đó' );
   ```
3. Lưu lại file. *(Không cần thay đổi `DB_HOST` trừ khi hosting của bạn yêu cầu khác `localhost`)*.

## Bước 5: Cập nhật lại đường dẫn Tên miền (Domain)
1. Quay lại **phpMyAdmin**, click vào Database của bạn.
2. Tìm và mở bảng **`wp_options`**.
3. Tìm 2 dòng có `option_name` là **`siteurl`** và **`home`** (thường ở ngay trang đầu tiên).
4. Chỉnh sửa `option_value` của cả 2 dòng này thành tên miền thực tế của bạn (Ví dụ: `https://tenmien.com`).
   *(Lưu ý: Phải có `http://` hoặc `https://` và **không có** dấu `/` ở cuối)*.

## Bước 6: Hoàn tất
1. Truy cập vào website bằng tên miền của bạn để kiểm tra.
2. Đăng nhập vào trang quản trị Admin (`https://tenmien.com/wp-admin`).
3. Truy cập **Cài đặt (Settings)** > **Đường dẫn tĩnh (Permalinks)**, cuộn xuống dưới cùng và nhấn **Lưu thay đổi (Save Changes)** để cập nhật lại cấu trúc URL (giúp tránh lỗi 404 cho các trang con).

Chúc bạn đưa website lên hosting thành công!
