@extends('layout.app')
@section('title', __('attendance'))
@section('content')
    <section>
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <x-section-header title="attendance" />
                <x-create-button name="add_attendance" target="createAttendanceModal" />
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTable" class="table table-hover dataTable" style="width: 100%">
                        <thead class="table-bg-color">
                            <tr>
                                <th>{{ __('sl') }}</th>
                                <th>{{ __('created_by') }}</th>
                                <th>{{ __('date') }}</th>
                                <th>{{ __('employee') }}</th>
                                <th>{{ __('checkin') }}</th>
                                <th>{{ __('checkout') }}</th>
                                <th class="not-exported">{{ __('action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($attendances as $attendance)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $attendance->user->name }}</td>
                                    <td>{{ dateFormat($attendance->date) }}</td>
                                    <td>{{ $attendance->employee->user->name }}</td>
                                    <td>{{ Carbon\Carbon::parse($attendance->checkin)->format('h:i A') }}</td>
                                    <td>{{ Carbon\Carbon::parse($attendance->checkout)->format('h:i A') }}</td>
                                    <td>
                                        <x-delete-button route="{{ route('attendance.delete', $attendance->id) }}" />
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
    <div id="createAttendanceModal" tabindex="-1" role="dialog" data-backdrop="static"
        aria-labelledby="createAttendanceModalLabel" aria-hidden="true" class="modal fade text-left">
        <div role="document" class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('attendance.store') }}" method="POST">
                    @csrf
                    <x-modal-header header="new_attendance" id="createAttendanceModalLabel" />
                    <div class="modal-body">
                        <div class="row mb-2">
                            <div class="col-md-6 mb-3">
                                <x-select name="employee_id" title="employee" placeholder="select_a_option">
                                    @foreach ($employees as $employee)
                                        <option {{ old('employee_id') == $employee->id ? 'selected' : '' }}
                                            value="{{ $employee->id }}">
                                            {{ $employee->user?->name }}
                                        </option>
                                    @endforeach
                                </x-select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <x-inputGroup name="date" title="purchase_date" type="date" :required="true"
                                    value="" placeholder="" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <x-inputGroup name="checkin" title="checkin" type="time" :required="true" value=""
                                    placeholder="" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <x-inputGroup name="checkout" title="checkout" type="time" :required="true"
                                    value="" placeholder="" />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <x-modal-close-button />
                        <x-common-button name="submit" />
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
