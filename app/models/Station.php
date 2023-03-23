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
    
    }