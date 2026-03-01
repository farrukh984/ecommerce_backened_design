@extends('layouts.admin')

@section('title', 'Categories')
@section('header_title', 'Manage Categories')

@section('admin_content')

<div class="premium-card" style="max-width: 900px;">
    <div class="action-header">
        <div class="header-title">
            <h2>Category List</h2>
            <p>Group your products together</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="btn-primary">
            <i class="fa-solid fa-plus"></i> Add Category
        </a>
    </div>

    <div class="table-responsive">
        <table class="premium-table">
            <thead>
                <tr>
                    <th style="width: 80px;">ID</th>
                    <th style="width: 100px;">Image</th>
                    <th>Name</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $category)
                <tr>
                    <td>#{{ $category->id }}</td>
                    <td>
                        @if($category->background_image)
                            <img src="{{ display_image($category->background_image) }}" width="60" style="border-radius: 4px; border: 1px solid #eee;">
                        @else
                            <div style="width: 60px; height: 40px; background: #eee; border-radius: 4px; display: flex; align-items: center; justify-content: center; font-size: 10px; color: #999;">No Image</div>
                        @endif
                    </td>
                    <td style="font-weight: 600;">{{ $category->name }}</td>
                    <td style="text-align: right;">
                        <div style="display: flex; justify-content: flex-end; gap: 8px;">
                            <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn-outline" style="color: var(--admin-primary);">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-outline" style="color: #eb001b; cursor: pointer;">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
