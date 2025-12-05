@extends('layout.master')
@section('title', 'SubCategories')
@section('content')

<div class="app-ecommerce-category">
  <!-- SubCategory List Table -->
  <div class="card">
    <!-- Header -->
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">SubCategories</h5>
      <a href="{{ route('subcategories.create') }}">
        <button class="btn btn-primary">
          + Add SubCategory
        </button>
      </a>
    </div>

    <!-- Table -->
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
            <th>SubCategory</th>
            <th>Parent Category</th>
            <th class="text-center">Image</th>
            <th class="text-center">Status</th>
            <th class="text-center">Actions</th>
          </tr>
        </thead>

        <tbody>
          @foreach ($subcategories as $subcategory)
          <tr>
            <!-- ID -->
            <td>{{ $loop->iteration }}</td>

            <!-- SubCategory Info -->
            <td>
              <div class="d-flex align-items-center">
                <div class="avatar me-3">
                  @if ($subcategory->sub_category_image)
                    <img src="{{ asset($subcategory->sub_category_image) }}" class="rounded" width="40" height="40" alt="SubCategory">
                  @else
                    <img src="{{ asset('assets/img/default.jpg') }}" class="rounded" width="40" height="40" alt="Default">
                  @endif
                </div>
                <div>
                  <strong>{{ $subcategory->sub_category_name }}</strong>
                </div>
              </div>
            </td>

            <!-- Parent Category -->
            <td>{{ $subcategory->category->category_name ?? 'N/A' }}</td>

            <!-- Image -->
            <td class="text-center">
              @if ($subcategory->sub_category_image)
                <img src="{{ asset($subcategory->sub_category_image) }}" class="rounded" width="60" height="50" style="object-fit:cover;">
              @else
                <span class="text-muted">No Image</span>
              @endif
            </td>

            <!-- Status -->
            <td class="text-center">
              @if ($subcategory->is_active)
                <span class="badge bg-success">Active</span>
              @else
                <span class="badge bg-secondary">Inactive</span>
              @endif
            </td>

            <!-- Actions -->
            <td class="text-center">
              <a href="{{ route('subcategories.edit', $subcategory->sub_category_id) }}" class="btn btn-sm btn-outline-primary me-1" title="Edit">
                <i class="ti ti-pencil me-1"></i>
              </a>
              <a href="{{ route('subcategories.delete', $subcategory->sub_category_id) }}" onclick="return confirm('Are you sure?')" class="btn btn-sm btn-outline-danger" title="Delete">
                <i class="ti ti-trash me-1"></i>
              </a>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

@endsection
