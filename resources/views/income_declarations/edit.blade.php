<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Chỉnh Sửa Khai Báo Thu Nhập') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('income_declarations.update', $incomeDeclaration) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        @include('income_declarations.form', ['incomeDeclaration' => $incomeDeclaration])
                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('income_declarations.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">Hủy</a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Cập Nhật Khai Báo
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>