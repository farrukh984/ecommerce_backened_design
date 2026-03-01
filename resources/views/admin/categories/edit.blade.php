@extends('layouts.admin')

@section('title', 'Edit Category')
@section('header_title', 'Update Category')

@section('admin_content')

<div class="premium-card" style="max-width: 600px;">
    <div class="action-header">
        <div class="header-title">
            <h2>Modify Category</h2>
            <p>Rename category: {{ $category->name }}</p>
        </div>
    </div>

    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" class="form-container" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label>Category Name</label>
            <input type="text" name="name" class="form-control" value="{{ $category->name }}" required>
        </div>

        <div class="form-group">
            <label for="background_image">Background Image</label>
            <input type="file" name="background_image" id="background_image" class="form-control" onchange="previewImage(event)">
            <div id="image_preview_container" style="margin-top: 15px; {{ $category->background_image ? '' : 'display: none;' }}">
                <p style="font-size: 12px; color: #666; margin-bottom: 5px;">{{ $category->background_image ? 'Current / New Image Preview:' : 'Image Preview:' }}</p>
                <img id="image_preview" src="{{ $category->background_image ? display_image($category->background_image) : '#' }}" width="150" style="border-radius: 8px; border: 2px solid #0d6efd; padding: 2px;">
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
                <i class="fa-solid fa-sync"></i> Update Category
            </button>
            <a href="{{ route('admin.categories.index') }}" class="btn-outline">Cancel</a>
        </div>
    </form>
</div>

@endsection
