# 🧾 Hệ Thống Tính Thuế Thu Nhập Cá Nhân (PIT System)

![Laravel](https://img.shields.io/badge/Laravel-12.x-red?logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.2%2B-blue?logo=php)
![License](https://img.shields.io/badge/license-MIT-green)
![Status](https://img.shields.io/badge/status-active-brightgreen)

> Hệ thống web giúp quản lý, khai báo và tính toán thuế thu nhập cá nhân (TNCN) cho cá nhân tại Việt Nam. Hỗ trợ quản lý người dùng, nguồn thu nhập, người phụ thuộc, các khoản giảm trừ, và xuất báo cáo PDF.

---

## 📑 Mục lục

- [Tính năng chính](#tính-năng-chính)
- [Công nghệ sử dụng](#công-nghệ-sử-dụng)
- [Cài đặt nhanh](#cài-đặt-nhanh)
- [Tài khoản mẫu](#tài-khoản-mẫu)
- [Lệnh Artisan hữu ích](#lệnh-artisan-hữu-ích)
- [Cấu trúc thư mục](#cấu-trúc-thư-mục)
- [Quy trình nghiệp vụ](#quy-trình-nghiệp-vụ)
- [Đóng góp](#đóng-góp)
- [Liên hệ](#liên-hệ)
- [Bản quyền](#bản-quyền)

---

## 🚀 Tính năng chính

- Đăng ký, đăng nhập, xác thực email, phân quyền Admin/người dùng.
- Quản lý thông tin cá nhân, đổi mật khẩu, xóa tài khoản.
- Khai báo nguồn thu nhập, khoản thu nhập hàng tháng/năm.
- Quản lý người phụ thuộc, khai báo thời gian giảm trừ.
- Tính toán thuế TNCN tự động theo quy định hiện hành.
- Quản lý các bậc thuế, tham số hệ thống (giảm trừ, tỷ lệ bảo hiểm...).
- Xuất báo cáo quyết toán thuế cuối năm và khai báo thu nhập ra file PDF.
- Giao diện hiện đại, responsive, hỗ trợ tiếng Việt.

---

## 🛠️ Công nghệ sử dụng

- **Backend:** Laravel 12+, PHP 8.2+
- **Frontend:** Blade, TailwindCSS, FontAwesome
- **Database:** MySQL
- **PDF:** barryvdh/laravel-dompdf
- **Xác thực:** Laravel Breeze, Sanctum

---

## ⚡ Cài đặt nhanh

```sh
git clone https://github.com/duongnv12/personal-income-tax-system.git
cd personal-income-tax-system

composer install
npm install

cp .env.example .env
# Chỉnh sửa thông tin kết nối DB trong .env

php artisan key:generate
php artisan migrate --seed
npm run build

php artisan serve
```

Truy cập hệ thống tại: [http://localhost:8000](http://localhost:8000)

---

## 👤 Tài khoản mẫu

| Loại tài khoản | Email                | Mật khẩu   |
|---------------|----------------------|------------|
| Admin         | admin@example.com    | password   |
| User thường   | user@example.com     | password   |

---

## 🧩 Lệnh Artisan hữu ích

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

---

## 📂 Cấu trúc thư mục

- `app/Http/Controllers/` – Controllers cho các chức năng chính
- `app/Services/TaxCalculationService.php` – Logic tính toán thuế
- `resources/views/` – Giao diện Blade
- `database/seeders/` – Seeder dữ liệu mẫu
- `routes/web.php` – Định nghĩa route web

---

## 🔄 Quy trình nghiệp vụ

Toàn bộ logic tính toán thuế thu nhập cá nhân được đóng gói trong [`app/Services/TaxCalculationService.php`](app/Services/TaxCalculationService.php).

### Quy trình tổng quát:

1. **Thu thập dữ liệu đầu vào:**  
   - Thông tin cá nhân, thu nhập, người phụ thuộc, các khoản giảm trừ, bảo hiểm, v.v.

2. **Tính tổng thu nhập chịu thuế:**  
   - Tổng hợp các khoản thu nhập hợp lệ theo quy định.

3. **Áp dụng các khoản giảm trừ:**  
   - Giảm trừ gia cảnh cho bản thân, người phụ thuộc, bảo hiểm bắt buộc, các khoản đóng góp hợp lệ.

4. **Tính thu nhập tính thuế:**  
   - Thu nhập chịu thuế = Tổng thu nhập - Tổng giảm trừ.

5. **Phân bậc thuế và tính số thuế phải nộp:**  
   - Áp dụng biểu thuế lũy tiến từng phần theo quy định hiện hành.

6. **Xuất kết quả:**  
   - Trả về số thuế phải nộp, chi tiết từng bậc thuế, tổng giảm trừ, và các thông tin liên quan để hiển thị hoặc xuất báo cáo PDF.

> **Lưu ý:**  
> Mọi thay đổi về quy định thuế, mức giảm trừ, hoặc biểu thuế đều được cấu hình tập trung tại Service này để dễ bảo trì và cập nhật.

---

## 🤝 Đóng góp

Mọi đóng góp, báo lỗi hoặc đề xuất đều được hoan nghênh!  
Vui lòng tạo [issue](https://github.com/duongnv12/personal-income-tax-system/issues) hoặc gửi pull request.

---

## 📬 Liên hệ

- Email: ngduog.04@gmail.com
- Github: [duongnv12](https://github.com/duongnv12)

---

## ©️ Bản quyền

**Bản quyền © 2025** – Dự án mã nguồn mở phục vụ mục đích học tập và tham khảo.