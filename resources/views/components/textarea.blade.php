@props(['name', 'label', 'opsi'])

<div class="form-group mb-2">
    <label for="{{ $name }}" class="form-label">{{ $label }}</label>
    @if ($opsi == 'false')
        <small class="text-muted">(opsional)</small>
    @else
        <span class="text-danger">*</span>
    @endif
    <textarea class="form-control" name="{{ $name }}" id="{{ $name }}" rows="3"></textarea>
</div>
