@extends('layout.master')
@section('title','Edit Product')

@section('content')

<div class="app-ecommerce">

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Please fix the following errors:</strong>
            <ul class="mt-2 mb-0">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="productForm" action="{{ route('products.update', $product->product_id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row">

            <!-- LEFT SIDE -->
            <div class="col-12 col-lg-8">

                <!-- Product Info -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Edit Product Information</h5>
                    </div>

                    <div class="card-body">

                        <div class="row mb-3">
                            <div class="col">
                                <label class="form-label">Product Name</label>
                                <input type="text" name="product_name" class="form-control" required value="{{ $product->product_name }}">
                            </div>

                            <div class="col">
                                <label class="form-label">Stock Quantity</label>
                                <input type="number" name="product_stock" class="form-control" required value="{{ $product->product_stock }}">
                            </div>
                        </div>

                        <label class="form-label">Description</label>
                        <textarea name="product_description" class="form-control" rows="2">{{ $product->product_description }}</textarea>

                    </div>
                </div>

                <!-- Media -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Media</h5>
                    </div>

                    <div class="card-body">

                        <div class="border rounded p-2 text-center">
                            <input type="file" class="form-control" id="imageInput" name="product_image">
                            <small class="text-muted">Upload new image (optional)</small>
                        </div>

                        <img id="previewImg"
                             src="{{ asset($product->product_image) }}"
                             class="img-fluid mt-3 rounded"
                             style="max-width:250px;">
                    </div>
                </div>

                <!-- Category & Brand -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Category & Brand</h5>
                    </div>

                    <div class="card-body">
                        <div class="row">

                            <!-- Category -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Category</label>
                                <select class="form-select" id="categorySelect" name="category_id" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->category_id }}"
                                            {{ $product->category_id == $cat->category_id ? 'selected' : '' }}>
                                            {{ $cat->category_name }}
                                        </option>
                                    @endforeach
                                </select>

                                <div id="subcategoryBox" class="mt-3">
                                    <label class="form-label">Subcategory</label>
                                    <select class="form-select" id="subcategorySelect" name="sub_category_id" required>
                                        <!-- Loaded by JS -->
                                    </select>
                                </div>
                            </div>

                            <!-- Brand -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Featured Brand</label>
                                <select class="form-select" name="feature_brand_id">
                                    <option value="">Select Brand</option>
                                    @foreach($featureBrands as $brand)
                                        <option value="{{ $brand->feature_brand_id }}"
                                            {{ $product->feature_brand_id == $brand->feature_brand_id ? 'selected' : '' }}>
                                            {{ $brand->feature_brand_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                        </div>

                    </div>
                </div>

            </div>

            <!-- RIGHT SIDE -->
            <div class="col-12 col-lg-4">

                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Pricing</h5>
                    </div>

                    <div class="card-body">

                        <label class="form-label">MRP</label>
                        <input type="number" class="form-control mb-3" id="mrp" name="product_mrp"
                               value="{{ $product->product_mrp }}" required>

                        <label class="form-label">Selling Price</label>
                        <input type="number" class="form-control mb-3" id="price" name="product_price"
                               value="{{ $product->product_price }}" required>

                        <div class="bg-light p-2 rounded">
                            <div class="d-flex justify-content-between">
                                <span>You Save:</span>
                                <strong id="saveAmount">₹{{ $product->product_mrp - $product->product_price }}</strong>
                            </div>
                        </div>

                    </div>
                </div>

                <button class="btn btn-primary w-100 mb-2">Update Product</button>
                <a href="{{ route('products.index') }}" class="btn btn-secondary w-100">Cancel</a>

            </div>
        </div>

    </form>

</div>

{{-- JS --}}
<script>
    // Image preview
    const imageInput = document.getElementById("imageInput");
    const previewImg = document.getElementById("previewImg");

    imageInput.onchange = () => {
        let file = imageInput.files[0];
        let reader = new FileReader();
        reader.onload = e => previewImg.src = e.target.result;
        reader.readAsDataURL(file);
    };

    // Price calculation
    function calc() {
        let m = parseFloat(mrp.value) || 0;
        let p = parseFloat(price.value) || 0;
        saveAmount.innerText = "₹" + (m - p).toFixed(2);
    }

    mrp.oninput = calc;
    price.oninput = calc;

    // Load subcategories
    const categories = @json($categories);
    const subSelect = document.getElementById('subcategorySelect');
    const categorySelect = document.getElementById('categorySelect');
    const productSubId = "{{ $product->sub_category_id }}";

    function loadSubcategories() {
        let id = categorySelect.value;
        subSelect.innerHTML = "";

        let cat = categories.find(c => c.category_id == id);

        if (cat) {
            cat.sub_categories.forEach(sc => {
                subSelect.innerHTML += `
                    <option value="${sc.sub_category_id}"
                        ${sc.sub_category_id == productSubId ? 'selected' : ''}>
                        ${sc.sub_category_name}
                    </option>`;
            });
        }
    }

    loadSubcategories();
    categorySelect.onchange = loadSubcategories;

</script>

@endsection
