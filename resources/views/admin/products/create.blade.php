@extends('layouts.admin')

@section('title', 'Add New Product')
@section('header_title', 'Create Product')

@section('admin_content')

<div class="premium-card">
    <div class="action-header">
        <div class="header-title">
            <h2>New Product Details</h2>
            <p>Fill in the information to list a new item</p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="btn-outline">
            <i class="fa-solid fa-arrow-left"></i> Back to List
        </a>
    </div>

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="form-container">
        @csrf
        
        <div class="form-group">
            <label>Product Name</label>
            <input type="text" name="name" class="form-control" placeholder="e.g. Samsung Galaxy S23" required>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Category</label>
                <select name="category_id" class="form-control" required>
                    <option value="">Select Category</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Brand</label>
                <select name="brand_id" class="form-control" required>
                    <option value="">Select Brand</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Current Price ($)</label>
                <input type="number" step="0.01" name="price" class="form-control" placeholder="0.00" required>
            </div>
            <div class="form-group">
                <label>Old Price ($) - Optional</label>
                <input type="number" step="0.01" name="old_price" class="form-control" placeholder="0.00">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Condition</label>
                <select name="condition_id" class="form-control" required>
                    @foreach($conditions as $cond)
                        <option value="{{ $cond->id }}">{{ $cond->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Rating (1-5)</label>
                <input type="number" name="rating" min="1" max="5" class="form-control" value="5">
            </div>
        </div>

        <div class="form-group">
            <label>Features</label>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 10px; background: #fcfcfc; padding: 15px; border-radius: 8px; border: 1px solid var(--admin-border);">
                @foreach($features as $feat)
                <label style="font-size: 13px; display: flex; align-items: center; gap: 8px; font-weight: 500;">
                    <input type="checkbox" name="features[]" value="{{ $feat->id }}"> {{ $feat->name }}
                </label>
                @endforeach
            </div>
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="4" placeholder="Briefly describe the product..."></textarea>
        </div>

        <div class="form-group">
            <label>Product Image</label>
            <div style="display: flex; align-items: center; gap: 20px;">
                <input type="file" name="image" class="form-control" style="flex: 1;" accept="image/*" onchange="previewImage(this)">
                <div class="image-preview-box" id="imagePreview">
                    <i class="fa-solid fa-image" style="font-size: 32px; color: var(--admin-text-sub);"></i>
                </div>
            </div>
        </div>

        <div style="margin-top: 40px; display: flex; gap: 12px;">
            <button type="submit" class="btn-primary" style="padding: 12px 32px;">
                <i class="fa-solid fa-save"></i> Save Product
            </button>
            <a href="{{ route('admin.products.index') }}" class="btn-outline" style="padding: 12px 32px;">
                Cancel
            </a>
        </div>
    </form>
</div>

@endsection

@section('scripts')
<script>
    function previewImage(input) {
        const preview = document.getElementById('imagePreview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = `<img src="${e.target.result}">`;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
