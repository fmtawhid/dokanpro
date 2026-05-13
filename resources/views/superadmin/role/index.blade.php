@extends('layout.app')
@section('title', __('supplier_edit'))
@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h3>{{ __('Role Management') }}</h3>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('superadmin.roles.create') }}" class="btn btn-primary">
                <i class="fa-solid fa-plus"></i> {{ __('Create Role') }}
            </a>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ $message }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($message = Session::get('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>{{ $message }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('Role Name') }}</th>
                            <th>{{ __('Permissions Count') }}</th>
                            <th>{{ __('Created At') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($roles as $role)
                            <tr>
                                <td>
                                    <strong>{{ ucfirst($role->name) }}</strong>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $role->permissions_count ?? $role->permissions()->count() }}</span>
                                </td>
                                <td>{{ $role->created_at->format('d M Y') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('superadmin.role.permissions', $role) }}" class="btn btn-sm btn-info" title="{{ __('Manage Permissions') }}">
                                            <i class="fa-solid fa-lock"></i>
                                        </a>
                                        <a href="{{ route('superadmin.roles.edit', $role) }}" class="btn btn-sm btn-warning" title="{{ __('Edit') }}">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>
                                        @if (!in_array($role->name, ['super admin', 'admin', 'store', 'customer']))
                                            <form action="{{ route('superadmin.roles.destroy', $role) }}" method="POST" style="display:inline;" onsubmit="return confirm('{{ __('Are you sure?') }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="{{ __('Delete') }}">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">{{ __('No roles found') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $roles->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection
