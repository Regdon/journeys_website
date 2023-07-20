<?php require APPROOT . '/views/inc/header_map.php';?>

<div class = "row">
    <div class = "col-xl mx-auto">
        <div class = "card card-body bg-light mt-5">            
            <form action = "<?php echo URLROOT; ?>/stats/map" method = "post">
                <div class = "row">
                    <div class = "form-group col-md-6">
                        <lable for = "choose_station">Choose Station:</lable>
                        <input type = "text" name = "choose_station" class = "autocomplete form-control form-control-lg <?php echo (!empty($data['choose_station_error'])) ? 'is-invalid' : ''; ?> " value= "<?php echo $data['choose_station']; ?>"> 
                        <span class = "invalid-feedback"><?php echo $data['choose_station_error']; ?></span>
                    </div>
                    <div class = "form-group col-md-3">
                        <input type = "submit" value = "Centre Map" class = "btn btn-success btn-block">
                    </div>
                </div>
            </form>
            <h2>Station Visited Map</h2>
            <p>400 closest stations, centred on <?php echo $data['centre_station_name'];?>.
            <p>Green = Visited, Blue = Called At, Red = Not been to</p>
            <div id = "chart_div" style="height: 600px;"></div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php';?>