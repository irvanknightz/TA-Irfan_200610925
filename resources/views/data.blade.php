<x-layout>
    <x-slot:title>Supplier Performance Data</x-slot:title>

    <h3 class="mb-3">Supplier Performance Data</h3>

    <!-- Year selection -->
    <div class="mb-4">
        <form action="{{ route('data.show') }}" method="GET">
            <label for="year">Select Year:</label>
            <select name="year" id="year" onchange="this.form.submit()">
                @foreach ($years as $year)
                    <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                        {{ $year }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    <!-- Table displaying supplier performance data -->
    <table class="border-collapse w-full">
        <thead>
            <tr class="bg-gray-200">
                <th class="py-2 px-4 border">Month</th>
                <th class="py-2 px-4 border">Year</th>
                <th class="py-2 px-4 border">Supplier</th>
                <th class="py-2 px-4 border">Product Defects</th>
                <th class="py-2 px-4 border">Delivery Timeliness</th>
                <th class="py-2 px-4 border">Cost per Unit</th>
                <th class="py-2 px-4 border">Return Time</th>
                <th class="py-2 px-4 border">Actions 1</th>
                <th class="py-2 px-4 border">Actions 2</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($supplierPerformances as $performance)
                <tr>
                    <td class="py-2 px-4 border text-center">{{ $performance->month }}</td>
                    <td class="py-2 px-4 border text-center">{{ $performance->year }}</td>
                    <td class="py-2 px-4 border text-center">{{ $performance->supplier->name }}</td>
                    <td class="py-2 px-4 border text-center">{{ $performance->product_defect }}</td>
                    <td class="py-2 px-4 border text-center">{{ $performance->delivery }}</td>
                    <td class="py-2 px-4 border text-center">{{ $performance->cost }}</td>
                    <td class="py-2 px-4 border text-center">{{ $performance->return_time }}</td>
                    <td class="py-2 px-4 border text-center">
                        <form action="{{ route('supplier-performances.destroy', $performance) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="border border-red-500 rounded-md px-4 py-2 bg-red-200 hover:bg-red-300 cursor-pointer">
                                Delete
                            </button>
                        </form>
                    </td>
                    <td class="py-2 px-4 border text-center">
                        <a href="{{ route('supplier-performances.edit', $performance) }}" class="border border-blue-500 rounded-md px-4 py-2 ml-8 bg-blue-200 hover:bg-blue-300 cursor-pointer">
                            Edit
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Button to redirect to comparison page -->
    <div class="mt-4">
        <a href="{{ route('supplier-performances.compare') }}"
            class="border border-black rounded-md px-4 py-2 bg-gray-200 hover:bg-gray-300 cursor-pointer">
            Compare Criteria
        </a>
    </div>

    <!-- Pagination links -->
    <div class="mt-4">
        {{ $supplierPerformances->links() }}
    </div>

    <!-- Four Chart.js canvases for each performance metric -->
    <div class="charts-wrapper">
        <div class="chart-container">
            <canvas id="productDefectsChart"></canvas>
        </div>
        <div class="chart-container">
            <canvas id="deliveryTimelinessChart"></canvas>
        </div>
        <div class="chart-container">
            <canvas id="costPerUnitChart"></canvas>
        </div>
        <div class="chart-container">
            <canvas id="returnTimeChart"></canvas>
        </div>
    </div>

    <!-- Include Chart.js library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        .charts-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 20px;
        }
        .chart-container {
            width: 100%; /* Adjust the width as needed */
            height: auto; /* Adjust height as needed */
            margin: 0 auto;
            display: inline-block;
            margin-bottom: 20px; /* Adjust margin as needed */
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var selectedYear = "{{ $selectedYear }}";

            createLineChart('productDefectsChart', 'Product Defects', selectedYear);
            createBarChart('deliveryTimelinessChart', 'Delivery Timeliness', selectedYear);
            createLineChart('costPerUnitChart', 'Cost per Unit', selectedYear);
            createLineChart('returnTimeChart', 'Return Time', selectedYear);
        });

        function createBarChart(canvasId, chartTitle, year) {
            var ctx = document.getElementById(canvasId).getContext('2d');
            var datasets = getDatasets(canvasId, year);
            var data = {
                labels: @json($months),
                datasets: datasets
            };
            var options = {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: chartTitle 
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            precision: 0
                        }
                    }
                }
            };
            new Chart(ctx, {
                type: 'bar',
                data: data,
                options: options
            });
        }

        function createLineChart(canvasId, chartTitle, year) {
            var ctx = document.getElementById(canvasId).getContext('2d');
            var data = {
                labels: @json($months),
                datasets: getDatasets(canvasId, year)
            };
            var options = {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: chartTitle
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            };
            new Chart(ctx, {
                type: 'line',
                data: data,
                options: options
            });
        }

        function getDatasets(canvasId, year) {
            var datasets = [];
            var suppliers = @json($supplierNames);
            var colors = ['rgba(255, 99, 132, 0.5)', 'rgba(54, 162, 235, 0.5)', 'rgba(75, 192, 192, 0.5)','rgba(255, 206, 86, 0.5)'];
            var borders = ['rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)', 'rgba(75, 192, 192, 1)'];

            suppliers.forEach((supplier, index) => {
                var data = getDataByCanvasId(canvasId, year, index);
                datasets.push({
                    label: supplier,
                    data: data,
                    backgroundColor: colors[index % colors.length],
                    borderColor: borders[index % borders.length],
                    borderWidth: 1
                });
            });
            return datasets;
        }

        function getDataByCanvasId(canvasId, year, supplierIndex) {
            switch (canvasId) {
                case 'productDefectsChart':
                    return @json($productDefectsGroupedByMonth)[year][supplierIndex];
                case 'deliveryTimelinessChart':
                    return @json($deliveryTimelinessGroupedByMonth)[year][supplierIndex];
                case 'costPerUnitChart':
                    return @json($costPerUnitGroupedByMonth)[year][supplierIndex];
                case 'returnTimeChart':
                    return @json($returnTimeGroupedByMonth)[year][supplierIndex];
                default:
                    return [];
            }
        }
    </script>
</x-layout>
