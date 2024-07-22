@props(['type', 'name', 'label', 'value', 'opsi'])

<div class="form-group mb-2">
    <label for="{{ $name }}" class="form-label">{{ $label }}</label>
    @if ($opsi == 'false')
        <small class="text-muted">(opsional)</small>
    @else
        <span class="text-danger">*</span>
    @endif
    <input type="{{ $type }}" name="{{ $name }}" id="{{ $name }}" value="{{ old($name) ?? $value }}"
        class="form-control @error($name) is-invalid @enderror">
    @error($name)
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>
