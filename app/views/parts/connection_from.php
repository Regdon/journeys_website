<div class = "col-md mx-auto">
    <div class = "card card-body mt-5">
        <h4>From</h4>
        <p>Journey ID <a href='<?php echo URLROOT . '/journeys/id/' .$data['connection_from_service']['journey_id'];?>'><?php echo $data['connection_from_service']['journey_id'];?></a></p>
        <p>The <?php echo $data['connection_from_service']['start_time']?> <?php echo $data['connection_from_service']['operator_name']?> service from <?php echo $data['connection_from_service']['start_station']?> to <?php echo $data['connection_from_service']['end_station']?> departing on <?php echo $data['connection_from_service']['journey_date']?></p>
        <p>Connection length <?php echo $data['connection_from_service']['connection_length']; ?> minutes</p>
    </div>
</div>