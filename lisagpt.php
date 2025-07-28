<?php include('include/top.php') ?>
<body>
    <div id="wrapper" style="width:100%">
        <?php include('include/sidebar.php') ?>
        <div id="content-wrapper">
            <div class="content">
                <div class="container-fluid">
                    <h3>LISA Score Search</h3>
                    <input type="text" id="searchBox" placeholder="Enter your query here..." style="width:50%">
                    <button id="searchBtn"class="btn btn-primary">Search</button>
                    <div id="results" style="border:1px solid #fff; padding:10px;">test</div>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
$(document).ready(function() {
    $('#searchBtn').click(function() {
        var query = $('#searchBox').val();
        $.post('search.php', { query: query }, function(data) {
            // Assuming data is already properly encoded in UTF-8
            $('#results').html(data);
        }).fail(function() {
            $('#results').text('Error processing query.');
        });
    });
});

</script>