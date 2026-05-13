@extends('layout.app')
@section('title', __('new_employee'))
@section('content')
    <section class="forms">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header custom-card-header d-flex justify-content-between card-header-color">
                            <x-section-header title="add_employee" class="text-white" />
                            <x-back-button route="{{ route('employee.index') }}" />
                        </div>
                        <div class="card-body">
                            <form action="{{ route('employee.store') }}" method="POST">
                                @csrf
                                <div class="row">
                                    @include('employee.form')
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <x-common-button name="submit" />
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
