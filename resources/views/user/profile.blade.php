@extends('layout.app')
@section('title', __('profile'))
@section('content')
    <section class="forms">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header custom-card-header card-header-color">
                            <x-section-header title="{{ __('profile') }}" class="text-white" />
                        </div>
                        <div class="card-body">
                            <form action="{{ route('profile.update', auth()->id()) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('put')
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <x-inputGroup name="name" title="full_name" type="text" :required="true"
                                            value="{{ auth()->user()->name }}" placeholder="enter_your_full_name" />
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <x-inputGroup name="email" title="email_address" type="email" :required="true"
                                            value="{{ auth()->user()->email }}" placeholder="enter_your_email_address" />
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <x-inputGroup name="phone" title="phone_number" type="text" :required="true"
                                            value="{{ auth()->user()->phone }}" placeholder="enter_your_phone_number" />
                                    </div>
                                    <div class="col-md-12">
                                        <x-fileInputGroup name="image" title="profile_image" :required="false" />
                                    </div>
                                    <div class="form-group my-3">
                                        <x-common-button name="update_and_save" />
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header custom-card-header card-header-color">
                            <x-section-header title="{{ __('change_password') }}" class="text-white" />
                        </div>
                        <div class="card-body">
                            <form action="{{ route('user.password', auth()->user()->id) }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <x-inputGroup name="current_password" title="current_password" type="password"
                                            :required="true" value="" placeholder="enter_your_current_password" />
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <x-inputGroup name="password" title="new_password" type="password" :required="true"
                                            value="" placeholder="enter_your_new_password" />
                                    </div>

                                    <div class="col-md-12">
                                        <x-inputGroup name="password_confirmation" title="confirm_password" type="password"
                                            :required="true" value="" placeholder="enter_your_confirm_password" />
                                    </div>
                                    <div class="form-group my-3">
                                        <x-common-button name="change_password" />
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
