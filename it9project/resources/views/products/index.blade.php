<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Products') }}
        </h2>
    </x-slot>


    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 flex justify-end">
                <button onclick="openModal()" class="bg-blue-500 text-white px-4 py-2 rounded ">
                    Add Product
                </button>
                
                <x-modal />
            </div>
        </div>
    </div>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-md sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="w-full border-collapse border border-gray-300">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="border border-gray-300 px-4 py-2 text-left font-semibold">Name</th>
                                <th class="border border-gray-300 px-4 py-2 text-left font-semibold">Description</th>
                                <th class="border border-gray-300 px-4 py-2 text-left font-semibold">Price</th>
                                <th class="border border-gray-300 px-4 py-2 text-left font-semibold">Quantity</th>
                                <th class="border border-gray-300 px-4 py-2 text-left font-semibold">Barcode</th>
                                <th class="border border-gray-300 px-4 py-2 text-left font-semibold">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="hover:bg-gray-50">
                               
                            </tr>
                            <tr class="hover:bg-gray-50">
                                
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
</x-app-layout>
