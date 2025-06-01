<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8 md:py-12 bg-gray-50"> {{-- Thêm bg-gray-50 cho nền nhẹ nhàng --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl"> {{-- Shadow lớn hơn, rounded đẹp hơn --}}
                <div class="p-6 sm:p-10 text-gray-900">
                    <h3 class="text-3xl font-bold text-gray-800 mb-8 text-center leading-tight">
                        {{ __('Tổng Quan Hệ Thống') }}
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
                        {{-- Card: Tổng số Người dùng --}}
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-xl shadow-lg hover:shadow-xl transition duration-300 transform hover:-translate-y-1 border border-blue-200 text-center">
                            <h4 class="text-lg font-semibold text-blue-800 mb-3">{{ __('Tổng số Người dùng') }}</h4>
                            <p class="text-5xl font-extrabold text-blue-700">{{ $totalUsers }}</p>
                        </div>
                        {{-- Card: Tổng số Khai báo Thu nhập --}}
                        <div class="bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-xl shadow-lg hover:shadow-xl transition duration-300 transform hover:-translate-y-1 border border-green-200 text-center">
                            <h4 class="text-lg font-semibold text-green-800 mb-3">{{ __('Tổng số Khai báo Thu nhập') }}</h4>
                            <p class="text-5xl font-extrabold text-green-700">{{ $totalIncomeDeclarations }}</p>
                        </div>
                        {{-- Card: Tổng số Người phụ thuộc --}}
                        <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-6 rounded-xl shadow-lg hover:shadow-xl transition duration-300 transform hover:-translate-y-1 border border-purple-200 text-center">
                            <h4 class="text-lg font-semibold text-purple-800 mb-3">{{ __('Tổng số Người phụ thuộc') }}</h4>
                            <p class="text-5xl font-extrabold text-purple-700">{{ $totalDependents }}</p>
                        </div>
                        {{-- Card: Tổng số Cấu hình Hệ thống --}}
                        <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 p-6 rounded-xl shadow-lg hover:shadow-xl transition duration-300 transform hover:-translate-y-1 border border-yellow-200 text-center">
                            <h4 class="text-lg font-semibold text-yellow-800 mb-3">{{ __('Tổng số Cấu hình Hệ thống') }}</h4>
                            <p class="text-5xl font-extrabold text-yellow-700">{{ $totalSystemConfigs }}</p>
                        </div>
                        {{-- Card: Tổng số Bậc thuế --}}
                        <div class="bg-gradient-to-br from-red-50 to-red-100 p-6 rounded-xl shadow-lg hover:shadow-xl transition duration-300 transform hover:-translate-y-1 border border-red-200 text-center">
                            <h4 class="text-lg font-semibold text-red-800 mb-3">{{ __('Tổng số Bậc thuế') }}</h4>
                            <p class="text-5xl font-extrabold text-red-700">{{ $totalTaxBrackets }}</p>
                        </div>
                    </div>

                    <h3 class="text-3xl font-bold text-gray-800 mb-6 text-center leading-tight mt-10">{{ __('Hành Động Nhanh (Admin)') }}</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                        <a href="{{ route('admin.system_configs.index') }}" class="block p-5 bg-gradient-to-br from-indigo-500 to-indigo-600 text-white rounded-xl shadow-lg hover:shadow-xl hover:from-indigo-600 hover:to-indigo-700 transition duration-300 transform hover:-translate-y-1 text-center font-semibold text-lg flex items-center justify-center">
                            <svg class="h-6 w-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.827 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.827 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.827-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.827-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            {{ __('Quản lý Cấu hình') }}
                        </a>
                        <a href="{{ route('admin.tax_brackets.index') }}" class="block p-5 bg-gradient-to-br from-teal-500 to-teal-600 text-white rounded-xl shadow-lg hover:shadow-xl hover:from-teal-600 hover:to-teal-700 transition duration-300 transform hover:-translate-y-1 text-center font-semibold text-lg flex items-center justify-center">
                            <svg class="h-6 w-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 3-3M6 12l3-3 3 3 3-3M18 12l3-3 3 3M4 12V4h16v8M4 12h16M4 12a2 2 0 01-2-2V4a2 2 0 012-2h16a2 2 0 012 2v6a2 2 0 01-2 2"></path></svg>
                            {{ __('Quản lý Bậc thuế') }}
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="block p-5 bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-xl shadow-lg hover:shadow-xl hover:from-blue-600 hover:to-blue-700 transition duration-300 transform hover:-translate-y-1 text-center font-semibold text-lg flex items-center justify-center">
                            <svg class="h-6 w-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h2a2 2 0 002-2V4a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2h2m-1-9h11m-1-2h.01M10 11H5m10 0a2 2 0 11-4 0 2 2 0 014 0zM7 20h2a2 2 0 002-2v-3a2 2 0 00-2-2H7a2 2 0 00-2 2v3a2 2 0 002 2z"></path></svg>
                            {{ __('Quản lý Người dùng') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>