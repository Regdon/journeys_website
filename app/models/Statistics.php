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
    
    }