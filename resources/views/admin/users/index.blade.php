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
    <div class="action-header">
        <div class="header-title">
            <h2>All Registered Users</h2>
            <p>View and manage all users who have signed up</p>
        </div>
        <div style="display: flex; gap: 10px; align-items: center;">
            <form method="GET" action="{{ route('admin.users.index') }}" style="display: flex; gap: 10px; align-items: center;">
                <input type="text" name="search" class="form-control" placeholder="Search users..."
                       value="{{ request('search') }}" style="max-width: 200px; padding: 8px 14px; font-size: 13px;">
                <select name="role" class="form-control" onchange="this.form.submit()" style="max-width: 140px; padding: 8px 14px; font-size: 13px;">
                    <option value="all">All Roles</option>
                    <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>Customer</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
                <button type="submit" class="btn-primary" style="padding: 8px 16px;">
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
                                <span style="padding: 4px 12px; border-radius: 20px; background: #f3e8ff; color: #7e22ce; font-size: 12px; font-weight: 600;">
                                    <i class="fa-solid fa-shield-halved"></i> Admin
                                </span>
                            @else
                                <span style="padding: 4px 12px; border-radius: 20px; background: #e0f2fe; color: #0369a1; font-size: 12px; font-weight: 600;">
                                    <i class="fa-solid fa-user"></i> Customer
                                </span>
                            @endif
                        </td>
                        <td>
                            <span style="background: #f1f5f9; padding: 4px 10px; border-radius: 6px; font-size: 13px; font-weight: 600;">
                                {{ $user->orders_count }}
                            </span>
                        </td>
                        <td style="color: var(--admin-text-sub);">{{ $user->created_at->format('M d, Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 40px; color: var(--admin-text-sub);">
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

@endsection
