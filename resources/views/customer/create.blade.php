@extends('layout.app')
@section('title', __('new_customer'))
@section('content')
    <section class="forms">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header custom-card-header d-flex justify-content-between card-header-color">
                            <x-section-header title="add_customer" class="text-white" />
                            <x-back-button route="{{ route('customer.index') }}" />
                        </div>
                        <div class="card-body">
                            <form action="{{ route('customer.store') }}" method="post">
                                @csrf
                                <div class="row">
                                    @include('customer.form')
                                </div>
                                <div class="form-group">
                                    <x-common-button name="submit" />
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
