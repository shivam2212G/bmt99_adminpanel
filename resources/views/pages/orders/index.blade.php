@extends('layout.master')
@section('title', 'Orders')

@section('content')

    <div class="app-ecommerce-category">

        <!-- Orders List Card -->
        <div class="card">

            <!-- Header -->
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Orders</h5>
            </div>

            <!-- Table -->
            <div class="card-datatable table-responsive">

                <table class="datatables-category-list table border-top align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Payment</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($allOrders as $order)
                            <tr>

                                <!-- ID -->
                                <td>{{ $order->order_id }}</td>

                                <!-- CUSTOMER (Avatar + Name + Phone) -->
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar me-3">
                                            <img src="{{ $order->user->avatar }}" class="rounded-circle" width="40"
                                                height="40">
                                        </div>
                                        <div>
                                            <strong>{{ $order->user->name }}</strong>
                                            <p class="text-muted small mb-0">
                                                {{ $order->phone ?? $order->user->phone }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <!-- AMOUNT -->
                                <td>
                                    <span class="fw-bold text-dark">₹{{ $order->final_amount }}</span>
                                    <br>
                                    <small class="text-muted">MRP: ₹{{ $order->total_amount }}</small>
                                </td>

                                <!-- PAYMENT -->
                                <td class="text-center">
                                    @if ($order->payment_method == '0')
                                        <span class="badge bg-label-warning">COD</span>
                                    @else
                                        <span class="badge bg-label-success">Online</span><br>
                                        <small class="text-muted">{{ $order->transaction_id }}</small>
                                    @endif
                                </td>


                                <!-- ORDER STATUS -->
                                <td class="text-center">

                                    <select class="form-select form-select-sm status-dropdown"
                                        data-id="{{ $order->order_id }}" style="min-width:120px">

                                        <option value="0" {{ $order->order_status == 0 ? 'selected' : '' }}>Pending
                                        </option>
                                        <option value="1" {{ $order->order_status == 1 ? 'selected' : '' }}>Confirmed
                                        </option>
                                        <option value="2" {{ $order->order_status == 2 ? 'selected' : '' }}>Shipped
                                        </option>
                                        <option value="3" {{ $order->order_status == 3 ? 'selected' : '' }}>Delivered
                                        </option>
                                        <option value="4" {{ $order->order_status == 4 ? 'selected' : '' }}>Cancelled
                                        </option>
                                        <option value="5" {{ $order->order_status == 5 ? 'selected' : '' }}>Returned
                                        </option>

                                    </select>

                                    <span
                                        class="badge mt-2 status-badge
        @switch($order->order_status)
            @case(0) bg-label-secondary @break
            @case(1) bg-label-info @break
            @case(2) bg-label-primary @break
            @case(3) bg-label-success @break
            @case(4) bg-label-danger @break
            @case(5) bg-label-dark @break
        @endswitch
    ">
                                        @switch($order->order_status)
                                            @case(0)
                                                Pending
                                            @break

                                            @case(1)
                                                Confirmed
                                            @break

                                            @case(2)
                                                Shipped
                                            @break

                                            @case(3)
                                                Delivered
                                            @break

                                            @case(4)
                                                Cancelled
                                            @break

                                            @case(5)
                                                Returned
                                            @break
                                        @endswitch
                                    </span>
                                </td>
                                <script>
                                    document.addEventListener("DOMContentLoaded", function() {

                                        $('.status-dropdown').on('change', function() {

                                            let orderId = $(this).data('id');
                                            let newStatus = $(this).val();
                                            let badge = $(this).closest('td').find('.status-badge');

                                            $.ajax({
                                                url: "{{ route('order.update.status') }}",
                                                type: "POST",
                                                data: {
                                                    order_id: orderId,
                                                    status: newStatus,
                                                    _token: "{{ csrf_token() }}"
                                                },

                                                success: function(response) {
                                                    // BADGE UPDATE
                                                    let text = "";
                                                    let color = "";

                                                    switch (newStatus) {
                                                        case "0":
                                                            text = "Pending";
                                                            color = "bg-label-secondary";
                                                            break;
                                                        case "1":
                                                            text = "Confirmed";
                                                            color = "bg-label-info";
                                                            break;
                                                        case "2":
                                                            text = "Shipped";
                                                            color = "bg-label-primary";
                                                            break;
                                                        case "3":
                                                            text = "Delivered";
                                                            color = "bg-label-success";
                                                            break;
                                                        case "4":
                                                            text = "Cancelled";
                                                            color = "bg-label-danger";
                                                            break;
                                                        case "5":
                                                            text = "Returned";
                                                            color = "bg-label-dark";
                                                            break;
                                                    }

                                                    badge.text(text)
                                                        .removeClass()
                                                        .addClass("badge mt-2 status-badge " + color);

                                                    toastr.success("Order status updated!");
                                                },
                                                error: function() {
                                                    toastr.error("Something went wrong");
                                                }
                                            });

                                        });

                                    });
                                </script>

                                <!-- DATE -->
                                <td>{{ date('d M Y', strtotime($order->created_at)) }}</td>

                                <!-- ACTION BUTTON -->
                                <td class="text-center">
                                    <a href="{{ route('order.details', $order->order_id) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="ti ti-eye me-1"></i> View
                                    </a>
                                </td>

                            </tr>

                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        No Orders Found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>

                </div>
            </div>
        </div>

    @endsection
