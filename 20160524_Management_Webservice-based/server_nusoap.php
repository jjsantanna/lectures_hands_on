<?php
require_once "lib/nusoap.php";
 
class functions {
    public function getGrade($studentType) {
        switch ($studentType) {
            case 'Lazy':
                return 'Grade 5';
                break;
            case 'Good':
                return 'Grade 7';
                break;
            case 'Awesome':
                return 'Grade 9';
                break;
            default:
                break;
        }
    }
    
    function getUserInfo($userId) {
        if ($userId == 1) {
            return array(
               'id' => 1,
                'username' => 'Jair Santanna',
                'email' => 'j.j.santanna@utwente.nl'
            );
        } else {
        return new soap_fault('SOAP-ENV:Server', '', 'Requested user not found', '');
        }
    }
    
    

#    public function getDate(){  
#        return date("Y-m-d H:i:s");  
#    }   

    public function getSystemInfo(){
        return shell_exec('uname -a');
    }

    public function getCoreNumbers(){
        return shell_exec('cat /proc/cpuinfo | grep processor | wc -l');
    }
  
}
 



$server = new soap_server();

$server->configureWSDL("Jair's WebServer Functions", "http://130.89.14.205/net_management/20160524_Lecture_Management_Webservice/server_nusoap.php");
 
$server->wsdl->addComplexType(
    'userInfo',
    'complextType',
    'struct',
    'sequence',
    '',
    array(
        'id' => array('name' => 'id', 'type' => 'xsd:integer'),
        'username' => array('name' => 'username', 'type' => 'xsd:string'),
        'email' => array('name' => 'email', 'type' => 'xsd:string')
    )
);

$server->register("functions.getGrade",
    array("studentType" => "xsd:string"),
    array("return" => "xsd:string"),
    "http://130.89.14.205/net_management/20160524_Lecture_Management_Webservice/server_nusoap.php",
    "http://130.89.14.205/net_management/20160524_Lecture_Management_Webservice/server_nusoap.php#getFood",
    "rpc",
    "encoded",
    "Get grade based on the type of student");


$server->register("functions.getUserInfo",
    array("userId" => "xsd:integer"),
    array("return" => "tns:userInfo"),
    "http://130.89.14.205/net_management/20160524_Lecture_Management_Webservice/server_nusoap.php",
    "http://130.89.14.205/net_management/20160524_Lecture_Management_Webservice/server_nusoap.php#getUserInfo",
    "rpc",
    "encoded",
    "Get info for user"
);



#$server->register("functions.getDate",
#    array(),
#    array("return" => "xsd:date"),
#    "http://130.89.14.205/net_management/20160524_Lecture_Management_Webservice/server_nusoap.php",
#    "http://130.89.14.205/net_management/20160524_Lecture_Management_Webservice/server_nusoap.php#getDate",
#    "rpc",
#    "encoded",
#    "Get current date");

$server->register("functions.getSystemInfo",
    array(),
    array("return" => "xsd:string"),
    "http://130.89.14.205/net_management/20160524_Lecture_Management_Webservice/server_nusoap.php",   "http://130.89.14.205/net_management/20160524_Lecture_Management_Webservice/server_nusoap.php#getSystemInfo",
    "rpc",
    "encoded",
    "Get the information of the system");

$server->register("functions.getCoreNumbers",
    array(),
    array("return" => "xsd:string"),
    "http://130.89.14.205/net_management/20160524_Lecture_Management_Webservice/server_nusoap.php",
    "http://130.89.14.205/net_management/20160524_Lecture_Management_Webservice/server_nusoap.php#getCoreNumbers",
    "rpc",
    "encoded",
    "Get the number of cores");
 
@$server->service($HTTP_RAW_POST_DATA);
