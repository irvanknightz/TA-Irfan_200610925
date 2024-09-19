<?php

// app/Http/Controllers/SupplierPerformanceController.php

namespace App\Http\Controllers;

use App\Models\SupplierPerformance;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SupplierPerformanceController extends Controller
{
    public function index()
    {
        // Get all supplier performances and pass them to the view
        $supplierPerformances = SupplierPerformance::with('supplier')->get();
        return view('about', compact('supplierPerformances'));
    }

    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'month' => 'required|string|max:255',
            'year' => 'required|integer',
            'supplier' => 'required|string|max:255',
            'product_defect' => 'required|integer',
            'delivery' => 'required|integer',
            'cost' => 'required|integer',
            'return_time' => 'required|integer',
        ]);

        // Find or create the supplier
        $supplier = Supplier::firstOrCreate(['name' => $request->input('supplier')]);

        // Create new supplier performance record
        SupplierPerformance::create([
            'month' => $request->input('month'),
            'year' => $request->input('year'),
            'supplier_id' => $supplier->id,
            'product_defect' => $request->input('product_defect'),
            'delivery' => $request->input('delivery'),
            'cost' => $request->input('cost'),
            'return_time' => $request->input('return_time'),
        ]);

        // Redirect back with success message
        return redirect()->route('supplier-performances.index')->with('success', 'Supplier Performance added successfully.');
    }

    public function destroy(SupplierPerformance $supplierPerformance)
    {
        $supplierPerformance->delete();
        return redirect()->route('data')->with('success', 'Supplier Performance deleted successfully.');
    }

    public function edit(SupplierPerformance $supplierPerformance)
    {
        return view('edit', compact('supplierPerformance'));
    }

    public function update(Request $request, SupplierPerformance $supplierPerformance)
    {
        $request->validate([
            // Define your validation rules here
        ]);

        $supplierPerformance->update($request->all());

        return redirect()->route('data')->with('success', 'Supplier performance updated successfully.');
    }

    // New method to show the comparison form
    public function compare()
    {
        // Retrieve suppliers with performance data
        $suppliersWithPerformances = SupplierPerformance::distinct()->pluck('supplier_id');

        // Retrieve suppliers based on the subquery
        $suppliers = Supplier::whereIn('id', $suppliersWithPerformances)->get();

        // Check if any suppliers exist
        if ($suppliers->isEmpty()) {
            return redirect()->back()->with('error', 'No suppliers with performance data found.');
        }

        $supplierNames = $suppliers->pluck('name', 'id'); // Create an array with id as key and name as value

        return view('compare', compact('supplierNames'));
    }

    // New method to handle the comparison form submission and calculate AHP results
    public function storeComparison(Request $request)
    {
        $comparisonData = $request->all();
        $supplierPerformances = SupplierPerformance::all();

        // Initialize criteria importance dynamically
        $criteriaImportance = [];

        // Extract the criteria and calculate the importance sums
        foreach ($comparisonData as $key => $value) {
            if (strpos($key, 'importance') !== false) {
                // Extract criterion name from the key
                $parts = explode('_', $key);
                $criterionName = end($parts);

                if (!isset($criteriaImportance[$criterionName])) {
                    $criteriaImportance[$criterionName] = 0;
                }
                $criteriaImportance[$criterionName] += (int)$value;
            }
        }

        // Calculate total importance
        $totalImportance = array_sum($criteriaImportance);

        // Calculate weight percentage for each criterion
        $criteria = [];
        foreach ($criteriaImportance as $criterion => $importance) {
            $criteria[] = [
                'name' => ucfirst(str_replace('_', ' ', $criterion)),
                'importance' => $importance,
                'weightPercentage' => ($importance / $totalImportance) * 100,
            ];
        }

        // Calculate the AHP results
        $results = $this->calculateAHPResults($criteria, $comparisonData, $supplierPerformances);

        if (empty($results)) {
            return redirect()->back()->with('error', 'Error: Unable to calculate AHP results.');
        }

        // Calculate the decision matrix
        $results['decisionMatrix'] = $this->calculateDecisionMatrix(array_column($criteria, 'name'), $supplierPerformances);

        // Calculate priorities of suppliers
        $results['prioritiesOfSuppliers'] = $this->calculatePrioritiesOfSuppliers($results['priorities']);

        // Retrieve suppliers
        $suppliers = Supplier::whereIn('id', $supplierPerformances->pluck('supplier_id')->unique())->get()->keyBy('id');

        // Pass the data to the view including $supplierNames
        return view('comparison-results', [
            'results' => $results,
            'suppliers' => $suppliers,
            'criteria' => $criteria,
            'supplierNames' => $suppliers->pluck('name', 'id')->toArray(), // Convert to array
        ]);
    }

    // Helper method to calculate priorities of suppliers
    private function calculatePrioritiesOfSuppliers($priorities)
    {;
        // Calculate the sum of all priorities
        $totalPriority = array_sum($priorities);

        // If the total priority is zero, return an empty array
        if ($totalPriority == 0) {
            return [];
        }

        // Calculate normalized priorities for each supplier
        $normalizedPriorities = [];
        foreach ($priorities as $supplierId => $priority) {
            $normalizedPriorities[$supplierId] = round(($priority / $totalPriority) * 100, 2);
        }


        return $normalizedPriorities;
    }

    // Helper method to calculate the decision matrix
    private function calculateDecisionMatrix($criteria, $supplierPerformances)
    {
        $decisionMatrix = [];
        $supplierIds = $supplierPerformances->pluck('supplier_id')->unique()->toArray();

        foreach ($criteria as $criterion) {
            $criterionValues = $supplierPerformances->pluck($criterion, 'supplier_id')->toArray();
            $min = min($criterionValues);
            $max = max($criterionValues);

            foreach ($supplierIds as $supplierId) {
                if ($max - $min == 0) {
                    $normalizedValue = 1;
                } else {
                    $normalizedValue = ($criterionValues[$supplierId] - $min) / ($max - $min);
                }
                $decisionMatrix[$criterion][$supplierId] = $normalizedValue;
            }
        }

        return $decisionMatrix;
    }


    // Helper method to calculate pairwise comparisons
    private function calculatePairwiseComparisons($suppliers)
    {
        $pairwiseComparison = [];
        $totalScores = [];

        // Retrieve supplier performances
        $supplierPerformances = SupplierPerformance::all();

        // Populate total scores array
        foreach ($suppliers as $supplier) {
            $totalScores[$supplier->id] = 0;
        }

        // Calculate total performance scores for each supplier
        foreach ($supplierPerformances as $supplierPerformance) {
            $totalScores[$supplierPerformance->supplier_id] +=
                $supplierPerformance->product_defect +
                $supplierPerformance->delivery +
                $supplierPerformance->cost +
                $supplierPerformance->return_time;
        }

        // Iterate through each pair of suppliers to determine their relative importance
        foreach ($suppliers as $supplier1) {
            foreach ($suppliers as $supplier2) {
                if ($supplier1->id !== $supplier2->id) {
                    if (isset($totalScores[$supplier2->id]) && $totalScores[$supplier2->id] != 0) {
                        // Calculate the pairwise comparison ratio based on total performance scores
                        $ratio = $totalScores[$supplier1->id] / $totalScores[$supplier2->id];
                    } else {
                        // If the total score is zero, assign a default value
                        $ratio = isset($totalScores[$supplier1->id]) && $totalScores[$supplier1->id] != 0 ? PHP_INT_MAX : 1;
                    }

                    // Assign the ratio as the importance factor
                    $pairwiseComparison[$supplier1->id][$supplier2->id] = $ratio;
                } else {
                    // For self-comparisons, assign the importance as 1
                    $pairwiseComparison[$supplier1->id][$supplier2->id] = 1;
                }
            }
        }

        return $pairwiseComparison;
    }

    // Helper function to calculate AHP results
    private function calculateAHPResults($criteria, $comparisonData, $supplierPerformances)
    {
        // Initialize arrays to store normalized data and weighted sums
        $normalizedData = [];
        $weightedSums = [];
        $criteriaImportance = [];

        // Extract importance values from the criteria and calculate the total importance
        foreach ($criteria as $criterionData) {
            $criterionName = $criterionData['name'];
            $importance = (int) $criterionData['importance'];
            $criteriaImportance[$criterionName] = $importance;
        }

        $totalImportance = array_sum($criteriaImportance);

        // Check if there are no criteria or total importance is 0
        if (empty($criteriaImportance) || $totalImportance === 0) {
            return [];
        }

        // Normalize the performance values for each criterion
        foreach ($criteria as $criterionData) {
            $criterionName = $criterionData['name'];

            // Map the criterion names to the corresponding attribute names in the model
            $attributeName = $this->mapCriterionToAttribute($criterionName);
            $performanceValues = [];

            // Extract performance values from comparisonData
            foreach ($comparisonData as $key => $value) {
                if (strpos($key, 'importance') !== false) {
                    // Extract supplier IDs from the key
                    $parts = explode('_', $key);
                    $supplierId1 = $parts[1];
                    $supplierId2 = $parts[2];

                    // Calculate normalized value based on pairwise comparison
                    $normalizedValue = $value === '1' ? 1 : ($value == 2 ? 1 / $value : $value);

                    // Assign normalized value to performanceValues array
                    $performanceValues[$supplierId1][$supplierId2] = $normalizedValue;
                    $performanceValues[$supplierId2][$supplierId1] = 1 / $normalizedValue;
                }
            }

            // Calculate the weighted sum for each supplier
            foreach ($performanceValues as $supplierId => $values) {
                $weightedSum = 0;
                foreach ($values as $normalizedValue) {
                    $weightedSum += $normalizedValue;
                }
                $weightedSums[$supplierId] = $weightedSum;
            }
        }

        // Calculate priorities based on weighted sums
        $priorities = $this->calculatePriorities($weightedSums);

        // Calculate weight calculations
        $weightCalculations = $this->calculateWeightCalculations($criteriaImportance, $totalImportance);

        return [
            'priorities' => $priorities,
            'weightCalculations' => $weightCalculations,
        ];
    }

    private function mapCriterionToAttribute($criterionName)
    {
        $map = [
            'Defect' => 'product_defect',
            'Delivery' => 'delivery',
            'Cost' => 'cost',
            'Time' => 'return_time',
        ];

        return $map[$criterionName] ?? $criterionName;
    }

    private function calculateWeightCalculations($criteriaImportance, $totalImportance)
    {
        $weightCalculations = [];
        foreach ($criteriaImportance as $criterionName => $importance) {
            $weightPercentage = ($importance / $totalImportance) * 100;
            $weightCalculations[] = [
                'name' => $criterionName,
                'importance' => $importance,
                'weightPercentage' => round($weightPercentage, 2),
            ];
        }

        // Debugging: Print weight calculations
        // dd($weightCalculations);

        return $weightCalculations;
    }
    // Helper function to calculate priorities
    private function calculatePriorities($weightedSums)
    {
        $sumWeightedSums = array_sum($weightedSums);

        if ($sumWeightedSums == 0) {
            return [];
        }

        $priorities = [];
        foreach ($weightedSums as $supplierId => $weightedSum) {
            $priorities[$supplierId] = round(($weightedSum / $sumWeightedSums) * 100, 2);
        }

        // Debugging: Print priorities
        // dd($priorities);

        return $priorities;
    }

    public function showData(Request $request)
    {
        // Default year
        $defaultYear = 2023;
        
        // Retrieve selected year or use default year
        $selectedYear = $request->query('year', $defaultYear);
    
        // Retrieve all supplier performance data for charts
        $allSupplierPerformances = SupplierPerformance::with('supplier')->get();
    
        // Retrieve paginated supplier performance data for the table
        $supplierPerformances = SupplierPerformance::with('supplier')->paginate(10);
    
        // Initialize the data arrays
        $months = [];
        $productDefectsGroupedByMonth = [];
        $deliveryTimelinessGroupedByMonth = [];
        $costPerUnitGroupedByMonth = [];
        $returnTimeGroupedByMonth = [];
    
        // Fill the months array with month names
        for ($i = 1; $i <= 12; $i++) {
            $months[] = Carbon::create(null, $i, 1)->format('F');
        }
    
        // Get unique years from the performances data
        $years = $allSupplierPerformances->pluck('year')->unique()->values()->all();
    
        // Get unique supplier names from the performances data
        $supplierNames = $allSupplierPerformances->pluck('supplier.name')->unique()->values()->all();
    
        // Initialize empty arrays for each supplier, month, and year
        foreach ($supplierNames as $supplierName) {
            foreach ($years as $year) {
                $productDefectsGroupedByMonth[$year][$supplierName] = array_fill(0, 12, 0);
                $deliveryTimelinessGroupedByMonth[$year][$supplierName] = array_fill(0, 12, 0);
                $costPerUnitGroupedByMonth[$year][$supplierName] = array_fill(0, 12, 0);
                $returnTimeGroupedByMonth[$year][$supplierName] = array_fill(0, 12, 0);
            }
        }
    
        // Group data by year, month, and supplier
        foreach ($allSupplierPerformances as $performance) {
            $year = $performance->year;
            $monthIndex = Carbon::parse($performance->month)->month - 1;
            $supplierName = $performance->supplier->name;
    
            $productDefectsGroupedByMonth[$year][$supplierName][$monthIndex] += $performance->product_defect;
            $deliveryTimelinessGroupedByMonth[$year][$supplierName][$monthIndex] += $performance->delivery;
            $costPerUnitGroupedByMonth[$year][$supplierName][$monthIndex] += $performance->cost;
            $returnTimeGroupedByMonth[$year][$supplierName][$monthIndex] += $performance->return_time;
        }
    
        // Convert associative arrays to indexed arrays for the view
        $productDefectsGroupedByMonth = array_map('array_values', $productDefectsGroupedByMonth);
        $deliveryTimelinessGroupedByMonth = array_map('array_values', $deliveryTimelinessGroupedByMonth);
        $costPerUnitGroupedByMonth = array_map('array_values', $costPerUnitGroupedByMonth);
        $returnTimeGroupedByMonth = array_map('array_values', $returnTimeGroupedByMonth);
    
        return view('data', compact(
            'supplierPerformances',
            'months',
            'supplierNames',
            'years',
            'selectedYear',
            'productDefectsGroupedByMonth',
            'deliveryTimelinessGroupedByMonth',
            'costPerUnitGroupedByMonth',
            'returnTimeGroupedByMonth'
        ));
    }
    
    
    
}
