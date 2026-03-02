@extends('layouts.admin')

@section('title', 'Add Supplier')
@section('header_title', 'Create Supplier')

@section('admin_content')

<div class="premium-card" style="max-width: 600px;">
    <div class="action-header">
        <div class="header-title">
            <h2>New Supplier</h2>
            <p>Define a new product supplier</p>
        </div>
    </div>

    <form action="{{ route('admin.suppliers.store') }}" method="POST" class="form-container">
        @csrf
        <div class="form-group">
            <label>Supplier Name *</label>
            <div class="input-with-icon">
                <i class="fa-solid fa-building"></i>
                <input type="text" name="name" class="form-control" placeholder="e.g. Acme Corp, Global Source..." required>
            </div>
            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="form-group mt-4">
            <label>Location (City, Country)</label>
            <div class="input-with-icon">
                <i class="fa-solid fa-location-dot"></i>
                <input type="text" name="location" class="form-control" placeholder="e.g. Shenzhen, China">
            </div>
        </div>

        <div class="form-group mt-4">
            <label>Country Flag URL (Icon/Link)</label>
            <div class="input-with-icon">
                <i class="fa-solid fa-image"></i>
                <input type="text" name="country_flag" class="form-control" placeholder="e.g. https://flagcdn.com/w20/cn.png">
            </div>
            <small class="text-muted">You can use FlagCDN links or any image URL.</small>
        </div>

        <div class="form-group mt-4">
            <label class="checkbox-container" style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                <input type="checkbox" name="is_verified" value="1" checked style="width: 18px; height: 18px;">
                <span style="font-weight: 500;">Is Verified Supplier?</span>
            </label>
        </div>

        <div class="form-group mt-2">
            <label class="checkbox-container" style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                <input type="checkbox" name="has_worldwide_shipping" value="1" style="width: 18px; height: 18px;">
                <span style="font-weight: 500;">Has Worldwide Shipping?</span>
            </label>
        </div>

        <div style="margin-top: 30px; display: flex; gap: 12px;">
            <button type="submit" class="btn-primary">
                <i class="fa-solid fa-save"></i> Save Supplier
            </button>
            <a href="{{ route('admin.suppliers.index') }}" class="btn-outline">Cancel</a>
        </div>
    </form>
</div>

@endsection
