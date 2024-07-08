@props(['name', 'label', 'icon'])

<div class="row mb-3">
    <label class="col-sm-2 col-form-label" for="{{ $name }}">{{ $label }}</label>
    <div class="col-sm-10">
        <div class="input-group input-group-merge">
            <span class="input-group-text"><i class="bx {{ $icon }}"></i></span>
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
    </div>
</div>
