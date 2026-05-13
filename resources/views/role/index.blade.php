@extends('layout.app')
@section('title', __('roles'))
@section('content')
    <section>
        <div class="container-fluid">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <x-section-header title="roles" />
                    <x-create-button name="add_role" target="createRoleModal" />
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dataTable" class="table dataTable table-hover">
                            <thead class="table-bg-color">
                                <tr>
                                    <th class="not-exported">{{ __('sl') }}</th>
                                    <th>{{ __('name') }}</th>
                                    <th>{{ __('description') }}</th>
                                    <th class="not-exported">{{ __('action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($roles as $role)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ ucfirst($role->name) }}</td>
                                        <td style="max-width: 400px; text-align:justify">
                                            {{ $role->description ?? __('no_description_available_yet') }}</td>
                                        <td>
                                            <x-edit-button target="editRoleModal_{{ $role->id }}" />
                                            <a href="{{ route('role.permission', $role->id) }}" class="btn btn-sm print-btn"
                                                title="Permission">
                                                <i class="fa fa-key"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <div id="editRoleModal_{{ $role->id }}" data-backdrop="static" tabindex="-1"
                                        role="dialog" aria-labelledby="editRoleModalLabel_{{ $role->id }}"
                                        aria-hidden="true" class="modal fade text-left">
                                        <div role="document" class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <form action="{{ route('role.update', $role->id) }}" method="POST">
                                                    @csrf
                                                    @method('put')
                                                    <x-modal-header header="edit_role_description"
                                                        id="editRoleModalLabel_{{ $role->id }}" />
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <x-textarea-group name="description" title="description"
                                                                    placeholder="enter_your_role_description"
                                                                    :required="true" value="{{ $role->description }}" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <x-modal-close-button />
                                                        <x-common-button name="update_and_save" />
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
    <div id="createRoleModal" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="createRoleModalLabel"
        aria-hidden="true" class="modal fade text-left">
        <div role="document" class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('role.store') }}" method="POST">
                    @csrf
                    <x-modal-header header="new_role" id="createRoleModalLabel" />
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <x-inputGroup type="text" name="name" title="name" value="" :required="true"
                                    placeholder="enter_your_role_name" />
                            </div>
                            <div class="col-md-12">
                                <x-textarea-group type="text" name="description" title="description"
                                    value="{{ old('description') }}" :required="false"
                                    placeholder="enter_your_role_description" />
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
