<x-layout>
    <x-slot:title>AHP Comparison Results</x-slot:title>

    <!-- Priorities of Suppliers -->
    <h3>Priorities of Suppliers</h3>
    <ul>
        @foreach($results['prioritiesOfSuppliers'] as $supplierId => $priority)
            <li>{{ $supplierNames[$supplierId] }}: {{ number_format($priority, 2) }}%</li>
        @endforeach
    </ul>

    <!-- Weight Calculation -->
    <h3>Weight Calculation</h3>
    <table>
        <thead>
            <tr>
                <th>Criteria</th>
                <th>Importance</th>
                <th>Weight (%)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($criteria as $criterion)
                <tr>
                    <td>{{ $criterion['name'] }}</td>
                    <td>{{ $criterion['importance'] }}</td>
                    <td>{{ number_format($criterion['weightPercentage'], 2) }}%</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Priorities of Suppliers Chart -->
    <h3>Priorities of Suppliers Chart</h3>
    <div style="width: 80%; height: 400px; margin: auto;">
        <canvas id="prioritiesChart"></canvas>
    </div>

    <a href="{{ route('supplier-performances.compare') }}">Back to Comparison</a>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var ctx = document.getElementById('prioritiesChart').getContext('2d');

            // Data for the chart
            var labels = @json(array_values($supplierNames));
            var data = @json(array_values($results['prioritiesOfSuppliers']));
            
            // Generate colors for each supplier
            function getRandomColor() {
                var r = Math.floor(Math.random() * 255);
                var g = Math.floor(Math.random() * 255);
                var b = Math.floor(Math.random() * 255);
                return `rgba(${r}, ${g}, ${b}, 0.2)`;
            }

            function getBorderColor(color) {
                return color.replace('0.2', '1');
            }

            var backgroundColors = data.map(() => getRandomColor());
            var borderColors = backgroundColors.map(color => getBorderColor(color));

            var chartData = {
                labels: labels,
                datasets: [{
                    label: 'Priority (%)',
                    data: data,
                    backgroundColor: backgroundColors,
                    borderColor: borderColors,
                    borderWidth: 1
                }]
            };

            var myChart = new Chart(ctx, {
                type: 'bar',
                data: chartData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Priority (%)'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Suppliers'
                            }
                        }
                    }
                }
            });
        });
    </script>
</x-layout>
