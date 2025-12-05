@extends('layout.master')
@section('title', 'Feature Brands')
@section('content')
    <div class="app-ecommerce">
        <div class="row">
            <!-- LEFT SIDE: LIST -->
            <div class="col-12 col-lg-7">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">All Feature Brands</h5>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="datatables-category-list table border-top align-middle">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Brand</th>
                                        <th class="text-center">Image</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($brands as $brand)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>

                                            <!-- Brand Name + Small Avatar -->
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar me-3">
                                                        <img src="{{ asset($brand->feature_brand_image) }}" class="rounded"
                                                            width="40" height="40" style="object-fit: cover;"
                                                            alt="Brand Image">
                                                    </div>
                                                    <div>
                                                        <strong>{{ $brand->feature_brand_name }}</strong>
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Main Image Preview -->
                                            <td class="text-center">
                                                <img src="{{ asset($brand->feature_brand_image) }}" class="rounded border"
                                                    width="60" height="50" style="object-fit:cover;">
                                            </td>

                                            <!-- Status Badge -->
                                            <td class="text-center">
                                                @if ($brand->is_active)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-secondary">Inactive</span>
                                                @endif
                                            </td>

                                            <!-- Actions -->
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center">

                                                    <!-- Edit -->
                                                    <button class="btn btn-sm btn-outline-primary me-1"
                                                        onclick="editBrand({{ $brand->feature_brand_id }},
                                    '{{ $brand->feature_brand_name }}',
                                    '{{ asset($brand->feature_brand_image) }}',
                                    {{ $brand->is_active ? 'true' : 'false' }})">
                                                        <i class="ti ti-pencil"></i>
                                                    </button>

                                                    <!-- Delete -->
                                                    <form
                                                        action="{{ route('feature_brands.destroy', $brand->feature_brand_id) }}"
                                                        method="POST" onsubmit="return confirm('Delete this brand?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                                            <i class="ti ti-trash"></i>
                                                        </button>
                                                    </form>

                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT SIDE: CREATE / EDIT FORM -->
            <div class="col-12 col-lg-5">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 id="formTitle" class="card-title mb-0">Add New Brand</h5>
                    </div>

                    <div class="card-body">
                        <form id="brandForm" action="{{ route('feature_brands.store') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="feature_brand_id" id="feature_brand_id">

                            <!-- Brand Name -->
                            <div class="mb-3">
                                <label class="form-label">Brand Name</label>
                                <input type="text" name="feature_brand_name" id="feature_brand_name" class="form-control"
                                    placeholder="Enter brand name" required>
                            </div>

                            <!-- Image -->
                            <div class="mb-3">
                                <label class="form-label">Brand Image</label>
                                <input type="file" name="feature_brand_image" id="feature_brand_image"
                                    class="form-control" accept="image/*">
                            </div>

                            <!-- Preview -->
                            <div class="text-center mb-3">
                                <img id="previewImage" src="{{ asset('/assets/img/customizer/system-dark.svg') }}"
                                    alt="Preview" class="img-fluid rounded border"
                                    style="max-height: 120px; object-fit: cover;">
                            </div>

                            <!-- Status -->
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" checked>
                                <label class="form-check-label" for="is_active">Active</label>
                            </div>

                            <!-- Buttons -->
                            <div class="text-end">
                                <button type="button" id="resetBtn" class="btn btn-outline-secondary me-2">Reset</button>
                                <button type="submit" class="btn btn-dark" id="submitBtn">Save Brand</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JS for Image Preview + Edit -->
    <script>
        // Preview selected image
        document.getElementById('feature_brand_image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('previewImage');
            preview.src = file ? URL.createObjectURL(file) : "{{ asset('assets/img/icons/misc/leaf-red.png') }}";
        });

        // Edit brand data
        function editBrand(id, name, image, active) {
            document.getElementById('formTitle').innerText = 'Edit Brand';
            document.getElementById('submitBtn').innerText = 'Update Brand';

            const form = document.getElementById('brandForm');
            form.action = '/feature_brands/' + id;
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'PUT';
            form.appendChild(methodInput);

            document.getElementById('feature_brand_id').value = id;
            document.getElementById('feature_brand_name').value = name;
            document.getElementById('previewImage').src = image;
            document.getElementById('is_active').checked = active;
        }

        // Reset form
        document.getElementById('resetBtn').addEventListener('click', function() {
            const form = document.getElementById('brandForm');
            form.reset();
            form.action = "{{ route('feature_brands.store') }}";
            document.getElementById('previewImage').src = "{{ asset('assets/img/icons/misc/leaf-red.png') }}";
            document.getElementById('formTitle').innerText = 'Add New Brand';
            document.getElementById('submitBtn').innerText = 'Save Brand';

            // Remove any existing PUT method field
            document.querySelectorAll('input[name="_method"]').forEach(e => e.remove());
        });
    </script>
@endsection
