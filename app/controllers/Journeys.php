<?php
    class Journeys extends Controller {
        public function __construct() {
            $this->journeyModel = $this->model('Journey');
        }

        public function add() {
            //Check for php POST
            if($_SERVER['REQUEST_METHOD'] == 'POST') {
                //Process form

                //Sanitise POST data
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

                //Init data
                $data = [
                    'date' => trim($_POST['date']),
                    'operator_code' => trim($_POST['operator_code']),
                    'notes' => trim($_POST['notes']),
                    'date_error' => '',
                    'operator_code_error' => '',
                    'notes_error' => '',

                    //Station variables
                    'start_station' => trim($_POST['start_station']),
                    'start_station_code' => '',
                    'start_station_error' => '',
                    'start_station_time' => trim($_POST['start_station_time']),
                    'start_station_time_error' => '',
                    'end_station' => trim($_POST['end_station']),
                    'end_station_code' => '',
                    'end_station_error' => '',
                    'end_station_time' => trim($_POST['end_station_time']),
                    'end_station_time_error' => '',
                    'call_stations' => $_POST['call_station'],
                    'call_stations_code' => array(),
                    'call_stations_error' => array(),
                    'call_stations_time' => $_POST['call_station_time'],
                    'call_stations_time_error' => array(),

                    //Unit variables
                    'unit' => $_POST['unit'],
                    'unit_error' => array(),

                    //Page variables
                    'operator_select_list' => $this->journeyModel->lov_operator()
                ];

                //Validate date
                if (empty($data['date'])) {
                    $data['date_error'] = 'Please enter a journey date';
                }

                //Validate operator_code
                if (empty($data['operator_code'])) {
                    $data['operator_code_error'] = 'Please enter an operator';
                } else {
                    if (!$this->journeyModel->validOperator($data['operator_code'])) {
                        $data['operator_code_error'] = 'Please enter a valid operator';
                    }
                }

                //Validate Start Station
                if (empty($data['start_station'])) {
                    $data['start_station_error'] = 'Please enter starting station';
                } else {
                    $data['start_station_code'] = $this->journeyModel->validStation($data['start_station']);
                    if (!$data['start_station_code']) {
                        $data['start_station_error'] = 'Please enter a valid starting station';
                    }
                }

                //Validate Start Time
                if (empty($data['start_station_time'])) {
                    $data['start_station_time_error'] = 'Please enter start time';
                }

                //Validate End Station
                if (empty($data['end_station'])) {
                    $data['end_station_error'] = 'Please enter a destination station';
                } else {
                    $data['end_station_code'] = $this->journeyModel->validStation($data['end_station']);
                    if (!$data['end_station_code']) {
                        $data['end_station_error'] = 'Please enter a vaild destination station';
                    }
                }

                //Validate End Time
                if (empty($data['end_station_time'])) {
                    $data['end_station_time_error'] = 'Please enter a destination time';
                }

                $call_station_error = False;
                //Validate Intermediate Station / Time
                foreach ($data['call_stations'] as $key => $value) {
                    $timeValue = $data['call_stations_time'][$key];

                    $data['call_stations_error'][$key] = '';
                    $data['call_stations_time_error'][$key] = '';

                    if (!empty($value) && empty($timeValue)) {
                        $data['call_stations_time_error'][$key] = 'Please enter a call time';
                        $call_station_error = True;
                    }

                    $data['call_stations_code'][$key] = $this->journeyModel->validStation($value);

                    if (empty($value) && !empty($timeValue)) {
                        $data['call_stations_error'][$key] = 'Please enter call station';
                        $call_station_error = True;
                    } elseif (!$data['call_stations_code'][$key]) {
                        $data['call_stations_error'][$key] = 'Please enter a vaild call station';
                        $call_station_error = True;
                    }
                }

                //Validate Units
                //??No validation to do??

                //echo var_dump($data);

                //Make sure errors are empty
                if (
                       empty($data['date_error'])
                    && empty($data['operator_code_error'])
                    && empty($data['notes_error'])

                    && empty($data['start_station_error'])
                    && empty($data['start_station_time_error'])
                    && empty($data['end_station_error'])
                    && empty($data['end_station_time_error'])

                    && !($call_station_error)
                ) {
                    //Validated - Insert data
                    $journeyID = $this->journeyModel->addJourney([
                        'date' => $data['date'],
                        'operator_code' => $data['operator_code'],
                        'notes' => $data['notes']
                    ]);

                    if ($journeyID) {
                        //Start Station
                        $this->journeyModel->addStationCall([
                            'journey_id' => $journeyID,
                            'station_code' => $data['start_station_code'],
                            'datetime' => $data['start_station_time'],
                            'call_type_code' => CALL_TYPE_START
                        ]);

                        //End Station
                        $this->journeyModel->addStationCall([
                            'journey_id' => $journeyID,
                            'station_code' => $data['end_station_code'],
                            'datetime' => $data['end_station_time'],
                            'call_type_code' => CALL_TYPE_END
                        ]);

                        //Intermediate Station(s)
                        for ($call = 0; $call < COUNT($data['call_stations']); $call ++) {
                            $this->journeyModel->addStationCall([
                                'journey_id' => $journeyID,
                                'station_code' => $data['call_stations_code'][$call],
                                'datetime' => $data['call_stations_time'][$call],
                                'call_type_code' => CALL_TYPE_CALL
                            ]);
                        }

                        //Unit(s)
                        for ($unit = 0; $unit < COUNT($data['unit']); $unit ++) {
                            $this->journeyModel->addUnit([
                                'journey_id' => $journeyID,
                                'unit' => $data['unit'][$unit]
                            ]);
                        }
                    }
                } else {
                    //Validation failed
                    $this->view('journeys/add', $data);
                }

            } else {
                //Init data
                $data = [
                    'date' => '',
                    'operator_code' => '',
                    'notes' => '',
                    'date_error' => '',
                    'operator_code_error' => '',
                    'notes_error' => '',

                    //Station variables
                    'start_station' => '',
                    'start_station_error' => '',
                    'start_station_time' => '',
                    'start_station_time_error' => '',
                    'end_station' => '',
                    'end_station_error' => '',
                    'end_station_time' => '',
                    'end_station_time_error' => '',
                    'call_stations' => array(),
                    'call_stations_error' => array(),
                    'call_stations_time' => array(),
                    'call_stations_time_error' => array(),

                    //Unit variables
                    'unit' => array(''),
                    'unit_error' => array(''),

                    //Page variables
                    'operator_select_list' => $this->journeyModel->lov_operator()
                ];

                //Load view
                $this->view('journeys/add', $data);
            }
        }

        public function id($id = 0) {
            //Check for php GET
            if ($id == 0) {
                redirect('journeys/add');
                return;
            }

            if ($id) {
                //Init data

                $data = [
                    'journey_id' => trim($id),
                    'journey_date' => '',
                    'journey_operator' => '',
                    'journey_notes' => '',

                    'start_station_code' => '',
                    'start_station' => '',
                    'start_station_time' => '',
                    'end_station_code' => '',
                    'end_station' => '',
                    'end_station_time' => '',
                    'call_stations' => array(),
                    'call_stations_time' => array(),

                    'units' => array(),
                    
                    
                    'connection_from_service' => array(),
                    'connection_to_service' => array()
                ];

                $journey = $this->journeyModel->journeyDataForID($data['journey_id']);

                if (empty($journey)) {
                    $this->view('journeys/journey_not_exist', $data);
                    return;
                }   
                
                $data['journey_date'] = $journey[0]->journey_date;
                $data['journey_operator'] = $journey[0]->operator_name;
                $data['journey_notes'] = $journey[0]->journey_comments;

                $calls = $this->journeyModel->callDataForJourneyID($data['journey_id']);
                //echo var_dump($calls);
                $n = 0;
                foreach ($calls as $call) {
                    //Start Station?
                    if ($call->call_type_code == 'sta') {
                        $data['start_station_code'] = $call->call_station_code;
                        $data['start_station'] = $call->station_name;
                        $data['start_station_time'] = $call->call_datetime;
                    }
                    //End Station?
                    if ($call->call_type_code == 'end') {
                        $data['end_station_code'] = $call->call_station_code;
                        $data['end_station'] = $call->station_name;
                        $data['end_station_time'] = $call->call_datetime;
                    }
                    //Call Station?
                    if ($call->call_type_code == 'cal') {
                        $data['call_stations'][$n] = $call->station_name;
                        $data['call_stations_time'][$n] = $call->call_datetime;
                        $n++;
                    }                    
                }
                //echo var_dump($data);

                $units = $this->journeyModel->unitDataForJourneyID($data['journey_id']);
                //echo var_dump($units);
                $n = 0;
                foreach ($units as $unit) {
                    $data['units'][$n] = $unit->unit;   
                    $n++;        
                }

                //Look for connections from a previous service
                $connection = $this->journeyModel->connectionFrom($data['journey_date'], $data['start_station_code'], $data['start_station_time']);
                //echo var_dump($connection);
                if ($connection) {
                    $data['connection_from_service']['journey_id'] = $connection->journey_id;
                    $data['connection_from_service']['journey_date'] = $connection->journey_date;
                    $data['connection_from_service']['operator_name'] = $connection->operator_name;
                    $data['connection_from_service']['start_station'] = $connection->start_station;
                    $data['connection_from_service']['start_time'] = $connection->start_time;
                    $data['connection_from_service']['end_station'] = $connection->end_station;
                    $data['connection_from_service']['end_time'] = $connection->end_time;
                    $data['connection_from_service']['connection_length'] = minutes_between($connection->end_time, $data['start_station_time']);
                }

                //Look for connections from a previous service
                $connection = $this->journeyModel->connectionTo($data['journey_date'], $data['end_station_code'], $data['end_station_time']);
                //echo var_dump($connection);
                if ($connection) {
                    $data['connection_to_service']['journey_id'] = $connection->journey_id;
                    $data['connection_to_service']['journey_date'] = $connection->journey_date;
                    $data['connection_to_service']['operator_name'] = $connection->operator_name;
                    $data['connection_to_service']['start_station'] = $connection->start_station;
                    $data['connection_to_service']['start_time'] = $connection->start_time;
                    $data['connection_to_service']['end_station'] = $connection->end_station;
                    $data['connection_to_service']['end_time'] = $connection->end_time;
                    $data['connection_to_service']['connection_length'] = minutes_between($data['end_station_time'], $connection->start_time);
                }

                $this->view('journeys/view', $data);

            } 
        }

        public function search() {

            $data = [
                'start_date' => '',
                'end_date' => '',
                'station' => '',
                'station_code' => '',
                'operator_code' => '',

                'search_start_date' => '2000-01-01',
                'search_end_date' => '2099-12-31',
                'search_station_code' => 'DEFAULT',
                'search_operator_code' => 'DEFAULT',

                'results' => array(),

                //Page variables
                'operator_select_list' => $this->journeyModel->lov_operator()
            ];

            //Check for php POST
            if($_SERVER['REQUEST_METHOD'] == 'POST') {
                //Sanitise POST data
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

                //Post Values
                if ($_POST['start_date']) {
                    $data['start_date'] = $_POST['start_date'];
                    $data['search_start_date'] = $data['start_date'];
                }
                if ($_POST['end_date']) {
                    $data['end_date'] = $_POST['end_date'];
                    $data['search_end_date'] = $data['end_date'];
                }
                if ($_POST['station']) {
                    $data['station'] = $_POST['station'];
                    $data['station_code'] = $this->journeyModel->validStation($data['station']);
                    $data['search_station_code'] = $data['station_code'];
                }
                if ($_POST['operator_code']) {
                    $data['operator_code'] = $_POST['operator_code'];
                    if ($data['operator_code'] <> 'XXX') {
                        $data['search_operator_code'] = $data['operator_code'];
                    } else {
                        $data['operator_code'] = '';
                    }
                }

                //Get Data
                $results = $this->journeyModel->search($data['search_start_date'],$data['search_end_date'], $data['search_operator_code'], $data['search_station_code']);

                foreach ($results as $key => $result) {
                    $data['results'][$key] = $result;
                }

                //echo var_dump($_POST);
                //echo '<br><br><br>';
                echo var_dump($data);

                //Load view
                $this->view('journeys/search', $data);

            } else {
                //Load view
                $this->view('journeys/search', $data);
            }
        }
    }

?>