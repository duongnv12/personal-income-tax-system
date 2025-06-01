@props(['disabled' => false, 'id', 'name', 'value'])

<textarea
    {{ $disabled ? 'disabled' : '' }}
    {!! $attributes->merge([
        'class' => 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm',
        'id' => $id,
        'name' => $name
    ]) !!}
>{{ $value }}</textarea>