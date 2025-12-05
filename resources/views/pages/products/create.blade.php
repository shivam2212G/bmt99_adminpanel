@extends('layout.master')
@section('title','Add Product')

@section('content')

<div class="app-ecommerce">


    <!-- Success / Error alerts -->
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


    <form id="productForm" action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row">

            <!-- LEFT SIDE (8 columns) -->
            <div class="col-12 col-lg-8">

                <!-- Product Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Product Information</h5>
                    </div>

                    <div class="card-body">

                        <!-- Product Name & Stock -->
                        <div class="row mb-3">
                            <div class="col">
                                <label class="form-label">Product Name</label>
                                <input type="text" name="product_name" class="form-control" required value="{{ old('product_name') }}">
                            </div>

                            <div class="col">
                                <label class="form-label">Stock Quantity</label>
                                <input type="number" name="product_stock" class="form-control" required min="0" value="{{ old('product_stock') }}">
                            </div>
                        </div>

                        <!-- Description -->
                        <label class="form-label">Description</label>
                        <textarea name="product_description" class="form-control" rows="2">{{ old('product_description') }}</textarea>

                    </div>
                </div>

                <!-- Media Upload -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between">
                        <h5 class="card-title mb-0">Media</h5>
                    </div>

                    <div class="card-body">
                        <div class="border rounded text-center" id="uploadArea" style="cursor:pointer;">
                            <input type="file" class="form-control" id="imageInput" name="product_image" required>
                        </div>

                        <img id="previewImg" class="img-fluid mt-3 rounded d-none" style="max-width:250px;">
                    </div>
                </div>


                <!-- Category & Brand -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Category & Brand</h5>
                    </div>

                    <div class="card-body">

                        <div class="row">

                            <!-- Category -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Category</label>
                                <select class="form-select" id="categorySelect" name="category_id" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->category_id }}">{{ $cat->category_name }}</option>
                                    @endforeach
                                </select>

                                <!-- Subcategory -->
                                <div id="subcategoryBox" class="mt-3 d-none">
                                    <label class="form-label">Subcategory</label>
                                    <select class="form-select" id="subcategorySelect" name="sub_category_id" required></select>
                                </div>
                            </div>

                            <!-- Brand -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Featured Brand</label>
                                <select class="form-select" name="feature_brand_id" >
                                    <option value="">Select Brand</option>
                                    @foreach($featureBrands as $brand)
                                        <option value="{{ $brand->feature_brand_id }}">{{ $brand->feature_brand_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>

                    </div>
                </div>

            </div>
            <!-- LEFT END -->



            <!-- RIGHT SIDE (4 columns) -->
            <div class="col-12 col-lg-4">

                <!-- Pricing -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Pricing Information</h5>
                    </div>

                    <div class="card-body">

                        <label class="form-label">Maximum Retail Price (MRP)</label>
                        <input type="number" class="form-control mb-3" step="0.01" name="product_mrp" id="mrp" required>

                        <label class="form-label">Selling Price</label>
                        <input type="number" class="form-control mb-3" step="0.01" name="product_price" id="price" required>

                        <div class="bg-light p-2 rounded">
                            <div class="d-flex justify-content-between">
                                <span>You Save:</span>
                                <strong id="saveAmount">₹0.00</strong>
                            </div>
                        </div>

                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 mb-2">Save Product</button>
                <a href="{{ route('products.index') }}" class="btn btn-secondary w-100">Cancel</a>

            </div>
        </div>
    </form>

</div>


{{-- JS --}}
<script>
    // Image preview
    const uploadArea = document.getElementById("uploadArea");
    const imageInput = document.getElementById("imageInput");
    const previewImg = document.getElementById("previewImg");

    // uploadArea.onclick = () => imageInput.click();

    imageInput.addEventListener("click", function (event) {
    event.stopPropagation();
});

    imageInput.onchange = () => {
        let file = imageInput.files[0];
        let reader = new FileReader();

        reader.onload = e => {
            previewImg.src = e.target.result;
            previewImg.classList.remove("d-none");
        };

        reader.readAsDataURL(file);
    };


    // Price calculation
    const mrp = document.getElementById('mrp');
    const price = document.getElementById('price');
    const discount = document.getElementById('discount');
    const saveAmount = document.getElementById('saveAmount');

    function calc() {
        let m = parseFloat(mrp.value) || 0;
        let p = parseFloat(price.value) || 0;

        saveAmount.innerText = "₹" + (m - p).toFixed(2);
    }

    mrp.oninput = calc;
    price.oninput = calc;



    // Category → Subcategory Loader
    const categories = @json($categories);
    const subBox = document.getElementById('subcategoryBox');
    const subSelect = document.getElementById('subcategorySelect');

    document.getElementById('categorySelect').addEventListener('change', function () {
        let id = this.value;
        subSelect.innerHTML = "";
        if (!id) {
            subBox.classList.add('d-none');
            return;
        }

        let cat = categories.find(c => c.category_id == id);

        if (cat && cat.sub_categories.length > 0) {
            subBox.classList.remove('d-none');
            cat.sub_categories.forEach(sc => {
                subSelect.innerHTML += `<option value="${sc.sub_category_id}">${sc.sub_category_name}</option>`;
            });
        } else {
            subBox.classList.remove('d-none');
            subSelect.innerHTML = `<option disabled>No subcategories</option>`;
        }
    });

</script>

@endsection
