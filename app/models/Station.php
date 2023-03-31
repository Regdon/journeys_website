<?php
    class Station {
        private $db;

        public function __construct() {
            $this->db = new Database;
        }
    
        //Search Stations
        public function searchStations($search_param) {
            $this->db->query("
                SELECT
                    station_code
                    ,station_name

                FROM
                    station

                WHERE
                    station_code LIKE CONCAT('%', UPPER(:search_param), '%')
                    OR UPPER(station_name) LIKE CONCAT('%', UPPER(:search_param), '%')

                ORDER BY
                    CASE
                        WHEN station_code = UPPER(:search_param) THEN 0
                        ELSE 1
                    END ASC
                    ,station_name ASC
                    
                LIMIT 10;           
            ");

            //echo 'aaaaaaa';
            //echo $search_param;

            $this->db->bind(':search_param', str_replace("'", "\'", $search_param));

            $data = $this->db->resultSet();

            return $data;
        }

        //Basic Station Data
        public function stationData($station_code) {
            $this->db->query("
                SELECT
                      station.station_code
                    , station.station_name
                    , station.station_longitude
                    , station.station_latitude
                    , station.station_usage_1920 AS station_usage
                    , station.station_rank_1920 AS station_rank
                    , station.country
                    , station.county
                FROM
                    station
                WHERE
                    station.station_code = :station_code
            ");

            $this->db->bind(':station_code', $station_code);
            $data = $this->db->singleRow();
            return $data;
        }

        public function visitedInformation($station_code) {
            $this->db->query("
                SELECT
                      station.station_code
                    ,  MIN(CASE WHEN station_call.call_type_code = 'sta' THEN journey.journey_date END) AS first_board_date
                    ,  MAX(CASE WHEN station_call.call_type_code = 'sta' THEN journey.journey_date END) AS last_board_date
                    ,  MIN(CASE WHEN station_call.call_type_code = 'cal' THEN journey.journey_date END) AS first_call_date
                    ,  MAX(CASE WHEN station_call.call_type_code = 'cal' THEN journey.journey_date END) AS last_call_date
                    ,  MIN(CASE WHEN station_call.call_type_code = 'end' THEN journey.journey_date END) AS first_alight_date
                    ,  MAX(CASE WHEN station_call.call_type_code = 'end' THEN journey.journey_date END) AS last_alight_date
                FROM
                    station
                    
                    LEFT JOIN station_call ON 
                        station.station_code = station_call.call_station_code
                    
                    LEFT JOIN journey ON
                        station_call.call_journey_id = journey.journey_id

                WHERE
                    station.station_code = :station_code

                GROUP BY
                        station.station_code
            ");

            $this->db->bind(':station_code', $station_code);
            $data = $this->db->singleRow();
            return $data;
        }

        public function journeyHistory($station_code) {
            $this->db->query("
                SELECT
                    journey.journey_id
                    ,journey.journey_date
                    ,operator.operator_name
                    ,call_type.call_type
                    ,station_call.call_datetime
                    ,journey_start.call_datetime AS journey_start_time
                    ,start_station.station_name AS journey_start_station
                    ,journey_end.call_datetime AS journey_end_time
                    ,end_station.station_name AS journey_end_station

                FROM
                    station_call

                    INNER JOIN journey ON
                        station_call.call_journey_id = journey.journey_id

                    LEFT JOIN station_call AS journey_start ON
                        journey.journey_id = journey_start.call_journey_id
                        AND journey_start.call_type_code = 'sta'

                    LEFT JOIN station_call AS journey_end ON
                        journey.journey_id = journey_end.call_journey_id
                        AND journey_end.call_type_code = 'end'

                    LEFT JOIN operator ON
                        journey.journey_operator_code = operator.operator_code

                    LEFT JOIN call_type ON
                        station_call.call_type_code = call_type.call_type_code

                    LEFT JOIN station AS start_station ON
                        journey_start.call_station_code = start_station.station_code

                    LEFT JOIN station AS end_station ON
                        journey_end.call_station_code = end_station.station_code

                WHERE
                    station_call.call_station_code = :station_code

                ORDER BY
                      journey.journey_date DESC
                    , station_call.call_datetime DESC   
                    
                LIMIT 50
            ");

            $this->db->bind(':station_code', $station_code);

            $data = $this->db->resultSet();

            return $data;
        }
    
    }