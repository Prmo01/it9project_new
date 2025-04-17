<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Stock In Management') }}
        </h2>
    </x-slot>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
        <title>Stock In</title>
        <style>
            .custom-table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 1rem;
            }
            .custom-table thead th {
                background-color: #f8f9fa;
                padding: 12px;
                text-align: left;
                font-weight: 600;
                border-bottom: 2px solid #dee2e6;
            }
            .custom-table tbody td {
                padding: 12px;
                border-bottom: 1px solid #dee2e6;
            }
            .custom-table tbody tr:hover {
                background-color: #f8f9fa;
            }
            .custom-table tbody tr:last-child td {
                border-bottom: none;
            }
            .action-buttons .btn {
                margin-right: 5px;
            }
            .search-filter-container {
                display: flex;
                gap: 10px;
                align-items: center;
                margin-bottom: 20px;
            }
            .product-row {
                margin-bottom: 15px;
                padding: 10px;
                border: 1px solid #dee2e6;
                border-radius: 5px;
            }
            .remove-product {
                color: #dc3545;
                cursor: pointer;
            }
            .status-badge {
                padding: 4px 8px;
                border-radius: 12px;
                font-size: 12px;
                font-weight: 600;
            }
            .status-draft { background-color: #e2e8f0; color: #4a5568; }
            .status-ordered { background-color: #bee3f8; color: #2b6cb0; }
            .status-received { background-color: #c6f6d5; color: #276749; }
            .status-partial { background-color: #feebc8; color: #b7791f; }
            .status-cancelled { background-color: #fed7d7; color: #c53030; }
            .order-detail-row {
                margin-bottom: 10px;
                padding-bottom: 10px;
                border-bottom: 1px solid #eee;
            }
            .order-detail-label {
                font-weight: 600;
                color: #4a5568;
            }
            .product-table {
                width: 100%;
                margin-top: 20px;
            }
            .product-table th {
                background-color: #f8f9fa;
                padding: 10px;
                text-align: left;
            }
            .product-table td {
                padding: 10px;
                border-bottom: 1px solid #eee;
            }
            .total-row {
                font-weight: bold;
                background-color: #f8f9fa;
            }
            
        </style>
    </head>
    <body>
        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="search-filter-container">
                    <!-- Search Form -->
                    <form action="{{ route('stockin.index') }}" method="GET" class="flex-grow-1">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search orders or suppliers..." 
                                   value="{{ $filters['search'] ?? '' }}">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </form>
                
                    <!-- Date Picker -->
                    <form action="{{ route('stockin.index') }}" method="GET" class="d-flex">
                        <input type="hidden" name="search" value="{{ $filters['search'] ?? '' }}">
                        <input type="hidden" name="status" value="{{ $filters['status'] ?? '' }}">
                        <div class="input-group" style="width: 250px;">
                            <input type="date" name="date" class="form-control" 
                                   value="{{ $filters['date'] ?? '' }}" onchange="this.form.submit()">
                            @if(isset($filters['date']))
                            <a href="{{ route('stockin.index', [
                                'search' => $filters['search'] ?? null,
                                'status' => $filters['status'] ?? null
                            ]) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x"></i>
                            </a>
                            @endif
                        </div>
                    </form>
                
                    <!-- Status Filter -->
                    <form action="{{ route('stockin.index') }}" method="GET">
                        <input type="hidden" name="search" value="{{ $filters['search'] ?? '' }}">
                        <input type="hidden" name="date" value="{{ $filters['date'] ?? '' }}">
                        <select name="status" class="form-select" onchange="this.form.submit()">
                            <option value="">All Statuses</option>
                            <option value="draft" {{ ($filters['status'] ?? '') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="ordered" {{ ($filters['status'] ?? '') == 'ordered' ? 'selected' : '' }}>Ordered</option>
                            <option value="received" {{ ($filters['status'] ?? '') == 'received' ? 'selected' : '' }}>Received</option>
                            <option value="partial" {{ ($filters['status'] ?? '') == 'partial' ? 'selected' : '' }}>Partial</option>
                            <option value="cancelled" {{ ($filters['status'] ?? '') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </form>
                
                    <!-- Clear Filters Button -->
                    @if(isset($filters['search']) || isset($filters['date']) || isset($filters['status']))
                    <a href="{{ route('stockin.index') }}" class="btn btn-outline-danger">
                        <i class="bi bi-x-circle"></i> Clear
                    </a>
                    @endif
                
                    <!-- Create Button -->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#stockInModal">
                        <i class="bi bi-plus"></i> Create
                    </button>
                </div>

                <div class="bg-white overflow-hidden shadow-md sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <table class="custom-table">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Supplier</th>
                                    <th>Status</th>
                                    <th>Total Qty</th>
                                    <th>Total Cost</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stockOrders as $order)
                                    <tr>
                                        <td>{{ $order->reference_number }}</td>
                                        <td>
                                            @if($order->supplier)
                                                {{ $order->supplier->name }}
                                                @if($order->supplier->location)
                                                    ({{ $order->supplier->location }})
                                                @endif
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>
                                            <span class="status-badge status-{{ $order->status }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $order->items->sum('quantity_added') }}</td>
                                        <td>
                                            @php
                                                $totalCost = 0;
                                                foreach($order->items as $item) {
                                                    $totalCost += $item->product->cost_price * $item->quantity_added;
                                                }
                                                echo '₱' . number_format($totalCost, 2);
                                            @endphp
                                        </td>
                                        <td>{{ $order->created_at->format('Y-m-d') }}</td>
                                        <td class="action-buttons">
                                            <button type="button" class="btn btn-info btn-sm view-order-btn" 
                                                data-order-id="{{ $order->stock_order_id }}">
                                                <i class="bi bi-eye"></i> View
                                            </button>
                                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" 
                                                data-bs-target="#editStatusModal{{ $order->stock_order_id }}">
                                                <i class="bi bi-pencil"></i> Status
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Edit Status Modal -->
                                    <div class="modal fade" id="editStatusModal{{ $order->stock_order_id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Update Order Status</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('stockin.update-status', $order->stock_order_id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="form-group mb-3">
                                                            <label class="form-label">Status</label>
                                                            <select name="status" class="form-control" required>
                                                                <option value="draft" {{ $order->status == 'draft' ? 'selected' : '' }}>Draft</option>
                                                                <option value="ordered" {{ $order->status == 'ordered' ? 'selected' : '' }}>Ordered</option>
                                                                <option value="received" {{ $order->status == 'received' ? 'selected' : '' }}>Received</option>
                                                                <option value="partial" {{ $order->status == 'partial' ? 'selected' : '' }}>Partial</option>
                                                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Update Status</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">No stock orders found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <div class="mt-4">
                            {{ $stockOrders->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stock In Modal -->
        <div class="modal fade" id="stockInModal" tabindex="-1" aria-labelledby="stockInModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form action="{{ route('stockin.store') }}" method="POST">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="stockInModalLabel">Create Stock Order</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group mb-3">
                                <label for="supplier_id" class="form-label">Supplier</label>
                                <select name="supplier_id" id="supplier_id" class="form-control" required>
                                    <option value="">Select Supplier</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->supplier_id }}">{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div id="products-container">
                                <div class="product-row">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="form-label">Product</label>
                                                <select name="products[0][product_id]" class="form-control product-select" required>
                                                    <option value="">Select Product</option>
                                                    @foreach($products as $product)
                                                        <option value="{{ $product->product_id }}" data-cost="{{ $product->cost_price }}">
                                                            {{ $product->name }} (₱{{ number_format($product->cost_price, 2) }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label class="form-label">Quantity</label>
                                                <input type="number" name="products[0][quantity]" class="form-control" value="1" min="1" required>
                                            </div>
                                        </div>
                                        <div class="col-md-2 d-flex align-items-end">
                                            <button type="button" class="btn btn-danger remove-product" style="display: none;">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="text-end mt-3">
                                <button type="button" id="add-product" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-plus"></i> Add Another Product
                                </button>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success">Submit Order</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- View Order Modal -->
        <div class="modal fade" id="viewOrderModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Order Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="orderDetailsContent">
                        <!-- Order details will be inserted here via JavaScript -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
    // Clear date button functionality
    document.getElementById('clearDateBtn').addEventListener('click', function() {
        document.getElementById('datePicker').value = '';
        // Submit the form to refresh the page
        const form = document.createElement('form');
        form.method = 'GET';
        form.action = window.location.pathname;
        
        // Keep other search parameters
        const searchParams = new URLSearchParams(window.location.search);
        searchParams.delete('date');
        
        // Add all remaining parameters
        searchParams.forEach((value, key) => {
            if (key !== 'date') {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = value;
                form.appendChild(input);
            }
        });
        
        document.body.appendChild(form);
        form.submit();
    });

    // If date is set in URL, update the date picker
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('date')) {
        document.getElementById('datePicker').value = urlParams.get('date');
    }
});
            document.addEventListener('DOMContentLoaded', function() {
                // Add product row
                document.getElementById('add-product').addEventListener('click', function() {
                    const container = document.getElementById('products-container');
                    const index = container.querySelectorAll('.product-row').length;
                    const template = `
                        <div class="product-row">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <select name="products[${index}][product_id]" class="form-control product-select" required>
                                            <option value="">Select Product</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->product_id }}" data-cost="{{ $product->cost_price }}">
                                                    {{ $product->name }} (₱{{ number_format($product->cost_price, 2) }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <input type="number" name="products[${index}][quantity]" class="form-control" value="1" min="1" required>
                                    </div>
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="button" class="btn btn-danger remove-product">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                    container.insertAdjacentHTML('beforeend', template);
                    
                    // Show remove button on all rows except first
                    container.querySelectorAll('.remove-product').forEach(btn => {
                        btn.style.display = 'block';
                    });
                });

                // Remove product row
                document.addEventListener('click', function(e) {
                    if (e.target.classList.contains('remove-product') || e.target.closest('.remove-product')) {
                        const btn = e.target.classList.contains('remove-product') ? e.target : e.target.closest('.remove-product');
                        btn.closest('.product-row').remove();
                        
                        // Reindex remaining products
                        const container = document.getElementById('products-container');
                        container.querySelectorAll('.product-row').forEach((row, index) => {
                            row.querySelector('select').name = `products[${index}][product_id]`;
                            row.querySelector('input').name = `products[${index}][quantity]`;
                        });
                        
                        // Hide remove button if only one row left
                        if (container.querySelectorAll('.product-row').length === 1) {
                            container.querySelector('.remove-product').style.display = 'none';
                        }
                    }
                });

                // View order details
               // Replace the existing view-order-btn event listener with this:
               document.querySelectorAll('.view-order-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const orderId = this.getAttribute('data-order-id');
        const modal = new bootstrap.Modal(document.getElementById('viewOrderModal'));
        
        // Show loading state
        document.getElementById('orderDetailsContent').innerHTML = `
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Loading order details...</p>
            </div>
        `;
        modal.show();

        // Use the correct route URL
        const url = `/stockin/${orderId}`;
        
        fetch(url, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => {
                    throw new Error(err.message || 'Failed to load order details');
                });
            }
            return response.json();
        })
        .then(data => {
            if (!data.success) {
                throw new Error(data.message || 'Invalid response format');
            }

            const order = data.data.order;
            const items = data.data.items;
            const totals = data.data.totals;
            const statusClass = `status-${order.status}`;
            
            // Build items table
            let itemsHtml = '';
            if (items && items.length > 0) {
                itemsHtml = `
                    <div class="order-detail-row">
                        <h6>Ordered Products</h6>
                        <table class="product-table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                `;
                
                items.forEach(item => {
                    itemsHtml += `
                        <tr>
                            <td>${item.product.name}</td>
                            <td>${item.quantity_added}</td>
                            <td>₱${item.product.cost_price}</td>
                            <td>₱${(item.product.cost_price * item.quantity_added)}</td>
                        </tr>
                    `;
                });
                
                itemsHtml += `
                            </tbody>
                            <tfoot>
                                <tr class="total-row">
                                    <td colspan="1">Total</td>
                                    <td>${totals.quantity}</td>
                                    <td></td>
                                    <td>₱${totals.cost.toFixed(2)}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                `;
            }
            
            // Build the modal content
            const modalContent = `
                <div class="order-detail-row">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="order-detail-label">Order Number</div>
                            <div>${order.reference_number}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="order-detail-label">Status</div>
                            <span class="status-badge ${statusClass}">
                                ${order.status.charAt(0).toUpperCase() + order.status.slice(1)}
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="order-detail-row">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="order-detail-label">Supplier</div>
                            <div>${order.supplier ? order.supplier.name + (order.supplier.location ? ` (${order.supplier.location})` : '') : 'N/A'}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="order-detail-label">Order Date</div>
                            <div>${new Date(order.created_at).toLocaleDateString()}</div>
                        </div>
                    </div>
                </div>
                
                ${itemsHtml}
            `;
            
            document.getElementById('orderDetailsContent').innerHTML = modalContent;
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('orderDetailsContent').innerHTML = `
                <div class="alert alert-danger">
                    Error loading order details: ${error.message}
                </div>
            `;
        });
    });
});

                @if(session('success'))
                    let modal = bootstrap.Modal.getInstance(document.getElementById('stockInModal'));
                    if (modal) {
                        modal.hide();
                    }
                @endif
            });
        </script>
    </body>
    </html>
</x-app-layout>