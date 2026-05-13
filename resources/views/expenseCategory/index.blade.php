@extends('layout.app')
@section('title', __('expense_categories'))
@section('content')
    <section>
        <div class="container-fluid">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <span class="list-title">{{ __('expense_categories') }}</span>
                    <button class="btn common-btn" data-toggle="modal" data-target="#createModal"><i class="fa fa-plus"></i>
                        {{ __('add_expense_category') }}</button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dataTable" class="table dataTable table-hover">
                            <thead class="table-bg-color">
                                <tr>
                                    <th class="not-exported">{{ __('sl') }}</th>
                                    <th>{{ __('code') }}</th>
                                    <th>{{ __('name') }}</th>
                                    <th class="not-exported">{{ __('action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($expenseCategories as $expenseCategory)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $expenseCategory->code }}</td>
                                        <td>{{ $expenseCategory->name }}</td>
                                        <td>
                                            <button type="button" data-toggle="modal"
                                                data-target="#editModal_{{ $expenseCategory->id }}"
                                                class="edit-btn btn btn-sm common-btn"><i class="fa fa-edit"></i></button>
                                            <a id="delete"
                                                href="{{ route('expenseCategory.destroy', $expenseCategory->id) }}"
                                                class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>

                                            <div id="editModal_{{ $expenseCategory->id }}" tabindex="-1" role="dialog"
                                                data-backdrop="static" aria-labelledby="exampleModalLabel"
                                                aria-hidden="true" class="modal fade text-left">
                                                <div role="document" class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <form
                                                            action="{{ route('expenseCategory.update', $expenseCategory->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            <div class="modal-header card-header-color">
                                                                <h5 id="exampleModalLabel" class="modal-title">
                                                                    {{ __('edit_expense_category') }}</h5>
                                                                <button type="button" data-dismiss="modal"
                                                                    aria-label="Close" class="close"><span
                                                                        aria-hidden="true"><i
                                                                            class="dripicons-cross text-white"></i></span></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    <label class="mb-2">{{ __('code') }}
                                                                        <span class="text-danger">*</span></label>
                                                                    <div class="input-group">
                                                                        <input type="text" name="code"
                                                                            value="{{ $expenseCategory->code }}"
                                                                            class="form-control"
                                                                            placeholder="{{ __('enter_expense_category_code') }}">
                                                                        @error('code')
                                                                            <span
                                                                                class="text-danger">{{ $message }}</span>
                                                                        @enderror
                                                                    </div>

                                                                </div>
                                                                <div class="form-group mt-2">
                                                                    <label class="mb-2">{{ __('name') }}
                                                                        <span class="text-danger">*</span></label>
                                                                    <input type="text" name="name"
                                                                        value="{{ $expenseCategory->name }}"
                                                                        class="form-control"
                                                                        placeholder="{{ __('enter_your_expense_category_name') }}">
                                                                    @error('name')
                                                                        <span class="text-danger">{{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">{{ __('close') }}</button>
                                                                <button type="submit"
                                                                    class="btn common-btn">{{ __('submit') }}</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div id="createModal" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="exampleModalLabel"
        aria-hidden="true" class="modal fade text-left">
        <div role="document" class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('expenseCategory.store') }}" method="POST">
                    @csrf
                    <div class="modal-header card-header-color">
                        <h5 id="exampleModalLabel" class="modal-title">{{ __('add_expense_category') }}
                        </h5>
                        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span
                                aria-hidden="true"><i class="dripicons-cross text-white"></i></span></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="mb-2">{{ __('code') }} <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" name="code" class="form-control"
                                    placeholder="{{ __('enter_expense_category_code') }}">
                                @error('code')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group mt-2">
                            <label class="mb-2">{{ __('name') }} <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control"
                                placeholder="{{ __('enter_your_expense_category_name') }}">
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">{{ __('close') }}</button>
                        <button type="submit" class="btn common-btn">{{ __('submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
