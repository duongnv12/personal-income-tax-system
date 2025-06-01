/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                // Thêm dòng này để định nghĩa font mới
                // 'sans' là font mặc định của Tailwind cho toàn bộ trang
                sans: ['Inter', 'sans-serif'], // Thay thế Roboto bằng tên font của bạn
                // Nếu bạn muốn giữ font mặc định và thêm một font khác, ví dụ font cho tiêu đề:
                heading: ['Inter', 'sans-serif'],
            },
        },
    },

    plugins: [require('@tailwindcss/forms')],
};