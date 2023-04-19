<?php require APPROOT . '/views/inc/header_charts.php';?>

<div class = "row">
    <div class = "col-md-6 mx-auto">
        <div class = "card card-body bg-light mt-5">
            <h2>Stats</h2>
            <h3>Current Percentage Visited</h3>
            <p>Chart showing the percentage of stations which have been visited, along with the percentage only called at and the percentage not yet visited at all.</p>
            <p>Total Currently Visited: <?php echo $data['visited_summary_visited'] . ' / ' . $data['visited_summary_total_stations'] . ' (' . fmt_percent($data['visited_summary_percentage_visited']) . ')'; ?> </p>
            <div id='visted_summary' style='width: 100%; height: 500px;'></div>

            <h3>Monthly New Stations</h3>
            <p>Chart showing the number of new stations visited each month for the past 12 months.</p>
            <p>Target stations per month: <?php echo $data['monthly_target']?>
            <p>Current 12 month total: <?php echo $data['twelve_month_count_new'];?>, an average of <?php echo fmt_int($data['twelve_month_average_new']);?> per month</p>
            <?php 
                if ($data['twelve_month_average_new'] < $data['monthly_target']) {
                    echo "<p><b><span style='color:#7F0000'>UNDER TARGET</span></b></p>";
                } elseif ($data['twelve_month_average_new'] == $data['monthly_target']) {
                    echo "<p><b><span style='color:#267F00'>ON TARGET</span></b></p>";
                } else {
                    echo "<p><b><span style='color:#267F00'>ABOVE TARGET</span></b></p>";
                }
            ?>
            <div id='monthly_new_stations' style='width: 100%; height: 500px;'></div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php';?>