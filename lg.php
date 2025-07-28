<?php include('include/top.php') ?>
<?php include('include/lisa_data_loader.php') ?>
<body>
    <div id="wrapper" style="width:100%">
        <?php include('include/sidebar.php') ?>
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
                                    <option value="<?php echo $province; ?>" <?php if ($province == 'गण्डकी प्रदेश') echo 'selected'; ?>><?php echo $province; ?></option>
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
                        <!-- line chart card -->
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
                        <!-- Rank chart card -->
                        <div class="col-xl-4 col-lg-5">
                            <div class="card">
                                <div class="card-header">
                                <h6 class="m-0 font-weight-bold text-primary" id="lgName2"></h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <canvas id="districtRankChart" width="400" height="120"></canvas>
                                        </div>
                                        <div class="col-md-12 mt-4">
                                            <canvas id="provinceRankChart" width="400" height="120"></canvas>
                                        </div>
                                        <div class="col-md-12 mt-4">
                                            <canvas id="countryRankChart" width="400" height="120"></canvas>
                                        </div>
                                        <i style="color:#d5d5d5; text-align:right; font-size:0.8em">**Lowest number (Higher Rank) is better</i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!--First cchart area end -->
                    <div class="row" style="margin-top:10px">
                        <div class="col-xl-3 col-md-6 mb-4">
                            <select id="categorySelect" class="form-select">
                                <option value="1">शासकीय प्रवन्ध</option>
                                <option value="2">संगठन तथा प्रशासन</option>
                                <option value="3">वार्षिक बजेट तथा योजना</option>
                                <option value="4">वित्तीय एवम् आर्थिक</option>
                                <option value="5">सेवा प्रवाह</option>
                                <option value="6">न्यायिक कार्य सम्पादन</option>
                                <option value="7">भौतिक पूर्वाधार</option>
                                <option value="8">सामाजिक समावेशीकरण</option>
                                <option value="9">वातावरण संरक्षण तथा विपद</option>
                                <option value="10">सहकार्य र समन्वय</option>
                            </select>
                        </div>
                    </div> <!--First chart area end -->
                    <div class="row">
                        <div class="col-xl-12 col-lg-12">
                            <div class="row">
                                <div class="col-xl-8 col-lg-8 col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="m-0 font-weight-bold text-primary" id="categoryChartTitle">Category Analysis</h6>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="categoryChart" width="400" height="200"></canvas>
                                        </div>
                                    </div>
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
    const lgName2 = document.getElementById('lgName2');
    const averageChartCtx = document.getElementById('averageChart').getContext('2d');
    const districtRankChartCtx = document.getElementById('districtRankChart').getContext('2d');
    const provinceRankChartCtx = document.getElementById('provinceRankChart').getContext('2d');
    const countryRankChartCtx = document.getElementById('countryRankChart').getContext('2d');

    // For Category Chart
    const categorySelect = document.getElementById('categorySelect');
    const categoryChartCtx = document.getElementById('categoryChart').getContext('2d');
    const categoryChartTitle = document.getElementById('categoryChartTitle');

    let categoryChart;
    let averageChart;
    let districtRankChart;
    let provinceRankChart;
    let countryRankChart;

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

        
    const calculateRank = (items, name) => {
        // Sort items in descending order of scores
        const sorted = items.sort((a, b) => b.score - a.score);
        
        // Find the index of the item with the specified name
        const index = sorted.findIndex(item => item.name === name);
        
        // Return the rank (1-based index)
        return index !== -1 ? index + 1 : 0;
    };

    const updateCharts = (lgData, provinceData, districtData, countryData) => {
        if (averageChart) averageChart.destroy();
        if (districtRankChart) districtRankChart.destroy();
        if (provinceRankChart) provinceRankChart.destroy();
        if (countryRankChart) countryRankChart.destroy();

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
                        backgroundColor: 'rgba(48, 128, 208, 0.2)', // Adding semi-transparent fill
                        fill: true, // Enable area fill
                        tension: 0.4,
                        pointRadius: 4,
                        // pointHoverRadius: 5,
                        pointBackgroundColor: '#3080d0',
                        pointBorderColor: '#3080d0',
                    },
                    {
                        label: `${provinceData.name}`,
                        data: provinceData.scores,
                        borderColor: '#ff6384',
                        backgroundColor: 'rgba(255, 99, 132, 0.2)', // Adding semi-transparent fill
                        fill: true, // Enable area fill
                        tension: 0.4,
                        pointRadius: 4,
                        // pointHoverRadius: 5,
                        pointBackgroundColor: '#ff6384',
                        pointBorderColor: '#ff6384',
                    },
                    {
                        label: 'Nepal',
                        data: countryData.scores,
                        borderColor: '#FF9020',
                        backgroundColor: 'rgba(255, 144, 32, 0.2)', // Adding semi-transparent fill
                        fill: true, // Enable area fill
                        tension: 0.4,
                        pointRadius: 4,
                        // pointHoverRadius: 5,
                        pointBackgroundColor: '#FF9020',
                        pointBorderColor: '#FF9020',
                    }
                ]
            },
            options: {
                responsive: true, // Ensure the chart resizes with the screen
                maintainAspectRatio: false, // Allow the chart to adjust its aspect ratio
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


        districtRankChart = new Chart(districtRankChartCtx, {
            type: 'line',
            data: {
                labels: Object.values(fiscalYears),
                datasets: [
                    {
                        label: `${lgData.name} vs District`,
                        data: districtData.ranks,
                        borderColor: '#3080d0',
                        fill: false,
                        tension: 0,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        pointBackgroundColor: '#3080d0',
                        pointBorderColor: '#3080d0',
                    }
                ]
            },
            options: {
                responsive: true, // Ensure the chart resizes with the screen
                maintainAspectRatio: false, // Allow the chart to adjust its aspect ratio
                plugins: {
                    datalabels: {
                        align: 'end',
                        anchor: 'top',
                        color: '#555',
                        font: {
                            weight: 'bold'
                        }
                    },
                    legend: {
                        display: false // Hide legend
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
                        display: false // Hide x-axis
                    },
                    y: {
                        beginAtZero: false,
                        title: {
                            display: true,
                            text: districtSelect.value
                        },
                        reverse: true // Reverse the y-axis
                    }
                },
                animation: {
                    duration: 1000,
                    easing: 'easeInOutQuart'
                },
                layout:{
                    padding:{
                        top:20
                    }
                }
            }
        });

        provinceRankChart = new Chart(provinceRankChartCtx, {
            type: 'line',
            data: {
                labels: Object.values(fiscalYears),
                datasets: [
                    {
                        label: `${lgData.name} vs Province`,
                        data: provinceData.ranks,
                        borderColor: '#ff6384',
                        fill: false,
                        tension: 0,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        pointBackgroundColor: '#ff6384',
                        pointBorderColor: '#ff6384',
                    }
                ]
            },
            options: {
                responsive: true, // Ensure the chart resizes with the screen
                maintainAspectRatio: false, // Allow the chart to adjust its aspect ratio
                plugins: {
                    datalabels: {
                        align: 'end',
                        anchor: 'top',
                        color: '#555',
                        font: {
                            weight: 'bold'
                        }
                    },
                    legend: {
                        display: false // Hide legend
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
                        display: false
                    },
                    y: {
                        beginAtZero: false,
                        title: {
                            display: true,
                            text: provinceSelect.value
                        },
                        reverse: true // Reverse the y-axis
                    }
                },
                animation: {
                    duration: 1000,
                    easing: 'easeInOutQuart'
                },
                layout:{
                    padding:{
                        top:20
                    }
                }
            }
        });

        countryRankChart = new Chart(countryRankChartCtx, {
            type: 'line',
            data: {
                labels: Object.values(fiscalYears),
                datasets: [
                    {
                        label: `${lgData.name} vs Country`,
                        data: countryData.ranks,
                        borderColor: '#FF9020',
                        fill: false,
                        tension: 0,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        pointBackgroundColor: '#FF9020',
                        pointBorderColor: '#FF9020',
                    }
                ]
            },
            options: {
                responsive: true, // Ensure the chart resizes with the screen
                maintainAspectRatio: false, // Allow the chart to adjust its aspect ratio
                plugins: {
                    datalabels: {
                        align: 'end',
                        anchor: 'top',
                        color: '#555',
                        font: {
                            weight: 'bold'
                        }
                    },
                    legend: {
                        display: false // Hide legend
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
                        display: false
                        // title: {
                        //     display: true,
                        //     text: 'Fiscal Years'
                        // }
                    },
                    y: {
                        beginAtZero: false,
                        title: {
                            display: true,
                            text: 'नेपाल'
                        },
                        reverse: true // Reverse the y-axis
                    }
                },
                animation: {
                    duration: 1000,
                    easing: 'easeInOutQuart'
                },
                layout:{
                    padding:{
                        top:20
                    }
                }
            }
        });
    };

    const updateComparison = () => {
        const selectedProvince = provinceSelect.value;
        const selectedDistrict = districtSelect.value;
        const selectedLg = lgSelect.value;
        lgName.textContent = selectedLg;
        lgName2.textContent = selectedLg + "को Rank in:";

        Promise.all(Object.keys(fiscalYears).map(fetchData)).then(data => {
            const lgData = {
                name: selectedLg,
                scores: data.map(fyData => fyData.response.find(item => item.name === selectedLg)?.score || 0),
            };
            const provinceData = {
                name: selectedProvince,
                scores: data.map(fyData => {
                    const items = fyData.response.filter(item => item.province === selectedProvince);
                    return calculateAverage(items);
                }),
                ranks: data.map(fyData => {
                    const items = fyData.response.filter(item => item.province === selectedProvince);
                    return calculateRank(items, selectedLg);
                })
            };
            const districtData = {
                ranks: data.map(fyData => {
                    const items = fyData.response.filter(item => item.district === selectedDistrict);
                    console.log(items);
                    return calculateRank(items, selectedLg);
                })
            };
            const countryData = {
                scores: data.map(fyData => calculateAverage(fyData.response)),
                ranks: data.map(fyData => {
                    const items = fyData.response;
                    return calculateRank(items, selectedLg);
                })
            };

            updateCharts(lgData, provinceData, districtData, countryData);
        }).catch(error => console.error('Error fetching data:', error));
    };

    // New Category chart start from here
    // ....................................................................................................

    const selectedLg = lgSelect.value;
    
    // Calculate average of category
    const calculateAverageCategory = (data, category) => {
        // Filter out items where category score is not "N/A"
        const validScores = data.filter(item => item.categories[category] !== "N/A");

        // Calculate the total sum of valid category scores
        const total = validScores.reduce((acc, item) => acc + parseFloat(item.categories[category]), 0);

        // Calculate the average
        return (total / validScores.length).toFixed(2);
    };

    const updateChart = (lgData, provinceData, countryData) => {
        if (categoryChart) categoryChart.destroy();
        categoryChart = new Chart(categoryChartCtx, {
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
                responsive: true, // Ensure the chart resizes with the screen
                maintainAspectRatio: false, // Allow the chart to adjust its aspect ratio
                plugins: {
                    datalabels: {
                        align: 'end',
                        anchor: 'top',
                        color: '#555',
                        font: {
                            weight: 'bold'
                        }
                    },
                    legend: {
                        display: true
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                    }
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
    // Cateogry full score
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
    const updateCategoryAnalysis = () => {
        const selectedCategory = categorySelect.value;
        const selectedLg = lgSelect.value;
        categoryChartTitle.textContent = categorySelect.options[categorySelect.selectedIndex].text + " [Full Score: " + categoryFullScores[categorySelect.value] + "]";

        Promise.all(Object.keys(fiscalYears).map(fetchData)).then(data => {
            const lgData = {
                name: selectedLg,
                scores: data.map(fyData => fyData.response.find(item => item.name === selectedLg)?.categories[selectedCategory] || 0)
            };
            const province = data[0].response.find(item => item.name === selectedLg).province;
            const provinceData = {
                name: province,
                scores: data.map(fyData => {
                    const items = fyData.response.filter(item => item.province === province);
                    return calculateAverageCategory(items, selectedCategory);
                })
            };
            const countryData = {
                name: 'Nepal',
                scores: data.map(fyData => calculateAverageCategory(fyData.response, selectedCategory))
            };

            updateChart(lgData, provinceData, countryData);
        }).catch(error => console.error('Error fetching data:', error));
    };

    const handleSelectionChange = () => {
        updateComparison();
        updateCategoryAnalysis();
    };

    // Add event listeners
    provinceSelect.addEventListener('change', handleSelectionChange);
    districtSelect.addEventListener('change', handleSelectionChange);
    lgSelect.addEventListener('change', handleSelectionChange);

    categorySelect.addEventListener('change', updateCategoryAnalysis);

    // Automatically select the first province, district, and LG, and load the charts
    provinceSelect.dispatchEvent(new Event('change'));
    setTimeout(() => {
        updateComparison();
        updateCategoryAnalysis();
    }, 500); // Delay to ensure all events are processed
});
</script>
