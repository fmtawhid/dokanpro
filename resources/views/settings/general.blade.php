@extends('layout.app')
@section('title', __('general_settings'))
@section('content')
    <section class="forms">
        <div class="container-fluid">
            <div class="row justify-content-center mt-5">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header custom-card-header d-flex align-items-center card-header-color">
                            <x-section-header title="general_settings" class="text-white" />
                        </div>
                        <div class="card-body">
                            <form action="{{ route('settings.general.store', $generalSettings?->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <x-inputGroup type="text" name="site_title" title="system_title"
                                            :required="true"
                                            value="{{ $generalSettings->site_title ?? old('site_title') }}"
                                            placeholder="enter_your_system_title" />
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <x-fileInputGroup name="site_logo" title="system_logo" :required="true" />
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <x-fileInputGroup name="small_logo" title="small_logo" />
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <x-fileInputGroup name="favicon" title="favicon" />
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <x-select name="currency_id" title="currency" :required="true"
                                            placeholder="select_a_option">
                                            @foreach ($currencies as $currency)
                                                <option
                                                    {{ isset($generalSettings->currency_id) && $generalSettings->currency_id == $currency->id ? 'selected' : '' }}
                                                    value="{{ $currency->id }}">{{ $currency->name }}
                                                </option>
                                            @endforeach
                                        </x-select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <x-inputGroup type="text" name="developed_by" title="developed_by"
                                            :required="false" value="{{ $generalSettings->developed_by ?? '' }}"
                                            placeholder="developed_by_description" />
                                    </div>
                                    <div
                                        class="{{ auth()->user()->hasRole('super admin') || !feature('barcodes') ? 'col-md-4' : 'col-md-2' }} mb-3">
                                        <x-select name="date_format" title="date_format" :required="true"
                                            placeholder="select_a_option">
                                            @foreach ($dateFormats as $dateFormat)
                                                <option
                                                    {{ isset($generalSettings->date_format->value) && $generalSettings->date_format->value == $dateFormat->value ? 'selected' : '' }}
                                                    value="{{ $dateFormat }}">{{ $dateFormat }}</option>
                                            @endforeach
                                        </x-select>
                                    </div>
                                    @if (feature('barcodes'))
                                        <div
                                            class="{{ auth()->user()->hasRole('super admin') ? 'col-md-4' : 'col-md-2' }} mb-3">
                                            <x-inputGroup type="text" name="barcode_digits" title="barcode_digits"
                                                :required="true" value="{{ $generalSettings->barcode_digits ?? '' }}"
                                                placeholder="enter_your_barcode_digits" />
                                        </div>
                                    @endif
                                    @role('super admin')
                                        <div class="col-md-4 mb-3">
                                            <x-inputGroup type="text" name="copyright_text"
                                                title="{{ __('copyright_text') }}" :required="false"
                                                value="{{ $generalSettings->copyright_text ?? '' }}"
                                                placeholder="{{ __('copyright_text') }}" />
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <x-inputGroup type="text" name="copyright_url" title="{{ __('copyright_url') }}"
                                                :required="false" value="{{ $generalSettings->copyright_url ?? '' }}"
                                                placeholder="{{ __('copyright_url') }}" />
                                        </div>
                                    @endrole
                                    <div class="col-md-4 mb-3">
                                        <x-select name="timezone" title="time_zone" :required="true" id="timezone"
                                            placeholder="select_a_option">
                                            @foreach ($zones as $zone)
                                                <option {{ $zone['zone'] == env('APP_TIMEZONE') ? 'selected' : '' }}
                                                    value="{{ $zone['zone'] }}">
                                                    {{ $zone['diff_from_GMT'] . ' - ' . $zone['zone'] }}</option>
                                            @endforeach
                                        </x-select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <x-inputGroup type="text" name="phone" title="{{ __('phone_number') }}"
                                            :required="false" value="{{ $generalSettings->phone ?? '' }}"
                                            placeholder="{{ __('enter_your_business_phone_number') }}" />
                                    </div>

                                    <div class="col-md-2">
                                        <x-inputGroup type="email" name="email" title="{{ __('email_address') }}"
                                            :required="false" value="{{ $generalSettings->email ?? '' }}"
                                            placeholder="{{ __('enter_your_business_email_address') }}" />
                                    </div>
                                    <div class="col-md-4">
                                        <x-inputGroup type="text" name="address" title="address" :required="false"
                                            value="{{ $generalSettings->address ?? '' }}"
                                            placeholder="enter_your_business_address" />
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-3 mb-3">
                                                <x-input-radio-group name="currency_position" label="currency_position"
                                                    :general-settings="$generalSettings" :options="['prefix', 'suffix']" :values="['Prefix', 'Suffix']"
                                                    :required="true" />
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <x-input-radio-group name="direction" label="direction" :general-settings="$generalSettings"
                                                    :options="['ltr', 'rtl']" :values="['ltr', 'rtl']" :required="true" />
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <x-input-radio-group name="dark_mode" label="theme" :general-settings="$generalSettings"
                                                    :options="['light', 'dark']" :values="[0, 1]" :required="true" />
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <x-input-radio-group name="date_with_time" label="date_with_time"
                                                    :general-settings="$generalSettings" :options="['enable', 'disable']" :values="['Enable', 'Disable']"
                                                    :required="true" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group my-3">
                                    <x-common-button name="update_and_save" />
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#timezone').select2();
        });
    </script>
@endpush
