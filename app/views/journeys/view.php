<?php require APPROOT . '/views/inc/header.php';?>

<div class = "row">
    <div class = "col-md-6 mx-auto">
        <div class = "card card-body bg-light mt-5">
            <h2>Journey ID <?php echo $data['journey_id'];?></h2>
            <p>The <?php echo $data['start_station_time']?> <?php echo $data['journey_operator']?> service from <?php echo $data['start_station']?> to <?php echo $data['end_station']?> departing on <?php echo $data['journey_date']?></p>

            <h3>Journey Details</h3>
            <div class = "row">
                <div class = "col-md-3 mx-auto">
                    Boarded
                </div>
                <div class = "col-md-3 mx-auto">
                    <?php echo $data['start_station']?>
                </div>
                <div class = "col-md-3 mx-auto">
                    <?php echo $data['start_station_time']?>
                </div>
            </div>

            <?php
                foreach ($data['call_stations'] as $key => $call_station) {
                    echo '<div class = "row">';
                    echo '<div class = "col-md-3 mx-auto">Called</div>';
                    echo '<div class = "col-md-3 mx-auto">' . $call_station . '</div>';
                    echo '<div class = "col-md-3 mx-auto">' . $data['call_stations_time'][$key] . '</div>';
                    echo '</div>';
                }
            ?>

            <div class = "row">
                <div class = "col-md-3 mx-auto">
                    Alighted
                </div>
                <div class = "col-md-3 mx-auto">
                    <?php echo $data['end_station']?>
                </div>
                <div class = "col-md-3 mx-auto">
                    <?php echo $data['end_station_time']?>
                </div>
            </div>  
            
            <h3>Unit Details</h3>
            <?php
                if (empty($data['units'])) {
                    echo '<p>No units recorded for this journey</p>';
                } else {
                    foreach ($data['units'] as $key => $unit) {
                        echo '<div class = "row">';
                        echo '<div class = "col">' . $unit . '</div>';
                        echo '</div>';
                    }
                }
            ?>
            <h3>Connections</h3>
            <div class = "row">
                <?php
                    if (!empty($data['connection_from_service'])) {
                        include APPROOT . '/views/parts/connection_from.php';
                    } else {
                        include APPROOT . '/views/parts/connection_from_missing.php';
                    }

                    if (!empty($data['connection_to_service'])) {
                        include APPROOT . '/views/parts/connection_to.php';
                    } else {
                        include APPROOT . '/views/parts/connection_to_missing.php';
                    }
                ?>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php';?>