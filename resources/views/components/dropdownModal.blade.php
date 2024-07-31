@props(['name', 'label', 'opsi'])

<div class="form-group mb-2">
    <label for="{{ $name }}" class="form-label">{{ $label }}</label>
    @if ($opsi == 'false')
        <small class="text-muted">(opsional)</small>
    @else
        <span class="text-danger">*</span>
    @endif
    <select id="{{ $name }}" name="{{ $name }}" class="form-control select2Modal" style="width: 100%;">
        <option selected disabled>::Pilih {{ $label }}::</option>
        {{ $slot }}
    </select>
    @error($name)
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>
