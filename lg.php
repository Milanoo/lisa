<?php include('top.php') ?>
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
<body>
    <div id="wrapper" style="width:100%">
        <?php include('sidebar.php') ?>
        <div id="content-wrapper">
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-xl-3 col-md-6 mb-4">
                            <h1 class="h3 mb-0 text-gray-800">LG Analysis<span id="selectedUnit"></span></h1>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-4">
                            <select id="provinceSelect" class="form-select">
                                <?php foreach ($provinces as $province): ?>
                                    <option value="<?php echo $province; ?>" <?php if ($province == 'लुम्बिनी प्रदेश') echo 'selected'; ?>><?php echo $province; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-4">
                            <select id="districtSelect" class="form-select">
                                <!-- District options will be populated dynamically -->
                            </select>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-4">
                            <select id="lgSelect" class="form-select">
                                <!-- LG options will be populated dynamically -->
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-8 col-lg-7">
                            <div class="card">
                                <div class="card-header">
                                <h6 class="m-0 font-weight-bold text-primary" id="lgName">LG Name</h6> 
                                </div>
                                <div class="card-body">
                                    <canvas id="averageChart" width="400" height="200"></canvas>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<script>
        document.addEventListener('DOMContentLoaded', () => {
            const provinceSelect = document.getElementById('provinceSelect');
            const districtSelect = document.getElementById('districtSelect');
            const lgSelect = document.getElementById('lgSelect');
            const loadChartBtn = document.getElementById('loadChartBtn');
            const lgName = document.getElementById('lgName');
            const averageChartCtx = document.getElementById('averageChart').getContext('2d');
            let averageChart;

            const districts = <?php echo json_encode($districts); ?>;
            const lgs = <?php echo json_encode($data['response']); ?>;
            const fiscalYears = <?php echo json_encode($fiscal_years); ?>;

            const populateOptions = (data, select) => {
                select.innerHTML = Object.keys(data).map(key => `<option value="${key}">${key}</option>`).join('');
            };

            const populateLgs = (district) => {
                const lgOptions = lgs.filter(lg => lg.district === district).map(lg => `<option value="${lg.name}">${lg.name}</option>`).join('');
                lgSelect.innerHTML = lgOptions;
            };

            // populateOptions(districts, provinceSelect);

            provinceSelect.addEventListener('change', () => {
                const selectedProvince = provinceSelect.value;
                populateOptions(districts[selectedProvince], districtSelect);
                districtSelect.dispatchEvent(new Event('change'));
            });

            districtSelect.addEventListener('change', () => {
                const selectedDistrict = districtSelect.value;
                populateLgs(selectedDistrict);
            });

            const fetchData = (fiscalYear) => fetch(`data/LISA_summary_fiscal_year_${fiscalYear}.json`)
                .then(response => response.json())
                .catch(error => console.error('Error fetching data:', error));

                const calculateAverage = (data) => {
                    // Filter out items where score is not "N/A"
                    const validScores = data.filter(item => item.score !== "N/A");

                    // Calculate the total sum of valid scores
                    const total = validScores.reduce((acc, item) => acc + parseFloat(item.score), 0);

                    // Calculate the average
                    const average = total / validScores.length;

                    return average;
                };


            const updateChart = (lgData, provinceData, countryData) => {
                if (averageChart) averageChart.destroy();
                Chart.register(ChartDataLabels);
                averageChart = new Chart(averageChartCtx, {
                    type: 'line',
                    data: {
                        labels: Object.values(fiscalYears),
                        datasets: [
                            {
                                label: `${lgData.name}`,
                                data: lgData.scores,
                                borderColor: '#3080d0',
                                fill: false,
                                tension: 0.4,
                                pointRadius: 5,
                                pointHoverRadius: 7,
                                pointBackgroundColor: '#3080d0',
                                pointBorderColor: '#3080d0',
                            },
                            {
                                label: `${provinceData.name}`,
                                data: provinceData.scores,
                                borderColor: '#ff6384',
                                fill: false,
                                tension: 0.4,
                                pointRadius: 5,
                                pointHoverRadius: 7,
                                pointBackgroundColor: '#ff6384',
                                pointBorderColor: '#ff6384',
                            },
                            {
                                label: 'Nepal',
                                data: countryData.scores,
                                borderColor: '#FF9020',
                                fill: false,
                                tension: 0.4,
                                pointRadius: 5,
                                pointHoverRadius: 7,
                                pointBackgroundColor: '#FF9020',
                                pointBorderColor: '#FF9020',
                            }
                        ]
                    },
                    options: {
                        plugins: {
                            datalabels: {
                                align: 'end',
                                anchor: 'end',
                                color: '#555',
                                font: {
                                    weight: 'bold'
                                },
                                formatter: (value) => value.toFixed(2)
                            },
                            legend: {
                                position: 'top',
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false,
                            }
                        },
                        interaction: {
                            mode: 'nearest',
                            axis: 'x',
                            intersect: false
                        },
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: 'Fiscal Years'
                                }
                            },
                            y: {
                                beginAtZero: false,
                                title: {
                                    display: true,
                                    text: 'Score'
                                }
                            }
                        },
                        animation: {
                            duration: 1000,
                            easing: 'easeInOutQuart'
                        }
                    }
                });
            };

            const updateComparison = () => {
                const selectedProvince = provinceSelect.value;
                const selectedDistrict = districtSelect.value;
                const selectedLg = lgSelect.value;
                lgName.textContent=selectedLg;

                Promise.all(Object.keys(fiscalYears).map(fetchData)).then(data => {
                    const lgData = {
                        name: selectedLg,
                        scores: data.map(fyData => fyData.response.find(item => item.name === selectedLg)?.score || 0)
                    };
                    const provinceData = {
                        name: selectedProvince,
                        scores: data.map(fyData => {
                            const items = fyData.response.filter(item => item.province === selectedProvince);
                            return calculateAverage(items);
                        })
                    };
                    const countryData = {
                        name: 'Nepal',
                        scores: data.map(fyData => calculateAverage(fyData.response))
                    };

                    updateChart(lgData, provinceData, countryData);
                }).catch(error => console.error('Error fetching data:', error));
            };

            
            lgSelect.addEventListener('change', updateComparison);
            provinceSelect.addEventListener('change', updateComparison);
            districtSelect.addEventListener('change', updateComparison);

            // provinceSelect.dispatchEvent(new Event('change'));
            // Automatically select the first province, district, and LG, and load the chart
            provinceSelect.dispatchEvent(new Event('change'));
            setTimeout(() => {
                updateComparison();
            }, 500); // Delay to ensure all events are processed
        });
    </script>