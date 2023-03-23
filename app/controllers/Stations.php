<?php
    class Stations extends Controller {
        public function __construct() {
            $this->stationModel = $this->model('Station');
        }

        public function search() {
            //Sanitise POST data
            if($_SERVER['REQUEST_METHOD'] == 'GET') {
                $_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);

                //Init Data
                $data = [
                    'search_param' => trim($_GET['term']),
                    'stations' => array(),
                    'stations_assoc' => array(),
                    'json_data' => ''
                ];

                $data['stations'] = $this->stationModel->searchStations($data['search_param']);
                
                foreach ($data['stations'] as $station) {
                    $data['stations_assoc'][$station->station_code] = $station->station_name; 
                }

                $data['json_data'] = json_encode($data['stations_assoc']);   
                
                $this->view('stations/asyncsearch', $data);
            }
        }

    }