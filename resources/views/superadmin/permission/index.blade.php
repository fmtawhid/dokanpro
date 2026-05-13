@extends('layout.app')
@section('title', __('supplier_edit'))
@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h3>{{ __('Permission Management') }}</h3>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('superadmin.permissions.create') }}" class="btn btn-primary">
                <i class="fa-solid fa-plus"></i> {{ __('Create Permission') }}
            </a>
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
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('Permission Name') }}</th>
                            <th>{{ __('Roles Count') }}</th>
                            <th>{{ __('Created At') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($permissions as $permission)
                            <tr>
                                <td>
                                    <code>{{ $permission->name }}</code>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $permission->roles()->count() }}</span>
                                </td>
                                <td>{{ $permission->created_at->format('d M Y') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('superadmin.permissions.edit', $permission) }}" class="btn btn-sm btn-warning" title="{{ __('Edit') }}">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>
                                        <form action="{{ route('superadmin.permissions.delete', $permission) }}" method="POST" style="display:inline;" onsubmit="return confirm('{{ __('Are you sure?') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="{{ __('Delete') }}">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">{{ __('No permissions found') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $permissions->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection
