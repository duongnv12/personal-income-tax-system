<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Bảng điều khiển Admin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p class="mb-8 text-lg font-medium">Chào mừng đến với Bảng điều khiển Quản trị, <span class="font-semibold text-blue-700">{{ Auth::user()->name }}</span>!</p>

                    <h3 class="text-2xl font-bold text-gray-800 mb-6 border-b-2 border-gray-200 pb-3">Tổng quan hệ thống</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        {{-- Card: Tổng số người dùng --}}
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-xl shadow-md flex items-center justify-between transition-transform duration-200 hover:scale-[1.02]">
                            <div>
                                <h4 class="font-semibold text-sm text-blue-700">Tổng số người dùng</h4>
                                <p class="text-3xl font-bold text-blue-900 mt-1">{{ $totalUsers }}</p>
                                <a href="{{ route('admin.users.index') }}" class="text-sm text-blue-600 hover:text-blue-800 hover:underline mt-2 inline-flex items-center">
                                    Xem chi tiết <i class="fa-solid fa-arrow-right ml-1 text-xs"></i>
                                </a>
                            </div>
                            <i class="fa-solid fa-users text-blue-400 text-3xl opacity-50"></i>
                        </div>

                        {{-- Card: Tổng khoản thu nhập --}}
                        <div class="bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-xl shadow-md flex items-center justify-between transition-transform duration-200 hover:scale-[1.02]">
                            <div>
                                <h4 class="font-semibold text-sm text-green-700">Tổng khoản thu nhập</h4>
                                <p class="text-3xl font-bold text-green-900 mt-1">{{ $totalIncomeEntries }}</p>
                                {{-- Có thể thêm liên kết đến một trang tổng quan thu nhập chung nếu cần --}}
                                <a href="{{ route('income-entries.index') }}" class="text-sm text-green-600 hover:text-green-800 hover:underline mt-2 inline-flex items-center">
                                    Xem các khoản thu <i class="fa-solid fa-arrow-right ml-1 text-xs"></i>
                                </a>
                            </div>
                            <i class="fa-solid fa-money-bill-transfer text-green-400 text-3xl opacity-50"></i>
                        </div>

                        {{-- Card: Tham số thuế --}}
                        <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 p-6 rounded-xl shadow-md flex items-center justify-between transition-transform duration-200 hover:scale-[1.02]">
                            <div>
                                <h4 class="font-semibold text-sm text-yellow-700">Tham số thuế</h4>
                                <p class="text-3xl font-bold text-yellow-900 mt-1">{{ $totalTaxParameters }}</p>
                                <a href="{{ route('admin.tax-parameters.index') }}" class="text-sm text-yellow-600 hover:text-yellow-800 hover:underline mt-2 inline-flex items-center">
                                    Quản lý <i class="fa-solid fa-arrow-right ml-1 text-xs"></i>
                                </a>
                            </div>
                            <i class="fa-solid fa-gear text-yellow-400 text-3xl opacity-50"></i>
                        </div>

                        {{-- Card: Bậc thuế --}}
                        <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-6 rounded-xl shadow-md flex items-center justify-between transition-transform duration-200 hover:scale-[1.02]">
                            <div>
                                <h4 class="font-semibold text-sm text-purple-700">Bậc thuế</h4>
                                <p class="text-3xl font-bold text-purple-900 mt-1">{{ $totalTaxBrackets }}</p>
                                <a href="{{ route('admin.tax-brackets.index') }}" class="text-sm text-purple-600 hover:text-purple-800 hover:underline mt-2 inline-flex items-center">
                                    Quản lý <i class="fa-solid fa-arrow-right ml-1 text-xs"></i>
                                </a>
                            </div>
                            <i class="fa-solid fa-percent text-purple-400 text-3xl opacity-50"></i>
                        </div>
                    </div>

                    {{-- Có thể thêm các biểu đồ hoặc thông tin chi tiết khác ở đây --}}

                    <h3 class="text-2xl font-bold text-gray-800 mt-10 mb-6 border-b-2 border-gray-200 pb-3">Các hành động nhanh</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center justify-center p-6 bg-white border border-gray-200 rounded-lg shadow-md text-gray-700 font-semibold text-lg hover:bg-gray-50 hover:shadow-lg transition duration-200 transform hover:-translate-y-1">
                            <i class="fa-solid fa-user-gear mr-3 text-2xl text-blue-600"></i> Quản lý Người dùng
                        </a>
                        <a href="{{ route('admin.tax-parameters.index') }}" class="inline-flex items-center justify-center p-6 bg-white border border-gray-200 rounded-lg shadow-md text-gray-700 font-semibold text-lg hover:bg-gray-50 hover:shadow-lg transition duration-200 transform hover:-translate-y-1">
                            <i class="fa-solid fa-cogs mr-3 text-2xl text-yellow-600"></i> Cấu hình Tham số Thuế
                        </a>
                        <a href="{{ route('admin.tax-brackets.index') }}" class="inline-flex items-center justify-center p-6 bg-white border border-gray-200 rounded-lg shadow-md text-gray-700 font-semibold text-lg hover:bg-gray-50 hover:shadow-lg transition duration-200 transform hover:-translate-y-1">
                            <i class="fa-solid fa-layer-group mr-3 text-2xl text-purple-600"></i> Cấu hình Bậc Thuế
                        </a>
                        {{-- Thêm các liên kết hành động nhanh khác nếu cần --}}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>