@extends('layout.app')
@section('title', __('add_supplier'))
@section('content')
    <section class="forms">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header custom-card-header d-flex justify-content-between card-header-color">
                            <x-section-header title="add_supplier" class="text-white" />
                            <x-back-button route="{{ route('supplier.index') }}" />
                        </div>
                        <div class="card-body">
                            <form action="{{ route('supplier.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    @include('supplier.form')
                                    <div class="col-md-12">
                                        <div class="form-group mt-3">
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
