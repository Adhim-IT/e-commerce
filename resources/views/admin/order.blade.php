@extends('layouts.layout-admin')

@section('title', 'Order')

@section('header_title', 'Order')
@section('header_subtitle', 'Manage your order')

@section('content')

<div class="container-fluid">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3"> Order List</h1>
        </div>

        <!-- Order Table -->
        <div class="card shadow">
            <div class="card-body">
                <div class="table-responsive">
                    {{-- search --}}
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <form action="{{ route('admin.orders.index') }}" method="GET"
                            class="d-flex align-items-center gap-2">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control form-control-sm"
                                    placeholder="Search orders ID..." value="{{ request('search') }}">
                                <select name="status" class="form-select form-select-sm" id="">
                                    <option value="">All Status</option>
                                    @foreach (['processing', 'shipped'] as $status)
                                        <option value="{{ $status }}">
                                            {{ request('status') == $status ? 'selected' : '' }}
                                            {{ ucfirst($status) }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-sm btn-outline-secondary ">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                            @if (request('search') || request('status'))
                                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-x-circle me-1"></i>Clear
                                </a>
                            @endif
                        </form>
                    </div>

                    {{-- order table --}}
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Order Code</th>
                                <th>Customer</th>
                                <th>Total Amount</th>
                                <th>Status</th>
                                <th>Payment Method</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                <tr>
                                    <td>{{ $order->order_code }}</td>
                                    <td>{{ $order->user->name ?? 'N/A' }}</td>
                                    <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $order->status_color }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $order->midtrans_payment_type }}</td>
                                    <td>{{ $order->created_at->format('d M Y') }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal"
                                            data-bs-target="#orderDetailModal{{ $order->id }}"><i class="bi bi-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#UpdateStatusModal{{ $order->id }}"
                                            @if (empty($order->getNextPossibleStatuses())) disabled @endif>
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach

                            @if ($orders->isEmpty())
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="bi bi-inbox display-4 text-muted mb-2"></i>
                                            <p class="text-muted mb-0">No Order Found</p>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </tbody>


                        {{-- pagination --}}
                        <div class="mt-4 d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                Showing{{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }}
                            </div>
                            <div>
                                {{ $orders->links() }}
                            </div>
                        </div>
                </div>
            </div>
        </div>

        {{-- modal --}}
        @foreach ($orders as $order)
        {{-- order detail modal --}}
            <div class="modal fade" id="orderDetailModal{{ $order->id }}" tabindex="-1" aria-labelledby="orderDetailModalLabel{{ $order->id }}">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <h5 class="modal-title fw-bold" id="orderDetailModallabel{{ $order->id }}" >
                            <i class="bi bi-box-seam me-2"></i>Order #{{ $order->id }}
                        </h5>
                        <button type="button"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row gap-4">
                            {{-- order status card --}}
                            <div class="col-12">
                                <div class="card borde-0 shadow-sm"></div>
                            </div>
                        </div>
                    </div>
                </div>
        @endforeach
</div>
@endsection
