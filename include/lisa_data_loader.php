<?php
// Set the default fiscal year
$fiscal_year_id = isset($_GET['fiscal_year_id']) ? $_GET['fiscal_year_id'] : 6;

// Define the file path based on the fiscal year
$file_path = dirname(__DIR__) . "/data/LISA_summary_fiscal_year_{$fiscal_year_id}.json";

// Check if the file exists
if (file_exists($file_path)) {
    // Fetch the data from the local JSON file
    $json_response = file_get_contents($file_path);
    $data = json_decode($json_response, true);
} else {
    die('Error: Data file not found.');
}
// Extract provinces, districts, and LGs for filters
$provinces = [];
$districts = [];
$lgs = [];
$fiscal_years = [
    1 => 'FY 2076/77', 
    2 => 'FY 2077/78', 
    3 => 'FY 2078/79', 
    4 => 'FY 2079/80',
    6 => 'FY 2080/81'
];

foreach ($data['response'] as $item) {
    $provinces[$item['province']] = $item['province'];
    $districts[$item['province']][$item['district']] = $item['district'];
    $lgs[$item['district']][$item['name']] = $item['name'];
}
?>