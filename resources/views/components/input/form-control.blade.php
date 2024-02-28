@props([
    'for' => '',
    'label' => '',
    'error' => false,
    'required' => false,
])

<div {{ $attributes ->merge(['class' => 'form-control']) }}>
    @if ($label)
        <label for="{{$for}}" class="label">
            <span class="label-text">
                {{ $label }} {{$required ? '*' : ''}}
            </span>
        </label>
    @endif
    {{ $slot }}
    <label class="label h-5">
        @if ($error)
            <span class="label-text-alt text-error relative top-1 ">
                {{$error}}
            </span>
        @endif
    </label>

</div>
