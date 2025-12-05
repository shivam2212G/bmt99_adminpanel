@extends('layout.master')
@section('title', 'Category List')
@section('content')
    <div class="app-ecommerce-category">
        <!-- Category List Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Categories</h5>
                <a href="{{ route('categories.create') }}">
                    {{-- <button class="btn btn-primary" data-bs-toggle="offcanvas" data-bs-target="#offcanvasEcommerceCategoryList"> --}}
                    <button class="btn btn-primary" data-bs-toggle="offcanvas">
                        + Add Category
                    </button>
                </a>
            </div>

            <div class="card-datatable table-responsive">
                @if (session('success'))
                    <div class="alert alert-success mx-3 mt-3">
                        {{ session('success') }}
                    </div>
                @endif

                <table class="datatables-category-list table border-top align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th class="text-center">Image</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar me-3">
                                            @if ($category->category_image)
                                                <img src="{{ asset($category->category_image) }}" class="rounded"
                                                    width="40" height="40" alt="Category">
                                            @else
                                                <img src="{{ asset('assets/img/default.jpg') }}" class="rounded"
                                                    width="40" height="40" alt="Default">
                                            @endif
                                        </div>
                                        <div>
                                            <strong>{{ $category->category_name }}</strong>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ Str::limit($category->category_description, 50) }}</td>
                                <td class="text-center">
                                    @if ($category->category_image)
                                        <img src="{{ asset($category->category_image) }}" class="rounded" width="60"
                                            height="50" style="object-fit:cover;">
                                    @else
                                        <span class="text-muted">No Image</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($category->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('categories.edit', $category->category_id) }}"
                                        class="btn btn-sm btn-outline-primary me-1">
                                        <i class="ti ti-pencil me-1"></i>
                                    </a>
                                    <a href="{{ route('categories.delete', $category->category_id) }}"
                                        onclick="return confirm('Are you sure?')" class="btn btn-sm btn-outline-danger">
                                        <i class="ti ti-trash me-1"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- <!-- Offcanvas Add Category -->
  <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasEcommerceCategoryList" aria-labelledby="offcanvasEcommerceCategoryListLabel">
    <div class="offcanvas-header py-4">
      <h5 id="offcanvasEcommerceCategoryListLabel" class="offcanvas-title">Add Category</h5>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body border-top">
      <form action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data" id="eCommerceCategoryListForm">
        @csrf
        <!-- Title -->
        <div class="mb-3">
          <label class="form-label">Title</label>
          <input type="text" name="category_name" class="form-control" placeholder="Enter category title" required>
        </div>
        <!-- Slug -->
        <div class="mb-3">
          <label class="form-label">Slug</label>
          <input type="text" name="slug" class="form-control" placeholder="Enter slug">
        </div>
        <!-- Image -->
        <div class="mb-3">
          <label class="form-label">Image</label>
          <input type="file" name="category_image" class="form-control">
        </div>
        <!-- Parent Category -->
        <div class="mb-3">
          <label class="form-label">Parent Category</label>
          <select name="parent_id" class="form-select">
            <option value="">Select Parent Category</option>
            @foreach ($categories as $parent)
              <option value="{{ $parent->category_id }}">{{ $parent->category_name }}</option>
            @endforeach
          </select>
        </div>
        <!-- Description -->
        <div class="mb-3">
          <label class="form-label">Description</label>
          <textarea name="category_description" class="form-control" rows="3" placeholder="Enter description"></textarea>
        </div>
        <!-- Status -->
        <div class="mb-4">
          <label class="form-label">Status</label>
          <select name="is_active" class="form-select">
            <option value="1">Active</option>
            <option value="0">Inactive</option>
          </select>
        </div>
        <!-- Submit -->
        <div class="mb-3">
          <button type="submit" class="btn btn-primary me-2">Add</button>
          <button type="button" class="btn btn-outline-danger" data-bs-dismiss="offcanvas">Cancel</button>
        </div>
      </form>
    </div>
  </div> --}}
    </div>
@endsection
