@extends('layout.app')
@section('title', __('smtp_configure'))
@section('content')
    <section class="forms">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header custom-card-header d-flex justify-content-between card-header-color">
                            <x-section-header title="smtp_configure" class="text-white" />
                        </div>
                        <div class="card-body">
                            <form action="{{ route('settings.mail.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <x-inputGroup type="text" name="host" title="mail_host" :required="true"
                                            placeholder="mail_host" value="{{ env('MAIL_HOST') }}" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <x-inputGroup type="text" name="email_from" title="mail_address"
                                            :required="true" placeholder="mail_address"
                                            value="{{ env('MAIL_FROM_ADDRESS') }}" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <x-inputGroup type="text" name="port" title="mail_port" :required="true"
                                            placeholder="mail_port" value="{{ env('MAIL_PORT') }}" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <x-inputGroup type="text" name="password" title="mail_password" :required="true"
                                            placeholder="mail_password" value="{{ env('MAIL_PASSWORD') }}" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <x-inputGroup type="text" name="encryption" title="mail_encryption"
                                            :required="true" placeholder="mail_encryption"
                                            value="{{ env('MAIL_ENCRYPTION') }}" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <x-inputGroup type="text" name="username" title="mail_username" :required="true"
                                            placeholder="mail_username" value="{{ env('MAIL_USERNAME') }}" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <x-inputGroup type="text" name="from_name" title="mail_from_name"
                                            :required="true" placeholder="mail_from_name"
                                            value="{{ env('MAIL_FROM_NAME') }}" />
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <x-common-button name="update_and_save" />
                                        </div>
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
