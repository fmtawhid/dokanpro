@extends('layout.app')
@section('title', __('holidays'))
@section('content')
    <section>
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <span class="list-title">{{ __('holidays') }}</span>
                <div>
                    <button type="button" class="btn common-btn" data-toggle="modal" data-target="#createModal"><i
                            class="fa fa-plus"></i>&nbsp;&nbsp;{{ __('add_holiday') }}</button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTable" class="table table-hover dataTable" style="width: 100%">
                        <thead class="table-bg-color">
                            <tr>
                                <th>{{ __('sl') }}</th>
                                <th>{{ __('created_by') }}</th>
                                <th>{{ __('reason') }}</th>
                                <th>{{ __('from') }}</th>
                                <th>{{ __('to') }}</th>
                                <th class="not-exported">{{ __('action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($holidays as $holiday)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $holiday->user->name }}</td>
                                    <td>{{ $holiday->reason }}</td>
                                    <td>{{ $holiday->from }}</td>
                                    <td>{{ $holiday->to }}</td>
                                    <td>
                                        <a data-action="{{ route('holiday.update', $holiday->id) }}"
                                            data-reason="{{ $holiday->reason }}"
                                            data-from="{{ $holiday->from }}"
                                            data-to="{{ $holiday->to }}"
                                            class="holidayEditBtn btn btn-sm common-btn text-white"><i
                                                class="fa fa-edit"></i></a>
                                        <a id="delete" href="{{ route('holiday.delete', $holiday->id) }}"
                                            class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
    <!-- Create Modal -->
    <div id="createModal" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="holidayCreateModal"
        aria-hidden="true" class="modal fade text-left">
        <div role="document" class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('holiday.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header card-header-color">
                        <span id="holidayCreateModal"
                            class="modal-title list-title text-white">{{ __('new_holiday') }}</span>
                        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span
                                aria-hidden="true"><i class="fa fa-times text-white"></i></span></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-2">
                            <div class="col-md-12 mb-3">
                                <x-input name="reason" title="{{ __('reason') }}"
                                    placeholder="{{ __('enter_your_holiday_reason') }}" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <x-input type="date" name="from" title="{{ __('from') }}"
                                    placeholder="{{ __('enter_your_holiday_from') }}" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <x-input type="date" name="to" title="{{ __('to') }}"
                                    placeholder="{{ __('enter_your_holiday_to') }}" />
                            </div>
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
    <!-- Edit Modal -->
    <div id="holidayEditModal" tabindex="-1" role="dialog" data-backdrop="static"
        aria-labelledby="holidayEditModalLabel" aria-hidden="true" class="modal fade text-left">
        <div role="document" class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="holidayEditForm" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <div class="modal-header card-header-color">
                        <span id="holidayEditModalLabel"
                            class="modal-title list-title text-white">{{ __('edit_holiday') }}</span>
                        <button type="button" data-dismiss="modal" aria-label="Close" class="close"
                            onclick="modalClose()"><span aria-hidden="true"><i
                                    class="fa fa-times text-white"></i></span></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label class="mb-2">{{ __('reason') }}<span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="holidayEditReason"
                                        placeholder="{{ __('enter_your_holiday_reason') }}"
                                        name="reason">
                                    @error('reason')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="mb-2">{{ __('from') }}<span
                                            class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="holidayEditFrom"
                                        placeholder="{{ __('enter_your_holidayfrom') }}"
                                        name="from">
                                    @error('from')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="mb-2">{{ __('to') }}<span
                                            class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="holidayEditTo"
                                        placeholder="{{ __('enter_your_holiday_to') }}"
                                        name="to">
                                    @error('to')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"
                            onclick="modalClose()">{{ __('close') }}</button>
                        <button type="submit"
                            class="btn common-btn">{{ __('update_and_save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).on('click', '.holidayEditBtn', function(e) {
            e.preventDefault();
            $('#holidayEditModal').modal('show');
            const action = $(this).attr('data-action');
            const reason = $(this).attr('data-reason');
            const from = $(this).attr('data-from');
            const to = $(this).attr('data-to');

            $('#holidayEditForm').attr('action', action)
            $('#holidayEditReason').val(reason)
            $('#holidayEditFrom').val(from)
            $('#holidayEditTo').val(to)
        });

        function modalClose() {
            $('#holidayEditModal').modal('hide');
        }
        $(document).on('click', '.holidayDeleteBtn', function(e) {
            const route = $(this).attr('data-action');
            confirmDelete(route)
        });
    </script>
@endpush
