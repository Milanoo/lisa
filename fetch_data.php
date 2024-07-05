<?php
$fiscal_years = [1 => 'FY 2076/77', 2 => 'FY 2077/78', 3 => 'FY 2078/79', 4 => 'FY 2079/80']; // Example fiscal years

function fetchData($fiscalYear) {
    $apiUrl = "https://lisa.mofaga.gov.np/backend/api/reports/summary?fiscal_year_id=" . $fiscalYear;
    $response = file_get_contents($apiUrl);
    if ($response === FALSE) {
        die('Error fetching data');
    }
    return $response;
}

function saveData($fiscalYear, $data) {
    $filePath = __DIR__ . "/data/LISA_summary_fiscal_year_{$fiscalYear}.json";
    file_put_contents($filePath, $data);
}

if (isset($_GET['fetch_data']) && $_GET['fetch_data'] === '1') {
    $fiscalYearsToFetch = isset($_GET['fiscal_year_id']) ? [$_GET['fiscal_year_id']] : array_keys($fiscal_years);

    foreach ($fiscalYearsToFetch as $fiscalYear) {
        $data = fetchData($fiscalYear);
        saveData($fiscalYear, $data);
    }

    $response = [];
    foreach ($fiscal_years as $fiscalYear => $yearName) {
        $file_path = __DIR__ . "/data/LISA_summary_fiscal_year_{$fiscalYear}.json";
        if (file_exists($file_path)) {
            $file_size = filesize($file_path);
            $updated_at = date("Y-m-d H:i:s", filemtime($file_path));
            $response[] = [
                'id' => $fiscalYear,
                'name' => "LISA_summary_fiscal_year_{$fiscalYear}.json",
                'fy' => $yearName,
                'size' => round($file_size / 1024, 2) . ' KB',
                'updated_at' => $updated_at
            ];
        }
    }

    echo json_encode($response);
    exit;
}
?>
