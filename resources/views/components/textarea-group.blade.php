<div class="form-group">
    <label class="mb-2" for="{{ $name }}">{{ __($title) }} @if ($required)
            <span class="text-danger">*</span>
        @endif
    </label>
    <textarea type="text" name="{{ $name }}" placeholder="{{ __($placeholder) }}" id="{{ $name }}"
        class="form-control @error($name) is-invalid @enderror" rows="3">{{ old($name) ?? $value }}</textarea>
    @error($name)
        <span class="text-danger d-block mt-1">{{ $message }}</span>
    @enderror
</div>
