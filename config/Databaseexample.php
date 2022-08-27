<?php

//  Database Class
//  This file and classname should be renamed to Database.php
//  This is uploaded to the repo so that the correct database connection details are not exposed publicly
class Databaseexample
{
    //  DB Parameters
    //  Access is set to private so that these properties can only be accessed from this class
    private $db_host        = 'db_host';
    private $db_name        = 'db_name';
    private $db_user        = 'db_user';
    private $db_password    = 'db_password';
    private $conn;

    //  Database Connection
    public function connect()
    {
        //  The connection is instantiated with NULL
        $this->conn = null;

        //  Since we want to throw an exception when the connection fails, we use a try catch block to handle the exception
        try {
            //  Connect to the Database
            //  Instantiate a new PDO instance
            //  PDO takes 3 parameters
                    //  1.  DSN - Data Source Network
                                    //  Database Type - MySQL
                                    //  Database Name
                    //  2.  Database Username
                    //  3.  Database Password
            $this->conn = new PDO('mysql:host=' . $this->db_host . ';dbname=' . $this->db_name, $this->db_user, $this->db_password);
            //    Catch the PDO Exception and store it in the variable $e
        } catch(PDOException $e) {
            //  Output the error message
            echo '<b>Connection Error:</b> ' . $e->getMessage();
        }

        //  Return the connection
        return $this->conn;
    }
}