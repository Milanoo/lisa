<?php
// Set the default fiscal year
$fiscal_year_id = isset($_GET['fiscal_year_id']) ? $_GET['fiscal_year_id'] : 4;

// Define the file path based on the fiscal year
$file_path = __DIR__ . "/data/LISA_summary_fiscal_year_{$fiscal_year_id}.json";

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
$fiscal_years = [1 => 'FY 2076/77', 2 => 'FY 2077/78', 3 => 'FY 2078/79', 4 => 'FY 2079/80']; // Example fiscal years

foreach ($data['response'] as $item) {
    $provinces[$item['province']] = $item['province'];
    $districts[$item['province']][$item['district']] = $item['district'];
    $lgs[$item['district']][$item['name']] = $item['name'];
}
?>
<?php include('top.php') ?>
<body id="page-top">
    <div id="wrapper" style="width:100%">
        <?php include('sidebar.php') ?>

        <!-- Content Start here -->
        <div id="content-wrapper" class="d-flex flex-column">
            <div class="content">
                <div class="container-fluid">
                    <!-- Selection Groups 1-->
                    <div class="row">
                        <div class="col-xl-3 col-md-6 mb-4">
                            <label for="fiscalYearSelect1" class="form-label">Fiscal Year:</label>
                            <select id="fiscalYearSelect1" class="form-select">
                                <?php foreach ($fiscal_years as $id => $name): ?>
                                    <option value="<?php echo $id; ?>" <?php if ($id == $fiscal_year_id) echo 'selected'; ?>><?php echo $name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-4">
                            <label for="provinceSelect1" class="form-label">Province:</label>
                            <select id="provinceSelect1" class="form-select">
                                <?php foreach ($provinces as $province): ?>
                                    <option value="<?php echo $province; ?>" <?php if ($province == 'लुम्बिनी प्रदेश') echo 'selected'; ?>><?php echo $province; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-4">
                            <label for="districtSelect1" class="form-label">District:</label>
                            <select id="districtSelect1" class="form-select">
                                <!-- Options will be dynamically loaded -->
                            </select>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-4">
                            <label for="lgSelect1" class="form-label">LG:</label>
                            <select id="lgSelect1" class="form-select">
                                <!-- Options will be dynamically loaded -->
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-3 col-md-6 mb-4">
                            <label for="fiscalYearSelect2" class="form-label">Fiscal Year:</label>
                            <select id="fiscalYearSelect2" class="form-select">
                                <?php foreach ($fiscal_years as $id => $name): ?>
                                    <option value="<?php echo $id; ?>" <?php if ($id == $fiscal_year_id) echo 'selected'; ?>><?php echo $name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div class="col-xl-3 col-md-6 mb-4">
                        <div class="col-xl-3 col-md-6 mb-4">
                            <label for="provinceSelect2" class="form-label">Province:</label>
                            <select id="provinceSelect2" class="form-select">
                                <?php foreach ($provinces as $province): ?>
                                    <option value="<?php echo $province; ?>" <?php if ($province == 'गण्डकी प्रदेश') echo 'selected'; ?>><?php echo $province; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-4">
                            <label for="districtSelect2" class="form-label">District:</label>
                            <select id="districtSelect2" class="form-select">
                                <!-- Options will be dynamically loaded -->
                            </select>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-4">
                            <label for="lgSelect2" class="form-label">LG:</label>
                            <select id="lgSelect2" class="form-select">
                                <!-- Options will be dynamically loaded -->
                            </select>
                        </div>
                    </div>

                    <!-- ................................................. -->
                      <!-- Radar Chart -->
                      <div class="row">
                        <div class="col-xl-6 col-md-6 mb-4">
                            <div class="card shadow">
                                <div class="card-header">
                                    <h6 class="m-0 font-weight-bold text-primary">Category Scores Comparison</h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="radarChart"></canvas>
                                </div>
                            </div>
                        </div>
                        <!-- Comparison Table -->
                        <div class="col-xl-6 col-md-6 mb-4">
                            <div class="card shadow">
                                <div class="card-header">
                                    <h6 class="m-0 font-weight-bold text-primary">Comparison Table</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-striped sortable" style="font-size:14px">
                                        <thead>
                                            <tr>
                                                <th>विषय क्षेत्र</th>
                                                <th id="lgName1"></th>
                                                <th id="lgName2"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="comparisonTableBody">
                                            <!-- Table rows will be dynamically added here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                      </div>
                      
                        
                                        
                    <!-- chart end here........................................................... -->
                </div>
                <!-- container fluid end -->
            </div>
            <!-- Content End -->
        </div>
    </div>
</body>
</html>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const fiscalYearSelect1 = document.getElementById('fiscalYearSelect1');
    const provinceSelect1 = document.getElementById('provinceSelect1');
    const districtSelect1 = document.getElementById('districtSelect1');
    const lgSelect1 = document.getElementById('lgSelect1');
    
    const fiscalYearSelect2 = document.getElementById('fiscalYearSelect2');
    const provinceSelect2 = document.getElementById('provinceSelect2');
    const districtSelect2 = document.getElementById('districtSelect2');
    const lgSelect2 = document.getElementById('lgSelect2');
    
    const radarChartCtx = document.getElementById('radarChart').getContext('2d');
    const comparisonTableBody = document.getElementById('comparisonTableBody');
    const lgName1Display = document.getElementById('lgName1');
    const lgName2Display = document.getElementById('lgName2');

    
    let radarChart;

    const districts = <?php echo json_encode($districts); ?>;
    const lgs = <?php echo json_encode($lgs); ?>;

    function populateDistricts(provinceSelect, districtSelect) {
        const province = provinceSelect.value;
        const districtOptions = districts[province] ? Object.values(districts[province]).map(district => `<option value="${district}">${district}</option>`).join('') : '';
        districtSelect.innerHTML = districtOptions;
        districtSelect.dispatchEvent(new Event('change'));
    }

    function populateLgs(districtSelect, lgSelect) {
        const district = districtSelect.value;
        const lgOptions = lgs[district] ? Object.values(lgs[district]).map(lg => `<option value="${lg}">${lg}</option>`).join('') : '';
        lgSelect.innerHTML = lgOptions;
    }

    provinceSelect1.addEventListener('change', () => populateDistricts(provinceSelect1, districtSelect1));
    districtSelect1.addEventListener('change', () => populateLgs(districtSelect1, lgSelect1));
    
    provinceSelect2.addEventListener('change', () => populateDistricts(provinceSelect2, districtSelect2));
    districtSelect2.addEventListener('change', () => populateLgs(districtSelect2, lgSelect2));

    function fetchData(fiscalYear) {
        const localUrl = `data/LISA_summary_fiscal_year_${fiscalYear}.json`;
        return fetch(localUrl)
            .then(response => response.json())
            .catch(error => {
                console.error('Error fetching data:', error);
            });
    }


    function filterData(data, lg) {
        return data.response.find(item => item.name === lg);
    }
    function updateComparison() {
            const fiscalYear1 = fiscalYearSelect1.value;
            const fiscalYear2 = fiscalYearSelect2.value;
            const lg1 = lgSelect1.value;
            const lg2 = lgSelect2.value;
            // alert(lg1);

            Promise.all([
                fetchData(fiscalYear1),
                fetchData(fiscalYear2)
            ]).then(([data1, data2]) => {
                const lgData1 = filterData(data1, lg1);
                const lgData2 = filterData(data2, lg2);
                // alert(lgData2.categories);
                // Update radar chart
                updateRadarChart(lgData1, lgData2);

                // Update comparison table
                updateComparisonTable(lgData1, lgData2);
            }).catch(error => {
                console.error('Error fetching data:', error);
            });
        }

        function updateRadarChart(lgData1, lgData2) {
            // const labels = Object.keys(lgData1.scores); // Assuming scores are under 'scores' key in the response

            if (radarChart) {
                radarChart.destroy();
            }

            radarChart = new Chart(radarChartCtx, {
                type: 'radar',
                data: {
                    labels: ['शासकीय प्रवन्ध', 'संगठन तथा प्रशासन', 'वार्षिक बजेट तथा योजना', 'वित्तीय एवम् आर्थिक', 'सेवा प्रवाह', 'न्यायिक कार्य सम्पादन', 'भौतिक पूर्वाधार', 'सामाजिक समावेशीकरण', 'वातावरण संरक्षण तथा विपद', 'सहकार्य र समन्वय'],
                    datasets: [
                        {
                            label: lgData1.name, // LG name or identifier
                            data: calculatePercentageScores(lgData1.categories),
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        },
                        {
                            label: lgData2.name, // LG name or identifier
                            data: calculatePercentageScores(lgData2.categories),
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    scales: {
                        r: {
                            angleLines: {
                                display: true
                            },
                            suggestedMin: 0,
                            suggestedMax: 12
                        }
                    }
                }
            });

            lgName1Display.textContent = lgData1.name;
            lgName2Display.textContent = lgData2.name;
        }

        const categoryNames = [
            'शासकीय प्रवन्ध', 
            'संगठन तथा प्रशासन', 
            'वार्षिक बजेट तथा योजना', 
            'वित्तीय एवम् आर्थिक', 
            'सेवा प्रवाह', 
            'न्यायिक कार्य सम्पादन', 
            'भौतिक पूर्वाधार', 
            'सामाजिक समावेशीकरण', 
            'वातावरण संरक्षण तथा विपद', 
            'सहकार्य र समन्वय'
        ];
        const categoryFullScores = {
            1: 9,
            2: 8,
            3: 11,
            4: 11,
            5: 16,
            6: 7,
            7: 13,
            8: 10,
            9: 9,
            10: 6
        };
        function calculatePercentageScores(scores) {
            return Object.keys(scores).map(categoryIndex => {
                const obtained = scores[categoryIndex];
                const full = categoryFullScores[categoryIndex];
                return (obtained / full) * 100;
            });
        }
        
        function updateComparisonTable(lgData1, lgData2) {
            const labels = Object.keys(lgData1.categories);

            const comparisonTableRows = labels.map(label => {
                const categoryIndex = parseInt(label) - 1;
                const categoryName = categoryNames[categoryIndex];

                const score1 = lgData1.categories[label];
                const score2 = lgData2.categories[label];

                const arrow1 = score1 > score2 ? '↑' : score1 < score2 ? '↓' : '';
                const bg1 = score1 > score2 ? '#e6f4ea' : score1 < score2 ? '#fce8e6' : 'none';
                const cl1 = score1 > score2 ? '#137333' : score1 < score2 ? '#a50e0e' : 'grey';
                
                const arrow2 = score2 > score1 ? '↑' : score2 < score1 ? '↓' : '';
                const bg2 = score2 > score1 ? '#e6f4ea' : score2 < score1 ? '#fce8e6' : 'none';
                const cl2 = score2 > score1 ? '#137333' : score2 < score1 ? '#a50e0e' : 'grey';

                return `
                    <tr>
                        <td>${categoryName}</td>
                        <td style="text-align:right"><span style="border-radius:8px; padding:5px 10px; background-color: ${bg1}; color:${cl1}">${arrow1} ${score1}</span></td>
                        <td style="text-align:right"><span style="border-radius:8px; padding:5px 10px; background-color: ${bg2}; color:${cl2}">${arrow2} ${score2}</span></td>
                    </tr>
                `;
            });


            comparisonTableBody.innerHTML = comparisonTableRows.join('');
        }

        // Initial update on page load
        // updateComparison();

        // Event listeners for select changes
        fiscalYearSelect1.addEventListener('change', updateComparison);
        provinceSelect1.addEventListener('change', updateComparison);
        districtSelect1.addEventListener('change', updateComparison);
        lgSelect1.addEventListener('change', updateComparison);

        fiscalYearSelect2.addEventListener('change', updateComparison);
        provinceSelect2.addEventListener('change', updateComparison);
        districtSelect2.addEventListener('change', updateComparison);
        lgSelect2.addEventListener('change', updateComparison);

        // just for initial load.
        provinceSelect1.dispatchEvent(new Event('change'));
        provinceSelect2.dispatchEvent(new Event('change'));
    });
</script>


