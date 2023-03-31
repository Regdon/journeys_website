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

        public function data($code = '') {

            if ($code == '') {
                //Do Something / redirect somewhere
                return;
            }

            $data = [
                'station_code' => trim($code),

                //Basic data
                'station_name' => '',
                'station_longitude' => '',
                'station_latitude' => '',
                'station_country' => '',
                'station_county' => '',
                'station_usage' => '',
                'station_rank' => '',

                //Visited status
                'visited_status' => '',
                'first_visit_date' => '',
                'first_call_date' => '',

                //Journey History
                'journey_history' => array()
            ];

            //Query to get basic station data
            $station_data = $this->stationModel->stationData($data['station_code']);

            if (empty($station_data)) {
                //Do Something / redirect somewhere
                return;
            }

            $data['station_name'] = $station_data->station_name;
            $data['station_longitude'] = $station_data->station_longitude;
            $data['station_latitude'] = $station_data->station_latitude;
            $data['station_country'] = $station_data->country;
            $data['station_county'] = $station_data->county;
            $data['station_usage'] = $station_data->station_usage;
            $data['station_rank'] = $station_data->station_rank;

            //Query to get visit status information
            $visited_info = $this->stationModel->visitedInformation($data['station_code']);
            
            if ($visited_info->first_board_date || $visited_info->first_alight_date) {
                $data['visited_status'] = 'Visited';
            } elseif ($visited_info->first_call_date) {
                $data['visited_status'] = 'Called';
            } else {
                $data['visited_status'] = 'Not Visited';
            }
            $data['first_call_date'] = $visited_info->first_call_date;
            $data['first_visit_date'] = max($visited_info->first_board_date, $visited_info->first_alight_date);

            //Query to get Journey History
            $journey_history = $this->stationModel->journeyHistory($data['station_code']);

            foreach ($journey_history as $key => $journey) {
                $temp = array();

                $temp['journey_id'] = $journey->journey_id;
                $temp['journey_date'] = $journey->journey_date;
                $temp['operator_name'] = $journey->operator_name;
                $temp['call_type'] = $journey->call_type;
                $temp['call_datetime'] = $journey->call_datetime;
                $temp['journey_start_time'] = $journey->journey_start_time;
                $temp['journey_start_station'] = $journey->journey_start_station;
                $temp['journey_end_time'] = $journey->journey_end_time;
                $temp['journey_end_station'] = $journey->journey_end_station;

                $data['journey_history'][$key] = $temp;
            }


            //echo var_dump($data);
            //Output data to view
            $this->view('stations/data', $data);

        }

    }