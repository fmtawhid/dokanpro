@extends('layout.app')
@section('title', __('categories_trash'))
@section('content')
    <section>
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <span class="list-title">{{ __('categories_trash') }}</span>
                <div>
                    <a href="{{ route('category.index') }}" class="btn common-btn"><i
                            class="fa fa-arrow-left"></i>&nbsp;&nbsp;{{ __('back') }}</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTable" class="table table-hover dataTable" style="width: 100%">
                        <thead class="table-bg-color">
                            <tr>
                                <th>{{ __('sl') }}</th>
                                <th>{{ __('image') }}</th>
                                <th>{{ __('name') }}</th>
                                <th>{{ __('parent') }}</th>
                                <th>{{ __('deleted_at') }}</th>
                                <th class="not-exported">{{ __('action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($categories as $category)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><img src="{{ $category->thumbnail->file ?? asset('defualt/defualt.jpg') }}"
                                            height="30" width="30"></td>
                                    <td>{{ $category->name }}</td>
                                    <td>{{ $category->parentCategory->name ?? 'N/A' }}</td>
                                    <td>{{ $category->deleted_at?->format('Y-m-d H:i') ?? 'N/A' }}</td>
                                    <td>
                                        <a href="{{ route('category.restore', $category->id) }}"
                                            class="btn btn-sm btn-success" title="{{ __('restore') }}"><i
                                                class="fa fa-undo"></i></a>
                                        <a href="{{ route('category.forceDelete', $category->id) }}"
                                            class="btn btn-sm btn-danger" onclick="return confirm('{{ __('are_you_sure') }}? This action cannot be undone.');"
                                            title="{{ __('permanent_delete') }}"><i class="fa fa-trash"></i></a>
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
    </section>
@endsection
