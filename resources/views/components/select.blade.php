<div class="form-group">
    <label for="{{ $id }}" class="mb-2">{{ __($title) }} @if ($required)
            <span class="text-danger">*</span>
        @endif
    </label>
    <select class="form-control select2bs4 @error($name) is-invalid @enderror" name="{{ $name }}"
        id="{{ $id }}">
        <option selected disabled>{{ __($placeholder) }}</option>
        {{ $slot }}
    </select>
    @error($name)
        <span class="text-danger d-block mt-1">{{ $message }}</span>
    @enderror
</div>


