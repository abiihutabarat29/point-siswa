@props(['name', 'label'])
<div class="form-group mb-2">
    <label for="{{ $name }}" class="form-label">{{ $label }}</label>
    <select id="{{ $name }}" name="{{ $name }}"
        class="select2 form-select @error($name) is-invalid @enderror">
        <option selected disabled>::Pilih {{ $label }}::</option>
        {{ $slot }}
    </select>
    @error($name)
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>
