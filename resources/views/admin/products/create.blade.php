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
                <select name="brand_id" class="form-control">
                    <option value="">Select Brand</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Supplier</label>
                @php $suppliers = \App\Models\Supplier::all(); @endphp
                <select name="supplier_id" class="form-control">
                    <option value="">Select Supplier</option>
                    @foreach($suppliers as $sup)
                        <option value="{{ $sup->id }}">{{ $sup->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Condition</label>
                <select name="condition_id" class="form-control">
                    @foreach($conditions as $cond)
                        <option value="{{ $cond->id }}">{{ $cond->name }}</option>
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
                <label>Old Price ($)</label>
                <input type="number" step="0.01" name="old_price" class="form-control" placeholder="0.00">
            </div>
            <div class="form-group">
                <label>Rating (1-5)</label>
                <input type="number" step="0.1" name="rating" min="0" max="5" class="form-control" value="5">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Options & Stock</label>
                <div class="options-container" style="display: flex; flex-wrap: wrap; gap: 15px; align-items: center; padding: 15px; background: #f9f9f9; border-radius: 12px; border: 1px solid var(--admin-border);">
                    <label style="cursor: pointer; margin-bottom: 0; display: flex; align-items: center; gap: 8px; font-size: 14px; font-weight: 500;">
                        <input type="hidden" name="in_stock" value="0"><input type="checkbox" name="in_stock" value="1" checked> In Stock
                    </label>
                    <label style="cursor: pointer; margin-bottom: 0; display: flex; align-items: center; gap: 8px; font-size: 14px; font-weight: 500;">
                        <input type="hidden" name="is_negotiable" value="0"><input type="checkbox" name="is_negotiable" value="1"> Price Negotiable
                    </label>
                    <label style="cursor: pointer; margin-bottom: 0; display: flex; align-items: center; gap: 8px; font-size: 14px; font-weight: 500;">
                        <input type="hidden" name="is_active" value="0"><input type="checkbox" name="is_active" value="1" checked> Enabled / Active
                    </label>
                    <div style="flex: 1; min-width: 140px; display: flex; align-items: center; gap: 10px; justify-content: flex-end;">
                        <span style="font-size: 13px; font-weight: 600; white-space: nowrap;">Stock Qty:</span>
                        <input type="number" name="stock_quantity" class="form-control" value="100" style="width: 80px; height: 35px; padding: 5px 10px; background: white;">
                    </div>
                </div>
            </div>
        </div>

        <div class="specs-grid">
            <div class="form-group"><label>Type</label><input type="text" name="type" class="form-control" placeholder="e.g. Smart Watch"></div>
            <div class="form-group"><label>Material</label><input type="text" name="material" class="form-control" placeholder="e.g. Leather"></div>
            <div class="form-group"><label>Design Style</label><input type="text" name="design_style" class="form-control" placeholder="e.g. Modern"></div>
            <div class="form-group"><label>Customization</label><input type="text" name="customization" class="form-control" placeholder="e.g. Logo Printing"></div>
            <div class="form-group"><label>Protection</label><input type="text" name="protection" class="form-control" placeholder="e.g. 2-year warranty"></div>
            <div class="form-group"><label>Warranty</label><input type="text" name="warranty" class="form-control" placeholder="e.g. Full support"></div>
            <div class="form-group"><label>Model Number</label><input type="text" name="model_number" class="form-control" placeholder="e.g. SM-G991B"></div>
            <div class="form-group"><label>Item Number</label><input type="text" name="item_number" class="form-control" placeholder="e.g. 100234"></div>
            <div class="form-group"><label>Size</label><input type="text" name="size" class="form-control" placeholder="e.g. 42mm"></div>
            <div class="form-group"><label>Memory</label><input type="text" name="memory" class="form-control" placeholder="e.g. 128GB"></div>
            <div class="form-group"><label>Certificate</label><input type="text" name="certificate" class="form-control" placeholder="e.g. CE, RoHS"></div>
            <div class="form-group"><label>Style</label><input type="text" name="style" class="form-control" placeholder="e.g. Sporty"></div>
        </div>

        <div class="form-group" style="margin-top: 20px;">
            <label>Features</label>
            <div class="features-grid">
                @foreach($features as $feat)
                <label class="feature-label">
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
            <label>Main Product Image</label>
            <div style="display: flex; align-items: center; gap: 20px;">
                <input type="file" name="image" class="form-control" style="flex: 1;" accept="image/*" onchange="previewImage(this, 'mainPreview')">
                <div class="image-preview-box" id="mainPreview">
                    <i class="fa-solid fa-image" style="font-size: 32px; color: var(--admin-text-sub);"></i>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label>Gallery Images (Multiple)</label>
            <input type="file" name="gallery[]" class="form-control" multiple accept="image/*">
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

@section('styles')
<style>
    .specs-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
        margin-top: 20px;
    }
    
    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 10px;
        background: #fcfcfc;
        padding: 15px;
        border-radius: 8px;
        border: 1px solid var(--admin-border);
    }

    .feature-label {
        font-size: 13px;
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 500;
        cursor: pointer;
    }

    @media (max-width: 768px) {
        .specs-grid {
            grid-template-columns: 1fr;
        }
        
        .options-container {
            flex-direction: column;
            align-items: flex-start !important;
        }
        
        .options-container div {
            width: 100%;
            justify-content: flex-start !important;
            margin-top: 10px;
        }
    }
</style>
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
