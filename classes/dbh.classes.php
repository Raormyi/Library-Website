<?php

class Dbh {
    public function connect()
    {
        try {
            $username = "root";
            $password = "root";
            $dbh = new PDO("mysql:host=localhost:8889;dbname=LibraryDatabase", $username, $password);
            return $dbh;
        }
        catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }
}

