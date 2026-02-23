@extends('layouts.admin')

@section('title', 'Create Deal')
@section('header_title', 'Create New Deal')

@section('admin_content')

<div class="premium-card">
    <div class="action-header">
        <div class="header-title">
            <h2>Deal Details</h2>
            <p>Fill in the deal information and set the countdown timer</p>
        </div>
        <a href="{{ route('admin.deals.index') }}" class="btn-outline">
            <i class="fa-solid fa-arrow-left"></i> Back to Deals
        </a>
    </div>

    <div style="padding: 32px;">
        <form method="POST" action="{{ route('admin.deals.store') }}">
            @csrf

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                {{-- Title --}}
                <div style="grid-column: span 2;">
                    <label style="display: block; font-weight: 700; font-size: 13px; color: #1e293b; margin-bottom: 8px;">Deal Title *</label>
                    <input type="text" name="title" value="{{ old('title') }}" required
                           style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 14px; transition: border-color 0.2s;"
                           placeholder="e.g. Summer Electronics Sale">
                    @error('title') <span style="color: #dc2626; font-size: 12px;">{{ $message }}</span> @enderror
                </div>

                {{-- Description --}}
                <div style="grid-column: span 2;">
                    <label style="display: block; font-weight: 700; font-size: 13px; color: #1e293b; margin-bottom: 8px;">Description</label>
                    <textarea name="description" rows="3"
                              style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 14px; resize: vertical;"
                              placeholder="Brief description of this deal">{{ old('description') }}</textarea>
                    @error('description') <span style="color: #dc2626; font-size: 12px;">{{ $message }}</span> @enderror
                </div>

                {{-- Discount --}}
                <div>
                    <label style="display: block; font-weight: 700; font-size: 13px; color: #1e293b; margin-bottom: 8px;">Discount Percent *</label>
                    <input type="number" name="discount_percent" value="{{ old('discount_percent') }}" min="1" max="99" required
                           style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 14px;"
                           placeholder="e.g. 25">
                    @error('discount_percent') <span style="color: #dc2626; font-size: 12px;">{{ $message }}</span> @enderror
                </div>

                {{-- Product --}}
                <div>
                    <label style="display: block; font-weight: 700; font-size: 13px; color: #1e293b; margin-bottom: 8px;">Link to Product (optional)</label>
                    <select name="product_id" style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 14px;">
                        <option value="">— None —</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->name }} (${{ number_format($product->price, 2) }})
                            </option>
                        @endforeach
                    </select>
                    @error('product_id') <span style="color: #dc2626; font-size: 12px;">{{ $message }}</span> @enderror
                </div>

                {{-- Start Date --}}
                <div>
                    <label style="display: block; font-weight: 700; font-size: 13px; color: #1e293b; margin-bottom: 8px;">
                        <i class="fa-solid fa-calendar-check" style="color: #10b981;"></i> Start Date & Time *
                    </label>
                    <input type="datetime-local" name="start_date" value="{{ old('start_date') }}" required
                           style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 14px;">
                    @error('start_date') <span style="color: #dc2626; font-size: 12px;">{{ $message }}</span> @enderror
                </div>

                {{-- End Date --}}
                <div>
                    <label style="display: block; font-weight: 700; font-size: 13px; color: #1e293b; margin-bottom: 8px;">
                        <i class="fa-solid fa-calendar-xmark" style="color: #ef4444;"></i> End Date & Time *
                    </label>
                    <input type="datetime-local" name="end_date" value="{{ old('end_date') }}" required
                           style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 14px;">
                    @error('end_date') <span style="color: #dc2626; font-size: 12px;">{{ $message }}</span> @enderror
                </div>

                {{-- Is Active --}}
                <div style="grid-column: span 2;">
                    <label style="display: flex; align-items: center; gap: 12px; cursor: pointer; font-weight: 600; font-size: 14px; color: #1e293b;">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                               style="width: 20px; height: 20px; accent-color: #3b82f6;">
                        Activate this deal (show on homepage)
                    </label>
                </div>
            </div>

            <div style="margin-top: 32px; display: flex; gap: 16px;">
                <button type="submit" class="btn-primary" style="padding: 14px 32px;">
                    <i class="fa-solid fa-rocket"></i> Launch Deal
                </button>
                <a href="{{ route('admin.deals.index') }}" class="btn-outline" style="padding: 14px 32px;">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection
