<div class="form-group">
    <label for="{{ $name }}" class="mb-2">{{ __($title) }}
        @if ($required)
            <span class="text-danger">*</span>
        @endif
    </label>
    <input type="file" name="{{ $name }}" id="{{ $name }}"
        class="form-control @error($name) is-invalid @enderror">
    @error($name)
        <small class="text-danger d-block mt-1">{{ $message }}</small>
    @enderror
</div>
