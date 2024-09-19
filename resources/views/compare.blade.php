<x-layout>
    <x-slot:title>Pairwise Comparison</x-slot:title>

    <h3 class="mb-3">Pairwise Comparison of Suppliers</h3>

    <form action="{{ route('supplier-performances.storeComparison') }}" method="POST">
        @csrf
        @if(isset($supplierNames) && $supplierNames->isNotEmpty())
            <p>Number of Suppliers: {{ $supplierNames->count() }}</p>
            <table class="border-collapse w-full">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="py-2 px-4 border">No.</th>
                        <th class="py-2 px-4 border">Criteria</th>
                        <th class="py-2 px-4 border">Comparison</th>
                        <th class="py-2 px-4 border">Equal</th>
                        <th class="py-2 px-4 border">More Important</th>
                    </tr>
                </thead>
                <tbody>
                    @php $comparisonIndex = 1; @endphp
                    @foreach(['product_defect', 'delivery', 'cost', 'return_time'] as $criterion)
                        @foreach($supplierNames as $id1 => $name1)
                            @foreach($supplierNames as $id2 => $name2)
                                @if ($id1 < $id2)
                                    <tr class="{{ $comparisonIndex % 2 == 0 ? 'bg-gray-100' : '' }}">
                                        <td class="px-8">{{ $comparisonIndex }}</td>
                                        <td class="py-2 px-4 border">{{ ucfirst(str_replace('_', ' ', $criterion)) }}</td>
                                        <td class="py-2 px-4 border">{{ $name1 }} vs {{ $name2 }}</td>
                                        <td class="py-2 px-4 border">
                                            <input type="radio" name="importance_{{ $id1 }}_{{ $id2 }}_{{ $criterion }}" value="1" checked>
                                            <label>1</label>
                                        </td>
                                        <td class="py-2 px-4 border">
                                            <label class="mr-2 ">{{ $name1 }}</label>
                                            @for ($k = 2; $k <= 9; $k++)
                                                <input type="radio" name="importance_{{ $id1 }}_{{ $id2 }}_{{ $criterion }}" value="{{ $k }}">
                                                <label>{{ $k }}</label>
                                            @endfor
                                            <label class="ml-6 mr-2">{{ $name2 }}</label>
                                            @for ($k = 2; $k <= 9; $k++)
                                                <input type="radio" name="importance_{{ $id2 }}_{{ $id1 }}_{{ $criterion }}" value="{{ $k }}">
                                                <label>{{ $k }}</label>
                                            @endfor
                                        </td>
                                    </tr>
                                    @php $comparisonIndex++; @endphp
                                @endif
                            @endforeach
                        @endforeach
                    @endforeach
                </tbody>
            </table>
            <input type="hidden" name="suppliers" value="{{ json_encode($supplierNames->keys()) }}">
            <button type="submit" class="border border-black rounded-md px-4 py-2 bg-gray-200 hover:bg-gray-300 cursor-pointer mt-4">
                Submit
            </button>
        @else
            <p>Number of Suppliers: 0</p>
        @endif
    </form>
</x-layout>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('input[type="radio"][value="1"]').forEach(function(radio) {
            radio.checked = true;
        });
    });

    document.querySelectorAll('input[type="radio"][name^="importance_"]').forEach(function(input) {
        input.addEventListener('change', function() {
            if (this.checked) {
                this.closest('tr').querySelectorAll('input[type="radio"][name^="importance_"]').forEach(function(scaleInput) {
                    if (scaleInput !== input) scaleInput.checked = false;
                });
            }
        });
    });
</script>
