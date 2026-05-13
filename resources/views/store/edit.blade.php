@extends('layout.app')
@section('title', __('edit_store'))
@section('content')
    <section class="forms">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between card-header-color">
                            <x-section-header title="edit_store" class="text-white" />
                            <x-back-button route="{{ route('store.index') }}" />
                        </div>
                        <div class="card-body">
                            <form action="{{ route('store.update', $store->id) }}" method="POST">
                                @csrf
                                @method('put')
                                <div class="row">
                                    @include('store.form')
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
