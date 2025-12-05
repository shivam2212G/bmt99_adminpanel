@extends('layout.master')
@section('title','Order Details')

@section('content')

    <!-- HEADER: ORDER INFO -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">

        <div class="d-flex flex-column justify-content-center gap-1">
            <h5 class="mb-1 d-flex flex-wrap gap-2 align-items-center">
                Order #{{ $order->order_id }}

                {{-- Payment status badge --}}
                @if($order->payment_status == 1)
                    <span class="badge bg-label-success">Paid</span>
                @elseif($order->payment_status == 0)
                    <span class="badge bg-label-warning">Unpaid</span>
                @else
                    <span class="badge bg-label-danger">Failed</span>
                @endif

                {{-- Order Status badge --}}
                @php
                    $statusColors = [
                        0 => 'secondary',
                        1 => 'info',
                        2 => 'primary',
                        3 => 'success',
                        4 => 'danger',
                        5 => 'dark'
                    ];
                @endphp
                <span class="badge bg-label-{{ $statusColors[$order->order_status] }}">
                    @switch($order->order_status)
                        @case(0) Pending @break
                        @case(1) Confirmed @break
                        @case(2) Shipped @break
                        @case(3) Delivered @break
                        @case(4) Cancelled @break
                        @case(5) Returned @break
                    @endswitch
                </span>
            </h5>

            <p class="text-body">
                {{ date('d M Y, h:i A', strtotime($order->created_at)) }}
            </p>
        </div>

        {{-- <div>
            <button class="btn btn-label-danger" onclick="confirmDelete()">Delete Order</button>
        </div> --}}
        <div><a href="{{ route('order.list') }}">
            <button class="btn btn-label-secondary">Back</button></a>
        </div>
    </div>

    <!-- ===================== MAIN CONTENT ===================== -->
    <div class="row">

        <!-- LEFT SIDE: ORDER PRODUCTS + TIMELINE -->
        <div class="col-12 col-lg-8">

            <!-- ORDER TABLE -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Order Details</h5>
                </div>

                <div class="card-datatable table-responsive">
                    <table class="table border-top">
                        <thead>
                            <tr>
                                <th></th>
                                <th></th>
                                <th class="w-50">Product</th>
                                <th>Price</th>
                                <th>Qty</th>
                                <th>Total</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($order->items as $item)
                                <tr>
                                    <td></td>
                                    <td>
                                        <img src="{{ asset($item->product->product_image) }}"
                                             width="60" height="60"
                                             class="rounded"
                                             style="object-fit:cover;">
                                    </td>

                                    <td>
                                        <strong>{{ $item->product->product_name }}</strong><br>
                                        <small class="text-muted">{{ $item->product->product_description }}</small>
                                    </td>

                                    <td>₹{{ $item->price }}</td>
                                    <td>{{ $item->quantity }}</td>

                                    <td class="fw-bold">
                                        ₹{{ $item->price * $item->quantity }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- ORDER CALCULATION BOX -->
                    <div class="d-flex justify-content-end align-items-center m-3 mb-2 p-1">
                        <div class="order-calculations">

                            <div class="d-flex justify-content-between mb-2">
                                <span class="w-px-100 text-heading">Subtotal:</span>
                                <h6 class="mb-0">₹{{ $order->total_amount }}</h6>
                            </div>

                            <div class="d-flex justify-content-between mb-2">
                                <span class="w-px-100 text-heading">Discount:</span>
                                <h6 class="mb-0">-₹{{ $order->discount_amount }}</h6>
                            </div>

                            <div class="d-flex justify-content-between mb-2">
                                <span class="w-px-100 text-heading">Delivery:</span>
                                <h6 class="mb-0">
                                    @if($order->delivery_charge == 0)
                                        FREE
                                    @else
                                        ₹{{ $order->delivery_charge }}
                                    @endif
                                </h6>
                            </div>

                            <div class="d-flex justify-content-between">
                                <h6 class="w-px-100 mb-0">Total:</h6>
                                <h6 class="mb-0">₹{{ $order->final_amount }}</h6>
                            </div>

                        </div>
                    </div>

                </div>
            </div>

            <!-- SHIPPING TIMELINE -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Order Progress</h5>
                </div>
                <div class="card-body">

                    <ul class="timeline pb-0 mb-0">

                        <!-- STEP 1 -->
                        <li class="timeline-item {{ $order->order_status >= 0 ? 'border-primary' : 'border-left-dashed' }}">
                            <span class="timeline-point timeline-point-primary"></span>
                            <div class="timeline-event">
                                <div class="timeline-header">
                                    <h6 class="mb-0">Order Placed</h6>
                                    <span class="text-muted">{{ date('d M Y, h:i A', strtotime($order->created_at)) }}</span>
                                </div>
                                <p class="mt-2">Your order was successfully placed.</p>
                            </div>
                        </li>

                        <!-- STEP 2 -->
                        <li class="timeline-item {{ $order->order_status >= 1 ? 'border-primary' : 'border-left-dashed' }}">
                            <span class="timeline-point timeline-point-primary"></span>
                            <div class="timeline-event">
                                <div class="timeline-header">
                                    <h6 class="mb-0">Confirmed</h6>
                                </div>
                                <p class="mt-2">Order has been confirmed by admin.</p>
                            </div>
                        </li>

                        <!-- STEP 3 -->
                        <li class="timeline-item {{ $order->order_status >= 2 ? 'border-primary' : 'border-left-dashed' }}">
                            <span class="timeline-point timeline-point-primary"></span>
                            <div class="timeline-event">
                                <div class="timeline-header">
                                    <h6 class="mb-0">Shipped</h6>
                                </div>
                                <p class="mt-2">Order has been shipped.</p>
                            </div>
                        </li>

                        <!-- STEP 4 -->
                        <li class="timeline-item {{ $order->order_status >= 3 ? 'border-primary' : 'border-left-dashed' }}">
                            <span class="timeline-point timeline-point-primary"></span>
                            <div class="timeline-event">
                                <div class="timeline-header">
                                    <h6 class="mb-0">Delivered</h6>
                                </div>
                                <p class="mt-2">Order delivered successfully.</p>
                            </div>
                        </li>

                        <!-- CANCELLED -->
                        @if($order->order_status == 4)
                            <li class="timeline-item border-danger">
                                <span class="timeline-point bg-danger"></span>
                                <div class="timeline-event">
                                    <div class="timeline-header">
                                        <h6 class="mb-0">Cancelled</h6>
                                    </div>
                                    <p class="mt-2 text-danger">
                                        Reason: {{ $order->cancel_reason }}
                                    </p>
                                </div>
                            </li>
                        @endif

                    </ul>

                </div>
            </div>

        </div>

        <!-- RIGHT SIDE: CUSTOMER DETAILS -->
        <div class="col-12 col-lg-4">

            <!-- CUSTOMER CARD -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">Customer Details</h6>
                </div>

                <div class="card-body">

                    <div class="d-flex align-items-center mb-4">
                        <img src="{{ $order->user->avatar }}"
                             width="48" height="48"
                             class="rounded-circle me-3">
                        <div>
                            <h6 class="mb-0">{{ $order->user->name }}</h6>
                            <small class="text-muted">Customer ID: #{{ $order->user->id }}</small>
                        </div>
                    </div>

                    <p class="mb-1"><strong>Email:</strong> {{ $order->user->email }}</p>
                    <p class="mb-1"><strong>Phone:</strong> {{ $order->phone ?? $order->user->phone }}</p>

                </div>
            </div>

            <!-- SHIPPING ADDRESS -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between">
                    <h6 class="card-title mb-0">Shipping Address</h6>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $order->address }}</p>
                </div>
            </div>

        </div>
    </div>


{{-- <script>
    function confirmDelete() {
        if(confirm("Are you sure you want to delete this order?")) {
            window.location.href = "{{ route('order.delete', $order->order_id) }}";
        }
    }
</script> --}}

@endsection
