@extends('layout.app')
@section('title', __('business_modules'))
@section('content')
    <section class="forms">
        <div class="container-fluid">
            <div class="row justify-content-center mt-5">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header custom-card-header d-flex align-items-center card-header-color">
                            <x-section-header title="business_modules" class="text-white" />
                        </div>
                        <div class="card-body">
                            <form action="{{ route('settings.business.modules.update') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    @foreach ($businessModules as $businessModule)
                                        @php
                                            $checked = in_array(
                                                $businessModule->id,
                                                $modules->pluck('id')->toArray(),
                                            )
                                                ? 'checked'
                                                : '';
                                        @endphp
                                        <div class="col-md-3">
                                            <div class="icheckbox_square-blue checked m-3">
                                                <div class="checkbox" style="font-size: 13px;">
                                                    <input type="checkbox" id="{{ $businessModule->name }}"
                                                        name="business_modules[]" style="transform: scale(1.5);"
                                                        value="{{ $businessModule->id }}" {{ $checked }} />
                                                    <label for="{{ $businessModule->name }}"
                                                        style="margin-left: 10px; font-size: 15px;">
                                                        {{ $businessModule->label }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
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
