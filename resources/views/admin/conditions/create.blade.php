@extends('layouts.admin')

@section('title', 'Add Condition')
@section('header_title', 'Create Condition')

@section('admin_content')

<div class="premium-card" style="max-width: 600px;">
    <div class="action-header">
        <div class="header-title">
            <h2>New Condition</h2>
            <p>Define a new item state</p>
        </div>
    </div>

    <form action="{{ route('admin.conditions.store') }}" method="POST" class="form-container">
        @csrf
        <div class="form-group">
            <label>Condition Name</label>
            <input type="text" name="name" class="form-control" placeholder="e.g. Brand New, Slightly Used..." required>
        </div>

        <div style="margin-top: 20px; display: flex; gap: 12px;">
            <button type="submit" class="btn-primary">
                <i class="fa-solid fa-save"></i> Save Condition
            </button>
            <a href="{{ route('admin.conditions.index') }}" class="btn-outline">Cancel</a>
        </div>
    </form>
</div>

@endsection
