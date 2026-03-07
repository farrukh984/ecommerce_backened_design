@extends('layouts.admin')

@section('title', 'Users Management')
@section('header_title', 'Users Management')

@section('admin_content')

<!-- User Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-content">
            <h3>Total Users</h3>
            <p>{{ $stats['total'] }}</p>
        </div>
        <div class="stat-icon bg-blue">
            <i class="fa-solid fa-users"></i>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-content">
            <h3>Customers</h3>
            <p>{{ $stats['customers'] }}</p>
        </div>
        <div class="stat-icon bg-green">
            <i class="fa-solid fa-user"></i>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-content">
            <h3>Admins</h3>
            <p>{{ $stats['admins'] }}</p>
        </div>
        <div class="stat-icon bg-purple">
            <i class="fa-solid fa-user-shield"></i>
        </div>
    </div>
</div>

<!-- Users Table -->
<div class="premium-card">
    <div class="action-header responsive-header">
        <div class="header-title">
            <h2>All Registered Users</h2>
            <p>View and manage all users who have signed up</p>
        </div>
        <div class="header-actions">
            <form method="GET" action="{{ route('admin.users.index') }}" class="search-filter-form">
                <input type="text" name="search" class="form-control" placeholder="Search users..."
                       value="{{ request('search') }}">
                <select name="role" class="form-control" onchange="this.form.submit()">
                    <option value="all">All Roles</option>
                    <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>Customer</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
                <button type="submit" class="btn-primary">
                    <i class="fa-solid fa-search"></i>
                </button>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table class="premium-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Orders</th>
                    <th>Joined</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>#{{ $user->id }}</td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 12px;">
                                @if($user->profile_image)
                                    <img src="{{ display_image($user->profile_image) }}" style="width: 40px; height: 40px; border-radius: 10px; object-fit: cover; border: 2px solid var(--admin-border);">
                                @else
                                    <div style="width: 40px; height: 40px; border-radius: 10px; background: linear-gradient(135deg, var(--admin-primary), var(--admin-secondary)); color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px;">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <strong style="display: block;">{{ $user->name }}</strong>
                                </div>
                            </div>
                        </td>
                        <td style="color: var(--admin-text-sub);">{{ $user->email }}</td>
                        <td>
                            @if($user->role === 'admin')
                                <span class="role-badge role-admin">
                                    <i class="fa-solid fa-shield-halved"></i> Admin
                                </span>
                            @else
                                <span class="role-badge role-user">
                                    <i class="fa-solid fa-user"></i> Customer
                                </span>
                            @endif
                        </td>
                        <td>
                            <span class="order-count-pill">
                                {{ $user->orders_count }}
                            </span>
                        </td>
                        <td style="color: var(--admin-text-sub);">{{ $user->created_at->format('M d, Y') }}</td>
                        <td style="text-align: right;">
                            @if($user->id !== auth()->id())
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="delete-user-form" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn-delete-icon" title="Delete User" onclick="confirmDelete(this)">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            @else
                                <span style="font-size: 11px; color: var(--admin-text-sub); font-style: italic;">Current Account</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 40px; color: var(--admin-text-sub);">
                            <i class="fa-solid fa-users" style="font-size: 40px; margin-bottom: 12px; display: block; opacity: 0.3;"></i>
                            No users found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
        <div style="padding: 16px 24px; border-top: 1px solid var(--admin-border);">
            {{ $users->withQueryString()->links() }}
        </div>
    @endif
</div>

@section('scripts')
<script>
    function confirmDelete(button) {
        const form = button.closest('.delete-user-form');
        Swal.fire({
            title: 'Delete User?',
            text: "This action cannot be undone and will permanently remove this user and all their data.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Yes, delete permanently!',
            background: document.documentElement.getAttribute('data-theme') === 'dark' ? '#1e293b' : '#fff',
            color: document.documentElement.getAttribute('data-theme') === 'dark' ? '#f1f5f9' : '#1e293b'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }

    // Success/Error Alerts from Session
    document.addEventListener('DOMContentLoaded', () => {
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: "{{ session('success') }}",
                timer: 4000,
                showConfirmButton: false,
                background: document.documentElement.getAttribute('data-theme') === 'dark' ? '#1e293b' : '#fff',
                color: document.documentElement.getAttribute('data-theme') === 'dark' ? '#f1f5f9' : '#1e293b'
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: "{{ session('error') }}",
                background: document.documentElement.getAttribute('data-theme') === 'dark' ? '#1e293b' : '#fff',
                color: document.documentElement.getAttribute('data-theme') === 'dark' ? '#f1f5f9' : '#1e293b'
            });
        @endif
    });
</script>
@endsection

@section('styles')
<style>
    .responsive-header { flex-wrap: wrap; gap: 20px; }
    .search-filter-form { display: flex; gap: 10px; align-items: center; }
    .search-filter-form .form-control { max-width: 200px; padding: 8px 14px; font-size: 13px; height: 38px; }
    .search-filter-form .btn-primary { padding: 8px 16px; height: 38px; }

    @media (max-width: 768px) {
        .responsive-header { flex-direction: column; align-items: stretch; }
        .search-filter-form { flex-direction: column; }
        .search-filter-form .form-control { max-width: 100%; width: 100%; }
        .search-filter-form .btn-primary { width: 100%; justify-content: center; }
    }

    .role-badge { 
        padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; 
        display: inline-flex; align-items: center; gap: 6px;
    }
    .role-admin { background: #f3e8ff; color: #7e22ce; }
    .role-user { background: #e0f2fe; color: #0369a1; }
    
    [data-theme="dark"] .role-admin { background: rgba(168, 85, 247, 0.15); color: #c084fc; }
    [data-theme="dark"] .role-user { background: rgba(14, 165, 233, 0.15); color: #38bdf8; }

    .order-count-pill { 
        background: var(--admin-card-alt, #f1f5f9); padding: 4px 10px; border-radius: 6px; 
        font-size: 13px; font-weight: 700; color: var(--admin-text);
        border: 1px solid var(--admin-border);
    }
    
    [data-theme="dark"] .order-count-pill {
        background: #1a2332;
        color: #f1f5f9;
        border-color: #334155;
    }

    [data-theme="dark"] .premium-table tr:hover td {
        background: rgba(255, 255, 255, 0.02);
    }

    .btn-delete-icon {
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.2);
        color: #ef4444;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-delete-icon:hover {
        background: #ef4444;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    [data-theme="dark"] .btn-delete-icon {
        background: rgba(239, 68, 68, 0.15);
    }
</style>
@endsection
@endsection
