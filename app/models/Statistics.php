<?php
    class Statistics {
        private $db;

        public function __construct() {
            $this->db = new Database;
        }
    
        //Visited Summary
        public function visitedSummary() {
            $this->db->query("
                SELECT
                    CASE
                        WHEN visited_stations.call_station_code IS NOT NULL THEN 'Visited'
                        WHEN called_stations.call_station_code IS NOT NULL THEN 'Called'
                        ELSE 'Not Visited'
                    END AS station_status
                    ,COUNT(1) AS number_of_stations

                FROM
                    station

                    LEFT JOIN (
                        SELECT DISTINCT
                            call_station_code
                        FROM
                            station_call
                        WHERE
                            call_type_code IN ('sta', 'end')
                    ) AS visited_stations ON
                        station.station_code = visited_stations.call_station_code

                    LEFT JOIN (
                        SELECT DISTINCT
                            call_station_code
                        FROM
                            station_call
                        WHERE
                            call_type_code = 'cal'
                    ) AS called_stations ON
                        station.station_code = called_stations.call_station_code

                GROUP BY
                    CASE
                        WHEN visited_stations.call_station_code IS NOT NULL THEN 'Visited'
                        WHEN called_stations.call_station_code IS NOT NULL THEN 'Called'
                        ELSE 'Not Visited'
                    END    
            ");

            $data = $this->db->resultSet();

            return $data;
        }

        //Monthly New Stations
        public function monthlyNewStations() {
            $this->db->query("
                SELECT
                    months.month
                    ,months.month_description
                    ,months.year
                    ,COUNT(first_visit_month.station_code) AS count_of_stations

                FROM
                    months

                    LEFT JOIN (
                        SELECT
                            station_call.call_station_code AS station_code
                            ,MIN(LAST_DAY(journey.journey_date)) AS first_visited_month

                        FROM
                            station_call

                            INNER JOIN journey ON
                                station_call.call_journey_id = journey.journey_id

                        WHERE
                            station_call.call_type_code IN ('sta', 'end')

                        GROUP BY
                            station_call.call_station_code
                    ) AS first_visit_month ON
                        first_visit_month.first_visited_month = months.month_end

                WHERE
                    months.month < CURDATE()

                GROUP BY
                    months.month
                    ,months.month_description
                    ,months.year

                ORDER BY 
                    months.month DESC
            ;");

            $data = $this->db->resultSet();
            return $data;
        }

        //Check for valid station code
        public function validStationCode($station_code = 'WRK') {
            $this->db->query("
                SELECT
                    *

                FROM
                    station

                WHERE
                    UPPER(station_code) = UPPER(:station_code)
            ");            
            $this->db->bind(':station_code', $station_code);
            $row = $this->db->singleRow();

            //check row
            if ($this->db->rowCount() > 0) {
                return $row;
            } else {
                return false;
            }
        }

        //Get closest x stations to the chosen station
        public function closestStations($to_station_code = 'WRK', $number_of_stations = 400) {
            $this->db->query("
                SELECT
                    station_code
                    ,station_longitude
                    ,station_latitude

                FROM
                    station

                WHERE
                    UPPER(station_code) = :station_code
            ");  
            $this->db->bind(':station_code', $to_station_code);

            $row = $this->db->singleRow();
            $long = $row->station_longitude;
            $lat = $row->station_latitude;

            $this->db->query("
                SELECT
                     station_code
                    ,station_name
                    ,station_longitude
                    ,station_latitude
                    ,ACOS(SIN(RADIANS(:lat)) * SIN(RADIANS(station_latitude)) + COS(RADIANS(:lat)) * COS(RADIANS(station_latitude)) * COS(RADIANS(ABS(:long - station_longitude)))) AS great_circle_distance
                    ,CASE
                        WHEN visited.station IS NOT NULL THEN 'green'
                        WHEN called.station IS NOT NULL THEN 'blue'
                        ELSE 'red'
                    END AS status
                FROM
                    station

                    LEFT JOIN (
                        SELECT DISTINCT
                            call_station_code AS station
                        FROM
                            station_call
                        WHERE
                            call_type_code = 'cal'
                        ) AS called ON
                            station.station_code = called.station

                    LEFT JOIN (
                        SELECT DISTINCT
                            call_station_code AS station
                        FROM
                            station_call
                        WHERE
                            call_type_code IN ('sta', 'end')
                        ) AS visited ON
                            station.station_code = visited.station

                ORDER BY
                    great_circle_distance ASC

                LIMIT :number_of_stations
            ");  

            $this->db->bind(':lat', $lat);
            $this->db->bind(':long', $long);
            $this->db->bind(':number_of_stations', $number_of_stations);

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
    
    }