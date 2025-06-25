@props(['disabled' => false])

@php
    $isPassword = ($attributes['type'] ?? null) === 'password';
    $isTaxParam = ($attributes['name'] ?? $attributes['id'] ?? null) === 'param_value';
@endphp

<div class="relative"
    @if($isPassword)
        x-data="{ show: false }"
    @elseif($isTaxParam)
        x-data="{ formatted: ($el.value ? Number($el.value).toLocaleString('en-US') : ''), raw: ($el.value ? $el.value.replace(/[^\d]/g, '') : '') }"
    @endif
>
    <input
        @disabled($disabled)
        @if($isPassword)
            :type="show ? 'text' : 'password'"
        @endif
        @if($isTaxParam)
            x-ref="input"
            x-bind:value="formatted"
            x-on:input="
                let rawVal = $event.target.value.replace(/[^\d]/g, '');
                formatted = rawVal ? Number(rawVal).toLocaleString('en-US') : '';
                raw = rawVal;
            "
            inputmode="numeric"
            pattern="[0-9,]*"
            autocomplete="off"
        @endif
        {{ $attributes->merge(['class' => 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full pr-10']) }}
    >
    @if($isTaxParam)
        <input type="hidden" name="param_value" x-bind:value="raw">
    @endif
    @if($isPassword)
        <button type="button"
            tabindex="-1"
            class="absolute inset-y-0 right-0 flex items-center px-2 text-gray-500 focus:outline-none"
            @click="show = !show"
        >
            <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
            <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.956 9.956 0 012.293-3.95m3.36-2.676A9.956 9.956 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.973 9.973 0 01-4.043 5.306M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
        </button>
    @endif
</div>
