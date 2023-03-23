<?php

    function minutes_between($start, $end) {
        $time_start = strtotime($start);
        $time_end = strtotime($end);
        $difference = round(abs($time_end - $time_start) / 3600,2);
        return round(60 * $difference, 0);
    }

?>