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

    }