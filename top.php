<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Visualization</title>
    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0/dist/chartjs-plugin-datalabels.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .container {
            margin-top: 20px;
        }
        #categoriesChart {
            width: 900px;
            max-width: 1200px;
            margin: auto;
        }
        #barChart {
            width: 900px;
            max-width: 1200px;
            margin: auto;
        }

        body {
            display: flex;
            min-height: 100vh;
            flex-direction: row;
        }
        .sidebar {
            width: 200px;
            /* background-color: #f8f9fa; */
            padding: 20px;
            color:#fff;
        }
        .content {
            flex: 1;
            padding: 20px;
        }
        .charts {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .chart-container {
            width: 60%;
        }
        .spider-container {
            width: 40%;
        }
        canvas {
            /* width: 98% !important; */
            height: auto !important;
        }
        .table-container {
            margin-top: 20px;
        }
        table {
            width: 100%;
            text-align: center;
        }
        .lg-down{
            background-color: #fce8e6;
            color: #a50e0e;
        }
        .lg-up{
            background-color: #e6f4ea;
            color: #137333;
        }
        .lg-up{
            background-color: grey;
            color: dark-grey;
        }
        /* Ensure the canvas fills the container */
        canvas {
            width: 100% !important;
            height: 100% !important;
        }
    </style>
    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>