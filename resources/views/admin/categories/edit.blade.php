@extends('layouts.admin')

@section('title', 'Edit Category')
@section('header_title', 'Update Category')

@section('admin_content')

<div class="premium-card" style="max-width: 600px;">
    <div class="action-header">
        <div class="header-title">
            <h2>Modify Category</h2>
            <p>Rename category: {{ $category->name }}</p>
        </div>
    </div>

    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" class="form-container">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label>Category Name</label>
            <input type="text" name="name" class="form-control" value="{{ $category->name }}" required>
        </div>

        <div style="margin-top: 20px; display: flex; gap: 12px;">
            <button type="submit" class="btn-primary">
                <i class="fa-solid fa-sync"></i> Update Category
            </button>
            <a href="{{ route('admin.categories.index') }}" class="btn-outline">Cancel</a>
        </div>
    </form>
</div>

@endsection
