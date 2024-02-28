@props([
  'for' => '',
  'error' => false,
])

@php
    $key = $key ?? md5($attributes->wire('model'));
    $style = 'select select-primary w-full max-w-xs';
@endphp

    <select
        {{ $attributes->whereDoesntStartWith('wire:key')->merge(['class' => $style]) }}
        wire:key="{{ $key }}"
        >
        {{ $slot }}
    </select>

