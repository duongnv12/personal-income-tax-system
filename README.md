# Hệ Thống Tính Thuế Thu Nhập Cá Nhân (PIT System)

Đây là một hệ thống web giúp quản lý, khai báo và tính toán thuế thu nhập cá nhân (TNCN) cho cá nhân tại Việt Nam. Hệ thống hỗ trợ quản lý người dùng, nguồn thu nhập, người phụ thuộc, các khoản giảm trừ, và xuất báo cáo PDF.

## Tính năng chính

- Đăng ký, đăng nhập, xác thực email, phân quyền Admin/người dùng.
- Quản lý thông tin cá nhân, đổi mật khẩu, xóa tài khoản.
- Khai báo nguồn thu nhập, khoản thu nhập hàng tháng.
- Quản lý người phụ thuộc, khai báo thời gian giảm trừ.
- Tính toán thuế TNCN tự động theo quy định hiện hành.
- Quản lý các bậc thuế, tham số hệ thống (giảm trừ, tỷ lệ bảo hiểm...).
- Xuất báo cáo quyết toán thuế cuối năm và khai báo thu nhập ra file PDF.
- Giao diện hiện đại, responsive, hỗ trợ tiếng Việt.

## Công nghệ sử dụng

- **Backend:** Laravel 10+, PHP 8.1+
- **Frontend:** Blade, TailwindCSS, FontAwesome
- **Database:** MySQL/MariaDB
- **PDF:** barryvdh/laravel-dompdf
- **Xác thực:** Laravel Breeze, Sanctum

## Cài đặt

### 1. Clone dự án

```sh
git clone https://github.com/your-username/personal-income-tax-system.git
cd personal-income-tax-system
```
### 2. Cài đặt các package

```sh
composer install
npm install
```

### 3. Tạo file cấu hình môi trường

```sh
cp .env.example .env
```
Sau đó chỉnh sửa các thông tin kết nối database trong file `.env` cho phù hợp với môi trường của bạn.

### 4. Tạo khóa ứng dụng

```sh
php artisan key:generate
```

### 5. Chạy migration và seed dữ liệu mẫu

```sh
php artisan migrate --seed
```

### 6. Biên dịch assets

```sh
npm run build
```

### 7. Khởi động server

```sh
php artisan serve
```

Truy cập hệ thống tại [http://localhost:8000](http://localhost:8000)

## Tài khoản mẫu

- **Admin:**  
  Email: `admin@example.com`  
  Mật khẩu: `password`

- **User thường:**  
  Email: `user@example.com`  
  Mật khẩu: `password`

## Một số lệnh Artisan hữu ích

- Làm mới database và seed lại dữ liệu:
  ```sh
  php artisan migrate:fresh --seed
  ```
- Xóa cache:
  ```sh
  php artisan config:clear
  php artisan cache:clear
  php artisan route:clear
  ```

## Cấu trúc thư mục

- `app/Http/Controllers/` - Controllers cho các chức năng chính
- `app/Services/TaxCalculationService.php` - Logic tính toán thuế
- `resources/views/` - Giao diện Blade
- `database/seeders/` - Seeder dữ liệu mẫu
- `routes/web.php` - Định nghĩa route web

## Đóng góp

Mọi đóng góp, báo lỗi hoặc đề xuất đều được hoan nghênh! Vui lòng tạo issue hoặc pull request.

---

**Bản quyền © 2025** – Dự án mã nguồn mở phục vụ mục đích học tập và tham khảo.