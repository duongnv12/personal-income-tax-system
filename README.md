# Hệ Thống Tính Thuế Thu Nhập Cá Nhân (PIT) Cá Nhân

Đây là một ứng dụng web được phát triển bằng Laravel, giúp người dùng cá nhân dễ dàng quản lý thu nhập, người phụ thuộc và tự động tính toán thuế thu nhập cá nhân (PIT) hàng tháng cũng như quyết toán thuế cuối năm theo quy định hiện hành của Việt Nam. Hệ thống cũng cung cấp giao diện quản trị để cấu hình các tham số tính thuế và quản lý người dùng.

-----

## Mục lục

  * [Tính năng chính](https://www.google.com/search?q=%23t%C3%ADnh-n%C4%83ng-ch%C3%ADnh)
  * [Công nghệ sử dụng](https://www.google.com/search?q=%23c%C3%B4ng-ngh%E1%BB%87-s%E1%BB%AD-d%E1%BB%A5ng)
  * [Cài đặt & Thiết lập](https://www.google.com/search?q=%23c%C3%A0i-%C4%91%E1%BA%B7t--thi%E1%BA%BFt-l%E1%BA%ADp)
  * [Sử dụng hệ thống](https://www.google.com/search?q=%23s%E1%BB%AD-d%E1%BB%A5ng-h%E1%BB%87-th%E1%BB%91ng)
  * [Cấu trúc dự án](https://www.google.com/search?q=%23c%E1%BA%A5u-tr%C3%BAc-d%E1%BB%B1-%C3%A1n)

-----

## Tính năng chính

### **Dành cho Người dùng (User)**

  * **Quản lý thông tin cá nhân:** Xem và cập nhật hồ sơ cá nhân.
  * **Quản lý người phụ thuộc:** Thêm, sửa, xóa thông tin người phụ thuộc (con cái, vợ/chồng, cha mẹ, v.v.), bao gồm ngày sinh, tình trạng khuyết tật và ngày đăng ký giảm trừ.
  * **Khai báo thu nhập hàng tháng:** Nhập chi tiết lương gross, các khoản thu nhập chịu thuế khác, thu nhập miễn thuế, và đóng góp từ thiện cho từng tháng.
  * **Tính toán PIT hàng tháng:** Hệ thống tự động tính toán thuế TNCN và lương net dựa trên khai báo thu nhập và các quy định hiện hành.
  * **Quyết toán thuế cuối năm:** Xem báo cáo tổng hợp thu nhập, giảm trừ, và thuế phải nộp/được hoàn lại cho cả năm tài chính.
  * **Xuất báo cáo PDF:** Tải xuống các báo cáo khai báo thu nhập tháng và quyết toán năm dưới dạng PDF.

### **Dành cho Quản trị viên (Admin)**

  * **Quản lý người dùng:** Xem, sửa, xóa người dùng, và bật/tắt trạng thái hoạt động của tài khoản người dùng.
  * **Quản lý cấu hình hệ thống:**
      * Thiết lập và cập nhật các tham số quan trọng cho việc tính thuế như:
          * Mức giảm trừ bản thân.
          * Mức giảm trừ người phụ thuộc.
          * Tỷ lệ đóng bảo hiểm xã hội (BHXH, BHYT, BHTN).
          * Mức trần lương đóng bảo hiểm xã hội.
      * Các cấu hình này có ngày hiệu lực, đảm bảo hệ thống áp dụng đúng quy định theo thời gian.
  * **Quản lý bậc thuế:** Xem, sửa các bậc thuế lũy tiến theo quy định của pháp luật.

-----

## Công nghệ sử dụng

  * **Backend:** Laravel (PHP)
  * **Database:** MySQL (hoặc các CSDL tương thích với Eloquent ORM)
  * **Frontend:** Blade Templates, Tailwind CSS, Alpine.js (kết hợp với Laravel Breeze)
  * **Báo cáo PDF:** Laravel DomPDF (hoặc tương tự)

-----

## Cài đặt & Thiết lập

### **Yêu cầu hệ thống**

  * PHP \>= 8.2
  * Composer
  * Node.js & npm
  * MySQL (hoặc PostgreSQL, SQLite)

### **Các bước cài đặt**

1.  **Clone dự án:**

    ```bash
    git clone https://github.com/duongnv12/personal-income-tax-system
    cd personal-income-tax-system
    ```

2.  **Cài đặt Composer Dependencies:**

    ```bash
    composer install
    ```

3.  **Cài đặt Node Dependencies & Compile Assets:**

    ```bash
    npm install
    npm run dev # Hoặc npm run build cho môi trường production
    ```

4.  **Tạo file `.env` và cấu hình Database:**
    Sao chép file `.env.example` thành `.env`:

    ```bash
    cp .env.example .env
    ```

    Mở file `.env` và cập nhật thông tin database của bạn:

    ```dotenv
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=laravel_pit
    DB_USERNAME=root
    DB_PASSWORD=
    ```

5.  **Tạo khóa ứng dụng:**

    ```bash
    php artisan key:generate
    ```

6.  **Chạy Migrations & Seeders:**
    Tạo các bảng trong database và điền dữ liệu mẫu (bao gồm tài khoản admin và cấu hình ban đầu):

    ```bash
    php artisan migrate --seed
    ```

    *Lưu ý:* Seeders sẽ tạo một tài khoản admin mặc định:

      * **Email:** `admin@example.com`
      * **Mật khẩu:** `password`

7.  **Cấu hình Ngôn ngữ (tiếng Việt):**
    Đảm bảo file `config/app.php` có cài đặt `locale` là `'vi'`:

    ```php
    // config/app.php
    'locale' => 'vi',
    // ...
    'fallback_locale' => 'en',
    ```

    Đảm bảo các file ngôn ngữ tiếng Việt đã tồn tại trong thư mục `lang/vi/` (ví dụ: `lang/vi/admin/config.php`).

8.  **Xóa Cache (quan trọng sau khi thay đổi cấu hình/ngôn ngữ):**

    ```bash
    php artisan optimize:clear # Hoặc từng lệnh: cache:clear, config:clear, route:clear, view:clear
    ```

9.  **Khởi động máy chủ phát triển:**

    ```bash
    php artisan serve
    ```

    Ứng dụng sẽ có sẵn tại `http://127.0.0.1:8000`.

-----

## Sử dụng hệ thống

1.  **Đăng ký tài khoản:**
    Truy cập `http://127.0.0.1:8000/register` để tạo tài khoản người dùng mới.

2.  **Đăng nhập tài khoản:**
    Truy cập `http://127.0.0.1:8000/login` để đăng nhập.

3.  **Truy cập Admin Panel:**
    Nếu bạn đã đăng nhập bằng tài khoản admin (ví dụ: `admin@example.com`), bạn có thể truy cập các tính năng quản trị thông qua menu điều hướng hoặc trực tiếp tại URL `/admin/dashboard`.

-----

## Cấu trúc dự án

  * **`app/Http/Controllers/`**: Chứa các logic xử lý yêu cầu HTTP.
      * `App\Http\Controllers\Admin`: Các controller dành cho quản trị viên.
  * **`app/Models/`**: Chứa các Eloquent Models tương tác với database.
  * **`app/Services/TaxCalculationService.php`**: Chứa toàn bộ logic tính toán thuế TNCN.
  * **`app/View/Components/`**: Định nghĩa các Blade Components tùy chỉnh (`Textarea.php`).
  * **`database/migrations/`**: Chứa các file định nghĩa cấu trúc bảng database.
  * **`database/seeders/`**: Chứa các file để điền dữ liệu ban đầu vào database.
  * **`routes/web.php`**: Định nghĩa tất cả các route web của ứng dụng.
  * **`resources/views/`**: Chứa các Blade templates (giao diện người dùng).
      * `resources/views/layouts/`: Các layout chung (ví dụ: `app.blade.php`, `navigation.blade.php`).
      * `resources/views/admin/`: Các view dành cho quản trị viên.
      * `resources/views/components/`: Các Blade Components (ví dụ: `textarea.blade.php`).
  * **`lang/vi/`**: Chứa các file ngôn ngữ tiếng Việt (ví dụ: `lang/vi/admin/config.php`).

-----