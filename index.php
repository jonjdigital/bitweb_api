<?php
include "variables.php";

//check if key is present before continuing
if(!isset($_GET['key'])){
//    die(json_encode("code : '401', message : 'No API Key Provided'"));
    $response = [];
    $response['code'] = 401;
    $response['message'] = "No API Key Provided";

    $response = json_encode($response);
    die($response);
}
//check if current page is index
//if($_SERVER['SCRIPT_NAME'] == "/index.php" || $_SERVER['SCRIPT_NAME'] == "/"){
//    echo "This is the index page";
//}
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

/** function to get the plan name from the BitWeb database once the user information has been pulled */
function get_plan($id){
    $connection = mysqli_connect("bitweb.cc",api_db_user,api_db_pass, 'jonjdigi_bitweb');
    $query = "select * from plans where plan_id = '$id'";

    $result = $connection->query($query);

    while($rows = mysqli_fetch_assoc($result)){
        $plan['plan_id'] = $rows['plan_id'];
        $plan['name'] = $rows['name'];

        return $plan;
    }
}


/** by stating an action parameter, it will reduce the risk of any external users from accessing user data**/
//http://api.bitweb.cc/index.php?key=key&action=get_user&data=user@domain.com
if($_GET['action'] == "get_user"){

    //see if email is provided and check if its valid
    if(!isset($_GET['data'])){
        $response = [];
        $response['code'] = 400;
        $response['message'] = "Email not Provided";

        $response = json_encode($response);
        die($response);
    }else{
        //connect to the BitWeb Database
        $connection = mysqli_connect("bitweb.cc",api_db_user,api_db_pass, 'jonjdigi_bitweb');

        //query BitWeb Database to see if user exists
        $email = $_GET['data'];
        $query = "select * from users where email = '$email'";

        $user_res = mysqli_query($connection,$query);
        $connection->close();
        $user = [];

        if($user_res->num_rows == 0){
            $user['user_id'] = null;
            $user['email'] = null;
            $user['plan_id'] = null;
            $user['plan_name'] = null;

            $reponse = [];
            $response['code'] = 404;
            $response['message'] = "User Not Found";
            $response['data'] = $user;

            die(json_encode($response));
        }else {
            while ($row = $user_res->fetch_assoc()) {
                $user['user_id'] = $row['user_id'];
                $user['email'] = $row['email'];
                $user['plan_id'] = $row['plan_id'];
                $plan = get_plan($user['plan_id']);

                $user['plan_name'] = $plan['name'];
            }
        }

        $reponse = [];
        $response['code'] = 200;
        $response['message'] = "User Found, Information in Data";
        $response['data'] = $user;

        die(json_encode($response));
    }
}
