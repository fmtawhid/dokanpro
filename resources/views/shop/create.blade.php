@extends('layout.app')
@section('title', __('new_shop'))
@section('content')
    <section class="forms">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header custom-card-header d-flex justify-content-between card-header-color">
                            <x-section-header title="new_shop" class="text-white" />
                            <x-back-button route="{{ route('shop.index') }}" />
                        </div>
                        <div class="card-body">
                            <form action="{{ route('shop.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <x-inputGroup type="text" title="shop_owner_name" name="name"
                                            placeholder="enter_your_shop_owner_name" value="" :required="true" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <x-inputGroup type="password" title="password" name="password"
                                            placeholder="enter_your_shop_owner_password" value="" :required="true" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <x-inputGroup type="email" title="shop_owner_email" name="email"
                                            placeholder="enter_your_shop_owner_email" value="" :required="true" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <x-inputGroup type="text" title="shop_name" name="shop_name"
                                            placeholder="enter_your_shop_name" value="" :required="true" />
                                    </div>
                                    <div class="col-md-4">
                                        <x-select name="shop_category_id" title="shop_category" :required="true"
                                            placeholder="select_a_option">
                                            @foreach ($shopCategories as $shopCategory)
                                                <option value="{{ $shopCategory->id }}">{{ $shopCategory->name }} </option>
                                            @endforeach
                                        </x-select>
                                    </div>
                                    <div class="col-md-4">
                                        <x-fileInputGroup name="shop_logo" title="shop_logo" :required="false" />
                                    </div>
                                    <div class="col-md-4">
                                        <x-fileInputGroup name="shop_favicon" title="shop_favicon" :required="false" />
                                    </div>
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
