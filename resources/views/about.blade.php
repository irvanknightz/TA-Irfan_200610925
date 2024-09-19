<!-- resources/views/about.blade.php -->

<x-layout>
    <x-slot:title>Supplier Performance Management</x-slot:title>

    <h3 class="mb-3">Supplier Performance Data</h3>

    <!-- Form to add new supplier performance data -->
    <form action="{{ route('supplier-performances.store') }}" method="POST" class="mb-6">
        @csrf

        <div class="mb-4">
            <label for="month" class="block text-gray-700">Month</label>
            <input type="text" name="month" id="month" class="border rounded w-full py-2 px-3" required>
        </div>

        <div class="mb-4">
            <label for="year" class="block text-gray-700">Year</label>
            <input type="number" name="year" id="year" class="border rounded w-full py-2 px-3" required>
        </div>

        <div class="mb-4">
            <label for="supplier" class="block text-gray-700">Supplier</label>
            <input type="text" name="supplier" id="supplier" class="border rounded w-full py-2 px-3" required>
        </div>

        <div class="mb-4">
            <label for="product_defect" class="block text-gray-700">Product Defects</label>
            <input type="number" name="product_defect" id="product_defect" class="border rounded w-full py-2 px-3" required>
        </div>

        <div class="mb-4">
            <label for="delivery" class="block text-gray-700">Delivery Timeliness</label>
            <input type="number" name="delivery" id="delivery" class="border rounded w-full py-2 px-3" required>
        </div>

        <div class="mb-4">
            <label for="cost" class="block text-gray-700">Cost per Unit</label>
            <input type="number" name="cost" id="cost" class="border rounded w-full py-2 px-3" required>
        </div>

        <div class="mb-4">
            <label for="return_time" class="block text-gray-700">Return Time</label>
            <input type="number" name="return_time" id="return_time" class="border rounded w-full py-2 px-3" required>
        </div>

        <button type="submit" class="border border-black rounded-md px-4 py-2 bg-gray-200 hover:bg-gray-300 cursor-pointer">
            Add Supplier Performance
        </button>
    </form>
</x-layout>
