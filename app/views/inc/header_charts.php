<?php require APPROOT . '/views/inc/header_standard_1.php'; ?>

<script type='text/javascript' src='https://www.gstatic.com/charts/loader.js'></script>

<script type='text/javascript'>
    google.charts.load('current', {packages:['corechart']});
    google.charts.setOnLoadCallback(drawVisitedSummary);
    google.charts.setOnLoadCallback(drawMonthlyNewStations);

    function drawVisitedSummary() {
        var data = google.visualization.arrayToDataTable([
            ['Status', 'Stations'],
            ['Visited', <?php echo $data['visited_summary_visited'];?>],
            ['Called At', <?php echo $data['visited_summary_called'];?>],
            ['Not Been To', <?php echo $data['visited_summary_not_visited'];?>]
        ]);

        var options = {
            title: 'Stations Visited Summary',
            slices: {
                0: { color: '#267F00' }, //Dark Green
                1: { color: '#00137F' }, //Dark Blue
                2: { color: '#7F0000' } //Dark Red
            }
        };

        var chart = new google.visualization.PieChart(document.getElementById('visted_summary'));
        chart.draw(data, options);
    }

    function drawMonthlyNewStations() {
        var data = google.visualization.arrayToDataTable([
            ['Month', 'New Stations', 'New Stations']
            <?php
                for ($i=count($data['visited_by_month_data']); $i > 0 ; $i--) { 
                    $bar = $data['visited_by_month_data'][$i - 1];
                    $bar_target = $data['monthly_target'];
                    include APPROOT . '/views/parts/monthly_new_stations_chart_bar.php';
                }
            ?>
        ]);

        var options = {
            title: 'Count of New Stations by Month',
            legend: {position: 'none'},
            bar: {groupWidth: '75%'},
            series: {
                0: { color: '#267F00' }, //Dark Green
                1: { color: '#7F0000' } //Dark Red
            },
            isStacked: true
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('monthly_new_stations'));
        chart.draw(data, options);
    }
</script>

<?php require APPROOT . '/views/inc/header_standard_2.php'; ?>