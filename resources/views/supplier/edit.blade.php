@extends('layout.app')
@section('title', __('supplier_edit'))
@section('content')
    <section class="forms">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header custom-card-header d-flex justify-content-between card-header-color">
                            <x-section-header title="supplier_edit" class="text-white" />
                            <x-back-button route="{{ route('supplier.index') }}" />
                        </div>
                        <div class="card-body">
                            <form action="{{ route('supplier.update', $supplier->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('put')
                                <div class="row">
                                    @include('supplier.form')
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
