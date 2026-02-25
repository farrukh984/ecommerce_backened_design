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

    @if(session('success'))
        <div style="padding: 12px 24px;">
            <div style="background: #dcfce7; color: #166534; padding: 12px 16px; border-radius: 8px; font-size: 14px; font-weight: 500;">
                <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
            </div>
        </div>
    @endif

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
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="width: 32px; height: 32px; border-radius: 50%; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 12px;">
                                    {{ strtoupper(substr($review->user->name ?? 'U', 0, 1)) }}
                                </div>
                                <span style="font-weight: 600;">{{ $review->user->name ?? 'Unknown' }}</span>
                            </div>
                        </td>
                        <td>
                            <a href="{{ route('products.show', $review->product_id) }}" target="_blank" style="color: var(--admin-primary); text-decoration: none;">
                                {{ Str::limit($review->product->name ?? 'N/A', 30) }}
                            </a>
                        </td>
                        <td>
                            <div style="color: #ff9017; font-size: 12px;">
                                @for($i=1; $i<=5; $i++)
                                    <i class="fa-{{ $i <= $review->rating ? 'solid' : 'regular' }} fa-star"></i>
                                @endfor
                            </div>
                        </td>
                        <td>
                            <div title="{{ $review->comment }}" style="max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                {{ $review->comment }}
                            </div>
                        </td>
                        <td>
                            @if($review->is_approved)
                                <span style="background: #dcfce7; color: #166534; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700;">Visible</span>
                            @else
                                <span style="background: #fff1e7; color: #f38332; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700;">Pending</span>
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
                                <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}" onsubmit="return confirm('Delete this review?')">
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
</style>
@endsection
