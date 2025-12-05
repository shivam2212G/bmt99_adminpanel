@extends('layout.master')
@section('title', 'Edit SubCategory')
@section('content')
    <div class="app-ecommerce">
        <div class="row">
            <!-- Left Column -->
            <div class="col-12 col-lg-8">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Edit SubCategory</h5>
                    </div>

                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger mb-3">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form id="subcategoryForm"
                            action="{{ route('subcategories.update', $subcategory->sub_category_id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            <!-- Select Category -->
                            <div class="mb-3">
                                <label class="form-label">Select Category</label>
                                <select name="category_id" class="form-select" required>
                                    <option value="">-- Select Category --</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->category_id }}"
                                            {{ $subcategory->category_id == $category->category_id ? 'selected' : '' }}>
                                            {{ $category->category_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- SubCategory Name -->
                            <div class="mb-3">
                                <label class="form-label">SubCategory Name</label>
                                <input type="text" name="sub_category_name" class="form-control"
                                    placeholder="Enter subcategory name" value="{{ $subcategory->sub_category_name }}"
                                    required>
                            </div>

                            <!-- Description -->
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="sub_category_description" class="form-control" rows="3" placeholder="Enter description...">{{ $subcategory->sub_category_description }}</textarea>
                            </div>

                            <!-- Image Upload -->
                            <div class="mb-3">
                                <label class="form-label">SubCategory Image</label>
                                <input type="file" name="sub_category_image" id="subCategoryImage" class="form-control"
                                    accept="image/*">
                                <small class="text-muted">Upload a new image to replace the current one.</small>
                            </div>

                            <!-- Status -->
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                                    {{ $subcategory->is_active ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Active</label>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- /Left Column -->

            <!-- Right Column -->
            <div class="col-12 col-lg-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Save</h5>
                        <small class="text-muted">Preview</small>
                    </div>

                    <div class="card-body text-center">
                        <!-- Image Preview -->
                        <img id="previewImage"
                            src="{{ $subcategory->sub_category_image ? asset($subcategory->sub_category_image) : asset('assets/img/default.jpg') }}"
                            alt="Preview" class="img-fluid rounded border mb-3"
                            style="max-height: 220px; object-fit: cover;">

                        <div class="text-end">
                            <a href="{{ route('subcategories.index') }}" class="btn btn-outline-secondary me-2">Cancel</a>
                            <button type="submit" form="subcategoryForm" class="btn btn-dark">Save</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Right Column -->
        </div>
    </div>

    <!-- JS for Live Image Preview -->
    <script>
        document.getElementById('subCategoryImage').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('previewImage');
            if (file) {
                preview.src = URL.createObjectURL(file);
            } else {
                preview.src =
                    "{{ $subcategory->sub_category_image ? asset($subcategory->sub_category_image) : asset('assets/img/default.jpg') }}";
            }
        });
    </script>
@endsection
