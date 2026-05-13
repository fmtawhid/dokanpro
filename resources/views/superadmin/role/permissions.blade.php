@extends('layout.app')
@section('title', __('supplier_edit'))
@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h3>{{ __('Manage Permissions for') }}: <strong>{{ ucfirst($role->name) }}</strong></h3>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ $message }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('superadmin.role.permissions.update', $role) }}" method="POST">
                @csrf

                <div class="row">
                    @foreach ($allPermissions as $module => $permissions)
                        <div class="col-md-4 mb-4">
                            <div class="card border">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0 text-capitalize font-weight-bold">{{ $module }}</h6>
                                </div>
                                <div class="card-body">
                                    @foreach ($permissions as $permission)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="permissions[]" 
                                                   id="permission_{{ $permission->id }}" 
                                                   value="{{ $permission->id }}"
                                                   @checked(in_array($permission->id, $rolePermissions))>
                                            <label class="form-check-label" for="permission_{{ $permission->id }}">
                                                {{ str_replace($module . '.', '', $permission->name) }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-success">
                        <i class="fa-solid fa-save"></i> {{ __('Update Permissions') }}
                    </button>
                    <a href="{{ route('superadmin.roles.index') }}" class="btn btn-secondary">
                        {{ __('Back') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
