@extends('layout.app')
@section('title', __('stores'))
@section('content')
    <section>
        <div class="container-fluid">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <x-section-header title="stores" />
                    <div>
                        <a href="{{ route('store.create') }}" class="btn common-btn"><i
                                class="fa fa-plus"></i>{{ __('add_store') }}</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dataTable" class="table purchase-list dataTable table-hover" style="width: 100%">
                            <thead class="table-bg-color">
                                <tr>
                                    <th class="not-exported">{{ __('sl') }}</th>
                                    <th>{{ __('name') }}</th>
                                    <th>{{ __('store_manager') }}</th>
                                    <th>{{ __('email') }}</th>
                                    <th>{{ __('phone_number') }}</th>
                                    <th class="not-exported">{{ __('action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($stores as $store)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $store->name }}</td>
                                        <td>{{ $store->user->name }}</td>
                                        <td>{{ $store->email }}</td>
                                        <td>{{ $store->phone_number ?? 'N/A' }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <a class="btn common-btn py-0 px-1" href="#" role="button"
                                                    id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                    <i class="fa fa-ellipsis-h"></i>
                                                </a>
                                                <div class="dropdown-menu" aria-labelledby="">
                                                    <a href="{{ route('store.edit', $store->id) }}"
                                                        class="dropdown-item"><i
                                                            class="fa fa-edit text-info"></i>&nbsp;&nbsp;
                                                        {{ __('edit') }}</a>
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
@endsection
