<?php

    function fmt_date($value) {
        $date_time = date_create($value);
        if ($date_time) {
            return date_format($date_time, 'd/m/Y');
        }
        return 'Not a date';
    }

    function fmt_int($value) {
        if (is_int($value)) {
            return number_format($value);
        }
        return '#NaN';
    }

    function fmt_hhmm($value) {
        $date_time = date_create($value);
        if ($date_time) {
            return date_format($date_time, 'H:i');
        }
        return 'Not a date';
    }

    function fmt_percent($value) {
        if (is_double($value)) {
            return sprintf("%.1f%%", $value * 100);
        }
        return '#NaN';
    }

?>