<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Product List') }}
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
        <title>Products</title>
        <style>
            /* Custom Table Styling */
            .custom-table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 1rem;
            }
            .custom-table thead th {
                background-color: #f8f9fa; /* Light gray background for header */
                padding: 12px;
                text-align: left;
                font-weight: 600;
                border-bottom: 2px solid #dee2e6; /* Add a bottom border to header */
            }
            .custom-table tbody td {
                padding: 12px;
                border-bottom: 1px solid #dee2e6; /* Add a bottom border to rows */
            }
            .custom-table tbody tr:hover {
                background-color: #f8f9fa; /* Light gray background on hover */
            }
            .custom-table tbody tr:last-child td {
                border-bottom: none; /* Remove border from the last row */
            }
            .action-buttons .btn {
                margin-right: 5px; /* Add spacing between action buttons */
            }
            .search-filter-container {
                display: flex;
                gap: 10px;
                align-items: center;
                margin-bottom: 20px;
            }
        </style>
    </head>
    <body>
        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Success Message -->
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Search and Filter Container -->
                <div class="search-filter-container">
                    <!-- Search Input -->
                    <form action="{{ route('products.index') }}" method="GET" class="flex-grow-1">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search by name or barcode" value="{{ request('search') }}">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </form>

                    <!-- Filter by Category -->
                    <form action="{{ route('products.index') }}" method="GET">
                        <select name="category" class="form-select" onchange="this.form.submit()">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->category_id }}" {{ request('category') == $category->category_id ? 'selected' : '' }}>
                                    {{ $category->category_name }}
                                </option>
                            @endforeach
                        </select>
                    </form>

                    <!-- Add Product Button -->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
                        <i class="bi bi-plus"></i> Add Product
                    </button>
                </div>

                <!-- Product Table -->
                <div class="bg-white overflow-hidden shadow-md sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <table class="custom-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Barcode</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                    <tr>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->category->category_name ?? 'N/A' }}</td>
                                        <td>₱{{ number_format($product->price, 2) }}</td>
                                        <td>{{ $product->quantity }}</td>
                                        <td>{{ $product->barcode }}</td>
                                        <td class="action-buttons">
                                            <!-- Edit Button -->
                                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editProductModal" 
                                                data-product-id="{{ $product->id }}"
                                                data-product-name="{{ $product->name }}"
                                                data-product-category-id="{{ $product->category_id }}"
                                                data-product-price="{{ $product->price }}"
                                                data-product-quantity="{{ $product->quantity }}"
                                                data-product-barcode="{{ $product->barcode }}">
                                                <i class="bi bi-pencil"></i> Edit
                                            </button>
                                            <!-- Delete Button -->
                                            <form action="{{ route('products.destroy', $product->product_id) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this product?')">
                                                    <i class="bi bi-trash"></i> Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">No products found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <!-- Pagination Links -->
                        <div class="mt-4">
                            {{ $products->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Product Modal -->
        <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="addProductModalLabel">Add Product</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('products.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <!-- Product Name -->
                            <div class="form-group mb-3">
                                <label for="name" class="form-label">Product Name</label>
                                <input type="text" name="name" id="name" class="form-control" 
                                    value="{{ old('name') }}" placeholder="Enter Product Name" maxlength="255" required>
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Category Dropdown -->
                            <div class="form-group mb-3">
                                <label for="category_id" class="form-label">Category</label>
                                <select name="category_id" id="category_id" class="form-control" required>
                                    <option value="" disabled {{ old('category_id') ? '' : 'selected' }}>Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->category_id }}" {{ old('category_id') == $category->category_id ? 'selected' : '' }}>
                                            {{ $category->category_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Price -->
                            <div class="form-group mb-3">
                                <label for="price" class="form-label">Price</label>
                                <input type="number" name="price" id="price" class="form-control" 
                                    value="{{ old('price') }}" placeholder="Enter Price" min="0" step="0.01" required>
                                @error('price')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Quantity (READONLY) -->
                            <div class="form-group mb-3">
                                <label for="quantity" class="form-label">Quantity</label>
                                <input type="number" name="quantity" id="quantity" class="form-control" 
                                    value="0" readonly>
                            </div>

                            <!-- Barcode -->
                            <div class="form-group mb-3">
                                <label for="barcode" class="form-label">Barcode</label>
                                <input type="text" name="barcode" id="barcode" class="form-control" 
                                    value="{{ old('barcode') }}" placeholder="Enter 12-Character Barcode" minlength="12" maxlength="12" required
                                    oninput="this.value = this.value.toUpperCase()">
                                @error('barcode')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Add Product</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Product Modal -->
        <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="editProductModalLabel">Edit Product</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="editProductForm" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <!-- Product Name -->
                            <div class="form-group mb-3">
                                <label for="edit_name" class="form-label">Product Name</label>
                                <input type="text" name="name" id="edit_name" class="form-control" 
                                    placeholder="Enter Product Name" maxlength="255" required>
                            </div>

                            <!-- Category Dropdown -->
                            <div class="form-group mb-3">
                                <label for="edit_category_id" class="form-label">Category</label>
                                <select name="category_id" id="edit_category_id" class="form-control" required>
                                    <option value="" disabled>Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->category_id }}">{{ $category->category_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Price -->
                            <div class="form-group mb-3">
                                <label for="edit_price" class="form-label">Price</label>
                                <input type="number" name="price" id="edit_price" class="form-control" 
                                    placeholder="Enter Price" min="0" step="0.01" required>
                            </div>

                            <!-- Quantity -->
                            <div class="form-group mb-3">
                                <label for="edit_quantity" class="form-label">Quantity</label>
                                <input type="number" name="quantity" id="edit_quantity" class="form-control" 
                                    placeholder="Enter Quantity" required>
                            </div>

                            <!-- Barcode -->
                            <div class="form-group mb-3">
                                <label for="edit_barcode" class="form-label">Barcode</label>
                                <input type="text" name="barcode" id="edit_barcode" class="form-control" 
                                    placeholder="Enter 12-Character Barcode" minlength="12" maxlength="12" required
                                    oninput="this.value = this.value.toUpperCase()">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>

        <!-- JavaScript to Populate Edit Modal -->
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Listen for the edit button click
                document.querySelectorAll('[data-bs-target="#editProductModal"]').forEach(button => {
                    button.addEventListener('click', function () {
                        // Get product data from the button's data attributes
                        const productId = button.getAttribute('data-product-id');
                        const productName = button.getAttribute('data-product-name');
                        const productCategoryId = button.getAttribute('data-product-category-id');
                        const productPrice = button.getAttribute('data-product-price');
                        const productQuantity = button.getAttribute('data-product-quantity');
                        const productBarcode = button.getAttribute('data-product-barcode');

                        // Set the form action URL
                        document.getElementById('editProductForm').action = `/products/${productId}`;

                        // Populate the form fields
                        document.getElementById('edit_name').value = productName;
                        document.getElementById('edit_category_id').value = productCategoryId;
                        document.getElementById('edit_price').value = productPrice;
                        document.getElementById('edit_quantity').value = productQuantity;
                        document.getElementById('edit_barcode').value = productBarcode;
                    });
                });
            });
        </script>

        <!-- Auto-close modal on success -->
        @if(session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    let modal = bootstrap.Modal.getInstance(document.getElementById('addProductModal'));
                    if (modal) {
                        modal.hide();
                    }
                });
            </script>
        @endif
    </body>
    </html>
</x-app-layout>