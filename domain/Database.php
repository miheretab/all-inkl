<?php

Class Database {

    private $db;

    /**
    * connect Database
    */
    function connect($host, $dbName, $username, $password, $databaseType = "mysql")
    {
        $dsn = "$databaseType:host=$host;dbname=$dbName";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ];

      $this->db = new PDO($dsn, $username, $password, $options);
    }

    /**
    * Query function
    */
    function query($query) {
        return $this->db->query($query)->fetchAll(PDO::FETCH_OBJ);
    }

}

