<!-- resources/views/edit.blade.php -->

<x-layout>
    <x-slot:title>Edit Supplier Performance</x-slot:title>

    <h3 class="mb-3">Edit Supplier Performance Data</h3>

    <!-- Form to edit supplier performance data -->
    <form action="{{ route('supplier-performances.update', $supplierPerformance) }}" method="POST" class="mb-6">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="month" class="block text-gray-700">Month</label>
            <input type="text" name="month" id="month" class="border rounded w-full py-2 px-3" value="{{ $supplierPerformance->month }}" required>
        </div>

        <!-- Add the year field -->
        <div class="mb-4">
            <label for="year" class="block text-gray-700">Year</label>
            <input type="number" name="year" id="year" class="border rounded w-full py-2 px-3" value="{{ $supplierPerformance->year }}" required>
        </div>

        <div class="mb-4">
            <label for="product_defect" class="block text-gray-700">Product Defects</label>
            <input type="number" name="product_defect" id="product_defect" class="border rounded w-full py-2 px-3" value="{{ $supplierPerformance->product_defect }}" required>
        </div>

        <div class="mb-4">
            <label for="delivery" class="block text-gray-700">Delivery Timeliness</label>
            <input type="number" name="delivery" id="delivery" class="border rounded w-full py-2 px-3" value="{{ $supplierPerformance->delivery }}" required>
        </div>

        <div class="mb-4">
            <label for="cost" class="block text-gray-700">Cost per Unit</label>
            <input type="number" name="cost" id="cost" class="border rounded w-full py-2 px-3" value="{{ $supplierPerformance->cost }}" required>
        </div>

        <div class="mb-4">
            <label for="return_time" class="block text-gray-700">Return Time</label>
            <input type="number" name="return_time" id="return_time" class="border rounded w-full py-2 px-3" value="{{ $supplierPerformance->return_time }}" required>
        </div>

        <button type="submit" class="border border-black rounded-md px-4 py-2 bg-gray-200 hover:bg-gray-300 cursor-pointer">
            Update Supplier Performance
        </button>
    </form>
</x-layout>
