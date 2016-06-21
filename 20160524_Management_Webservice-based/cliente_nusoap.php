<?php
require_once "lib/nusoap.php";
 
$client = new nusoap_client("http://130.89.14.205/net_management/20160524_Lecture_Management_Webservice/server_nusoap.php?wsdl", true);
$error  = $client->getError();
 
if ($error) {
    echo "<h2>Constructor error</h2><pre>" . $error . "</pre>";
}
 
########################################
########################################
## CALL YOUR SERVER FUNCTIONS HERE !!
########################################
########################################
$response1 = $client->call("functions.getGrade", array("studentType" => $_GET["studentType"]));
$response2 = $client->call("functions.getUserInfo", array("userId" => 1));
#$response3 = $client->call("functions.getDate", array());
$response4 = $client->call("functions.getSystemInfo", array());
$response5 = $client->call("functions.getCoreNumbers", array());


########################################
########################################
## DISPLAY THE RESPONSES HERE!!!
########################################
########################################
if ($client->fault) {
    echo "<h2>Fault</h2><pre>";
    print_r($result);
    echo "</pre>";
} else {
    $error = $client->getError();
    if ($error) {
        echo "<h2>Error</h2><pre>" . $error . "</pre>";
    } else {
        echo "<h2>Response</h2>";
        echo "<h1>".$response1."</h1>";
        echo "<br>";
        echo "<br>";
        print_r ($response2);
 #       echo "Time: ".$response3;
        echo "<br>";
       echo "SystemInfo: ".$response4;
        echo "<br>";
        echo "Core Numbershame: ".$response5;



    }
}
 
// Showing the Response and the Request
echo "<h2>Request</h2>";
echo "<pre>" . htmlspecialchars($client->request, ENT_QUOTES) . "</pre>";
echo "<h2>Response</h2>";
echo "<pre>" . htmlspecialchars($client->response, ENT_QUOTES) . "</pre>";

?>
