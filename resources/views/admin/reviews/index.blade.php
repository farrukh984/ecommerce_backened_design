@extends('layouts.admin')

@section('title', 'Product Reviews')
@section('header_title', 'Product Reviews')

@section('admin_content')

<div class="premium-card">
    <div class="action-header">
        <div class="header-title">
            <h2>User Reviews</h2>
            <p>Moderate and manage product reviews</p>
        </div>
    </div>

    <div class="table-responsive">
        <table class="premium-table">
            <thead>
                <tr>
                    <th>Review ID</th>
                    <th>User</th>
                    <th>Product</th>
                    <th>Rating</th>
                    <th>Comment</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reviews as $review)
                    <tr>
                        <td><strong>#{{ $review->id }}</strong></td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 12px;">
                                @if($review->user && $review->user->profile_image)
                                    <img src="{{ display_image($review->user->profile_image) }}" class="user-avatar-circle">
                                @else
                                    <div class="user-initial-circle">
                                        {{ strtoupper(substr($review->user->name ?? 'U', 0, 1)) }}
                                    </div>
                                @endif
                                <span style="font-weight: 600; color: var(--admin-text);">{{ $review->user->name ?? 'Unknown' }}</span>
                            </div>
                        </td>
                        <td>
                            <a href="{{ route('products.show', $review->product_id) }}" target="_blank" style="color: var(--admin-primary); text-decoration: none; font-weight: 500;">
                                {{ Str::limit($review->product->name ?? 'N/A', 30) }}
                            </a>
                        </td>
                        <td>
                            <div style="color: #ffb800; font-size: 13px;">
                                @for($i=1; $i<=5; $i++)
                                    <i class="fa-{{ $i <= $review->rating ? 'solid' : 'regular' }} fa-star"></i>
                                @endfor
                            </div>
                        </td>
                        <td>
                            <div title="{{ $review->comment }}" style="max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; color: var(--admin-text); opacity: 0.9;">
                                {{ $review->comment }}
                            </div>
                        </td>
                        <td>
                            @if($review->is_approved)
                                <span class="status-pill status-visible">Visible</span>
                            @else
                                <span class="status-pill status-pending">Pending</span>
                            @endif
                        </td>
                        <td>{{ $review->created_at->format('M d, Y') }}</td>
                        <td>
                            <div style="display: flex; gap: 8px;">
                                @if(!$review->is_approved)
                                    <form method="POST" action="{{ route('admin.reviews.approve', $review) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn-primary" style="padding: 6px 12px; font-size: 11px;">Approve</button>
                                    </form>
                                @endif
                                <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}" onsubmit="confirmAction(event, {title: 'Delete Review?'})">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background: #fee2e2; color: #991b1b; border: none; padding: 6px 12px; border-radius: 8px; font-size: 11px; cursor: pointer;">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 40px;">No reviews found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="padding: 20px;">
        {{ $reviews->links() }}
    </div>
</div>

@endsection

@section('styles')
<style>
    .premium-table td {
        white-space: nowrap;
    }
    
    .status-pill {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .status-visible {
        background: #dcfce7;
        color: #166534;
    }
    
    .status-pending {
        background: #fff1e7;
        color: #f38332;
    }
    
    [data-theme="dark"] .status-visible {
        background: rgba(34, 197, 94, 0.15);
        color: #4ade80;
    }
    
    [data-theme="dark"] .status-pending {
        background: rgba(243, 131, 50, 0.15);
        color: #fb923c;
    }
    
    [data-theme="dark"] .premium-table tr:hover td {
        background: rgba(255, 255, 255, 0.02);
    }

    .user-avatar-circle {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid var(--admin-border);
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }

    .user-initial-circle {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--admin-primary), var(--admin-secondary));
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 14px;
        border: 2px solid var(--admin-border);
    }
</style>
@endsection
