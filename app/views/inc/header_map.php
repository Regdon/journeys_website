<?php require APPROOT . '/views/inc/header_standard_1.php'; ?>

<script type='text/javascript' src='https://www.gstatic.com/charts/loader.js'></script>
<script type='text/javascript'>
    google.charts.load('current', {'packages': ['map'], 'mapsApiKey': 'AIzaSyAibU-pLvNFHcHHGX2rcRdGxk00LgJIldE'});
    google.charts.setOnLoadCallback(drawMap);

    function drawMap() {
            var data = google.visualization.arrayToDataTable([
                ['Lat', 'Long', 'Name', 'Marker']
                <?php
                    foreach ($data['closest_stations'] as $key => $value) {
                        $lat = $value['latitude'];
                        $long = $value['longitude'];
                        $station = htmlspecialchars($value['station_name'], ENT_QUOTES) . ' (' . $value['station_code'] . ')';
                        $marker = $value['status'];
                        echo ", [$lat, $long, '$station', '$marker']";
                    }
                ?>            
            ]);

            var options = {
                showTooltip: true,
                showInfoWindow: true,
                enableScrollWheel: true,
                icons: {
                    green: {
                        normal: 'https://icons.iconarchive.com/icons/icons-land/vista-map-markers/48/Map-Marker-Marker-Outside-Chartreuse-icon.png',
                        selected: 'https://icons.iconarchive.com/icons/icons-land/vista-map-markers/48/Map-Marker-Marker-Outside-Chartreuse-icon.png'
                    },
                    red: {
                        normal: 'https://icons.iconarchive.com/icons/paomedia/small-n-flat/48/map-marker-icon.png',
                        selected: 'https://icons.iconarchive.com/icons/paomedia/small-n-flat/48/map-marker-icon.png'
                    },
                    blue: {
                        normal: 'https://icons.iconarchive.com/icons/icons-land/vista-map-markers/48/Map-Marker-Marker-Outside-Azure-icon.png',
                        selected: 'https://icons.iconarchive.com/icons/icons-land/vista-map-markers/48/Map-Marker-Marker-Outside-Azure-icon.png'
                    }            
                }
            };

            var map = new google.visualization.Map(document.getElementById('chart_div'));
            map.draw(data, options);
        };

    
</script>


<?php require APPROOT . '/views/inc/header_standard_2.php'; ?>