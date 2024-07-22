@props(['name', 'label', 'opsi'])

<div class="form-group mb-2">
    <label for="{{ $name }}" class="form-label">{{ $label }}</label>
    @if ($opsi == 'false')
        <small class="text-muted">(opsional)</small>
    @else
        <span class="text-danger">*</span>
    @endif
    <input type="file" name="{{ $name }}" id="{{ $name }}"
        {{ $attributes->merge(['class' => 'form-control']) }}>
    <div class="d-flex justify-content-center align-items-center mt-2">
        <img class="img-thumbnail rounded" id="{{ $name }}Preview" width='150' alt="">
    </div>
</div>
