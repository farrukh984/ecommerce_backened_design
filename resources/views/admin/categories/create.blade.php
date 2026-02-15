@extends('layouts.admin')

@section('title', 'Add Category')
@section('header_title', 'Create Category')

@section('admin_content')

<div class="premium-card" style="max-width: 600px;">
    <div class="action-header">
        <div class="header-title">
            <h2>New Category</h2>
            <p>Enter the category name</p>
        </div>
    </div>

    <form action="{{ route('admin.categories.store') }}" method="POST" class="form-container">
        @csrf
        <div class="form-group">
            <label>Category Name</label>
            <input type="text" name="name" class="form-control" placeholder="e.g. Home Appliances" required>
        </div>

        <div style="margin-top: 20px; display: flex; gap: 12px;">
            <button type="submit" class="btn-primary">
                <i class="fa-solid fa-save"></i> Save Category
            </button>
            <a href="{{ route('admin.categories.index') }}" class="btn-outline">Cancel</a>
        </div>
    </form>
</div>

@endsection
