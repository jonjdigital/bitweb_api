<?php
include "variables.php";

//check if key is present before continuing
if(!isset($_GET['key'])){
    die("{ code : '401', message : 'No API Key Provided' }");
}

//check if current page is index
if($_SERVER['SCRIPT_NAME'] == "/index.php" || $_SERVER['SCRIPT_NAME'] == "/"){
    echo "This is the index page";
}

//initiate DB connection
$connection = mysqli_connect("bitweb.cc",api_db_user, api_db_pass, api_db);

//get variables from DB
$stm = "select * from variables";
$result = mysqli_query($connection,$stm);
$connection->close();
//$db_vars = [];
//var_dump($result->fetch_row());
while($rows = $result->fetch_assoc()){
//    var_dump($rows);
    $db_vars[$rows['key']] = $rows['value'];
}

//var_dump($db_vars);