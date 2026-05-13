<div class="form-group">
    <label class="mb-2">{{ __($label) }}
        @if ($required)
            <span class="text-danger">*</span>
        @endif
    </label>
    <div class="d-flex gap-3">
        @foreach ($options as $index => $option)
            @php($checked = isset($generalSettings->{$name}->value) && $generalSettings->{$name}->value ? $generalSettings->{$name}->value : $generalSettings->{$name})
            @php($isChecked = $checked == $values[$index])
            <label class="radio-inline">
                <input type="radio" name="{{ $name }}" value="{{ $values[$index] }}"
                    {{ $isChecked ? 'checked' : '' }}>
                {{ __($option) }}
            </label>
        @endforeach
    </div>
    @error($name)
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>
