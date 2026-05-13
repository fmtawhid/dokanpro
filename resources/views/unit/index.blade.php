@extends('layout.app')
@section('title', __('units'))
@section('content')
    <section>
        <div class="container-fluid">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <span class="list-title">{{ __('units') }}</span>
                    <button href="#" data-toggle="modal" data-target="#createModal" class="btn common-btn"><i
                            class="fa fa-plus"></i>&nbsp;&nbsp;{{ __('add_unit') }}</button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dataTable" class="table dataTable table-hover" style="width: 100%">
                            <thead class="table-bg-color">
                                <tr>
                                    <th class="not-exported">{{ __('sl') }}</th>
                                    <th>{{ __('code') }}</th>
                                    <th>{{ __('name') }}</th>
                                    <th>{{ __('base_unit') }}</th>
                                    <th>{{ __('operator') }}</th>
                                    <th>{{ __('operation_value') }}</th>
                                    <th class="not-exported">{{ __('action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($units as $unit)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $unit->code }}</td>
                                        <td>{{ $unit->name }}</td>
                                        <td>{{ $unit->baseUnit->name ?? 'N/A' }}</td>
                                        <td>{{ $unit->operator ?? 'N/A' }}</td>
                                        <td>{{ $unit->operation_value ?? 'N/A' }}</td>
                                        <td class="">
                                            <a data-toggle="modal" data-target="#editeModal_{{ $unit->id }}"
                                                href="#" class="btn btn-sm common-btn"><i class="fa fa-edit"></i></a>

                                            <a id="delete" href="{{ route('unit.delete', $unit->id) }}"
                                                class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    <div id="editeModal_{{ $unit->id }}" tabindex="-1" role="dialog"
                                        data-backdrop="static" aria-labelledby="editModalLabel" aria-hidden="true"
                                        class="modal fade text-left">
                                        <div role="document" class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <form action="{{ route('unit.update', $unit->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-header card-header-color">
                                                        <span id="editModalLabel" class="modal-title list-title text-white">
                                                            {{ __('edit_unit') }}</span>
                                                        <button type="button" data-dismiss="modal" aria-label="Close"
                                                            class="close"><span aria-hidden="true"><i
                                                                    class="fa fa-times text-white"></i></span></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label class="mb-2">{{ __('name') }}
                                                                        <span class="text-danger">*</span></label>
                                                                    <input type="text" name="name"
                                                                        class="form-control mb-2"
                                                                        value="{{ $unit->name }}"
                                                                        placeholder="{{ __('enter_your_unit_name') }}">
                                                                    @error('name')
                                                                        <span class="text text-danger" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                </div>
                                                            </div>

                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label class="mb-2">{{ __('code') }}
                                                                        <span class="text-danger">*</span></label>
                                                                    <input type="text" name="code"
                                                                        class="form-control mb-2"
                                                                        value="{{ $unit->code }}"
                                                                        placeholder="{{ __('enter_your_unit_code') }}">
                                                                    @error('code')
                                                                        <span class="text text-danger" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label class="mb-2">{{ __('base_unit') }}</label>
                                                                    <select class="form-control mb-2" name="base_unit_id">
                                                                        <option selected disabled>{{ __('select_a_option') }}
                                                                        </option>
                                                                        @if ($units->isNotEmpty())
                                                                            @foreach ($units as $uti)
                                                                                <option
                                                                                    {{ isset($unit->baseUnit->id) && $unit->baseUnit->id == $uti->id ? 'selected' : '' }}
                                                                                    value="{{ $uti->id }}">
                                                                                    {{ $uti->name }}</option>
                                                                            @endforeach
                                                                        @endif
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row mt-3">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label class="mb-2">{{ __('operator') }}</label>
                                                                    <input type="text" name="operator"
                                                                        placeholder="{{ __('enter_your_operator_name') }}"
                                                                        class="form-control mb-2"
                                                                        value="{{ $unit->operator }}" />
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label
                                                                        class="mb-2">{{ __('operation_value') }}</label><input
                                                                        type="number" name="operation_value"
                                                                        placeholder="{{ __('enter_your_operation_value') }}"
                                                                        class="form-control"
                                                                        value="{{ $unit->operation_value }}" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit"
                                                            class="btn common-btn">{{ __('update_and_save') }}</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Modal -->
    <div id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true"
        data-backdrop="static" class="modal fade text-left">
        <div role="document" class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('unit.store') }}" method="POST">
                    @csrf
                    <div class="modal-header card-header-color">
                        <span id="createModalLabel" class="modal-title list-title text-white">{{ __('new_unit') }}</span>
                        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span
                                aria-hidden="true"><i class="fa fa-times text-white"></i></span></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="mb-2">{{ __('name') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control mb-2"
                                        placeholder="{{ __('enter_your_unit_name') }}">
                                    @error('name')
                                        <span class="text text-danger" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="mb-2">{{ __('code') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="code" class="form-control mb-2"
                                        placeholder="{{ __('enter_your_unit_code') }}">
                                    @error('code')
                                        <span class="text text-danger" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="mb-2">{{ __('base_unit') }}</label>
                                    <select class="form-control mb-2" name="base_unit_id">
                                        <option selected disabled>{{ __('select_a_option') }}</option>
                                        @if ($units->isNotEmpty())
                                            @foreach ($units as $unit)
                                                <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <div class="form-group operator">
                                    <label class="mb-2">{{ __('operator') }}</label>
                                    <input type="text" name="operator"
                                        placeholder="{{ __('enter_your_operator_name') }}" class="form-control mb-2" />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group operation_value">
                                    <label class="mb-2">{{ __('operation_value') }}</label>
                                    <input type="number" name="operation_value"
                                        placeholder="{{ __('enter_your_operation_value') }}" class="form-control mb-2" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn common-btn">{{ __('submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
