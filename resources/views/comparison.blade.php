{{-- <x-layout>
    <x-slot:title>Pairwise Comparison</x-slot:title>

    <h3 class="mb-3">Pairwise Comparison of Criteria</h3>

    <form action="{{ route('comparison.storeComparison') }}" method="POST">
        @csrf
        <p>Number of Criteria: {{ count($criteria) }}</p>

        <table class="border-collapse w-full">
            <thead>
                <tr class="bg-gray-200">
                    <th class="py-2 px-4 border">No.</th>
                    <th colspan="2" class="py-2 px-4 border">Criteria Comparison</th>
                    <th class="py-2 px-4 border">Equal</th>
                    <th class="py-2 px-4 border">How Important?</th>
                </tr>
            </thead>
            <tbody>
                @php $comparisonIndex = 1; @endphp
                @for ($i = 0; $i < count($criteria); $i++)
                    @for ($j = $i + 1; $j < count($criteria); $j++)
                        <tr class="{{ $comparisonIndex % 2 == 0 ? 'bg-gray-100' : '' }}">
                            <td class="px-8">
                                {{ $comparisonIndex }}
                            </td>
                            <td class="py-2 px-4 border">
                                <span class="ml-16">
                                    <input type="radio" name="importance_{{ $i }}_{{ $j }}"
                                        value="{{ $i }}" checked>
                                    <label>{{ $criteria[$i] }}</label>
                                </span>
                            </td>
                            <td class="py-2 px-4 border">
                                <span class="mr-8">
                                    <input type="radio" name="importance_{{ $i }}_{{ $j }}"
                                        value="{{ $j }}">
                                    <label>{{ $criteria[$j] }}</label>
                                </span>
                            </td>
                            <td class="py-2 px-4 border">
                                <span class="ml-4">
                                    @if(isset($recommendedValues[$i][$j]) && $recommendedValues[$i][$j] == 1)
                                        <label class="text-green-500 font-bold">1</label>
                                    @else
                                        <input type="radio" name="importance_{{ $i }}_{{ $j }}"
                                            value="equal">
                                        <label>1</label>
                                    @endif
                                </span>
                            </td>
                            <td class="py-2 px-4 border">
                                @for ($k = 2; $k <= 9; $k++)
                                    <span class="mr-1">
                                        @if(isset($recommendedValues[$i][$j]) && $recommendedValues[$i][$j] == $k)
                                            <label class="text-green-500 font-bold">{{ $k }}</label>
                                        @else
                                            <input type="radio" name="scale_{{ $i }}_{{ $j }}"
                                                value="{{ $k }}" {{ $k == 2 ? 'checked' : '' }}>
                                            <label>{{ $k }}</label>
                                        @endif
                                    </span>
                                @endfor
                            </td>
                        </tr>
                        @php $comparisonIndex++; @endphp
                    @endfor
                @endfor
            </tbody>
        </table>

        <input type="hidden" name="criteria" value="{{ json_encode($criteria) }}">
        <button type="submit" class="border border-black rounded-md px-4 py-2 bg-gray-200 hover:bg-gray-300 cursor-pointer mt-4">
            Submit
        </button>
    </form>
</x-layout>
 --}}





<x-layout>
    <x-slot:title>Pairwise Comparison</x-slot:title>

    <h3 class="mb-3">Pairwise Comparison of Criteria</h3>

    <form action="{{ route('comparison.storeComparison') }}" method="POST">
        @csrf
        <p>Number of Criteria: {{ count($criteria) }}</p>

        <table class="border-collapse w-full">
            <thead>
                <tr class="bg-gray-200">
                    <th class="py-2 px-4 border">No.</th>
                    <th colspan="2" class="py-2 px-4 border">Criteria Comparison</th>
                    <th class="py-2 px-4 border">Equal</th>
                    <th class="py-2 px-4 border">How Important?</th>
                </tr>
            </thead>
            <tbody>
                @php $comparisonIndex = 1; @endphp
                @for ($i = 0; $i < count($criteria); $i++)
                    @for ($j = $i + 1; $j < count($criteria); $j++)
                        <tr class="{{ $comparisonIndex % 2 == 0 ? 'bg-gray-100' : '' }}">
                            <td class="px-8">
                                {{ $comparisonIndex }}
                            </td>
                            <td class="py-2 px-4 border">
                                <span class="ml-16">
                                    <input type="radio" name="importance_{{ $i }}_{{ $j }}"
                                        value="{{ $i }}" checked>
                                    <label>{{ $criteria[$i] }}</label>
                                </span>
                            </td>
                            <td class="py-2 px-4 border">
                                <span class="mr-8">
                                    <input type="radio" name="importance_{{ $i }}_{{ $j }}"
                                        value="{{ $j }}">
                                    <label>{{ $criteria[$j] }}</label>
                                </span>
                            </td>
                            <td class="py-2 px-4 border">
                                <span class="ml-4">
                                    @if(isset($recommendedValues[$i][$j]) && $recommendedValues[$i][$j] == 1)
                                        <label class="text-green-500 font-bold">1</label>
                                    @else
                                        <input type="radio" name="importance_{{ $i }}_{{ $j }}"
                                            value="equal" class="equal-radio">
                                        <label>1</label>
                                    @endif
                                </span>
                            </td>
                            <td class="py-2 px-4 border">
                                @for ($k = 2; $k <= 9; $k++)
                                    <span class="mr-1">
                                        @if(isset($recommendedValues[$i][$j]) && $recommendedValues[$i][$j] == $k)
                                            <label class="text-green-500 font-bold">{{ $k }}</label>
                                        @else
                                            <input type="radio" name="scale_{{ $i }}_{{ $j }}"
                                                value="{{ $k }}" class="importance-radio">
                                            <label>{{ $k }}</label>
                                        @endif
                                    </span>
                                @endfor
                            </td>
                        </tr>
                        @php $comparisonIndex++; @endphp
                    @endfor
                @endfor
            </tbody>
        </table>

        <input type="hidden" name="criteria" value="{{ json_encode($criteria) }}">
        <button type="submit" class="border border-black rounded-md px-4 py-2 bg-gray-200 hover:bg-gray-300 cursor-pointer mt-4">
            Submit
        </button>
    </form>

    <script>
        document.querySelectorAll('.equal-radio').forEach(function(equalRadio) {
            equalRadio.addEventListener('change', function() {
                if (this.checked) {
                    const nameParts = this.name.split('_');
                    const importanceRadios = document.querySelectorAll(`input[name^="scale_${nameParts[1]}_${nameParts[2]}"]`);
                    importanceRadios.forEach(function(radio) {
                        radio.checked = false;
                    });
                }
            });
        });

        document.querySelectorAll('.importance-radio').forEach(function(importanceRadio) {
            importanceRadio.addEventListener('change', function() {
                if (this.checked) {
                    const nameParts = this.name.split('_');
                    const equalRadio = document.querySelector(`input[name="importance_${nameParts[1]}_${nameParts[2]}"][value="equal"]`);
                    if (equalRadio) {
                        equalRadio.checked = false;
                    }
                }
            });
        });
    </script>
</x-layout>
