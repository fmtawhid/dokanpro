@extends('layout.app')
@section('title', __('add_language'))
@section('content')
    <div class="container-fluid my-4">
        <div class="row">
            <div class="col-lg-7 mt-2 mx-auto ">
                <form action="{{ route('language.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card border-0 shadow-sm">
                        <div class="card-header">
                            <h3 class="m-0">{{ __('create_new_language') }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-4">
                                    <x-input type="text" name="title"
                                        placeholder="{{ __('enter_your_language_title') }}" title="{{ __('title') }}"
                                        value="" />
                                    @error('title')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-lg-4">
                                    <label class="mb-2">{{ __('short_name') }}
                                        <small>({{ __('only_allow_english_characters') }})</small></label>
                                    <input name="name" oninput="this.value=this.value.replace(/[^a-z]/gi,'')"
                                        class="form-control" placeholder="{{ __('example') }}: bn" autocomplete="off"
                                        required />
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-lg-4">
                                    <label for="" class="mb-2">{{ __('Flag') }}</label>
                                    <input type="file" name="image" class="form-control mb-2" />
                                    @error('image')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-between flex-wrap gap-2 ">
                            <a href="{{ route('language.index') }}" class="btn btn-danger">{{ __('back') }}</a>
                            <button type="submit" class="btn common-btn">{{ __('submit') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
