@extends('layouts.admin')

@section('title', 'Edit Brand')
@section('header_title', 'Update Brand')

@section('admin_content')

<div class="premium-card" style="max-width: 600px;">
    <div class="action-header">
        <div class="header-title">
            <h2>Modify Brand</h2>
            <p>Update name for: {{ $brand->name }}</p>
        </div>
    </div>

    <form action="{{ route('admin.brands.update', $brand->id) }}" method="POST" class="form-container">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label>Brand Name</label>
            <input type="text" name="name" class="form-control" value="{{ $brand->name }}" required>
        </div>

        <div style="margin-top: 20px; display: flex; gap: 12px;">
            <button type="submit" class="btn-primary">
                <i class="fa-solid fa-sync"></i> Update Brand
            </button>
            <a href="{{ route('admin.brands.index') }}" class="btn-outline">Cancel</a>
        </div>
    </form>
</div>

@endsection
