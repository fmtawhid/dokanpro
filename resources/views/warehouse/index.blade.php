@extends('layout.app')
@section('title', __('warehouses'))
@section('content')
    <section>
        <div class="container-fluid">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <span class="list-title">{{ __('warehouses') }}</span>
                    <div>
                        <a href="{{ route('warehouse.trash') }}" class="btn btn-warning"><i
                                class="fa fa-trash"></i>&nbsp;&nbsp;{{ __('trash') }}</a>
                        <button data-toggle="modal" data-target="#createModal" class="btn common-btn"><i class="fa fa-plus"></i>
                            {{ __('add_warehouse') }}</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dataTable" class="table dataTable table-hover" style="width: 100%">
                            <thead class="table-bg-color">
                                <tr>
                                    <th class="not-exported">{{ __('sl') }}</th>
                                    <th>{{ __('warehouse') }}</th>
                                    <th>{{ __('phone_number') }}</th>
                                    <th>{{ __('email') }}</th>
                                    <th>{{ __('address') }}</th>
                                    <th>{{ __('number_of_product') }}</th>
                                    <th class="not-exported">{{ __('action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($warehouses as $key => $warehouse)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $warehouse->name }}</td>
                                        <td>{{ $warehouse->phone }}</td>
                                        <td>{{ $warehouse->email ?? 'N/A' }}</td>
                                        <td>{{ $warehouse->address }}</td>
                                        <td>{{ $warehouse->purchases->sum('total_qty') }}</td>
                                        <td>
                                            <a data-toggle="modal" data-target="#editeModal"
                                                data-action="{{ route('warehouse.update', $warehouse->id) }}"
                                                data-name="{{ $warehouse->name }}" data-phone="{{ $warehouse->phone }}"
                                                data-email="{{ $warehouse->email }}"
                                                data-address="{{ $warehouse->address }}" href="#"
                                                class="btn btn-sm common-btn edit-btn"><i class="fa fa-edit"></i></a>

                                            <a id="delete" href="{{ route('warehouse.delete', $warehouse->id) }}"
                                                class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
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
        <div role="document" class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form action="{{ route('warehouse.store') }}" method="POST">
                    @csrf
                    <div class="modal-header card-header-color">
                        <span id="exampleModalLabel" class="modal-title list-title text-white">{{ __('new_warehouse') }}</span>
                        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span
                                aria-hidden="true"><i class="fa fa-times text-white"></i></span></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="mb-2">{{ __('name') }} <span class="text-danger">*</span></label>
                                    <input type="text" placeholder="{{ __('enter_your_wareHouse_name') }}" name="name"
                                        class="form-control">
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="mb-2">{{ __('phone_number') }} <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="phone" class="form-control"
                                        placeholder="{{ __('enter_your_wareHouse_phone_number') }}">
                                    @error('phone')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="mb-2">{{ __('email_address') }}</label>
                                    <input type="email" name="email" placeholder="{{ __('enter_your_wareHouse_email_address') }}"
                                        class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="mb-2">{{ __('address') }} <span class="text-danger">*</span></label>
                                    <textarea class="form-control" rows="3" name="address" placeholder="{{ __('enter_your_warehouse_address') }}"></textarea>
                                    @error('address')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('close') }}</button>
                        <button type="submit" class="btn common-btn">{{ __('submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="editeModal" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="warehouseModalLabel"
        aria-hidden="true" class="modal fade text-left">
        <div role="document" class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form id="warehouseUpdate" method="post">
                    @csrf

                    <div class="modal-header card-header-color">
                        <h5 id="warehouseModalLabel" class="modal-title list-title text-white">{{ __('edit_warehouse') }}</h5>
                        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span
                                aria-hidden="true"><i class="fa fa-times text-white"></i></span></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="mb-2">{{ __('name') }} <span class="text-danger">*</span></label>
                                    <input type="text" placeholder="{{ __('enter_your_wareHouse_name') }}" name="name"
                                        class="form-control" id="name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="mb-2">{{ __('phone_number') }} <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="phone" class="form-control" id="phone"
                                        placeholder="{{ __('enter_your_wareHouse_phone_number') }}">
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="mb-2">{{ __('email_address') }}</label>
                                    <input type="email" name="email" class="form-control" id="email"
                                        placeholder="{{ __('enter_your_wareHouse_email_address') }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="mb-2">{{ __('address') }} <span class="text-danger">*</span></label>
                                    <textarea class="form-control" rows="3" name="address" id="address"
                                        placeholder="{{ __('enter_your_warehouse_address') }}"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('close') }}</button>
                        <button type="submit" class="btn common-btn">{{ __('update_and_save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).on('click', '.edit-btn', function() {
            const action = $(this).attr('data-action');
            const name = $(this).attr('data-name');
            const phone = $(this).attr('data-phone');
            const email = $(this).attr('data-email');
            const address = $(this).attr('data-address');
            $('#warehouseUpdate').attr('action', action);
            $('#name').val(name);
            $('#phone').val(phone);
            $('#email').val(email);
            $('#address').val(address);
        });
    </script>
@endpush
