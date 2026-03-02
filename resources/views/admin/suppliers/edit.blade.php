@extends('layouts.admin')

@section('title', 'Edit Supplier')
@section('header_title', 'Update Supplier')

@section('admin_content')

<div class="premium-card" style="max-width: 600px;">
    <div class="action-header">
        <div class="header-title">
            <h2>Edit #{{ $supplier->id }}</h2>
            <p>Modify supplier details</p>
        </div>
    </div>

    <form action="{{ route('admin.suppliers.update', $supplier->id) }}" method="POST" class="form-container">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label>Supplier Name *</label>
            <div class="input-with-icon">
                <i class="fa-solid fa-building"></i>
                <input type="text" name="name" class="form-control" value="{{ $supplier->name }}" required>
            </div>
            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="form-group mt-4">
            <label>Location (City, Country)</label>
            <div class="input-with-icon">
                <i class="fa-solid fa-location-dot"></i>
                <input type="text" name="location" class="form-control" value="{{ $supplier->location }}">
            </div>
        </div>

        <div class="form-group mt-4">
            <label>Country Flag URL (Icon/Link)</label>
            <div class="input-with-icon">
                <i class="fa-solid fa-image"></i>
                <input type="text" name="country_flag" class="form-control" value="{{ $supplier->country_flag }}">
            </div>
            @if($supplier->country_flag)
                <div class="mt-2">
                    <img src="{{ $supplier->country_flag }}" alt="Current Flag" style="height: 24px; border-radius: 2px;">
                </div>
            @endif
        </div>

        <div class="form-group mt-4">
            <label class="checkbox-container" style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                <input type="checkbox" name="is_verified" value="1" {{ $supplier->is_verified ? 'checked' : '' }} style="width: 18px; height: 18px;">
                <span style="font-weight: 500;">Is Verified Supplier?</span>
            </label>
        </div>

        <div class="form-group mt-2">
            <label class="checkbox-container" style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                <input type="checkbox" name="has_worldwide_shipping" value="1" {{ $supplier->has_worldwide_shipping ? 'checked' : '' }} style="width: 18px; height: 18px;">
                <span style="font-weight: 500;">Has Worldwide Shipping?</span>
            </label>
        </div>

        <div style="margin-top: 30px; display: flex; gap: 12px;">
            <button type="submit" class="btn-primary">
                <i class="fa-solid fa-save"></i> Update Supplier
            </button>
            <a href="{{ route('admin.suppliers.index') }}" class="btn-outline">Cancel</a>
        </div>
    </form>
</div>

@endsection
