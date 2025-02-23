<?php include('top.php') ?>
<body>
    <div id="wrapper" style="width:100%">
        <?php include('sidebar.php') ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div class="content">
                <h3 class="h3 mb-0 text-gray-800">Update Fiscal Year Data</h3>
                <button id="fetchDataBtn" class="btn btn-primary">Fetch and Update All Data</button>
                <div id="result"></div>

                <h3 class="mb4">Data Files</h3>
                <table class="table table-striped sortable" id="dataFilesTable">
                    <thead>
                        <tr>
                            <th>SN</th>
                            <th>File Name</th>
                            <th>FY</th>
                            <th>File Size</th>
                            <th>Updated At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $fiscal_years = [1 => 'FY 2076/77', 2 => 'FY 2077/78', 3 => 'FY 2078/79', 4 => 'FY 2079/80', 6 => 'FY 2080/81']; // Example fiscal years
                        $data_files = [];
                        foreach ($fiscal_years as $id => $year) {
                            $file_path = __DIR__ . "/data/LISA_summary_fiscal_year_{$id}.json";
                            if (file_exists($file_path)) {
                                $file_size = filesize($file_path);
                                $updated_at = date("Y-m-d H:i:s", filemtime($file_path));
                                $data_files[] = [
                                    'id' => $id,
                                    'name' => "LISA_summary_fiscal_year_{$id}.json",
                                    'fy' => $year,
                                    'size' => round($file_size/1024,2).' KB',
                                    'updated_at' => $updated_at
                                ];
                            }
                        }

                        foreach ($data_files as $index => $file): ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td><?php echo $file['name']; ?></td>
                                <td><?php echo $file['fy']; ?></td>
                                <td><?php echo $file['size']; ?></td>
                                <td><?php echo $file['updated_at']; ?></td>
                                <td>
                                    <button class="update-btn btn btn-primary" data-id="<?php echo $file['id']; ?>">Update</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
<script>
        $(document).ready(function() {
            $('#fetchDataBtn').click(function() {
                $.get('fetch_data.php?fetch_data=1', function(data) {
                    $('#result').text('Data fetched and saved successfully.');
                    updateTable(JSON.parse(data));
                }).fail(function() {
                    $('#result').text('Error fetching data.');
                });
            });

            $(document).on('click', '.update-btn', function() {
                const fiscalYearId = $(this).data('id');
                $.get(`fetch_data.php?fetch_data=1&fiscal_year_id=${fiscalYearId}`, function(data) {
                    $('#result').text('Data fetched and saved successfully.');
                    updateTable(JSON.parse(data));
                }).fail(function() {
                    $('#result').text('Error fetching data.');
                });
            });
        });

        function updateTable(data) {
            const tbody = $('#dataFilesTable tbody');
            tbody.empty();
            data.forEach((file, index) => {
                tbody.append(`
                    <tr>
                        <td>${index + 1}</td>
                        <td>${file.name}</td>
                        <td>${file.fy}</td>
                        <td>${file.size}</td>
                        <td>${file.updated_at}</td>
                        <td>
                            <button class="update-btn btn btn-primary" data-id="${file.id}">Update</button>
                        </td>
                    </tr>
                `);
            });
        }
    </script>
