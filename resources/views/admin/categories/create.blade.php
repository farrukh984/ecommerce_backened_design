@extends('layouts.admin')

@section('title', 'Add Category')
@section('header_title', 'Create Category')

@section('admin_content')

<div class="premium-card" style="max-width: 600px;">
    <div class="action-header">
        <div class="header-title">
            <h2>New Category</h2>
            <p>Enter the category name</p>
        </div>
    </div>

    <form action="{{ route('admin.categories.store') }}" method="POST" class="form-container" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label>Category Name</label>
            <input type="text" name="name" class="form-control" placeholder="e.g. Home Appliances" required>
        </div>

        <div class="form-group">
            <label for="background_image">Background Image</label>
            <input type="file" name="background_image" id="background_image" class="form-control" onchange="previewImage(event)">
            <div id="image_preview_container" style="margin-top: 15px; display: none;">
                <p style="font-size: 12px; color: #666; margin-bottom: 5px;">Image Preview:</p>
                <img id="image_preview" src="#" width="150" style="border-radius: 8px; border: 2px solid #0d6efd; padding: 2px;">
            </div>
        </div>

        <script>
            function previewImage(event) {
                const reader = new FileReader();
                reader.onload = function() {
                    const output = document.getElementById('image_preview');
                    const container = document.getElementById('image_preview_container');
                    output.src = reader.result;
                    container.style.display = 'block';
                }
                if (event.target.files[0]) {
                    reader.readAsDataURL(event.target.files[0]);
                }
            }
        </script>

        <div style="margin-top: 20px; display: flex; gap: 12px;">
            <button type="submit" class="btn-primary">
                <i class="fa-solid fa-save"></i> Save Category
            </button>
            <a href="{{ route('admin.categories.index') }}" class="btn-outline">Cancel</a>
        </div>
    </form>
</div>

@endsection
