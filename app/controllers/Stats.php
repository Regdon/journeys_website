<?php
    class Stats extends Controller {
        public function __construct() {
            $this->statsModel = $this->model('Statistics');
        }

        public function summary() {

            $data = [
                'visited_summary_visited' => 0,
                'visited_summary_called' => 0,
                'visited_summary_not_visited' => 0,
                'visited_summary_total_stations' => 0,
                'visited_summary_percentage_visited' => 0.0,

                'visited_by_month_data' => array(),
                'twelve_month_count_new' => 0,
                'twelve_month_average_new' => 0,
                'monthly_target' => 22
            ];

            $visited_summary_data = $this->statsModel->visitedSummary();

            foreach ($visited_summary_data as $key => $value) {
                if ($value->station_status == 'Visited') {
                    $data['visited_summary_visited'] = $value->number_of_stations;
                } elseif ($value->station_status == 'Called') {
                    $data['visited_summary_called'] = $value->number_of_stations;
                } elseif ($value->station_status == 'Not Visited') {
                    $data['visited_summary_not_visited'] = $value->number_of_stations;
                }                
            }
            $data['visited_summary_total_stations'] = $data['visited_summary_visited'] + $data['visited_summary_called'] + $data['visited_summary_not_visited'];
            $data['visited_summary_percentage_visited'] = 1.0 * $data['visited_summary_visited'] / $data['visited_summary_total_stations'];

            $visted_by_month_data = $this->statsModel->monthlyNewStations();
            $n = 0;
            foreach ($visted_by_month_data as $key => $value) {
                $data['visited_by_month_data'][$n] = $value;
                $data['twelve_month_count_new'] += $value->count_of_stations;
                $n ++;
                if ($n >= 12) {break;}
            }             
            $data['twelve_month_average_new'] = $data['twelve_month_count_new'] / 12;

            //echo var_dump($data);
            $this->view('stats/summary', $data);

        }

        public function map($centre_station_code = 'DEFAULT') {

            $data = [
                'centre_station_code' => '',
                'centre_station_name' => '',
                'centre_station_error' => '',

                'closest_stations' => array(),

                'choose_station' => '',
                'choose_station_code' => '',
                'choose_station_error' => ''                
            ];

            //Check for POST station submission
            if($_SERVER['REQUEST_METHOD'] == 'POST' && $centre_station_code == 'DEFAULT') {
                $data['choose_station'] = trim($_POST['choose_station']);

                //Validate chosen station
                if (empty($data['choose_station'])) {
                    $data['choose_station_error'] = 'Please enter a station';
                } else {
                    $data['choose_station_code'] = $this->statsModel->validStation($data['choose_station']);
                    if (!$data['choose_station_code']) {
                        $data['choose_station_error'] = 'Please enter a valid station';
                    }
                }

                //Valid, use code
                if(empty($data['choose_station_error'])) {
                    $centre_station_code = $data['choose_station_code'];
                }
            }

            //Get and validate the centre station requested
            $centre_station = $this->statsModel->validStationCode($centre_station_code);
            if ($centre_station) {
                $data['centre_station_code'] = $centre_station->station_code;
                $data['centre_station_name'] = $centre_station->station_name;
            } else {                
                $data['centre_station_code'] = 'WRK';
                $data['centre_station_name'] = 'Worksop';
                $data['centre_station_error'] = 'Invalid station code requested: ' . $centre_station_code;
            }

            //Get the 400 closest stations
            $closest_stations = $this->statsModel->closestStations($data['centre_station_code']);
            
            foreach ($closest_stations as $key => $value) {
                $station = [
                    'station_code' => $value->station_code,
                    'station_name' => $value->station_name,
                    'longitude' => $value->station_longitude,
                    'latitude' => $value->station_latitude,
                    'status' => $value->status
                ];
                $data['closest_stations'][$key] = $station;
            }

            //echo var_dump($data);
            $this->view('stats/map', $data);
        }

    }