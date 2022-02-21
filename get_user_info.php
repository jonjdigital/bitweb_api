<?php
include "index.php";

//check if users email is in the url
if(!isset($_GET['user_email'])){
    die("{ 'code' : '400', 'message' : 'Users email is not provided'}");
}

//convert the vars array into an object for easier calling
$vars = (object) $db_vars;

//connect to the BitWeb Database
$connection = mysqli_connect("bitweb.cc",api_db_user,api_db_pass, 'jonjdigi_bitweb');

//echo $connection->host_info;
//var_dump($bitweb_connection);

//if($connection){
//    echo "Connected to BitWeb Database.";
//}else{
//    echo "Not connected to BitWeb Database";
//}

//query BitWeb Database to see if user exists
$email = $_GET['user_email'];
$query = "select * from users where email = '$email'";

$user_res = mysqli_query($connection,$query);
$connection->close();
//declare user array
$user = [];
if($user_res->num_rows == 0){
    $user['user_id'] = null;
    $user['email'] = null;
    $user['plan_id'] = null;
}else {
    while ($row = $user_res->fetch_assoc()) {
        $user['user_id'] = $row['user_id'];
        $user['email'] = $row['email'];
        $user['plan_id'] = $row['plan_id'];
    }
}
//convert user array into an object
$user = (object) $user;