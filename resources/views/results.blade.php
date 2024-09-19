<!-- resources/views/results.blade.php -->

<x-layout>
    <x-slot:title>Results</x-slot:title>

    <h3 class="mb-3">Priorities of Criteria</h3>
    <ul>
        @foreach ($priorities as $index => $priority)
            <li>{{ $criteria[$index] }}: {{ number_format($priority * 100, 2) }}%</li>
        @endforeach
    </ul>


    <h3 class="mb-3">Consistency Ratio</h3>
    <p>Consistency Ratio (CR): {{ number_format($CR, 4) }}</p>
    @if ($CR > 0.20)
        <p class="text-red-500 font-bold">Warning: The Consistency Ratio exceeds 0.20. You can adjust the input value's scores to improve consistency.</p>
    @endif

    <h3 class="mb-3">Decision Matrix</h3>
    <table class="border-collapse w-full text-center">
        <thead>
            <tr class="bg-gray-200">
                <th class="py-2 px-4 border">Criteria</th>
                @foreach ($criteria as $criterion)
                    <th class="py-2 px-4 border">{{ $criterion }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($decisionMatrix as $i => $row)
                <tr>
                    <td class="py-2 px-4 border">{{ $criteria[$i] }}</td>
                    @foreach ($row as $value)
                        <td class="py-2 px-4 border">{{ number_format($value, 2) }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3 class="mb-3">Criteria Priorities Chart</h3>
    <canvas id="criteriaChart"></canvas>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var ctx = document.getElementById('criteriaChart').getContext('2d');
            var chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($criteria),
                    datasets: [
                        {
                            label: 'Min Values (%)',
                            data: @json(array_map(function($value) { return $value * 100; }, $minValues)),
                            backgroundColor: 'rgba(255, 99, 132, 1)', // Full opacity
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Max Values (%)',
                            data: @json(array_map(function($value) { return $value * 100; }, $maxValues)),
                            backgroundColor: 'rgba(75, 192, 192, 1)', // Full opacity
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Result Values (%)',
                            data: @json(array_map(function($value) { return $value * 100; }, $priorities)),
                            backgroundColor: 'rgba(54, 162, 235, 1)', // Full opacity
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value + '%';
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
</x-layout>
