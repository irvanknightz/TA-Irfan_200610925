{{-- <x-layout>
  <x-slot:title>{{ $title }}</x-slot:title>
  
  <h3 class="text-xl mb-5 font-family: verdana;">Input Nama Kriteria AHP:</h3>

  <form action="{{ route('criteria.store') }}" method="POST">
    @csrf
    @for ($i = 1; $i <= $n; $i++)
      <div class="mb-4">
        <label for="criteria_{{ $i }}">Kriteria {{ $i }}:</label>
        <input type="text" id="criteria_{{ $i }}" name="criteria[]" class="border border-black px-3 py-2 ml-5" required>
      </div>
    @endfor
    <input type="submit" value="Save" class="border border-black rounded-md px-4 py-2 bg-gray-200 hover:bg-gray-300 cursor-pointer">
  </form>
</x-layout>  --}}

<x-layout>
  <x-slot:title>{{ $title }}</x-slot:title>

  <style>
    .text-xl.mb-5.font-verdana {
      font-family: verdana; 
    }

    .mb-4 label {
      display: block;  
    }

    .mb-4 input {
      margin-left: 0px; 
    }
  </style>

  <h3 class="mb-4">Input Nama Kriteria AHP:</h3>

  <form action="{{ route('comparison.create') }}" method="POST">
    @csrf
    @for ($i = 1; $i <= $n; $i++)
      <div class="mb-4">
        <label for="criteria_{{ $i }}">Kriteria {{ $i }}:</label>
        <input type="text" id="criteria_{{ $i }}" name="criteria[]" class="border border-black px-3 py-2 ml-5" required>
      </div>
    @endfor
    <input type="hidden" name="n" value="{{ $n }}">
    <input type="submit" value="Save" class="border border-black rounded-md px-4 py-2 bg-gray-200 hover:bg-gray-300 cursor-pointer">
  </form>
</x-layout>



