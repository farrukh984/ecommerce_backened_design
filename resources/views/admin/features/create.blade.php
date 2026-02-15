@extends('layouts.admin')

@section('title', 'Add Feature')
@section('header_title', 'Create Feature')

@section('admin_content')

<div class="premium-card" style="max-width: 600px;">
    <div class="action-header">
        <div class="header-title">
            <h2>New Feature</h2>
            <p>Define a reusable product characteristic</p>
        </div>
    </div>

    <form action="{{ route('admin.features.store') }}" method="POST" class="form-container">
        @csrf
        <div class="form-group">
            <label>Feature Name</label>
            <input type="text" name="name" class="form-control" placeholder="e.g. Water Resistant, 5G Support..." required>
        </div>

        <div style="margin-top: 20px; display: flex; gap: 12px;">
            <button type="submit" class="btn-primary">
                <i class="fa-solid fa-save"></i> Save Feature
            </button>
            <a href="{{ route('admin.features.index') }}" class="btn-outline">Cancel</a>
        </div>
    </form>
</div>

@endsection
