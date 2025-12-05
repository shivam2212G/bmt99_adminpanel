@extends('layout.master')
@section('title', 'Edit Slider')
@section('content')
<div class="app-ecommerce">
  <div class="row">
    <!-- Left Column -->
    <div class="col-12 col-lg-8">
      <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="card-title mb-0">Edit Slider</h5>
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

          <form id="sliderForm" action="{{ route('sliders.update', $slider->slider_id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            {{-- @method('PUT') --}}

            <!-- Title -->
            <div class="mb-3">
              <label class="form-label">Slider Title</label>
              <input
                type="text"
                name="slider_title"
                class="form-control"
                placeholder="Enter slider title"
                value="{{ $slider->slider_title }}"
                required>
            </div>

            <!-- Description -->
            <div class="mb-3">
              <label class="form-label">Description</label>
              <textarea
                name="slider_description"
                class="form-control"
                rows="3"
                placeholder="Enter slider description...">{{ $slider->slider_description }}</textarea>
            </div>

            <!-- Image Upload -->
            <div class="mb-3">
              <label class="form-label">Slider Image</label>
              <input
                type="file"
                name="slider_image"
                id="sliderImage"
                class="form-control"
                accept="image/*">
              <small class="text-muted">Upload a new image to replace the current one.</small>
            </div>

            <!-- Status -->
            <div class="form-check form-switch mb-3">
              <input
                class="form-check-input"
                type="checkbox"
                name="is_active"
                id="is_active"
                {{ $slider->is_active ? 'checked' : '' }}>
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
            src="{{ $slider->slider_image ? asset($slider->slider_image) : asset('assets/img/default.jpg') }}"
            alt="Preview"
            class="img-fluid rounded border mb-3"
            style="max-height: 220px; object-fit: cover;">

          <div class="text-end">
            <a href="{{ route('settings.index') }}" class="btn btn-outline-secondary me-2">Cancel</a>
            <button type="submit" form="sliderForm" class="btn btn-dark">Update Slider</button>
          </div>
        </div>
      </div>
    </div>
    <!-- /Right Column -->
  </div>
</div>

<!-- JS for Live Image Preview -->
<script>
  document.getElementById('sliderImage').addEventListener('change', function (event) {
    const file = event.target.files[0];
    const preview = document.getElementById('previewImage');
    if (file) {
      preview.src = URL.createObjectURL(file);
    } else {
      preview.src = "{{ $slider->slider_image ? asset($slider->slider_image) : asset('assets/img/default.jpg') }}";
    }
  });
</script>
@endsection
