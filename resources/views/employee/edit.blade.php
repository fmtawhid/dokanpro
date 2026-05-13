@extends('layout.app')
@section('title', __('employee_edit'))
@section('content')
    <section class="forms">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header custom-card-header d-flex justify-content-between card-header-color">
                            <x-section-header title="employee_edit" class="text-white" />
                            <x-back-button route="{{ route('employee.index') }}" />
                        </div>
                        <div class="card-body">
                            <form action="{{ route('employee.update', $employee->id) }}" method="post">
                                @csrf
                                @method('put')
                                <div class="row">
                                    @include('employee.form')
                                    <div class="col-md-12">
                                        <div class="form-group mt-3">
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
