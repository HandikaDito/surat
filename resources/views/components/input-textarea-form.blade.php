<div class="mb-3">

    <label for="{{ $name }}" class="form-label">
        {{ $label }}
    </label>

    <textarea
        id="{{ $name }}"
        name="{{ $name }}"
        rows="{{ $rows ?? 3 }}"
        class="form-control @error($name) is-invalid @enderror"
    >{{ old($name, $value ?? '') }}</textarea>

    @error($name)
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror

</div>