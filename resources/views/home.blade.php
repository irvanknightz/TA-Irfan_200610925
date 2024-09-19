<x-layout>
  <x-slot:title>{{ $title }}</x-slot:title>
  
  <h3 class="text-xl mb-5">Tentukan jumlah kriteria AHP yang akan diperhitungkan:</h3>

  <form action="{{ route('criteria.create') }}" method="GET">
    <label for="quantity">Inputkan Jumlah Kriteria (3-10):</label>
    <input type="number" id="quantity" name="n" min="3" max="10" value="2" class="border border-black" required>
    <input type="submit" value="Go" name="new" class="border border-black">
  </form>
</x-layout>
