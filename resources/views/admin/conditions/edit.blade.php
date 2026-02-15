@extends('layouts.admin')

@section('title', 'Edit Condition')
@section('header_title', 'Update Condition')

@section('admin_content')

<div class="premium-card" style="max-width: 600px;">
    <div class="action-header">
        <div class="header-title">
            <h2>Modify Condition</h2>
            <p>Update condition: {{ $condition->name }}</p>
        </div>
    </div>

    <form action="{{ route('admin.conditions.update', $condition->id) }}" method="POST" class="form-container">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label>Condition Name</label>
            <input type="text" name="name" class="form-control" value="{{ $condition->name }}" required>
        </div>

        <div style="margin-top: 20px; display: flex; gap: 12px;">
            <button type="submit" class="btn-primary">
                <i class="fa-solid fa-sync"></i> Update Condition
            </button>
            <a href="{{ route('admin.conditions.index') }}" class="btn-outline">Cancel</a>
        </div>
    </form>
</div>

@endsection
