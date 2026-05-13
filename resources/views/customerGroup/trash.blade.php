@extends('layout.app')
@section('title', __('customer_group_trash'))
@section('content')
    <section>
        <div class="container-fluid">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <span class="list-title">{{ __('customer_group_trash') }}</span>
                    <div>
                        <a href="{{ route('customer.group.index') }}" class="btn common-btn"><i
                                class="fa fa-arrow-left"></i>&nbsp;&nbsp;{{ __('back') }}</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dataTable" class="table dataTable table-hover" style="width: 100%">
                            <thead class="table-bg-color">
                                <tr>
                                    <th class="not-exported">{{ __('sl') }}</th>
                                    <th>{{ __('customer_group') }}</th>
                                    <th>{{ __('customers') }}</th>
                                    <th>{{ __('deleted_at') }}</th>
                                    <th class="not-exported">{{ __('action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($customerGroups as $key => $customerGroup)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $customerGroup->name }}</td>
                                        <td>
                                            <span class="badge {{ $customerGroup->customer()->count() > 0 ? 'badge-danger' : 'badge-success' }}">
                                                {{ $customerGroup->customer()->count() }}
                                            </span>
                                        </td>
                                        <td>{{ $customerGroup->deleted_at?->format('Y-m-d H:i') ?? 'N/A' }}</td>
                                        <td>
                                            <a href="{{ route('customer.group.restore', $customerGroup->id) }}"
                                                class="btn btn-sm btn-success" title="{{ __('restore') }}"><i
                                                    class="fa fa-undo"></i></a>
                                            @if ($customerGroup->customer()->count() === 0)
                                                <a href="{{ route('customer.group.forceDelete', $customerGroup->id) }}"
                                                    class="btn btn-sm btn-danger" onclick="return confirm('{{ __('are_you_sure') }}? This action cannot be undone.');"
                                                    title="{{ __('permanent_delete') }}"><i class="fa fa-trash"></i></a>
                                            @else
                                                <button class="btn btn-sm btn-danger" disabled title="Has {{ $customerGroup->customer()->count() }} customer(s) - Move customers to another group first"><i class="fa fa-trash"></i></button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">{{ __('no_data_found') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
