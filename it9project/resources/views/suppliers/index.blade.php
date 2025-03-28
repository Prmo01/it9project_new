<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Suppliers') }}
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
        <title>Suppliers</title>
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

            <!-- Search and Add Supplier Container -->
            <div class="search-filter-container">
                <form action="{{ route('suppliers.index') }}" method="GET" class="flex-grow-1">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Search by supplier name" value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#supplierModal">
                    <i class="bi bi-plus"></i> Add Supplier
                </button>
            </div>

            <!-- Suppliers Table -->
            <div class="bg-white overflow-hidden shadow-md sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>Supplier Name</th>
                                <th>Contact Number</th>
                                <th>Location</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($suppliers as $supplier)
                                <tr>
                                    <td>{{ $supplier->name }}</td>
                                    <td>{{ $supplier->contact_number }}</td>
                                    <td>{{ $supplier->location }}</td>
                                    <td class="action-buttons">
                                        <!-- Edit Button -->
                                        <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editSupplierModal{{ $supplier->supplier_id }}">
                                            <i class="bi bi-pencil"></i> Edit
                                        </button>
                                        <!-- Delete Button -->
                                        <form action="{{ route('suppliers.destroy', $supplier->supplier_id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Edit Supplier Modal -->
                                <div class="modal fade" id="editSupplierModal{{ $supplier->supplier_id }}" tabindex="-1" aria-labelledby="editSupplierModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="editSupplierModalLabel">Edit Supplier</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('suppliers.update', $supplier->supplier_id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="form-group mb-3">
                                                        <label for="name" class="form-label">Supplier Name</label>
                                                        <input type="text" name="name" id="name" class="form-control" placeholder="Supplier Name" value="{{ $supplier->name }}" maxlength="100" required>
                                                    </div>

                                                    <div class="form-group mb-3">
                                                        <label for="contact_number" class="form-label">Contact Number</label>
                                                        <input type="text" name="contact_number" id="contact_number" class="form-control"
                                                            value="{{ $supplier->contact_number }}" maxlength="11" pattern="\d{11}" placeholder="Contact number must be exactly 11 digits" required>
                                                    </div>

                                                    <div class="form-group mb-3">
                                                        <label for="location" class="form-label">Location</label>
                                                        <input type="text" name="location" id="location" class="form-control"
                                                            value="{{ $supplier->location }}" maxlength="255" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Update Supplier</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4">No suppliers found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $suppliers->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Supplier Modal -->
    <div class="modal fade" id="supplierModal" tabindex="-1" aria-labelledby="supplierModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="supplierModalLabel">Add Supplier</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('suppliers.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label for="name" class="form-label">Supplier Name</label>
                            <input type="text" name="name" id="name" class="form-control" maxlength="100" placeholder="Supplier Name" required>
                        </div>
                        <!-- Change from type="number" to type="text" -->
<div class="form-group mb-3">
    <label for="contact_number" class="form-label">Contact Number</label>
    <input type="text" 
           name="contact_number" 
           id="contact_number" 
           class="form-control" 
           placeholder="11-digit number (e.g., 09123456789)" 
           pattern="\d{11}" 
           maxlength="11"
           inputmode="numeric"
           required>
</div>
                        <div class="form-group mb-3">
                            <label for="location" class="form-label">Location</label>
                            <input type="text" name="location" id="location" class="form-control" maxlength="255" placeholder="Location" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Add Supplier</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    </body>
    </html>
</x-app-layout>
