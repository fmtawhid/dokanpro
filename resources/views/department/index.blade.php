@extends('layout.app')
@section('title', __('departments'))
@section('content')
    <section>
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <x-section-header title="departments" />
                <x-create-button name="add_department" target="createDepartmentModal" />
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTable" class="table table-hover dataTable" style="width: 100%">
                        <thead class="table-bg-color">
                            <tr>
                                <th>{{ __('sl') }}</th>
                                <th>{{ __('name') }}</th>
                                <th class="not-exported">{{ __('action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($departments as $department)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $department->name }}</td>
                                    <td>
                                        <x-edit-button target="editDepartmentModal_{{ $department->id }}" />
                                        <x-delete-button route="{{ route('department.delete', $department->id) }}" />
                                        <!-- Edit Modal -->
                                        <div id="editDepartmentModal_{{ $department->id }}" tabindex="-1" role="dialog"
                                            data-backdrop="static"
                                            aria-labelledby="editDepartmentModalLabel_{{ $department->id }}"
                                            aria-hidden="true" class="modal fade text-left">
                                            <div role="document" class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <form action="{{ route('department.update', $department->id) }}"
                                                        method="post">
                                                        @csrf
                                                        @method('put')
                                                        <x-modal-header header="edit_department"
                                                            id="editDepartmentModalLabel_{{ $department->id }}" />
                                                        <div class="modal-body">
                                                            <div class="row mb-3">
                                                                <div class="col-md-12 mb-3">
                                                                    <x-inputGroup type="text" name="name"
                                                                        title="name"
                                                                        placeholder="enter_your_department_name"
                                                                        :required="true"
                                                                        value="{{ $department->name }}" />
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
    <div id="createDepartmentModal" tabindex="-1" role="dialog" data-backdrop="static"
        aria-labelledby="createDepartmentModalLabel" aria-hidden="true" class="modal fade text-left">
        <div role="document" class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('department.store') }}" method="POST">
                    @csrf
                    <x-modal-header header="new_department" id="createDepartmentModalLabel" />
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <x-inputGroup type="text" name="name" title="name"
                                    placeholder="enter_your_department_name" :required="true" value="" />
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
