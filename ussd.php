<?php
/* 
* Author : Chilufya Mukuka @ChilusoftCorp
* check more interesting projects at my github url -- > github.com/chilusoft
* App Name : XXXXXXX USSD Application written in PHP for consumer deployment
*/

require_once('dbh.php');

//here i do the database tests


/* We get the super global variables from the Hosting gateway, ZICTA's Gateway test bed job here :-)
* Depending with the gateway we might be forced to change the method POST or they might remain
* the same thoughtout
*/
$TransId = $_GET['TransId'];
$MSISDN = $_GET['MSISDN'];
$Pid = $_GET['Pid'];
$RequestType =  $_GET['RequestType'];
$AppId = $_GET['AppId'];
$sessionID = $_GET['sessionId']; // comes in handy when doing the audits, 
$ShortCode = $_GET['ShortCode'];
$CellID =  $_GET['CellID'];
$USSDString = $_GET['USSDString'];

// Did the first milestone of fetching the variables from the USSD gateway test bed, lets move on

// for purposes of registration of new clients,partners and agents
$regNo="";
$fName="";
$lName="";
$gender="";
$genderV="";
$pass="";
$acceptDeny="";
$username="";
$password="";

$resLevel = 0; //tracking the level of user input, level 0 is the welcome screen

if($USSDString != ""){
    $USSDString = str_replace("#","*", $USSDString);
    $USSDStringExplode = explode("*", $USSDString);
    $resLevel = count($USSDStringExplode);

}
if($resLevel == 0 || USSDString == ""){ // TODO: 0 or 1, 1 seemed to work but 0 was the default initial response
    showMenu();
}
// the show_menu() function  is created here, just below
function showMenu(){
    $ussdText = "CON Welcome to Moyo Life Health Insurance. <br>".
    "1. login<br>".
    "2. Register<br>";
    
    ussdnext($ussdText);
    goBack($details);
}
function ussdnext($ussdText){
    echo $ussdText;
    return 0;
}
if($resLevel > 0){
    switch($USSDStringExplode[0]){
        case 0:
            showMenu();
            break;
        case 1:
        login($USSDStringExplode,$MSISDN); 
        break;
        case 2:
        register($USSDStringExplode,$MSISDN);
        break;
        
        default:
            echo "Invalid response!";
            $RequestType = 3;
            break;
    }
}

//TOP LEVEL FUNCTIONS GO HERE ==========
function login($details,$number){

    
    if(count($details) == 1){
        $ussdText = "CON Welcome To the login Portal. Login As:<br>".
                "1. Client<br>".
                "2. Agent<br>".
                "3. Partners(Hospital)<br>";
        ussdnext($ussdText);
        goBack($details);
    }elseif(count($details == 2)){
            switch ($details[1]) {
                case 0:
                    goBackAct($details);
                    break;
                case 1:
                    if(count($details) == 2){
                        echo "Enter username or ID<br>";
                        goBack($details);

                    }
                    elseif(count($details) == 3){
                       
                        echo "Enter PIN code<br>";
                        goBack($details);
                        
                    }elseif(count($details) == 4){
                        echo "Welcome user. Choose an Option<br>".
                            "1. Life Insurance<br>".
                            "2. Health Insurance<br>".
                            "3. Insurance Cover<br>".
                            "4. Appointments<br>";
                            goBack($details);
                    }elseif(count($details) == 5){
                        switch ($details[4]) {
                            case 1:
                                echo "Life Insurance Cover details go here and/or send an SMS<br>";
                                goBack($details);
                                break;
                            case 2:
                                echo "Health insurance details go here<br>";
                                goBack($details);
                                break;
                            case 3:
                                echo "Insurance Cover details<br>";
                                goBack($details);
                                break;
                            default:
                                echo "invalid Option, try again<br>";
                                goBack($details);
                                break;
                        }
                    }
                    break;
                case 2:
                    if(count($details) == 2){
                        echo "Enter agent id";
                        goBack($details);
                    }elseif(count($details) == 3){
                        echo "Enter password";
                        goBack($details);
                    }elseif(count($details) == 4){
                        echo "Welcome Agent. <br>".
                            "1. View details<br>";
                            goBack($details);
                    }
                    break;

                case 3:
                    if(count($details) == 2){
                        echo "enter partner id";
                    }
                    elseif(count($details) == 3){
                        echo "enter PIN code";
                    }elseif(count($details) == 4){
                        echo "welcome {partner}<br>".
                        "Choose an option ";    
                    }
                default:
                    echo "invalid option entered";
                    break;
            }
    }
    
}
//the login route ends here
function register($details,$number){
        if(count($details) == 1){
            $ussdText = "Enter you NRC/ID Number<br>";
            ussdnext($ussdText);
            goBack($details);
        }elseif(count($details) ==2){
                $ussdText = "Use ".$number." as your mobile number? <br>y=yes, n=no<br>";
                ussdnext($ussdText);
                goBack($details);
              
        }elseif(count($details) ==3){
                if($details[2] == "y"){
      
                $ussdText = "Enter your First Name<br>";
                ussdnext($ussdText);
                }elseif($details[2] == "n"){
                        echo "Enter the mobile to register<br>";
                }
                goback($details);
        }elseif(count($details) ==4){
                $ussdText = "Enter  your Last Name<br>";
                ussdnext($ussdText);
                goback($details);
        }elseif(count($details) ==5){
                $ussdText = "Enter your new PIN (6 digits)<br>";
                ussdnext($ussdText);
                goback($details);
        }elseif(count($details) ==6){
                $ussdText = "Enter  PIN again<br>";
                ussdnext($ussdText);
                goback($details);
        }elseif(count($details) == 7){
               if($details[6] != $details[5]){
                       echo "PIN does not match<br>";
                       goback($details);
               }else{
                       echo "registration done, you now login<br>enter 9 to login";
                       print_r($details);
                       goback($details);
               }
        
        }elseif(count($details) == 8){
                if($details[7] == '9'){
                       //TODO: modify the query string parameters
                     // modify_url($USSDString);
                        showMenu();
                }
        }
}
//TOP LEVELS FUNCTIONS END HERE
function validateLogin(){}
//MULTI LEVEL FUNCTIONS GO HERE
 function goBack($details){
     echo "b. back  0. exit<br>";
     
   echo "<span style='font-family:monospace;color:red;'><br>this text is for debuging purposes only, will be removed at deployment<ul>";
                for($i = 0;$i <= count($details) - 1;$i++){
                        if(($i == 5) || ($i == 6)){ $details[$i] = md5($details[$i]);}
                        echo "<li>User response : " . $details[$i]." </li>";
                }
                echo "</ul><span style='color:yellow;'>Response Level : " .count($details). "</span></span>";
 
 }
 function goBackAct($details){
        array_pop($details);
 }
 //mmodify url needsw further analysis.....
 function modify_url($mod, $url = FALSE){
                            // If $url wasn't passed in, use the current url
                            if($url == FALSE){
                                $scheme = $_SERVER['SERVER_PORT'] == 80 ? 'http' : 'https';
                                $url = $scheme.'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
                            }
                        
                            // Parse the url into pieces
                            $url_array = parse_url($url);
                        
                            // The original URL had a query string, modify it.
                            if(!empty($url_array['query'])){
                                parse_str($url_array['query'], $query_array);
                                foreach ($mod as $key => $value) {
                                    if(!empty($query_array[$key])){
                                        $query_array[$key] = $value;
                                    }
                                }
                            }
                        
                            // The original URL didn't have a query string, add it.
                            else{
                                $query_array = $mod;
                            }
                        
                            return $url_array['scheme'].'://'.$url_array['host'].'/'.$url_array['path'].'?'.http_build_query($query_array);
                        }
                                      
 
//MULTI-LEVEL FUNCTIONS END HERE
?>
