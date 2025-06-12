<section class="space-y-6">
    <header class="text-center mb-8"> {{-- text-center mb-8 --}}
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Xóa tài khoản') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Khi tài khoản của bạn bị xóa, tất cả tài nguyên và dữ liệu của nó sẽ bị xóa vĩnh viễn. Vui lòng nhập mật khẩu của bạn để xác nhận rằng bạn muốn xóa vĩnh viễn tài khoản của mình.') }}
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="inline-flex items-center px-5 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md" {{-- Đồng bộ style nút --}}
    >
        <i class="fa-solid fa-trash-alt mr-2"></i> {{ __('Xóa tài khoản') }}
    </x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Bạn có chắc chắn muốn xóa tài khoản của mình không?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ __('Khi tài khoản của bạn bị xóa, tất cả tài nguyên và dữ liệu của nó sẽ bị xóa vĩnh viễn. Vui lòng nhập mật khẩu của bạn để xác nhận rằng bạn muốn xóa vĩnh viễn tài khoản của mình.') }}
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('Mật khẩu') }}" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-3/4 rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition duration-150 ease-in-out text-sm" {{-- text-sm --}}
                    placeholder="{{ __('Mật khẩu') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')" class="px-5 py-2 bg-gray-200 hover:bg-gray-300 focus:ring-gray-500 shadow-md"> {{-- px-5 py-2, shadow-md --}}
                    <i class="fa-solid fa-ban mr-2"></i> {{ __('Hủy') }}
                </x-secondary-button>

                <x-danger-button class="ms-3 px-5 py-2 bg-red-600 hover:bg-red-700 focus:ring-red-500 shadow-md"> {{-- px-5 py-2, shadow-md --}}
                    <i class="fa-solid fa-trash-alt mr-2"></i> {{ __('Xóa tài khoản') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
