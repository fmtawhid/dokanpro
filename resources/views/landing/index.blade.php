@extends('layout.app')
@section('title', __('landing_install'))
@section('content')
    <section class="forms">
        <div class="container-fluid">
            <div class="row justify-content-center mt-5">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header custom-card-header d-flex align-items-center card-header-color">
                            <x-section-header title="landing_install" class="text-white" />
                        </div>
                        <div class="card-body">
                            <form action="{{ route('landing.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <x-fileInputGroup name="landing_zip_file" title="landing_zip_file" :required="true" />
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
