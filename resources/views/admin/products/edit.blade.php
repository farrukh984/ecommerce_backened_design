@extends('layouts.admin')

@section('title', 'Edit Product')
@section('header_title', 'Update Product')

@section('admin_content')

<div class="premium-card">
    <div class="action-header">
        <div class="header-title">
            <h2>Modify Product: {{ $product->name }}</h2>
            <p>Update the information for this item</p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="btn-outline">
            <i class="fa-solid fa-arrow-left"></i> Back to List
        </a>
    </div>

    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="form-container">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label>Product Name</label>
            <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Category</label>
                <select name="category_id" class="form-control" required>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Brand</label>
                @php $brands = \App\Models\Brand::all(); @endphp
                <select name="brand_id" class="form-control">
                    <option value="">Select Brand</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" {{ $product->brand == $brand->name ? 'selected' : '' }}>
                            {{ $brand->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Supplier</label>
                <select name="supplier_id" class="form-control">
                    <option value="">Select Supplier</option>
                    @foreach($suppliers as $sup)
                        <option value="{{ $sup->id }}" {{ $product->supplier_id == $sup->id ? 'selected' : '' }}>
                            {{ $sup->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Condition</label>
                <select name="condition_id" class="form-control">
                    @foreach($conditions as $cond)
                        <option value="{{ $cond->id }}" {{ $product->condition_id == $cond->id ? 'selected' : '' }}>
                            {{ $cond->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Current Price ($)</label>
                <input type="number" step="0.01" name="price" class="form-control" value="{{ $product->price }}" required>
            </div>
            <div class="form-group">
                <label>Old Price ($)</label>
                <input type="number" step="0.01" name="old_price" class="form-control" value="{{ $product->old_price }}">
            </div>
            <div class="form-group">
                <label>Rating (1-5)</label>
                <input type="number" step="0.1" name="rating" min="0" max="5" class="form-control" value="{{ $product->rating }}">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Options</label>
                <div style="display: flex; gap: 20px; align-items: center; padding: 10px; background: #f9f9f9; border-radius: 6px;">
                    <label style="cursor: pointer;"><input type="hidden" name="in_stock" value="0"><input type="checkbox" name="in_stock" value="1" {{ $product->in_stock ? 'checked' : '' }}> In Stock</label>
                    <label style="cursor: pointer;"><input type="hidden" name="is_negotiable" value="0"><input type="checkbox" name="is_negotiable" value="1" {{ $product->is_negotiable ? 'checked' : '' }}> Price Negotiable</label>
                </div>
            </div>
        </div>

        <div class="specs-grid" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; margin-top: 20px;">
            <div class="form-group"><label>Type</label><input type="text" name="type" class="form-control" value="{{ $product->type }}"></div>
            <div class="form-group"><label>Material</label><input type="text" name="material" class="form-control" value="{{ $product->material }}"></div>
            <div class="form-group"><label>Design Style</label><input type="text" name="design_style" class="form-control" value="{{ $product->design_style }}"></div>
            <div class="form-group"><label>Customization</label><input type="text" name="customization" class="form-control" value="{{ $product->customization }}"></div>
            <div class="form-group"><label>Protection</label><input type="text" name="protection" class="form-control" value="{{ $product->protection }}"></div>
            <div class="form-group"><label>Warranty</label><input type="text" name="warranty" class="form-control" value="{{ $product->warranty }}"></div>
            <div class="form-group"><label>Model Number</label><input type="text" name="model_number" class="form-control" value="{{ $product->model_number }}"></div>
            <div class="form-group"><label>Item Number</label><input type="text" name="item_number" class="form-control" value="{{ $product->item_number }}"></div>
            <div class="form-group"><label>Size</label><input type="text" name="size" class="form-control" value="{{ $product->size }}"></div>
            <div class="form-group"><label>Memory</label><input type="text" name="memory" class="form-control" value="{{ $product->memory }}"></div>
            <div class="form-group"><label>Certificate</label><input type="text" name="certificate" class="form-control" value="{{ $product->certificate }}"></div>
            <div class="form-group"><label>Style</label><input type="text" name="style" class="form-control" value="{{ $product->style }}"></div>
        </div>

        <div class="form-group" style="margin-top: 20px;">
            <label>Features</label>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 10px; background: #fcfcfc; padding: 15px; border-radius: 8px; border: 1px solid var(--admin-border);">
                @php $productFeatures = $product->features()->pluck('features.id')->toArray(); @endphp
                @foreach($features as $feat)
                <label style="font-size: 13px; display: flex; align-items: center; gap: 8px; font-weight: 500;">
                    <input type="checkbox" name="features[]" value="{{ $feat->id }}" {{ in_array($feat->id, $productFeatures) ? 'checked' : '' }}> {{ $feat->name }}
                </label>
                @endforeach
            </div>
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="4">{{ $product->description }}</textarea>
        </div>

        <div class="form-group">
            <label>Main Product Image</label>
            <div style="display: flex; align-items: center; gap: 20px;">
                <input type="file" name="image" class="form-control" style="flex: 1;" accept="image/*" onchange="previewImage(this, 'mainPreview')">
                <div class="image-preview-box" id="mainPreview">
                    <img src="{{ asset('storage/' . $product->image) }}">
                </div>
            </div>
        </div>

        <div class="form-group">
            <label>Gallery Images (Add multiple)</label>
            <input type="file" name="gallery[]" class="form-control" multiple accept="image/*">
            <div class="gallery-preview" style="display: flex; gap: 10px; flex-wrap: wrap; margin-top: 10px;">
                @foreach($product->images as $gImg)
                    <div class="image-preview-box">
                        <img src="{{ asset('storage/' . $gImg->image) }}">
                    </div>
                @endforeach
            </div>
        </div>

        <div style="margin-top: 40px; display: flex; gap: 12px;">
            <button type="submit" class="btn-primary" style="padding: 12px 32px;">
                <i class="fa-solid fa-sync"></i> Update Product
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
    function previewImage(input, previewId) {
        const preview = document.getElementById(previewId);
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
