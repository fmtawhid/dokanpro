@extends('layout.app')
@section('title', __('add_store'))
@section('content')
    <section class="forms">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header custom-card-header d-flex justify-content-between card-header-color">
                            <x-section-header title="add_store" class="text-white" />
                            <x-back-button route="{{ route('store.index') }}" />
                        </div>
                        <div class="card-body">
                            <form action="{{ route('store.store') }}" method="POST">
                                @csrf
                                <div class="row">
                                    @include('store.form')
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
