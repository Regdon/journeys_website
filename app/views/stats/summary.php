<?php require APPROOT . '/views/inc/header_charts.php';?>

<div class = "row">
    <div class = "col-md-6 mx-auto">
        <div class = "card card-body bg-light mt-5">
            <h2>Stats</h2>
            <p>Chart showing the percentage of stations which have been visited, along with the percentage only called at and the percentage not yet visited at all.</p>
            <p>Total Currently Visited: <?php echo $data['visited_summary_visited'] . ' / ' . $data['visited_summary_total_stations'] . ' (' . fmt_percent($data['visited_summary_percentage_visited']) . ')'; ?> </p>
            <div id='visted_summary' style='width: 100%; height: 500px;'></div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php';?>