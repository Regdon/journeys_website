<?php require APPROOT . '/views/inc/header.php';?>

<div class = "row">
    <div class = "col-md-12 mx-auto">
        <div class = "card card-body bg-light mt-5">
            <h2>Search Journeys</h2>

            <form action = "<?php echo URLROOT; ?>/journeys/search" method = "post">

                <div class = "row">                    
                    <div class = "form-group col-md-2">
                        <lable for = "start_date">Start Date: </lable>
                        <input type = "date" name = "start_date" class = "form-control form-control-lg" value= "<?php echo $data['start_date']; ?>">
                    </div>

                    <div class = "form-group col-md-2">
                        <lable for = "end_date">End Date: </lable>
                        <input type = "date" name = "end_date" class = "form-control form-control-lg" value= "<?php echo $data['end_date']; ?>">
                    </div>

                    <div class = "form-group col-md-3">
                        <lable for = "operator_code">Operator: </lable>
                        <select name = "operator_code" class = "form-control form-control-lg">
                            <option value = "XXX" selected = "<?php if(!$data['operator_select_list']) {echo " selected";}?>">Blank</option>
                            <?php
                                foreach ($data['operator_select_list'] as $operator) {
                                    if ($data['operator_code'] == $operator->operator_code) {$selected = ' selected';} else {$selected = '';}
                                    echo '<option value = "'. $operator->operator_code . '"'. $selected . '>' . $operator->operator_name . '</option>';
                                }
                            ?>
                        </select>
                    </div>

                    <div class = "form-group col-md-4">
                        <lable for = "station">Station: </lable>
                        <input type = "text" name = "station" class = "autocomplete form-control form-control-lg" value= "<?php echo $data['station']; ?>"> 
                    </div>

                    <div class = "col-md-1">
                        <lable for = "submit">Search: </lable>
                        <input type = "submit" value = "Search" class = "btn btn-success btn-block">
                    </div>

                </div>

            </form>
        </div>
    </div>
</div>

<div class = "row">
    <div class = "col-md-12 mx-auto">
        <div class = "card card-body bg-light mt-5">
            <?php
                $last_date = '';
                foreach ($data['results'] as $key => $value) {
                    $search_row = $value;
                    if ($last_date <> $search_row->journey_date) {
                        echo '<h2>' . $search_row->journey_date . '</h2>';
                        $last_date = $search_row->journey_date;
                    }

                    include APPROOT . '/views/parts/journey_search_row.php';
                }
            ?>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php';?>