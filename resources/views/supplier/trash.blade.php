@extends('layout.app')
@section('title', __('supplier_trash'))
@section('content')
    <section>
        <div class="container-fluid">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <span class="list-title">{{ __('supplier_trash') }}</span>
                    <div>
                        <a href="{{ route('supplier.index') }}" class="btn common-btn"><i
                                class="fa fa-arrow-left"></i>&nbsp;&nbsp;{{ __('back') }}</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dataTable" class="table dataTable table-hover" style="width: 100%">
                            <thead class="table-bg-color">
                                <tr>
                                    <th class="not-exported">{{ __('sl') }}</th>
                                    <th>{{ __('supplier') }}</th>
                                    <th>{{ __('email') }}</th>
                                    <th>{{ __('products') }}</th>
                                    <th>{{ __('deleted_at') }}</th>
                                    <th class="not-exported">{{ __('action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($suppliers as $key => $supplier)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $supplier->name }}</td>
                                        <td>{{ $supplier->email ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge {{ $supplier->product()->count() > 0 ? 'badge-danger' : 'badge-success' }}">
                                                {{ $supplier->product()->count() }}
                                            </span>
                                        </td>
                                        <td>{{ $supplier->deleted_at?->format('Y-m-d H:i') ?? 'N/A' }}</td>
                                        <td>
                                            <a href="{{ route('supplier.restore', $supplier->id) }}"
                                                class="btn btn-sm btn-success" title="{{ __('restore') }}"><i
                                                    class="fa fa-undo"></i></a>
                                            @if ($supplier->product()->count() === 0)
                                                <a href="{{ route('supplier.forceDelete', $supplier->id) }}"
                                                    class="btn btn-sm btn-danger" onclick="return confirm('{{ __('are_you_sure') }}? This action cannot be undone.');"
                                                    title="{{ __('permanent_delete') }}"><i class="fa fa-trash"></i></a>
                                            @else
                                                <button class="btn btn-sm btn-danger" disabled title="Has {{ $supplier->product()->count() }} product(s) - Assign products to another supplier first"><i class="fa fa-trash"></i></button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">{{ __('no_data_found') }}</td>
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
