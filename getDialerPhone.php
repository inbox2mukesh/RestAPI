<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET,POST");
//header("Access-Control-Allow-Methods: POST");
//header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json");

$apikey = ""; $result = "";$phone_no = "";
if(isset($_GET['phone_no']) && $_GET['phone_no']!=''){
    $phone_no = $_GET['phone_no'];
}

try {
    foreach (apache_request_headers() as $key => $value) {
        if (($key == strtolower("API-KEY") || $key == "API-KEY") && $value == "wosa") {
            $apikey = $value;
        }
    }

    //require_once('../../include/MysqliDb.php');
    include('../../include/config.php');
    //include "dbConnection.php";

    if ($apikey != "" || $apikey == "") {
            if($phone_no!=''){
            $commonSql = "SELECT * FROM students WHERE contactNo = '".$phone_no."' ";
            $result = $db->query($commonSql);
            if (count($result) >= 1) {
                response("success",$result,200);
            } else {
                $q = "Insert INTO dialer_new_leads (phoneno) VALUES ('".$phone_no."')";
                $insert = $db->query($q);
                response("success",$result,400);
            }

        }else{
            response("Phone No required",$result,400);
        }
    }
    else {
             response("Authentication required",$result,400);
         }
}
catch (Exception $exception)
{
    response($exception->getMessage(),$result,500);
}


function response($message, $result, $resp_code)
{
    $response = array("message" => $message, "data" => $result);
    http_response_code($resp_code);
    echo json_encode($response);
    return 0;

}


