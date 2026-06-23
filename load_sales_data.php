<?php
// Path to your cleaned CSV file - adjust if your folder structure is different
$csvFile = __DIR__ . '/data/monthly_sales.csv';

$months = [];  // This will hold month indexes: 1, 2, 3, ...
$sales = [];   // This will hold corresponding Total Sales numbers

// Open the CSV file
if (($handle = fopen($csvFile, 'r')) !== FALSE) {
    // Skip the header row
    fgetcsv($handle);

    $startDate = new DateTime('2022-01');  // The first month in your data; adjust if necessary

    // Loop through each row
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $dateStr = $data[0];         // Date column - example: "2022-01"
        $totalSalesStr = $data[1];   // Sales amount column - example: "136438.16"
        
        // Remove any commas in sales amount (just in case)
        $totalSalesClean = floatval(str_replace(',', '', $totalSalesStr));
        
        // Convert date string to DateTime object
        $currentDate = DateTime::createFromFormat('Y-m', $dateStr);
        if (!$currentDate) {
            // Skip rows with invalid dates
            continue;
        }
        
        // Calculate the month index as a number starting at 1 for Jan 2022
        $diff = $startDate->diff($currentDate);
        $monthIndex = $diff->y * 12 + $diff->m + 1;

        // Store results in arrays
        $months[] = $monthIndex;
        $sales[] = $totalSalesClean;
    }
    fclose($handle);
} else {
    die('Error: Unable to open the CSV file.');
}

// Optional: print arrays to verify data loaded correctly
echo "Month indexes:\n";
print_r($months);

echo "Sales amounts:\n";
print_r($sales);

// --- Model Training and Forecasting ---

require __DIR__ . '/vendor/autoload.php';

use Phpml\Regression\LeastSquares;

// Convert month indexes to arrays of arrays as PHP-ML expects
$samples = array_map(fn($m) => [$m], $months);

// Initialize and train regression
$regression = new LeastSquares();
$regression->train($samples, $sales);

// Predict next 6 months after last month
$lastMonthIndex = end($months);
$futureMonths = [];
for ($i = 1; $i <= 6; $i++) {
    $futureMonths[] = $lastMonthIndex + $i;
}

$forecastedSales = [];
foreach ($futureMonths as $monthIndex) {
    $forecastedSales[] = $regression->predict([$monthIndex]);
}

// Output forecast
echo "Forecasted sales for next 6 months:\n";
foreach ($futureMonths as $key => $monthIndex) {
    echo "Month $monthIndex: " . number_format($forecastedSales[$key], 2) . "\n";
}

?>
