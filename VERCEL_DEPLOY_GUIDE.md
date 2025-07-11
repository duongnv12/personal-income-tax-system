# Hướng dẫn deploy Laravel lên Vercel

## 1. Cài đặt các package cần thiết

Bạn nên sử dụng package `laravel-vercel` hoặc `bref` để hỗ trợ chạy Laravel trên môi trường serverless. Tuy nhiên, Vercel đã hỗ trợ PHP nên bạn có thể deploy trực tiếp với cấu hình đơn giản.

## 2. Tạo file cấu hình Vercel
- File `vercel.json` đã được tạo sẵn.

## 3. Đẩy code lên GitHub
- Tạo repository trên GitHub và push toàn bộ mã nguồn lên đó.

## 4. Kết nối Vercel với GitHub
- Truy cập https://vercel.com/import/git
- Chọn repository vừa tạo
- Chọn framework là **Other** hoặc **PHP**

## 5. Thiết lập biến môi trường
- Vào dashboard Vercel > Project > Settings > Environment Variables
- Thêm các biến từ file `.env` (không cần dấu ngoặc kép)

## 6. Kết nối database
- Sử dụng dịch vụ database cloud như PlanetScale, Supabase, hoặc RDS
- Cập nhật các biến DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD cho đúng thông tin cloud

## 7. Build và deploy
- Vercel sẽ tự động build và deploy khi có thay đổi trên GitHub
- Kiểm tra log và truy cập domain do Vercel cấp

## 8. Lưu ý
- Không nên lưu file `.env` lên GitHub, chỉ copy giá trị lên Vercel dashboard
- Nếu sử dụng queue, cache, session... nên chuyển sang các dịch vụ cloud (Redis, S3, v.v)
- Nếu cần gửi mail, đảm bảo các biến MAIL_* đã đúng và dịch vụ mail cho phép gửi từ server Vercel

## 9. Debug
- Kiểm tra log tại dashboard Vercel
- Kiểm tra file `storage/logs/laravel.log` nếu có lỗi

---

Nếu cần hướng dẫn chi tiết hơn cho từng bước, hãy hỏi nhé!
