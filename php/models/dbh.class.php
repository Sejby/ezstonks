<?php 
    class DBH{
        protected function connect(){
            try {
                $username = "root";
                $password = "";
                $dbh = new PDO('mysql:host=localhost;dbname=ezstonks', $username, $password);
                return $dbh;

            } catch (PDOException $e) {
                echo 'MySQL Error...'. $e->getMessage() . '</br>';
                die();
            }
        }


    }

?>