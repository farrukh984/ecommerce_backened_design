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

    <div class="form-container-premium">
        <form method="POST" action="{{ route('admin.deals.store') }}">
            @csrf

            <div class="premium-form-grid">
                {{-- Title --}}
                <div class="form-col-full">
                    <label class="premium-label">Deal Title *</label>
                    <input type="text" name="title" value="{{ old('title') }}" required
                           class="premium-input"
                           placeholder="e.g. Summer Electronics Sale">
                    @error('title') <span class="error-text">{{ $message }}</span> @enderror
                </div>

                {{-- Description --}}
                <div class="form-col-full">
                    <label class="premium-label">Description</label>
                    <textarea name="description" rows="3"
                              class="premium-input"
                              placeholder="Brief description of this deal">{{ old('description') }}</textarea>
                    @error('description') <span class="error-text">{{ $message }}</span> @enderror
                </div>

                {{-- Discount --}}
                <div class="form-col-half">
                    <label class="premium-label">Discount Percent *</label>
                    <input type="number" name="discount_percent" value="{{ old('discount_percent') }}" min="1" max="99" required
                           class="premium-input"
                           placeholder="e.g. 25">
                    @error('discount_percent') <span class="error-text">{{ $message }}</span> @enderror
                </div>

                {{-- Product --}}
                <div class="form-col-half">
                    <label class="premium-label">Link to Product (optional)</label>
                    <select name="product_id" class="premium-input">
                        <option value="">— None —</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->name }} (${{ number_format($product->price, 2) }})
                            </option>
                        @endforeach
                    </select>
                    @error('product_id') <span class="error-text">{{ $message }}</span> @enderror
                </div>

                {{-- Start Date --}}
                <div class="form-col-half">
                    <label class="premium-label">
                        <i class="fa-solid fa-calendar-check" style="color: #10b981;"></i> Start Date & Time *
                    </label>
                    <input type="datetime-local" name="start_date" value="{{ old('start_date') }}" required
                           class="premium-input">
                    @error('start_date') <span class="error-text">{{ $message }}</span> @enderror
                </div>

                {{-- End Date --}}
                <div class="form-col-half">
                    <label class="premium-label">
                        <i class="fa-solid fa-calendar-xmark" style="color: #ef4444;"></i> End Date & Time *
                    </label>
                    <input type="datetime-local" name="end_date" value="{{ old('end_date') }}" required
                           class="premium-input">
                    @error('end_date') <span class="error-text">{{ $message }}</span> @enderror
                </div>

                {{-- Is Active --}}
                <div class="form-col-full">
                    <label class="premium-checkbox-label">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                        Activate this deal (show on homepage)
                    </label>
                </div>
            </div>

            <div class="premium-form-actions">
                <button type="submit" class="btn-primary">
                    <i class="fa-solid fa-rocket"></i> Launch Deal
                </button>
                <a href="{{ route('admin.deals.index') }}" class="btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection

@section('styles')
<style>
    .form-container-premium { padding: 32px; }
    .premium-form-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; }
    .form-col-full { grid-column: span 2; }
    .form-col-half { grid-column: span 1; }
    
    .premium-label { display: block; font-weight: 700; font-size: 13px; color: var(--admin-text, #1e293b); margin-bottom: 8px; }
    .premium-input { 
        width: 100%; padding: 12px 16px; 
        border: 2px solid var(--admin-border, #e2e8f0); 
        border-radius: 12px; font-size: 14px; 
        transition: border-color 0.2s; 
        background: var(--admin-card-alt, #f8fafc);
        color: var(--admin-text, #1e293b);
        outline: none;
    }
    .premium-input:focus { border-color: var(--admin-primary); background: var(--admin-card); }
    .error-text { color: #dc2626; font-size: 12px; display: block; margin-top: 4px; }
    
    .premium-checkbox-label { display: flex; align-items: center; gap: 12px; cursor: pointer; font-weight: 600; font-size: 14px; color: var(--admin-text, #1e293b); }
    .premium-checkbox-label input { width: 20px; height: 20px; accent-color: var(--admin-primary); }
    
    .premium-form-actions { margin-top: 32px; display: flex; gap: 16px; }
    .premium-form-actions .btn-primary, .premium-form-actions .btn-outline { padding: 14px 32px; }

    /* Dark Mode Clarity */
    [data-theme="dark"] .premium-label { color: #f1f5f9; }
    [data-theme="dark"] .premium-input { background: #1a2332; color: #f1f5f9; border-color: #334155; }
    [data-theme="dark"] .premium-checkbox-label { color: #f1f5f9; }

    @media (max-width: 991px) {
        .form-container-premium { padding: 20px; }
        .premium-form-grid { grid-template-columns: 1fr; width: 100%; }
        .form-col-full, .form-col-half { grid-column: span 1; }
    }

    @media (max-width: 600px) {
        .premium-form-actions { flex-direction: column; width: 100%; }
        .premium-form-actions button, .premium-form-actions a { width: 100%; justify-content: center; }
        .form-container-premium { padding: 15px; }
        .premium-form-grid { gap: 15px; }
    }
</style>
@endsection
