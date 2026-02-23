@extends('layouts.admin')

@section('title', 'Manage Deals')
@section('header_title', 'Deals & Countdown Management')

@section('admin_content')

@if(session('success'))
<div style="background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; padding: 16px 24px; border-radius: 14px; margin-bottom: 24px; font-weight: 600; display: flex; align-items: center; gap: 10px;">
    <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
</div>
@endif

<div class="premium-card">
    <div class="action-header">
        <div class="header-title">
            <h2>All Deals</h2>
            <p>Manage your deals and set countdown timers</p>
        </div>
        <a href="{{ route('admin.deals.create') }}" class="btn-primary">
            <i class="fa-solid fa-plus-circle"></i> Create New Deal
        </a>
    </div>
    <div class="table-responsive">
        <table class="premium-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Discount</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Status</th>
                    <th>Product</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($deals as $deal)
                @php
                    $isExpired = $deal->end_date && $deal->end_date->isPast();
                    $isRunning = $deal->is_active && !$isExpired && $deal->start_date && $deal->start_date->isPast();
                @endphp
                <tr>
                    <td style="font-weight: 800; color: var(--admin-primary);">#{{ $deal->id }}</td>
                    <td style="font-weight: 700;">{{ $deal->title }}</td>
                    <td>
                        <span style="background: #fee2e2; color: #dc2626; padding: 4px 12px; border-radius: 20px; font-weight: 800; font-size: 12px;">
                            -{{ $deal->discount_percent }}%
                        </span>
                    </td>
                    <td style="font-size: 13px; color: var(--admin-text-sub);">{{ $deal->start_date?->format('M d, Y H:i') ?? '—' }}</td>
                    <td style="font-size: 13px; color: var(--admin-text-sub);">{{ $deal->end_date?->format('M d, Y H:i') ?? '—' }}</td>
                    <td>
                        @if($isExpired)
                            <span style="background: #f1f5f9; color: #64748b; padding: 5px 14px; border-radius: 20px; font-size: 11px; font-weight: 800;">EXPIRED</span>
                        @elseif($isRunning)
                            <span style="background: #dcfce7; color: #166534; padding: 5px 14px; border-radius: 20px; font-size: 11px; font-weight: 800;">🟢 LIVE</span>
                        @elseif($deal->is_active)
                            <span style="background: #fef3c7; color: #92400e; padding: 5px 14px; border-radius: 20px; font-size: 11px; font-weight: 800;">⏳ SCHEDULED</span>
                        @else
                            <span style="background: #fee2e2; color: #991b1b; padding: 5px 14px; border-radius: 20px; font-size: 11px; font-weight: 800;">INACTIVE</span>
                        @endif
                    </td>
                    <td style="font-size: 13px;">{{ $deal->product?->name ?? '—' }}</td>
                    <td>
                        <div style="display: flex; gap: 8px;">
                            <a href="{{ route('admin.deals.edit', $deal->id) }}" class="btn-outline" style="padding: 6px 14px; font-size: 12px;">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.deals.destroy', $deal->id) }}" onsubmit="return confirm('Delete this deal?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-outline" style="padding: 6px 14px; font-size: 12px; color: #dc2626; border-color: #fecaca;">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 40px; color: var(--admin-text-sub);">
                        <i class="fa-solid fa-tag" style="font-size: 40px; opacity: 0.3; display: block; margin-bottom: 12px;"></i>
                        No deals created yet. Create your first deal to start the countdown!
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($deals->hasPages())
<div style="margin-top: 20px;">
    {{ $deals->links() }}
</div>
@endif

@endsection
