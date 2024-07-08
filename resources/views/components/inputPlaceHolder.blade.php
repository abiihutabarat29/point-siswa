@props(['type', 'name', 'label', 'placeholder'])

<div class="form-group mb-2">
    <label for="{{ $name }}" class="form-label">{{ $label }}</label>
    <input type="{{ $type }}" name="{{ $name }}" id="{{ $name }}" placeholder="{{ $placeholder }}"
        {{ $attributes->merge(['class' => 'form-control']) }}>
</div>
