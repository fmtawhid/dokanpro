@extends('layout.app')
@section('title', __('permissions'))
@section('content')
    <style>
        .table-hover>tbody>tr:hover {
            border-bottom: none;
            background: none !important;
        }
    </style>
    <section class="forms">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header custom-card-header d-flex justify-content-between card-header-color">
                            <x-section-header title="permissions" class="text-white" />
                            <x-back-button route="{{ route('role.index') }}" />
                        </div>
                        <form action="{{ route('role.set.permission', $role->id) }}" method="POST">
                            @csrf
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered permission-table table-hover">
                                        <thead>
                                            <tr>
                                                <th colspan="8" class="text-center">
                                                    <div class="checkbox">
                                                        <input type="checkbox" id="select_all" style="margin-right: 5px">
                                                        <label for="select_all">{{ ucfirst($role->name) }}
                                                            {{ __('permissions') }}</label>
                                                    </div>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $count = 0;
                                                $skipPermissions = [
                                                    'root',
                                                    'signout',
                                                    'dashboard',
                                                    'subscription.index',
                                                    'subscription.store',
                                                    'subscription.status.chanage',
                                                    'subscription.update',
                                                    'payment-gateway.index',
                                                    'payment-gateway.update',
                                                    'settings.mail',
                                                    'settings.mail.store',
                                                    'language.index',
                                                    'language.create',
                                                    'language.store',
                                                    'language.edit',
                                                    'language.update',
                                                    'language.destroy',
                                                    'shop.category.index',
                                                    'shop.category.store',
                                                    'shop.category.update',
                                                    'shop.category.status.chanage',
                                                    'shop.index',
                                                    'shop.create',
                                                    'shop.store',
                                                    'shop.update',
                                                    'shop.status.chanage',
                                                    'shop.delete',
                                                ];
                                            @endphp
                                            @foreach ($permissions as $permission)
                                                @if (
                                                    !in_array($permission->name, $skipPermissions) &&
                                                        !str_ends_with($permission->name, '.store') &&
                                                        !str_ends_with($permission->name, '.update'))
                                                    @if ($count % 8 == 0)
                                                        @if ($count > 0)
                                                            </tr>
                                                        @endif
                                                        <tr>
                                                    @endif
                                                    @php
                                                        $withoutIndex = str_replace('.index', 's', $permission->name);
                                                    @endphp
                                                    <td>
                                                        <div class="icheckbox_square-blue checked" aria-checked="false"
                                                            aria-disabled="false">
                                                            <div class="checkbox">
                                                                <input type="checkbox" id="{{ $permission->name }}"
                                                                    {{ in_array($permission->name, $rolePermissions) ? 'checked' : '' }}
                                                                    name="permission[{{ $permission->name }}]" />
                                                                <label for="{{ $permission->name }}"
                                                                    style="margin-left: 5px">{{ str_replace('.', ' ', ucwords($withoutIndex, '.')) }}</label>
                                                            </div>
                                                        </div>
                                                    </td>

                                                    @php $count++ @endphp
                                                @endif
                                            @endforeach
                                            @if ($count > 0)
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                                <div class="form-group">
                                    <x-common-button name="update_and_save" />
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script>
        $('#select_all').on('click', function() {
            if ($(this).is(':not(:checked)')) {
                $('input[type="checkbox"]').attr('checked', false);
            } else {
                $('input[type="checkbox"]').attr('checked', true);
            }
        });
    </script>
@endpush
