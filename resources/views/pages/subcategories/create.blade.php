@extends('layout.master')
@section('title', 'Add SubCategory')
@section('content')
<div class="app-ecommerce">
  <div class="row">
    <!-- Left Column -->
    <div class="col-12 col-lg-8">
      <div class="card mb-4">
        <div class="card-header">
          <h5 class="card-title mb-0">SubCategory Information</h5>
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

          <form id="subcategoryForm" action="{{ route('subcategories.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Select Category -->
            <div class="mb-3">
              <label class="form-label">Select Category</label>
              <select name="category_id" class="form-select" required>
                <option value="">-- Select Category --</option>
                @foreach($categories as $category)
                  <option value="{{ $category->category_id }}">{{ $category->category_name }}</option>
                @endforeach
              </select>
            </div>

            <!-- SubCategory Name -->
            <div class="mb-3">
              <label class="form-label">SubCategory Name</label>
              <input
                type="text"
                name="sub_category_name"
                class="form-control"
                placeholder="Enter subcategory name"
                required>
            </div>

            <!-- Description -->
            <div class="mb-3">
              <label class="form-label">Description</label>
              <textarea
                name="sub_category_description"
                class="form-control"
                rows="3"
                placeholder="Enter subcategory description..."></textarea>
            </div>

            <!-- Image Upload -->
            <div class="mb-3">
              <label class="form-label">SubCategory Image</label>
              <input
                type="file"
                name="sub_category_image"
                class="form-control"
                id="subCategoryImage"
                accept="image/*" required>
              <small class="text-muted">Choose an image to preview it on the right.</small>
            </div>

            <!-- Status -->
            <div class="form-check form-switch mb-3">
              <input
                class="form-check-input"
                type="checkbox"
                name="is_active"
                id="is_active"
                checked>
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
          <img
            id="previewImage"
            src="{{ asset('assets/img/customizer/system-dark.svg') }}"
            alt="Preview"
            class="img-fluid rounded border mb-3"
            style="max-height: 220px; object-fit: cover;">

          <div class="text-end">
            <a href="{{ route('subcategories.index') }}" class="btn btn-outline-secondary me-2">Cancel</a>
            <button type="submit" form="subcategoryForm" class="btn btn-dark">Save SubCategory</button>
          </div>
        </div>
      </div>
    </div>
    <!-- /Right Column -->
  </div>
</div>

<!-- JavaScript for Live Image Preview -->
<script>
  document.getElementById('subCategoryImage').addEventListener('change', function (event) {
    const file = event.target.files[0];
    const preview = document.getElementById('previewImage');
    if (file) {
      preview.src = URL.createObjectURL(file);
    } else {
      preview.src = "{{ asset('assets/img/default.jpg') }}";
    }
  });
</script>
@endsection
