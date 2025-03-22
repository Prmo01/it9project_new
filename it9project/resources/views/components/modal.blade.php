<div id="myModal" class="hidden fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg w-1/3">
        <div class="p-6">
            <h2 class="text-xl font-bold mb-4">Create Product</h2>
            
            <!-- Form -->
            <form id="productForm">
                <div class="mb-4">
                    <label for="productName" class="block">Product Name</label>
                    <input type="text" id="productName" 
                        class="border-gray-300 border rounded w-full p-2"
                        placeholder="Product Name" required>
                </div>

                <div class="mb-4">
                    <label for="productPrice" class="block">Description</label>
                    <input type="text" id="productPrice" 
                    class="border-gray-300 border rounded w-full p-2" 
                    placeholder="Description" required>
                </div>

                <div class="mb-4">
                    <label for="productPrice" class="block">Price</label>
                    <input type="number" id="productPrice" 
                    class="border-gray-300 border rounded w-full p-2" 
                    placeholder="Price" required>
                </div>

                <div class="mb-4">
                    <label for="productPrice" class="block">Quantity</label>
                    <input type="number" id="productPrice" 
                    class="border-gray-300 border rounded w-full p-2" 
                    placeholder="Quantity" required>
                </div>

                <div class="mb-4">
                    <label for="productPrice" class="block">Barcode</label>
                    <input type="text" id="productPrice" 
                    class="border-gray-300 border rounded w-full p-2" 
                    placeholder="Barcode" required>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end">
                    <button type="button" onclick="saveProduct()" class="bg-blue-500 text-white px-4 py-2 rounded">
                        Save
                    </button>
                    <button type="button" onclick="closeModal()" class="ml-2 bg-gray-400 text-white px-4 py-2 rounded">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    // Function to open the modal
    function openModal() {
        document.getElementById('myModal').classList.remove('hidden');
    }

    // Function to close the modal
    function closeModal() {
        document.getElementById('myModal').classList.add('hidden');
    }

    // Function to handle form submission
    function saveProduct() {
        const name = document.getElementById('productName').value;
        const price = document.getElementById('productPrice').value;

        if (name && price) {
            alert(`Product saved: ${name} - $${price}`);
            closeModal(); // Close modal after saving
        } else {
            alert('Please fill all fields');
        }
    }
</script>
