<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ComparisonController extends Controller
{
    // Handle the submission of the criteria form
    public function storeCriteria(Request $request)
    {
        $criteria = explode(',', $request->input('criteria'));
        $criteria = array_map('trim', $criteria);

        return view('comparison', compact('criteria'));
    }

    // Handle the submission of the pairwise comparison form
    public function storeComparison(Request $request)
    {
        $criteria = json_decode($request->input('criteria'), true);
        $pairwiseComparison = $request->except('_token', 'criteria'); // Get all form data except token and criteria

        // Calculate AHP results
        $results = $this->calculateAHPResults($criteria, $pairwiseComparison);

        // Redirect to results page with calculated data
        return view('results', $results);
    }

    // Helper function to calculate AHP results
    private function calculateAHPResults($criteria, $pairwiseComparison)
    {
        $n = count($criteria);
        $decisionMatrix = [];

        // Construct decision matrix from pairwise comparison data
        for ($i = 0; $i < $n; $i++) {
            $row = [];
            for ($j = 0; $j < $n; $j++) {
                if ($i == $j) {
                    $row[] = 1; // Diagonal elements are always 1
                } elseif ($i < $j) {
                    $keyImportance = "importance_{$i}_{$j}";
                    $keyScale = "scale_{$i}_{$j}";
                    if ($pairwiseComparison[$keyImportance] == 'equal') {
                        $row[] = 1;
                    } else {
                        $value = $pairwiseComparison[$keyImportance] == $i ? $pairwiseComparison[$keyScale] : 1 / $pairwiseComparison[$keyScale];
                        $row[] = $value;
                    }
                } else {
                    $row[] = 1 / $decisionMatrix[$j][$i]; // Fill lower triangular matrix
                }
            }
            $decisionMatrix[] = $row;
        }

        // Calculate priorities from the decision matrix
        $priorities = [];
        for ($i = 0; $i < $n; $i++) {
            $priorities[] = array_product($decisionMatrix[$i]) ** (1 / $n);
        }

        // Normalize priorities
        $totalPriority = array_sum($priorities);
        foreach ($priorities as &$priority) {
            $priority = $priority / $totalPriority;
        }

        // Calculate lambda max
        $lambdaMax = 0;
        for ($i = 0; $i < $n; $i++) {
            $weightedSum = 0;
            for ($j = 0; $j < $n; $j++) {
                $weightedSum += $decisionMatrix[$i][$j] * $priorities[$j];
            }
            $lambdaMax += $weightedSum / $priorities[$i];
        }
        $lambdaMax /= $n;

        // Calculate Consistency Index (CI)
        $CI = ($lambdaMax - $n) / ($n - 1);

        // Predefined Random Index (RI) values
        $RIValues = [0.00, 0.00, 0.58, 0.90, 1.12, 1.24, 1.32, 1.41, 1.45, 1.49]; // for n = 1 to 10
        $RI = $RIValues[$n];

        // Calculate Consistency Ratio (CR)
        $CR = $CI / $RI;

        // Generate random min and max values for illustration
        $minValues = array_map(function($priority) { return $priority * 0.8; }, $priorities);
        $maxValues = array_map(function($priority) { return $priority * 1.2; }, $priorities);

        // Prepare the data for display
        $data = [
            'criteria' => $criteria,
            'decisionMatrix' => $decisionMatrix,
            'priorities' => $priorities,
            'minValues' => $minValues,
            'maxValues' => $maxValues,
            'CR' => $CR,
            'pairwiseComparison' => $pairwiseComparison // Pass pairwiseComparison to the view
        ];

        // Return the data to the results view
        return $data;
    }

    
}
