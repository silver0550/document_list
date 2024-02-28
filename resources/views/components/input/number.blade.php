@props([
    'key' => null,
    'error' => false,
    'readonly' => false,
    'placeholder' => '',
])

@php
    $key = $key ?? md5($attributes->wire('model'));

    $style = 'input input-bordered input-primary w-full ';
    if ($error) {
        $style .= ' input-error';
    }
@endphp

<input
    type="number"
    placeholder="{{ $placeholder }}"
    @disabled($readonly)
    {{ $attributes->whereDoesntStartWith('wire:key')->merge(['class' => $style]) }}
    wire:key="{{ $key }}"
/>
