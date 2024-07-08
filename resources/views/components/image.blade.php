@props(['name', 'label'])

<div class="form-group mb-2">
    <label for="{{ $name }}" class="form-label">{{ $label }}</label>
    <input type="file" name="{{ $name }}" id="{{ $name }}"
        {{ $attributes->merge(['class' => 'form-control']) }}>
    <div class="d-flex justify-content-center align-items-center mt-2">
        <img class="img-thumbnail rounded" id="{{ $name }}Preview" width='150' alt="">
    </div>
</div>
