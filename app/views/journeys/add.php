<?php require APPROOT . '/views/inc/header.php';?>

<div class = "row">
    <div class = "col-md-6 mx-auto">
        <div class = "card card-body bg-light mt-5">
            <h2>Add Journey</h2>
            <p>Fill out this form to record a journey</p>
            <form action = "<?php echo URLROOT; ?>/journeys/add" method = "post">
                <div class = "form-group">
                    <lable for = "date">Date: <sup>*</sup></lable>
                    <input type = "date" name = "date" class = "form-control form-control-lg <?php echo (!empty($data['date_error'])) ? 'is-invalid' : ''; ?> " value= "<?php echo $data['date']; ?>">
                    <span class = "invalid-feedback"><?php echo $data['date_error']; ?></span>
                </div>

                <div class = "form-group">
                    <lable for = "operator_code">Operator: <sup>*</sup></lable>
                    <select name = "operator_code" class = "form-control form-control-lg <?php echo (!empty($data['operator_code_error'])) ? 'is-invalid' : ''; ?> ">
                        <?php
                            foreach ($data['operator_select_list'] as $operator) {
                                if ($data['operator_code'] == $operator->operator_code) {$selected = ' selected';} else {$selected = '';}
                                echo '<option value = "'. $operator->operator_code . '"'. $selected . '>' . $operator->operator_name . '</option>';
                            }
                        ?>
                    </select>
                    <span class = "invalid-feedback"><?php echo $data['operator_code_error']; ?></span>
                </div>

                <div class = "form-group">
                    <lable for = "notes">Notes: </lable>
                    <textarea name = "notes" class = "form-control form-control-lg <?php echo (!empty($data['notes_error'])) ? 'is-invalid' : ''; ?> " value= "<?php echo $data['notes']; ?>"></textarea>
                    <span class = "invalid-feedback"><?php echo $data['notes_error']; ?></span>
                </div>

                <h3>Station Details</h3>
                <div class = "row">
                    <div class = "form-group col-md-9">
                        <lable for = "start_station">Start Station: <sup>*</sup></lable>
                        <input type = "text" name = "start_station" class = "autocomplete form-control form-control-lg <?php echo (!empty($data['start_station_error'])) ? 'is-invalid' : ''; ?> " value= "<?php echo $data['start_station']; ?>"> 
                        <span class = "invalid-feedback"><?php echo $data['start_station_error']; ?></span>
                    </div>
                    <div class = "form-group col-md-3">
                        <lable for = "start_station_time">Time: <sup>*</sup></lable>
                        <input type = "time" name = "start_station_time" class = "form-control form-control-lg <?php echo (!empty($data['start_station_time_error'])) ? 'is-invalid' : ''; ?> " value= "<?php echo $data['start_station_time']; ?>"> 
                        <span class = "invalid-feedback"><?php echo $data['start_station_time_error']; ?></span>
                    </div>                        
                </div>

                <div id = "call-stations">
                    <?php
                        foreach ($data['call_stations'] as $key => $value) {
                            $timeValue = $data['call_stations_time'][$key];

                            if (!empty($value) || !empty($timeValue)) {
                                $stationErr = '';  
                                if (!empty($data['call_stations_error'][$key])) {$stationErr = 'is-invalid';}
                                $timeErr = '';  
                                if (!empty($data['call_stations_time_error'][$key])) {$timeErr = 'is-invalid';}
                                echo '
                                <div class = "row">
                                    <div class = "form-group col-md-9">
                                        <lable for = "call_station[]">Intermediate Station: </lable>
                                        <input type = "text" name = "call_station[]" class = "autocomplete form-control form-control-lg ' . $stationErr .' " value= "' . $value . '"> 
                                        <span class = "invalid-feedback">' . $data['call_stations_error'][$key] . '</span>
                                    </div>
                                    <div class = "form-group col-md-3">
                                        <lable for = "call_station_time[]">Time: </lable>
                                        <input type = "time" name = "call_station_time[]" class = "form-control form-control-lg ' . $timeErr . ' " value= "' . $timeValue . '"> 
                                        <span class = "invalid-feedback">' . $data['call_stations_time_error'][$key] . '</span>
                                    </div>                        
                                </div>                                
                                ';
                            }
                        }
                    ?>
                </div>
                
                <button type="button" class="btn btn-outline-primary" onclick="add_intermediate_station()">Add Intermediate Station</button>

                <div class = "row">
                    <div class = "form-group col-md-9">
                        <lable for = "end_station">Destination Station: <sup>*</sup></lable>
                        <input type = "text" name = "end_station" class = "autocomplete form-control form-control-lg <?php echo (!empty($data['end_station_error'])) ? 'is-invalid' : ''; ?> " value= "<?php echo $data['end_station']; ?>">  
                        <span class = "invalid-feedback"><?php echo $data['end_station_error']; ?></span>
                    </div>
                    <div class = "form-group col-md-3">
                        <lable for = "end_station_time">Time: <sup>*</sup></lable>
                        <input type = "time" name = "end_station_time" class = "form-control form-control-lg <?php echo (!empty($data['end_station_time_error'])) ? 'is-invalid' : ''; ?> " value= "<?php echo $data['end_station_time']; ?>"> 
                        <span class = "invalid-feedback"><?php echo $data['end_station_time_error']; ?></span> 
                    </div>                        
                </div>

                <h3>Units (Optional)</h3>
                <div id = "units">
                    <?php
                        foreach ($data['unit'] as $key => $value) {
                            $unitErr = '';
                            if (!empty($data['unit_error'][$key])) {$unitErr = 'is-invalid';}

                            echo '
                            <div class = "form-group">
                                <lable for = "unit[]">First Unit: </lable>
                                <input type = "text" name = "unit[]" class = "form-control form-control-lg ' . $unitErr . '" value = "' . $value . '">
                                <span class = "invalid-feedback">' . $data['unit_error'][$key] . '</span>
                            </div>                            
                            ';
                        }
                    ?>
                </div>

                <button type="button" class="btn btn-outline-primary" onclick="add_additional_unit()">Add Additional Unit</button>

                <div class = "row">
                    <div class = "col">
                        <input type = "submit" value = "Add Journey" class = "btn btn-success btn-block">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php';?>