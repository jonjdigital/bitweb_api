<?php
include "get_user_info.php";

if($user->email === null){
    die("{'code' : '404' , 'message' : 'User not found'}");
}
//get all plans available from the BitWeb database. Save as an object.
//var_dump($user);
$connection->connect("bitweb.cc",api_db_user,api_db_pass, 'jonjdigi_bitweb');
$query = "select * from plans where plan_id = '$user->plan_id'";

$result = $connection->query($query);

$rows = (object) $result->fetch_assoc();

echo "{ 'code' : '200', 'user': '$user->email', 'plan' : '$rows->name'}";