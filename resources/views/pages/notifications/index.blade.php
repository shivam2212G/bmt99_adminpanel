@extends('layout.master')
@section('title', 'Notifications')
@section('content')
<div class="app-ecommerce-category">

    <!-- Notifications Card -->
    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">All Notifications</h5>
        </div>

        <div class="card-datatable table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Message</th>
                        <th>Image</th>
                        <th>Date</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($notifications as $notification)
                    <tr>
                        <td>{{ $notification->notification_id }}</td>

                        <td><strong>{{ $notification->title }}</strong></td>

                        <td style="max-width: 350px;">
                            <span class="text-muted">{{ $notification->message }}</span>
                        </td>

                        <td>
                            @if($notification->image)
                                <img src="{{ asset($notification->image) }}" width="60" height="60"
                                     class="rounded" style="object-fit: cover;">
                            @else
                                <span class="text-muted">No Image</span>
                            @endif
                        </td>

                        <td>{{ $notification->created_at->format('d M Y') }}</td>

                        <td class="text-center">
                            <button
                                class="btn btn-sm btn-outline-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#editModal{{ $notification->notification_id }}"
                            >
                                <i class="ti ti-edit me-1"></i>Edit
                            </button>
                        </td>
                    </tr>


                    <!-- ==================== EDIT MODAL ==================== -->
                    <div class="modal fade" id="editModal{{ $notification->notification_id }}" tabindex="-1">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">

                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Notification</h5>
                                    <button class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <form action="{{ route('notifications.update', $notification->notification_id) }}"
                                      method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <div class="modal-body">

                                        <div class="mb-3">
                                            <label class="form-label">Title</label>
                                            <input type="text" name="title" class="form-control"
                                                   value="{{ $notification->title }}" required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Message</label>
                                            <textarea name="message" rows="4" class="form-control"
                                                      required>{{ $notification->message }}</textarea>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Image</label>
                                            <input type="file" name="image" class="form-control">

                                            @if($notification->image)
                                                <div class="mt-2">
                                                    <img src="{{ asset($notification->image) }}" width="80"
                                                         height="80" class="rounded">
                                                </div>
                                            @endif
                                        </div>

                                    </div>

                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-success">Update</button>
                                        <button type="button" class="btn btn-light border"
                                                data-bs-dismiss="modal">Cancel</button>
                                    </div>

                                </form>

                            </div>
                        </div>
                    </div>
                    <!-- ==================== END EDIT MODAL ==================== -->

                    @endforeach
                </tbody>

            </table>
        </div>
    </div>

</div>
@endsection
