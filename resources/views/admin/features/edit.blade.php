@extends('layouts.admin')

@section('title', 'Edit Feature')
@section('header_title', 'Update Feature')

@section('admin_content')

<div class="premium-card" style="max-width: 600px;">
    <div class="action-header">
        <div class="header-title">
            <h2>Modify Feature</h2>
            <p>Update feature: {{ $feature->name }}</p>
        </div>
    </div>

    <form action="{{ route('admin.features.update', $feature->id) }}" method="POST" class="form-container">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label>Feature Name</label>
            <input type="text" name="name" class="form-control" value="{{ $feature->name }}" required>
        </div>

        <div style="margin-top: 20px; display: flex; gap: 12px;">
            <button type="submit" class="btn-primary">
                <i class="fa-solid fa-sync"></i> Update Feature
            </button>
            <a href="{{ route('admin.features.index') }}" class="btn-outline">Cancel</a>
        </div>
    </form>
</div>

@endsection
