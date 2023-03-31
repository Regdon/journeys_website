    <div class = "card card-body mt-2">
        <b>Journey ID <a href='<?php echo URLROOT . '/journeys/id/' . $journey_history['journey_id'];?>'><?php echo $journey_history['journey_id'];?></a>, <?php echo $journey_history['call_type'];?></b>
        The <?php echo fmt_hhmm($journey_history['journey_start_time']);?> <?php echo $journey_history['operator_name'];?> service from <?php echo $journey_history['journey_start_station'];?> to <?php echo $journey_history['journey_end_station'];?>.
    </div>
