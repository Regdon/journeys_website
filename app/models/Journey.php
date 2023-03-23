<?php
    class Journey {
        private $db;

        public function __construct() {
            $this->db = new Database;
        }

        //Add Journey
        public function addJourney($data) {
            $this->db->query('
                INSERT INTO journey (
                     journey_date
                    ,journey_operator_code
                    ,journey_comments
                ) VALUES (
                     :journey_date
                    ,:journey_operator_code
                    ,:journey_comments
                )'
            );

            $this->db->bind(':journey_date', $data['date']);
            $this->db->bind(':journey_operator_code', $data['operator_code']);
            $this->db->bind(':journey_comments', $data['notes']);

            if ($this->db->execute()) {
                return $this->db->insertID();
            } else {
                return false;
            }
        }

        //Add Journey Station Call
        public function addStationCall($data) {
            $this->db->query('
                INSERT INTO station_call (
                    call_journey_id
                    ,call_station_code
                    ,call_datetime
                    ,call_type_code
                ) VALUES (
                    :call_journey_id
                    ,:call_station_code
                    ,:call_datetime
                    ,:call_type_code
                )'
            );

            $this->db->bind(':call_journey_id', $data['journey_id']);
            $this->db->bind(':call_station_code', $data['station_code']);
            $this->db->bind(':call_datetime', $data['datetime']);
            $this->db->bind(':call_type_code', $data['call_type_code']);

            if ($this->db->execute()) {
                return true;
            } else {
                return false;
            }           
        }

        //Add Journey Unit
        public function addUnit($data) {
            $this->db->query('
                INSERT INTO journey_unit (
                    journey_id
                    ,unit
                ) VALUES (
                    :journey_id
                    ,:unit
                )'
            );

            $this->db->bind(':journey_id', $data['journey_id']);
            $this->db->bind(':unit', $data['unit']);

            if ($this->db->execute()) {
                return true;
            } else {
                return false;
            } 
        }

        //Return journey data for id
        public function journeyDataForID($journey_id) {
            $this->db->query('
                SELECT
                    journey.*
                    ,operator.operator_name
                FROM
                    journey
                    LEFT JOIN operator ON
                        journey.journey_operator_code = operator.operator_code
                WHERE
                    journey.journey_id = :journey_id
            ');
            $this->db->bind(':journey_id', $journey_id);
            $data = $this->db->resultSet();

            return $data;       
        }

        //Return station_call data for joiurney_id
        public function callDataForJourneyID($journey_id) {
            $this->db->query('
                SELECT
                    station_call.*
                    ,station.station_name
                    ,call_type.call_type
                FROM
                    station_call
                    LEFT JOIN station ON
                        station_call.call_station_code = station.station_code
                    LEFT JOIN call_type ON
                        station_call.call_type_code = call_type.call_type_code
                WHERE
                    station_call.call_journey_id = :journey_id
            ');
            $this->db->bind(':journey_id', $journey_id);
            $data = $this->db->resultSet();

            return $data;  
        }

        //Return journey_unit data for journey_id
        public function unitDataForJourneyID($journey_id) {
            $this->db->query('
                SELECT
                    journey_unit.*
                FROM
                    journey_unit
                WHERE
                    journey_unit.journey_id = :journey_id
            ');
            $this->db->bind(':journey_id', $journey_id);
            $data = $this->db->resultSet();

            return $data;  
        }

        //Check for valid operator
        public function validOperator($operator_code) {
            $this->db->query('
                SELECT * FROM operator WHERE operator_code = :operator_code
            ');
            $this->db->bind(':operator_code', $operator_code);
            $row = $this->db->singleRow();

            //check row
            if ($this->db->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        }

        //List of Operators
        public function lov_operator() {
            $this->db->query('
                SELECT
                     operator_code
                    ,operator_name
                FROM
                    operator
            ');

            $data = $this->db->resultSet();

            return $data;
        }

        //Check for valid station
        public function validStation($station_name) {
            $this->db->query("
                SELECT * FROM station WHERE REPLACE(station_name, '''', '&#39;') = :station_name
            ");            
            $this->db->bind(':station_name', $station_name);
            $row = $this->db->singleRow();

            //check row
            if ($this->db->rowCount() > 0) {
                return $row -> station_code;
            } else {
                return false;
            }
        }

        //Connection From...
        public function connectionFrom($date, $station_code, $time) {
            $this->db->query('
            SELECT
                journey.journey_id
                ,journey.journey_date
                ,operator.operator_name
                ,start_station_details.station_name AS start_station
                ,start_station.call_datetime AS start_time
                ,end_station_details.station_name AS end_station
                ,end_station.call_datetime AS end_time
            FROM
                journey
                
                INNER JOIN station_call AS start_station ON
                    journey.journey_id = start_station.call_journey_id
                    AND start_station.call_type_code = "sta"
                    
                INNER JOIN station_call AS end_station ON
                    journey.journey_id = end_station.call_journey_id  
                    AND end_station.call_type_code = "end"
                    
                LEFT JOIN operator ON
                    journey.journey_operator_code = operator.operator_code
                    
                LEFT JOIN station AS start_station_details ON
                    start_station.call_station_code = start_station_details.station_code
                    
                LEFT JOIN station AS end_station_details ON
                    end_station.call_station_code = end_station_details.station_code
                    
            WHERE
                journey.journey_date = :date
                AND end_station.call_station_code = :station_code
                AND end_station.call_datetime BETWEEN ADDTIME(:time, "-02:00:00") AND :time
                
            ORDER BY
                start_station.call_datetime DESC
                
            LIMIT
                1;
            ');
            $this->db->bind(':date', $date);
            $this->db->bind(':station_code', $station_code);
            $this->db->bind(':time', $time);

            $data = $this->db->singleRow();

            return $data;  
        }

        //Connection To...
        public function connectionTo($date, $station_code, $time) {
            $this->db->query('
            SELECT
                journey.journey_id
                ,journey.journey_date
                ,operator.operator_name
                ,start_station_details.station_name AS start_station
                ,start_station.call_datetime AS start_time
                ,end_station_details.station_name AS end_station
                ,end_station.call_datetime AS end_time
            FROM
                journey
                
                INNER JOIN station_call AS start_station ON
                    journey.journey_id = start_station.call_journey_id
                    AND start_station.call_type_code = "sta"
                    
                INNER JOIN station_call AS end_station ON
                    journey.journey_id = end_station.call_journey_id  
                    AND end_station.call_type_code = "end"
                    
                LEFT JOIN operator ON
                    journey.journey_operator_code = operator.operator_code
                    
                LEFT JOIN station AS start_station_details ON
                    start_station.call_station_code = start_station_details.station_code
                    
                LEFT JOIN station AS end_station_details ON
                    end_station.call_station_code = end_station_details.station_code
                    
            WHERE
                journey.journey_date = :date
                AND start_station.call_station_code = :station_code
                AND start_station.call_datetime BETWEEN :time AND ADDTIME(:time, "02:00:00")
                
            ORDER BY
                start_station.call_datetime ASC
                
            LIMIT
                1;
            ');
            $this->db->bind(':date', $date);
            $this->db->bind(':station_code', $station_code);
            $this->db->bind(':time', $time);

            $data = $this->db->singleRow();

            return $data;  
        }

        //Search Journeys
        public function search($start_date = '2000-01-01', $end_date = '2099-12-31', $operator_code = 'DEFAULT', $station_code = 'DEFAULT') {
            
            $this->db->query('
                SELECT
                    journey.journey_id
                    ,journey.journey_date
                    ,journey.journey_operator_code
                    ,operator.operator_name
                    ,start_station.call_station_code AS start_station_code
                    ,start_station_desc.station_name AS start_station
                    ,start_station.call_datetime AS start_time
                    ,end_station.call_station_code AS end_station_code
                    ,end_station_desc.station_name AS end_station
                    ,end_station.call_datetime AS end_time

                FROM
                    journey
                    
                    INNER JOIN operator ON
                        journey.journey_operator_code = operator.operator_code
                        
                    INNER JOIN station_call AS start_station ON
                        journey.journey_id = start_station.call_journey_id
                        AND start_station.call_type_code = "sta"
                        
                    INNER JOIN station AS start_station_desc ON
                        start_station.call_station_code = start_station_desc.station_code
                        
                    INNER JOIN station_call AS end_station ON
                        journey.journey_id = end_station.call_journey_id
                        AND end_station.call_type_code = "end"
                        
                    INNER JOIN station AS end_station_desc ON
                        end_station.call_station_code = end_station_desc.station_code
                        
                    INNER JOIN (
                        SELECT DISTINCT
                            journey.journey_id
                        FROM
                            journey
                        
                            INNER JOIN station_call ON
                                journey.journey_id = station_call.call_journey_id
                        
                        WHERE
                            journey.journey_date BETWEEN :start_date AND :end_date
                            AND (journey.journey_operator_code = :operator_code OR :operator_code = "DEFAULT")
                            AND (station_call.call_station_code = :station_code OR :station_code = "DEFAULT")
                    ) AS filter ON
                        journey.journey_id = filter.journey_id
                        
                ORDER BY
                    journey.journey_date DESC
                    ,start_station.call_datetime DESC;
            ');

            $this->db->bind(':start_date', $start_date);
            $this->db->bind(':end_date', $end_date);
            $this->db->bind(':operator_code', $operator_code);
            $this->db->bind(':station_code', $station_code);

            $data = $this->db->resultSet();

            return $data;
        }
    }
    
?>