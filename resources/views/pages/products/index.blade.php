@extends('layout.master')
@section('title', 'Products')
@section('content')

<div class="app-ecommerce-category">
  <!-- Products List Table -->
  <div class="card">
    <!-- Header -->
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">Products</h5>
      <a href="{{ route('products.create') }}">
        <button class="btn btn-primary">
          + Add Product
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
            <th>Product</th>
            <th>Category</th>
            <th>SubCategory</th>
            <th class="text-center">Price</th>
            <th class="text-center">Stock</th>
            <th class="text-center">Status</th>
            <th class="text-center">Image</th>
            <th class="text-center">Actions</th>
          </tr>
        </thead>

        <tbody>
          @foreach ($products as $product)
          <tr>
            <!-- ID -->
            <td>{{ $loop->iteration }}</td>

            <!-- Product Info -->
            <td>
              <div class="d-flex align-items-center">
                <div class="avatar me-3">
                  @if ($product->product_image)
                    <img src="{{ asset($product->product_image) }}" class="rounded" width="40" height="40" alt="Product">
                  @else
                    <img src="{{ asset('assets/img/default.jpg') }}" class="rounded" width="40" height="40" alt="Default">
                  @endif
                </div>
                <div>
                  <strong>{{ $product->product_name }}</strong>
                  <p class="text-muted mb-0 small">{{ $product->featurebrand->feature_brand_name ?? 'N/A' }}</p>
                </div>
              </div>
            </td>

            <!-- Category -->
            <td>{{ $product->category->category_name ?? 'N/A' }}</td>

            <!-- SubCategory -->
            <td>{{ $product->subCategory->sub_category_name ?? 'N/A' }}</td>

            <!-- Price -->
            <td class="text-center">
              <p class="text-xs text-secondary text-decoration-line-through mb-0">
                ₹{{ $product->product_mrp }}
              </p>
              <p class="text-sm font-weight-bold text-dark mb-0">
                ₹{{ $product->product_price }}
              </p>
            </td>

            <!-- Stock -->
            <td class="text-center">
              <span class="badge bg-label-primary">
                {{ $product->product_stock }}
              </span>
            </td>

            <!-- Status -->
            <td class="text-center">
              @if ($product->is_active)
                <span class="badge bg-success">Active</span>
              @else
                <span class="badge bg-secondary">Inactive</span>
              @endif
            </td>

            <!-- Image -->
            <td class="text-center">
              @if ($product->product_image)
                <img src="{{ asset($product->product_image) }}" class="rounded zoom-image" width="60" height="50" style="object-fit:cover;">
              @else
                <span class="text-muted">No Image</span>
              @endif
            </td>

            <!-- Actions -->
            <td class="text-center">
              <a href="{{ route('products.edit', $product->product_id) }}" class="btn btn-sm btn-outline-primary me-1" title="Edit">
                <i class="ti ti-pencil me-1"></i>
              </a>
              <a href="{{ route('products.delete', $product->product_id) }}" onclick="return confirm('Are you sure?')" class="btn btn-sm btn-outline-danger" title="Delete">
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

<!-- Image Preview Modal -->
<div class="modal fade" id="imageModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-body p-0 text-center">
        <img id="modalImage" src="" class="img-fluid rounded" style="max-height: 80vh; object-fit: contain;">
      </div>
    </div>
  </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const modalImage = document.getElementById("modalImage");
    const imageModal = new bootstrap.Modal(document.getElementById("imageModal"));

    document.querySelectorAll(".zoom-image").forEach(img => {
        img.addEventListener("click", function () {
            modalImage.src = this.src;
            imageModal.show();
        });
    });
});
</script>


@endsection
