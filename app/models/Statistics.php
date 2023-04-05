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
    
    }