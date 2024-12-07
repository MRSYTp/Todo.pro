<?php 
session_start();
include "constants.php";
include TODO_PATH ."bootstrap/config.php";
include TODO_PATH ."libs/helpers.php";
include TODO_PATH ."vendor/autoload.php";



// var_dump($config_database);
try {
    $conn = new PDO("mysql:host=$config_database->host;dbname=$config_database->db", $config_database->user,$config_database->pass);
} catch (PDOException $e) {
     echo "ERROR!";
     die();
}


include TODO_PATH ."libs/lib_functions.php";
include TODO_PATH ."libs/lib-auth.php";