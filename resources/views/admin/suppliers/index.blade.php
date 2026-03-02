@extends('layouts.admin')

@section('title', 'Suppliers')
@section('header_title', 'Manage Suppliers')

@section('admin_content')

<div class="premium-card" style="max-width: 1000px;">
    <div class="action-header">
        <div class="header-title">
            <h2>Supplier List</h2>
            <p>Managing product sources and partners</p>
        </div>
        <a href="{{ route('admin.suppliers.create') }}" class="btn-primary">
            <i class="fa-solid fa-plus"></i> Add Supplier
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success mt-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="table-responsive">
        <table class="premium-table">
            <thead>
                <tr>
                    <th style="width: 80px;">ID</th>
                    <th>Name</th>
                    <th>Location</th>
                    <th>Verified</th>
                    <th>Shipping</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($suppliers as $supplier)
                <tr>
                    <td>#{{ $supplier->id }}</td>
                    <td style="font-weight: 600;">{{ $supplier->name }}</td>
                    <td>
                        @if($supplier->country_flag)
                            <img src="{{ $supplier->country_flag }}" alt="flag" style="width: 20px; vertical-align: middle; margin-right: 5px;">
                        @endif
                        {{ $supplier->location ?? 'N/A' }}
                    </td>
                    <td>
                        @if($supplier->is_verified)
                            <span class="badge" style="background: rgba(39, 174, 96, 0.1); color: #27ae60;">
                                <i class="fa-solid fa-check-circle"></i> Yes
                            </span>
                        @else
                            <span class="badge" style="background: rgba(149, 165, 166, 0.1); color: #7f8c8d;">
                                <i class="fa-solid fa-circle-xmark"></i> No
                            </span>
                        @endif
                    </td>
                    <td>
                        @if($supplier->has_worldwide_shipping)
                            <span class="badge" style="background: rgba(52, 152, 219, 0.1); color: #3498db;">
                                <i class="fa-solid fa-globe"></i> Worldwide
                            </span>
                        @else
                            <span class="badge" style="background: rgba(230, 126, 34, 0.1); color: #e67e22;">
                                <i class="fa-solid fa-truck"></i> Local
                            </span>
                        @endif
                    </td>
                    <td style="text-align: right;">
                        <div style="display: flex; justify-content: flex-end; gap: 8px;">
                            <a href="{{ route('admin.suppliers.edit', $supplier->id) }}" class="btn-outline" style="color: var(--admin-primary);">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <form action="{{ route('admin.suppliers.destroy', $supplier->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this supplier?')">
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
