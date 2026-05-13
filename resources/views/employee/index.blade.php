@extends('layout.app')
@section('title', __('employees'))
@section('content')
    <section>
        <div class="container-fluid">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <x-section-header title="employees" />
                    @can('employee.create')
                        <a href="{{ route('employee.create') }}" class="btn common-btn">
                            <i class="fa fa-plus"></i>
                            {{ __('add_employee') }}
                        </a>
                    @endcan
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dataTable" class="table dataTable table-hover">
                            <thead class="table-bg-color">
                                <tr>
                                    <th class="not-exported">{{ __('sl') }}</th>
                                    <th>{{ __('name') }}</th>
                                    <th>{{ __('email') }}</th>
                                    <th>{{ __('phone_number') }}</th>
                                    <th>{{ __('role') }}</th>
                                    <th>{{ __('department') }}</th>
                                    <th>{{ __('country') }}</th>
                                    <th>{{ __('city') }}</th>
                                    <th>{{ __('address') }}</th>
                                    <th>{{ __('staff_id') }}</th>
                                    <th class="not-exported">{{ __('action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($staffs as $staff)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $staff->user->name ?? 'N/A' }}</td>
                                        <td>{{ $staff->user->email ?? 'N/A' }}</td>
                                        <td>{{ $staff->user->phone ?? 'N/A' }}</td>
                                        <td>{{ ucfirst($staff->user->roles[0]->name ?? 'N/A') }}</td>
                                        <td>{{ $staff->department->name ?? 'N/A' }}</td>
                                        <td>{{ $staff->country ?? 'N/A' }}</td>
                                        <td>{{ $staff->city ?? 'N/A' }}</td>
                                        <td>{{ $staff->address ?? 'N/A' }}</td>
                                        <td>{{ $staff->staff_id ?? 'N/A' }}</td>
                                        <td>
                                            <a href="{{ route('employee.edit', $staff->id) }}"
                                                class="btn btn-sm common-btn">
                                                <i class="fa fa-edit"></i>
                                            </a>
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
@endsection
