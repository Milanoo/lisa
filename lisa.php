<?php include('top.php') ?>
<?php
    // // Fetch the JSON data from the API
    // $fiscal_year_id = isset($_GET['fiscal_year_id']) ? $_GET['fiscal_year_id'] : 4;
    // $json_url = "https://lisa.mofaga.gov.np/backend/api/reports/summary?fiscal_year_id=". $fiscal_year_id;
    // $json_response = file_get_contents($json_url);
    // $data = json_decode($json_response, true);

    // // Extract provinces and districts for filters
    // $provinces = [];
    // $districts = [];
    // $fiscal_years = [1 => 'FY 2076/77', 2 => 'FY 2077/78', 3 => 'FY 2078/79', 4 => 'FY 2079/80']; // Example fiscal years

    // foreach ($data['response'] as $item) {
    //     $provinces[$item['province']] = $item['province'];
    //     $districts[$item['province']][$item['district']] = $item['district'];
    // }
?>
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

    // Extract provinces and districts for filters
    $provinces = [];
    $districts = [];
    $fiscal_years = [
        1 => 'FY 2076/77', 
        2 => 'FY 2077/78', 
        3 => 'FY 2078/79', 
        4 => 'FY 2079/80'
    ]; // Example fiscal years

    foreach ($data['response'] as $item) {
        $provinces[$item['province']] = $item['province'];
        $districts[$item['province']][$item['district']] = $item['district'];
    }
?>

<body id="page-top">
    <div id="wrapper">
        <?php include('sidebar.php') ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div class="content">
                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- selection filter row -->
                    <div class="row">
                        <form id="filterForm" class="row g-3">
                            <!-- Page Heading -->
                            <div class="col-xl-3 col-md-6 mb-4">
                                <h1 class="h3 mb-0 text-gray-800">Dashboard - <span id="selectedUnit"></span></h1>
                            </div>
                            <div class="col-xl-3 col-md-6 mb-4">
                            <label for="fiscal_year" class="form-label">Select Fiscal Year:</label>
                                <select id="fiscal_year" name="fiscal_year" class="form-select">
                                    <?php foreach ($fiscal_years as $id => $fy): ?>
                                        <option value="<?php echo $id; ?>" <?php if ($id == $fiscal_year_id) echo 'selected'; ?>><?php echo $fy; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-xl-3 col-md-6 mb-4">
                            <label for="province" class="form-label">Select Province:</label>
                                <select id="province" name="province" class="form-select">
                                    <option value="">All Provinces</option>
                                    <?php foreach ($provinces as $province): ?>
                                        <option value="<?php echo $province; ?>" <?php if ($province == 'लुम्बिनी प्रदेश') echo 'selected'; ?>><?php echo $province; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-xl-3 col-md-6 mb-4">
                            <label for="district" class="form-label">Select District:</label>
                                <select id="district" name="district" class="form-select">
                                    <option value="">All Districts</option>
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="row">
                        <!-- total lg -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card">
                                <div class="card-body row">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total LGs</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalLGs">0</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fa fa-building fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Province Score -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card">
                                <div class="card-body row">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Average Score (Province)</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="avgScoreProvince">0</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fa fa-map fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- District Score -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card">
                                <div class="card-body row">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Average Score (District)</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="avgScoreDistrict">0</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fa fa-globe fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Top Score -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card">
                                <div class="card-body row">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">LG Scoring Highest</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="highestScoreLG">0</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fa fa-trophy fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Card row end here -->

                    <!-- CHART START -->
                    <div class="row">
                        <div class="col-xl-8 col-lg-7">
                            <div class="card shadow">
                                <div class="card-header">
                                    <h6 class="m-0 font-weight-bold text-primary">Local Government Scores</h6>  
                                </div>
                                <div class="card-body">
                                    <div class="chart-area">
                                        <canvas id="barChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-7">
                            <div class="card shadow">
                                <div class="card-header">
                                    <h6 class="m-0 font-weight-bold text-primary">Category</h6>  
                                </div>
                                <div class="card-body">
                                    <div class="chart-area">
                                        <canvas id="categoriesChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- chart row end here -->
                    <?php
                    $headers = [
                        'शासकीय प्रवन्ध', 'संगठन तथा प्रशासन', 'वार्षिक बजेट तथा योजना', 'वित्तीय एवम् आर्थिक',
                        'सेवा प्रवाह', 'न्यायिक कार्य सम्पादन', 'भौतिक पूर्वाधार', 'सामाजिक समावेशीकरण',
                        'वातावरण संरक्षण तथा विपद', 'सहकार्य र समन्वय'
                    ];
                    ?>
                    <!-- Table row -->
                    <div class="row" style="margin-top: 20px">
                        <div class="col-lg-12 mb-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="m-0 font-weight-bold text-primary">Local Government Score</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-striped sortable">
                                        <thead>
                                            <tr>
                                                <th>LG Name</th>
                                                <?php foreach ($headers as $header): ?>
                                                    <th><?php echo $header; ?></th>
                                                <?php endforeach; ?>
                                            </tr>
                                        </thead>
                                        <tbody id="lgTableBody">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- table row end here -->
                </div>
                <!-- fluid container end here -->
            </div>
            <!-- End of Main Content -->
        </div>
        <!-- End of  Wrapper -->
    </div>
</body>
</html>
<script>
// Fetch and display data on fiscal year change
document.getElementById('fiscal_year').addEventListener('change', function () {
    var fiscalYear = this.value;
    window.location.href = window.location.pathname + '?fiscal_year_id=' + fiscalYear;
});
document.addEventListener('DOMContentLoaded', function() {
    const provinces = <?php echo json_encode($provinces); ?>;
    const districts = <?php echo json_encode($districts); ?>;
    const data = <?php echo json_encode($data['response']); ?>;

    const totalLGsDisplay = document.getElementById('totalLGs');
    const provinceSelect = document.getElementById('province');
    const districtSelect = document.getElementById('district');
    const averageScoreDisplayProvince = document.getElementById('avgScoreProvince');
    const averageScoreDisplayDistrict = document.getElementById('avgScoreDistrict');
    const highestScoreLGDisplay = document.getElementById('highestScoreLG');
    const selectedUnitDisplay = document.getElementById('selectedUnit');
    const categoriesChartCtx = document.getElementById('categoriesChart').getContext('2d');
    const barChartCtx = document.getElementById('barChart').getContext('2d');

    let categoriesChart, barChart;

    provinceSelect.addEventListener('change', updateDistricts);
    document.getElementById('filterForm').addEventListener('change', updateData);

    function updateDistricts() {
        const selectedProvince = provinceSelect.value;
        districtSelect.innerHTML = '<option value="">All Districts</option>';

        if (selectedProvince && districts[selectedProvince]) {
            Object.keys(districts[selectedProvince]).forEach(district => {
                const option = document.createElement('option');
                option.value = district;
                option.textContent = district;
                districtSelect.appendChild(option);
            });
        }
    }

    function updateData() {
        const selectedProvince = provinceSelect.value;
        const selectedDistrict = districtSelect.value;

        let filteredData = data;
        // let selectedUnit = Nepal;
        if (selectedProvince) {
            filteredData = filteredData.filter(item => item.province === selectedProvince);
            let provinceData = filteredData.filter(item => item.province === selectedProvince);
            let totalScoreProvince = 0;
            let validScoresCount = 0;
            
            provinceData.forEach(item => {
                if (!isNaN(item.score) && item.score !== null) {
                    totalScoreProvince += parseFloat(item.score);
                    validScoresCount++;
                }
            });
            
            const averageScoreProvince = validScoresCount ? (totalScoreProvince / validScoresCount).toFixed(2) : '0.00';
            averageScoreDisplayProvince.textContent = averageScoreProvince;
            selectedUnitDisplay.textContent=selectedProvince;
        }
        if (selectedDistrict) {
            filteredData = filteredData.filter(item => item.district === selectedDistrict);
            
            // Calculate average score
            let totalScoreDistrict = 0;
            let validScoresCountDistrict = 0;

            filteredData.forEach(item => {
                if (!isNaN(item.score) && item.score !== null) {
                    totalScoreDistrict += parseFloat(item.score);
                    validScoresCountDistrict++;
                }
            });

            const averageScoreDistrict = validScoresCountDistrict ? (totalScoreDistrict / validScoresCountDistrict).toFixed(2) : '0.00';
            averageScoreDisplayDistrict.textContent = averageScoreDistrict;
            selectedUnitDisplay.textContent=selectedDistrict;
        }

        // Calculate total LGs
        const totalLGs = filteredData.length;
        totalLGsDisplay.textContent = totalLGs;

         // Calculate average score for province and district
        let totalScore = 0;
        let highestScore = 0;
        let highestScoreLG = 'N/A';

        filteredData.forEach(item => {
            totalScore += item.score;
            if (item.score > highestScore) {
                highestScore = item.score;
                highestScoreLG = `${item.name} (${item.score})`;
            }
        });
        highestScoreLGDisplay.textContent = highestScoreLG;

       
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
        // function calculatePercentageScores(scores) {
        //     return Object.keys(scores).map(key => {
        //         const obtained = scores[key];
        //         const full = categoryFullScores[key];
        //         return (obtained / full) * 100;
        //     });
        // }
        function calculatePercentageScores(scores) {
            const percentageScores = [];
            for (let i = 0; i < 10; i++) {
                // const key = i.toString();
                const obtained = scores[i];
                const full = categoryFullScores[i+1];
                const percentage = (obtained / full) * 100;
                percentageScores.push(percentage.toFixed(2));
            }
            return percentageScores;
        }

        // Calculate average category scores
        const categorySums = {};
        const categoryCounts = {};
        for (let i = 1; i <= 10; i++) {
            categorySums[i] = 0;
            categoryCounts[i] = 0;
        }

        filteredData.forEach(item => {
            for (let i = 1; i <= 10; i++) {
                if (item.categories[i] !== undefined || item.categories[i]!=='N/A' || item.categories[i] !== null) {
                    categorySums[i] += item.categories[i];
                    categoryCounts[i]++;
                }
            }
        });
        const averageCategoryScores = [];
        for (let i = 1; i <= 10; i++) {
            averageCategoryScores.push(categoryCounts[i] ? (categorySums[i] / categoryCounts[i]).toFixed(2) : 0);
        }
        console.log(averageCategoryScores);
        console.log(calculatePercentageScores(averageCategoryScores));
        // Render radar chart
        if (categoriesChart) {
            categoriesChart.destroy();
        }
        // Chart.register(ChartDataLabels);
        categoriesChart = new Chart(categoriesChartCtx, {
            type: 'radar',
            data: {
                labels: ['शासकीय प्रवन्ध', 'संगठन तथा प्रशासन', 'वार्षिक बजेट तथा योजना', 'वित्तीय एवम् आर्थिक', 'सेवा प्रवाह', 'न्यायिक कार्य सम्पादन', 'भौतिक पूर्वाधार', 'सामाजिक समावेशीकरण', 'वातावरण संरक्षण तथा विपद', 'सहकार्य र समन्वय'],
                datasets: [{
                    label: 'Average Category Scores',
                    data: calculatePercentageScores(averageCategoryScores),
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    r: {
                        angleLines: {
                            display: true
                        },
                        suggestedMin: 0,
                        suggestedMax: 100
                    }
                }
            }
        });

        // Update table
        lgTableBody.innerHTML = '';
        filteredData.forEach(item => {
            const row = document.createElement('tr');
            const nameCell = document.createElement('td');
            nameCell.textContent = item.name;
            row.appendChild(nameCell);

            for (let i = 1; i <= 10; i++) {
                const cell = document.createElement('td');
                cell.textContent = item.categories[i] !== undefined ? item.categories[i] : 'N/A';
                row.appendChild(cell);
            }

            lgTableBody.appendChild(row);
        });

        // Render bar chart
        const labels = filteredData.map(item => item.name);
        const scores = filteredData.map(item => item.score);

        if (barChart) {
            barChart.destroy();
        }
        barChart = new Chart(barChartCtx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Scores of Local Governments',
                    data: scores,
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        
    }

    // Initial data update
    updateData();
});
</script>
