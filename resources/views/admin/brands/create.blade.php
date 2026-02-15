@extends('layouts.admin')

@section('title', 'Add Brand')
@section('header_title', 'Create Brand')

@section('admin_content')

<div class="premium-card" style="max-width: 600px;">
    <div class="action-header">
        <div class="header-title">
            <h2>New Brand</h2>
            <p>Define a new brand name</p>
        </div>
    </div>

    <form action="{{ route('admin.brands.store') }}" method="POST" class="form-container">
        @csrf
        <div class="form-group">
            <label>Brand Name</label>
            <input type="text" name="name" class="form-control" placeholder="e.g. Sony, Nike, LG..." required>
        </div>

        <div style="margin-top: 20px; display: flex; gap: 12px;">
            <button type="submit" class="btn-primary">
                <i class="fa-solid fa-save"></i> Save Brand
            </button>
            <a href="{{ route('admin.brands.index') }}" class="btn-outline">Cancel</a>
        </div>
    </form>
</div>

@endsection
