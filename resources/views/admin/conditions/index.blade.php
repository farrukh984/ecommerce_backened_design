@extends('layouts.admin')

@section('title', 'Product Conditions')
@section('header_title', 'Manage Conditions')

@section('admin_content')

<div class="premium-card" style="max-width: 900px;">
    <div class="action-header">
        <div class="header-title">
            <h2>Condition List</h2>
            <p>Define product states (New, Used, etc.)</p>
        </div>
        <a href="{{ route('admin.conditions.create') }}" class="btn-primary">
            <i class="fa-solid fa-plus"></i> Add Condition
        </a>
    </div>

    <div class="table-responsive">
        <table class="premium-table">
            <thead>
                <tr>
                    <th style="width: 80px;">ID</th>
                    <th>Name</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($conditions as $condition)
                <tr>
                    <td>#{{ $condition->id }}</td>
                    <td style="font-weight: 600;">{{ $condition->name }}</td>
                    <td style="text-align: right;">
                        <div style="display: flex; justify-content: flex-end; gap: 8px;">
                            <a href="{{ route('admin.conditions.edit', $condition->id) }}" class="btn-outline" style="color: var(--admin-primary);">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <form action="{{ route('admin.conditions.destroy', $condition->id) }}" method="POST">
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
