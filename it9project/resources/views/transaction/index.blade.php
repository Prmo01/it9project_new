<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Point of Sale') }}
        </h2>
    </x-slot>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>POS System</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
        <style>
            :root {
                --primary: #4361ee;
                --primary-dark: #3a56d4;
                --secondary: #3f37c9;
                --success: #4cc9f0;
                --danger: #f72585;
                --light: #f8f9fa;
                --dark: #212529;
                --gray: #6c757d;
                --border-radius: 0.75rem;
                --shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
                --transition: all 0.2s ease;
            }
            
            body {
                background-color: #f5f7fb;
                font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            }
            
            .pos-container {
                display: grid;
                grid-template-columns: 1.5fr 1fr;
                gap: 1.5rem;
                margin-top: 1rem;
            }
            
            .card {
                border: none;
                border-radius: var(--border-radius);
                box-shadow: var(--shadow);
                overflow: hidden;
                transition: var(--transition);
                background: white;
            }
            
            .card-header {
                background: var(--primary);
                color: white;
                font-weight: 600;
                padding: 1.25rem 1.5rem;
                border-bottom: none;
                display: flex;
                align-items: center;
                gap: 0.75rem;
            }
            
            .card-header i {
                font-size: 1.25rem;
            }
            
            .search-container {
                position: relative;
            }
            
            .search-container input {
                font-size: 1.1rem;
                padding: 1rem 1.5rem;
                border: 2px solid #e9ecef;
                border-radius: var(--border-radius);
                transition: var(--transition);
            }
            
            .search-container input:focus {
                border-color: var(--primary);
                box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
            }
            
            .search-container button {
                position: absolute;
                right: 5px;
                top: 5px;
                bottom: 5px;
                padding: 0 1.5rem;
                background: var(--primary);
                border: none;
                border-radius: calc(var(--border-radius) - 5px);
                color: white;
                font-weight: 600;
                transition: var(--transition);
            }
            
            .search-container button:hover {
                background: var(--primary-dark);
            }
            
            .cart-table {
                width: 100%;
                border-collapse: separate;
                border-spacing: 0;
            }
            
            .cart-table thead th {
                background: #f8fafc;
                padding: 1rem 1.5rem;
                font-weight: 600;
                color: var(--gray);
                text-transform: uppercase;
                font-size: 0.8rem;
                letter-spacing: 0.5px;
                border-bottom: 1px solid #e9ecef;
            }
            
            .cart-table tbody td {
                padding: 1.25rem 1.5rem;
                border-bottom: 1px solid #f1f3f5;
                vertical-align: middle;
            }
            
            .cart-table tbody tr:last-child td {
                border-bottom: none;
            }
            
            .qty-input {
                width: 70px;
                text-align: center;
                padding: 0.5rem;
                border: 1px solid #e9ecef;
                border-radius: 6px;
                font-weight: 500;
                transition: var(--transition);
            }
            
            .qty-input:focus {
                border-color: var(--primary);
                box-shadow: 0 0 0 2px rgba(67, 97, 238, 0.15);
            }
            
            .btn-action {
                width: 36px;
                height: 36px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                border-radius: 8px;
                transition: var(--transition);
            }
            
            .btn-action:hover {
                transform: translateY(-2px);
            }
            
            .empty-cart {
                padding: 3rem 2rem;
                text-align: center;
            }
            
            .empty-cart i {
                font-size: 3.5rem;
                color: #e9ecef;
                margin-bottom: 1.5rem;
            }
            
            .empty-cart h5 {
                color: var(--dark);
                font-weight: 600;
                margin-bottom: 0.5rem;
            }
            
            .empty-cart p {
                color: var(--gray);
                font-size: 0.95rem;
            }
            
            .summary-item {
                display: flex;
                justify-content: space-between;
                padding: 0.75rem 0;
                border-bottom: 1px dashed #e9ecef;
            }
            
            .summary-total {
                font-size: 1.25rem;
                font-weight: 700;
                color: var(--dark);
                padding: 1rem 0;
            }
            
            .payment-input {
                font-size: 1.1rem;
                padding: 1rem 1.5rem;
                border: 2px solid #e9ecef;
                border-radius: var(--border-radius);
                transition: var(--transition);
            }
            
            .payment-input:focus {
                border-color: var(--primary);
                box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
            }
            
            .btn-checkout {
                background: var(--primary);
                color: white;
                font-weight: 600;
                padding: 1rem;
                border-radius: var(--border-radius);
                transition: var(--transition);
                border: none;
                width: 100%;
                font-size: 1.1rem;
            }
            
            .btn-checkout:hover {
                background: var(--primary-dark);
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(67, 97, 238, 0.2);
            }
            
            @media (max-width: 992px) {
                .pos-container {
                    grid-template-columns: 1fr;
                }
            }
        </style>
    </head>
    <body>
    <div class="py-4">
        <div class="container">
            <!-- Success Message -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="pos-container">
                <!-- Left Column - Products & Cart -->
                <div class="left-column">
                    <!-- Product Search -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="bi bi-upc-scan"></i>
                            <span>Scan Products</span>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('transaction.add') }}" method="POST">
                                @csrf
                                <div class="search-container">
                                    <input type="text" name="product_code" class="form-control" 
                                           placeholder="Scan barcode or search products..." required autofocus>
                                    <button type="submit">
                                        <i class="bi bi-plus-lg me-1"></i> Add
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Shopping Cart -->
                    <div class="card">
                        <div class="card-header">
                            <i class="bi bi-cart3"></i>
                            <span>Order Items</span>
                        </div>
                        <div class="card-body p-0">
                            @if(count($cart) > 0)
                                <div class="table-responsive">
                                    <table class="cart-table">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Price</th>
                                                <th>Qty</th>
                                                <th>Total</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($cart as $item)
                                                <tr>
                                                    <td>
                                                        <div class="fw-medium">{{ $item['name'] }}</div>
                                                    </td>
                                                    <td>₱{{ number_format($item['price'], 2) }}</td>
                                                    <td>
                                                        <input type="number" name="quantity" min="1" 
                                                               value="{{ $item['quantity'] }}" 
                                                               class="form-control qty-input" 
                                                               data-id="{{ $item['id'] }}">
                                                    </td>
                                                    <td class="fw-medium">₱{{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                                                    <td class="text-end">
                                                        <form action="{{ route('transaction.remove', $item['id']) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-action">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="empty-cart">
                                    <i class="bi bi-cart-x"></i>
                                    <h5>Your cart is empty</h5>
                                    <p>Scan or search for products to add them to your order</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right Column - Payment -->
                <div class="right-column">
                    <div class="card sticky-top" style="top: 1rem;">
                        <div class="card-header bg-dark">
                            <i class="bi bi-credit-card"></i>
                            <span>Payment Summary</span>
                        </div>
                        <div class="card-body">
                            @if(count($cart) > 0)
                                <div class="mb-4">
                                    <div class="summary-item">
                                        <span>Subtotal:</span>
                                        <span class="fw-medium">₱{{ number_format($total, 2) }}</span>
                                    </div>
                                    <div class="summary-item">
                                        <span>Tax (12%):</span>
                                        <span class="fw-medium">₱{{ number_format($total * 0.12, 2) }}</span>
                                    </div>
                                    <div class="summary-total d-flex justify-content-between">
                                        <span>Total:</span>
                                        <span>₱{{ number_format($total * 1.12, 2) }}</span>
                                    </div>
                                </div>

                                <form action="{{ route('transaction.complete') }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label fw-medium mb-2">Amount Paid</label>
                                        <input type="number" name="payment_amount" id="payment_amount" 
                                               class="form-control payment-input mb-3" 
                                               placeholder="Enter amount paid" required>
                                        <label class="form-label fw-medium mb-2">Change</label>
                                        <input type="number" name="change" id="change" 
                                               class="form-control payment-input" 
                                               placeholder="Change" readonly>
                                    </div>
                                    <button type="submit" class="btn btn-checkout mt-3">
                                        <i class="bi bi-check-circle me-2"></i>Complete Order
                                    </button>
                                </form>
                            @else
                                <div class="empty-cart">
                                    <i class="bi bi-credit-card"></i>
                                    <h5>No items to checkout</h5>
                                    <p>Add products to cart to enable payment</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Update quantity and reload
        document.querySelectorAll('.qty-input').forEach(input => {
            input.addEventListener('change', (event) => {
                let productId = event.target.getAttribute('data-id');
                let quantity = Math.max(1, event.target.value); // Ensure at least 1
                
                fetch(`/transaction/update-quantity/${productId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify({ quantity: quantity })
                })
                .then(response => {
                    if (!response.ok) throw new Error('Network error');
                    window.location.reload();
                })
                .catch(error => {
                    console.error('Error:', error);
                    event.target.value = event.target.getAttribute('value');
                });
            });
        });

        // Calculate change
        const paymentInput = document.getElementById('payment_amount');
        if (paymentInput) {
            paymentInput.addEventListener('input', (event) => {
                const amountPaid = parseFloat(event.target.value) || 0;
                const total = parseFloat('{{ $total * 1.12 }}') || 0;
                const change = amountPaid - total;
                document.getElementById('change').value = change >= 0 ? change.toFixed(2) : 0;
            });
        }

        // Focus on search field
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelector('input[name="product_code"]')?.focus();
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>
</x-app-layout>