<?php
require "Database.php";
require "GoogleChartFormatter.php";

Class Query {

    /**
    * search query with post requst
    */
    function search() {
        $type = $_POST['type'];
        $dbName = $_POST['database'];
        $host = $_POST['host'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $query = $_POST['query'];
        $xaxis = isset($_POST['xaxis']) ? $_POST['xaxis'] : "";
        $yaxis = isset($_POST['yaxis']) ? $_POST['yaxis'] : "";
        $chartType = isset($_POST['chartType']) ? $_POST['chartType'] : "";

        if (!empty($dbName) && !empty($host) && !empty($username) && !empty($query)) {
            $db = new Database();
            $result = null;

            try {
                $db->connect($host, $dbName, $username, $password, $type);
                $result = $db->query($query);
            } catch (\PDOException $e) {
                echo $e->getMessage();
            }

            header('Content-Type: application/json; charset=utf-8');
            $formatter = new GoogleChartFormatter();

            if ($result && $chartType == 'multiple' && $xaxis && $yaxis) {
                echo $formatter->formatForBars($result, $xaxis, $yaxis);

            } else if ($result && $chartType == 'single') {
                echo $formatter->formatForPie($result);

            }

        }
    }
}

(new Query())->search();

