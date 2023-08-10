<?php require APPROOT . '/views/inc/header.php';?>

<div class = "row">
    <div class = "col-md-6 mx-auto">
        <div class = "card card-body bg-light mt-5">
            <h2><?php echo $data['station_name'];?></h2>
            <p>Status: <?php echo $data['visited_status'];?></p>
            <p>First Visited: <?php if($data['first_visit_date']) {echo fmt_date($data['first_visit_date']);} else {echo 'Not Visisted';}?></p>
            <p>First Called: <?php if($data['first_call_date']) {echo fmt_date($data['first_call_date']);} else {echo 'Not Called';}?></p>
            <p>Station Usage: <?php echo fmt_int($data['station_usage']);?> (Rank <?php echo fmt_int($data['station_rank']);?>)</p>

            <?php
                //Image carousel
                if ($data['image_url']) {
                    include APPROOT . '/views/parts/image_carousel.php';
                }
            ?>

            <h3>Recent Journeys</h3>
            <?php
                $last_date = '';
                foreach ($data['journey_history'] as $key => $journey_history) {
                    if ($journey_history['journey_date'] <> $last_date) {
                        $last_date = $journey_history['journey_date'];
                        echo '<h4>' . fmt_date($last_date) . '</h4>';
                    }
                    include APPROOT . '/views/parts/station_journey_row.php';
                }
            ?>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php';?>