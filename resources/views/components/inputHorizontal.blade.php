@props(['name', 'label', 'icon', 'value'])

<div class="row mb-3">
    <label class="col-sm-2 col-form-label" for="{{ $name }}">{{ $label }}</label>
    <div class="col-sm-10">
        <div class="input-group input-group-merge has-validation">
            <span class="input-group-text"><i class="bx {{ $icon }}"></i></span>
            <input type="text" class="form-control  @error($name) is-invalid @enderror" name="{{ $name }}"
                id="{{ $name }}" value="{{ old($name) ?? $value }}" />
            @error($name)
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
</div>
