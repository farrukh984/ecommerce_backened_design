@extends('layouts.admin')

@section('title', 'Product Features')
@section('header_title', 'Manage Features')

@section('admin_content')

<div class="premium-card" style="max-width: 900px;">
    <div class="action-header">
        <div class="header-title">
            <h2>Feature List</h2>
            <p>Characteristics that can be attached to products</p>
        </div>
        <a href="{{ route('admin.features.create') }}" class="btn-primary">
            <i class="fa-solid fa-plus"></i> Add Feature
        </a>
    </div>

    <div class="table-responsive">
        <table class="premium-table">
            <thead>
                <tr>
                    <th style="width: 80px;">ID</th>
                    <th>Feature Name</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($features as $feature)
                <tr>
                    <td>#{{ $feature->id }}</td>
                    <td style="font-weight: 600;">{{ $feature->name }}</td>
                    <td style="text-align: right;">
                        <div style="display: flex; justify-content: flex-end; gap: 8px;">
                            <a href="{{ route('admin.features.edit', $feature->id) }}" class="btn-outline" style="color: var(--admin-primary);">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <form action="{{ route('admin.features.destroy', $feature->id) }}" method="POST">
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
