<nav x-data="{ open: false }"
    class="bg-indigo-50 border-b border-indigo-200 shadow-sm sticky top-0 z-50 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    {{-- Chuyển hướng về dashboard phù hợp với vai trò --}}
                    @auth
                        @if (Auth::user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}"
                                class="flex items-center space-x-2 text-gray-800 hover:text-gray-900 transition duration-150 ease-in-out">
                                {{-- Có thể thay thế x-application-logo bằng SVG hoặc hình ảnh logo của bạn --}}
                                <x-application-logo class="block h-9 w-auto fill-current text-indigo-600" />
                            </a>
                        @else
                            <a href="{{ route('dashboard') }}"
                                class="flex items-center space-x-2 text-gray-800 hover:text-gray-900 transition duration-150 ease-in-out">
                                <x-application-logo class="block h-9 w-auto fill-current text-indigo-600" />
                            </a>
                        @endif
                    @else
                        <a href="{{ url('/') }}"
                            class="flex items-center space-x-2 text-gray-800 hover:text-gray-900 transition duration-150 ease-in-out">
                            {{-- Nếu chưa đăng nhập, về trang chủ --}}
                            <x-application-logo class="block h-9 w-auto fill-current text-indigo-600" />
                        </a>
                    @endauth
                </div>

                <div class="hidden space-x-6 sm:-my-px sm:ml-10 sm:flex">
                    @auth {{-- Chỉ hiển thị khi đã đăng nhập --}}
                        @if (Auth::user()->isAdmin())
                            {{-- Liên kết cho Admin --}}
                            <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')"
                                class="text-base font-medium hover:text-indigo-600 hover:border-indigo-400 transition-colors duration-200">
                                {{ __('Bảng điều khiển Admin') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')"
                                class="text-base font-medium hover:text-indigo-600 hover:border-indigo-400 transition-colors duration-200">
                                {{ __('Quản lý Người dùng') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.tax-parameters.index')"
                                :active="request()->routeIs('admin.tax-parameters.*')"
                                class="text-base font-medium hover:text-indigo-600 hover:border-indigo-400 transition-colors duration-200">
                                {{ __('Quản lý Tham số Thuế') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.tax-brackets.index')"
                                :active="request()->routeIs('admin.tax-brackets.*')"
                                class="text-base font-medium hover:text-indigo-600 hover:border-indigo-400 transition-colors duration-200">
                                {{ __('Quản lý Bậc Thuế') }}
                            </x-nav-link>
                        @else
                            {{-- Liên kết cho User thông thường --}}
                            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')"
                                class="text-base font-medium hover:text-indigo-600 hover:border-indigo-400 transition-colors duration-200">
                                {{ __('Bảng điều khiển cá nhân') }}
                            </x-nav-link>
                            <x-nav-link :href="route('income-sources.index')" :active="request()->routeIs('income-sources.*')"
                                class="text-base font-medium hover:text-indigo-600 hover:border-indigo-400 transition-colors duration-200">
                                {{ __('Nguồn thu nhập') }}
                            </x-nav-link>
                            <x-nav-link :href="route('income-entries.index')" :active="request()->routeIs('income-entries.*')"
                                class="text-base font-medium hover:text-indigo-600 hover:border-indigo-400 transition-colors duration-200">
                                {{ __('Khoản thu nhập') }}
                            </x-nav-link>
                            <x-nav-link :href="route('dependents.index')" :active="request()->routeIs('dependents.*')"
                                class="text-base font-medium hover:text-indigo-600 hover:border-indigo-400 transition-colors duration-200">
                                {{ __('Người phụ thuộc') }}
                            </x-nav-link>
                        @endif
                    @endauth
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-4 font-semibold rounded-full text-gray-700 bg-gray-100 hover:text-gray-900 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm transform hover:scale-[1.02]">
                                <div>{{ Auth::user()->name }}</div>
                                <div class="ms-2">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')"
                                class="hover:bg-indigo-50 hover:text-indigo-700 transition-colors duration-200">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault();
                                                        this.closest('form').submit();"
                                    class="hover:bg-red-50 hover:text-red-700 transition-colors duration-200">
                                    {{ __('Đăng xuất') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <a href="{{ route('login') }}"
                        class="font-medium text-gray-600 hover:text-gray-900 transition-colors duration-200 mr-4">
                        {{ __('Đăng nhập') }}
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                            {{ __('Đăng ký') }}
                        </a>
                    @endif
                @endauth
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @auth
                @if (Auth::user()->isAdmin())
                    <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')"
                        class="font-medium hover:text-indigo-700 hover:bg-indigo-50 transition-colors duration-200">
                        {{ __('Bảng điều khiển Admin') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')"
                        class="font-medium hover:text-indigo-700 hover:bg-indigo-50 transition-colors duration-200">
                        {{ __('Quản lý Người dùng') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.tax-parameters.index')"
                        :active="request()->routeIs('admin.tax-parameters.*')"
                        class="font-medium hover:text-indigo-700 hover:bg-indigo-50 transition-colors duration-200">
                        {{ __('Quản lý Tham số Thuế') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.tax-brackets.index')"
                        :active="request()->routeIs('admin.tax-brackets.*')"
                        class="font-medium hover:text-indigo-700 hover:bg-indigo-50 transition-colors duration-200">
                        {{ __('Quản lý Bậc Thuế') }}
                    </x-responsive-nav-link>
                @else
                    <x-responsive-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')"
                        class="font-medium hover:text-indigo-700 hover:bg-indigo-50 transition-colors duration-200">
                        {{ __('Bảng điều khiển cá nhân') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link href="{{ route('income-sources.index')"
                        :active="request()->routeIs('income-sources.*')"
                        class="font-medium hover:text-indigo-700 hover:bg-indigo-50 transition-colors duration-200">
                        {{ __('Nguồn thu nhập') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link href="{{ route('income-entries.index')"
                        :active="request()->routeIs('income-entries.*')"
                        class="font-medium hover:text-indigo-700 hover:bg-indigo-50 transition-colors duration-200">
                        {{ __('Khoản thu nhập') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link href="{{ route('dependents.index')" :active="request()->routeIs('dependents.*')"
                        class="font-medium hover:text-indigo-700 hover:bg-indigo-50 transition-colors duration-200">
                        {{ __('Người phụ thuộc') }}
                    </x-responsive-nav-link>
                @endif
            @endauth
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')"
                    class="font-medium hover:text-gray-800 hover:bg-gray-100 transition-colors duration-200">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault();
                                        this.closest('form').submit();"
                        class="font-medium hover:text-red-700 hover:bg-red-50 transition-colors duration-200">
                        {{ __('Đăng xuất') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>