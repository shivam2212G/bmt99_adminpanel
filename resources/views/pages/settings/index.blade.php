@extends('layout.master')
@section('title', 'Settings')
@section('content')
    <div class="app-ecommerce-category">

        <!-- Slider List Table -->

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Sliders</h5>
                <a href="{{ route('sliders.create') }}">
                    <button class="btn btn-primary">
                        + Add Slider
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
                            <th>Title</th>
                            {{-- <th>Description</th> --}}
                            <th class="text-center">Image</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sliders as $slider)
                            <tr>
                                <!-- ID -->
                                <td>{{ $loop->iteration }}</td>


                                <!-- Title -->
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar me-3">
                                            @if ($slider->slider_image)
                                                <img src="{{ asset($slider->slider_image) }}" class="rounded" width="40"
                                                    height="40" alt="Slider">
                                            @else
                                                <img src="{{ asset('assets/img/default.jpg') }}" class="rounded"
                                                    width="40" height="40" alt="Default">
                                            @endif
                                        </div>
                                        <div>
                                            <strong>{{ $slider->slider_title }}</strong>
                                        </div>
                                    </div>
                                </td>

                                <!-- Description -->
                                {{-- <td>{{ Str::limit($slider->slider_description, 50) ?? '-' }}</td> --}}

                                <!-- Image -->
                                <td class="text-center">
                                    @if ($slider->slider_image)
                                        <img src="{{ asset($slider->slider_image) }}" class="rounded" width="80"
                                            height="60" style="object-fit:cover;">
                                    @else
                                        <span class="text-muted">No Image</span>
                                    @endif
                                </td>

                                <!-- Status -->
                                <td class="text-center">
                                    @if ($slider->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>

                                <!-- Actions -->
                                <td class="text-center">
                                    <a href="{{ route('sliders.edit', $slider->slider_id) }}"
                                        class="btn btn-sm btn-outline-primary me-1" title="Edit">
                                        <i class="ti ti-pencil me-1"></i>
                                    </a>
                                    <a href="{{ route('sliders.delete', $slider->slider_id) }}"
                                        onclick="return confirm('Are you sure?')" class="btn btn-sm btn-outline-danger"
                                        title="Delete">
                                        <i class="ti ti-trash me-1"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    <div style="height: 20px"></div>

        <!-- ========== SETTINGS + SETTINGS FORM ========== -->
        <div class="row">

            <!-- LEFT SIDE: SHOW SETTINGS VALUES -->
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">API Settings</h5>
                    </div>
                    <div class="card-body">

                        <table class="table table-bordered">
                            <tr>
                                <th width="40%">New Products</th>
                                <td>Last {{ $settings->new_products }} Days</td>
                            </tr>

                            <tr>
                                <th>Best Offers</th>
                                <td>{{ $settings->best_offers }}% Off</td>
                            </tr>

                            <tr>
                                <th>Less In Stock</th>
                                <td>If Stock less than {{ $settings->less_in_stock }}</td>
                            </tr>

                            <tr>
                                <th>Shop Address</th>
                                <td>{{ $settings->shop_address }}</td>
                            </tr>

                            <tr>
                                <th>Privacy Policy</th>
                                <td>{{ $settings->privacy_policy }}</td>
                            </tr>
                            <tr>
                                <th>Discamer</th>
                                <td>{{ $settings->discamer }}</td>
                            </tr>
                            <tr>
                                <th>Shop Phone</th>
                                <td>{{ $settings->shop_phone }}</td>
                            </tr>
                            <tr>
                                <th>Shop Email</th>
                                <td>{{ $settings->shop_email }}</td>
                            </tr>
                            <tr>
                                <th>Facebook Link</th>
                                <td>{{ $settings->facebook_link }}</td>
                            </tr>
                            <tr>
                                <th>Twitter Link</th>
                                <td>{{ $settings->twitter_link }}</td>
                            </tr>
                            <tr>
                                <th>Instagram Link</th>
                                <td>{{ $settings->instagram_link }}</td>
                            </tr>

                        </table>

                    </div>
                </div>
            </div>

            <!-- RIGHT SIDE: EDIT FORM -->
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">App Api Settings</h5>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('settings.update') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">New Products ( In Days )</label>
                                <input type="number" name="new_products" value="{{ $settings->new_products }}"
                                    class="form-control">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Best Offers ( In % )</label>
                                <input type="number" name="best_offers" value="{{ $settings->best_offers }}"
                                    class="form-control">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Less In Stock</label>
                                <input type="number" name="less_in_stock" value="{{ $settings->less_in_stock }}"
                                    class="form-control">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Shop Address</label>
                                <input type="text" name="shop_address" value="{{ $settings->shop_address }}"
                                    class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Privacy Policy</label>
                                <input type="text" name="privacy_policy" value="{{ $settings->privacy_policy }}"
                                    class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Discamer</label>
                                <input type="text" name="discamer" value="{{ $settings->discamer }}"
                                    class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Shop Phone</label>
                                <input type="text" name="shop_phone" value="{{ $settings->shop_phone }}"
                                    class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Shop Email</label>
                                <input type="email" name="shop_email" value="{{ $settings->shop_email }}"
                                    class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Facebook Link</label>
                                <input type="text" name="facebook_link" value="{{ $settings->facebook_link }}"
                                    class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Twitter Link</label>
                                <input type="text" name="twitter_link" value="{{ $settings->twitter_link }}"
                                    class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Instagram Link</label>
                                <input type="text" name="instagram_link" value="{{ $settings->instagram_link }}"
                                    class="form-control">
                            </div>


                            <button type="submit" class="btn btn-primary mt-2">Update</button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
        <!-- ========== END SETTINGS SECTION ========== -->


    </div>
@endsection
