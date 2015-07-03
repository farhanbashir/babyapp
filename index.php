<?php

error_reporting(E_ALL);

require "config.php";
require "functions.php";

//require "NotORM.php";

$db = new PDO("mysql:host=".$config["db"]["db_host"].";dbname=".$config["db"]["db_name"], $config["db"]["db_user"], $config["db"]["db_password"],array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
//$db->set_charset(‘utf8′);
//$db = new NotORM($pdo);

require 'Slim/Slim.php';
require 'Slim/Middleware.php';
require 'Slim/Middleware/myMiddleWare.php';

\Slim\Slim::registerAutoloader();


$app = new \Slim\Slim(array("MODE" => "development"));

$app->add(new \HttpBasicAuth());

$response = array();

$app->get('/users','getUsers');
$app->get('/getMilestones/:user_id/:cat_id','getMilestones');
$app->get('/getMilestoneImages/:baby_id/:milestone_id','getMilestoneImages');
$app->get('/getBabyProfile/:user_id','getBabyProfile');
$app->get("/getProfile/:params+",'getProfile');
$app->get("/getFeeds/:user_id",'getFeeds');
$app->get("/test1","test1");
$app->get("/verify/:email/:code",'verify');

$app->post('/signup','signup');
$app->post('/setBabyProfile','setBabyProfile');
$app->post('/editBabyProfile','editBabyProfile');
$app->post("/login",'login');
$app->post("/logout",'logout');
$app->post('/updatePassword','updatePassword');
$app->post('/deletePhotoInMilestone','deletePhotoInMilestone');

$app->post('/updatePhotoInMilestone','updatePhotoInMilestone');
$app->post('/imgSave','imgSave');
$app->post('/editProfile','editProfile');
$app->post('/askExpert','askExpert');
$app->post('/updateBabyGrowth','updateBabyGrowth');
$app->post("/getBabyGrowth",'getBabyGrowth');
$app->post("/getGrowthTracker",'getGrowthTracker');
$app->post('/forgotPassword','forgotPassword');
$app->post('/setAlbumImage','setAlbumImage');

function test1()
{
    $myDateString = '2015-01-01';
    debug((bool)strtotime($myDateString));
    //checkFolder(10);
    //debug(file_exists("images/2"));
}

function get_user_device_id($user_id)
{
    global $db,$app,$response,$config;

    $sql = "SELECT * FROM devices WHERE user_id=$user_id";

    try{
        $stmt   = $db->query($sql);
        $users  = $stmt->fetchAll(PDO::FETCH_NAMED);
        return $users;
    }
    catch(PDOException $e){
        return false;
    }
}

function getUsers()
{
	global $app ,$db, $response,$config;
	$users = array();

    $sql = "SELECT * FROM users where is_active=1";

    try{
        $stmt   = $db->query($sql);
        $users  = $stmt->fetchAll(PDO::FETCH_NAMED);
        $response["header"]["error"] = "0";
        $response["header"]["message"] = "Success";
    }
    catch(PDOException $e){
        $response["header"]["error"] = "1";
        $response["header"]["message"] = $e->getMessage();
        $response["header"]["message_arabic"] = $config["message_error_ar"];
    }



    $response["body"] = $users;

    $app->response()->header("Content-Type", "application/json");
    echo json_encode($response);


}

function getMilestones($baby_id,$cat_id)
{
    global $app ,$db, $response,$config;
    $milestones = array();

    //$sql = "SELECT milestone_id,milestone_name FROM milestones";
    $sql =  "SELECT m.milestone_id,m.milestone_name,m.milestone_name_ar,
            (select image from album_images where baby_id=$baby_id and milestone_id=m.milestone_id limit 1) as album_cover,
            (select image from baby_milestones where baby_id=$baby_id and milestone_id=m.milestone_id order by baby_milestone_id desc limit 1) as last_image
            FROM milestones m where cat_id=$cat_id
            ";

    try{
        $stmt   = $db->query($sql);
        $milestones  = $stmt->fetchAll(PDO::FETCH_NAMED);
        $response["header"]["error"] = "0";
        $response["header"]["message"] = $config["message_success_en"];
        $response["header"]["message_arabic"] = $config["message_success_ar"];
    }
    catch(PDOException $e){
        $response["header"]["error"] = "1";
        $response["header"]["message"] = $e->getMessage();
        $response["header"]["message_arabic"] = $config["message_error_ar"];
    }



    $response["body"] = $milestones;

    $app->response()->header("Content-Type", "application/json");
    echo json_encode($response);


}

function getMilestoneImages($baby_id,$milestone_id)
{
    global $app ,$db, $response,$config;
    $milestones = array();

    $sql = "SELECT * FROM baby_milestones where baby_id=$baby_id and milestone_id=$milestone_id";

    try{
        $stmt   = $db->query($sql);
        $milestones  = $stmt->fetchAll(PDO::FETCH_NAMED);
        $response["header"]["error"] = "0";
        $response["header"]["message"] = $config["message_success_en"];
        $response["header"]["message_arabic"] = $config["message_success_ar"];
    }
    catch(PDOException $e){
        $response["header"]["error"] = "1";
        $response["header"]["message"] = $e->getMessage();
        $response["header"]["message_arabic"] = $config["message_error_ar"];
    }



    $response["body"] = $milestones;

    $app->response()->header("Content-Type", "application/json");
    echo json_encode($response);


}


function getBabyProfile($user_id)
{
    global $app ,$db, $response,$config;
    $users = array();

    $sql = "SELECT * FROM babies where user_id=:user_id";

    try{
        $stmt = $db->prepare($sql);
        $stmt->bindParam("user_id", $user_id);
        $stmt->execute();
        $data  = $stmt->fetch(PDO::FETCH_NAMED);
        if(is_array($data) && count($data) > 0)
        {
            $users = $data;
        }
        $response["header"]["error"] = "0";
        $response["header"]["message"] = $config["message_success_en"];
        $response["header"]["message_arabic"] = $config["message_success_ar"];
    }
    catch(PDOException $e){
        $response["header"]["error"] = "1";
        $response["header"]["message"] = $e->getMessage();
        $response["header"]["message_arabic"] = $config["message_error_ar"];
    }



    $response["body"] = $users;

    $app->response()->header("Content-Type", "application/json");
    echo json_encode($response);


}


function send_notification_iphone($deviceToken, $message, $sound='default')
{

        //$deviceToken = '7229e0f7cc34bd639a31e81802def2c02945b0a89d01ce52c7528f8671ef8f32';

        // Put your private key's passphrase here:
        //$passphrase = 'developmentc2gapns';

    global $config;
    $passphrase = $config['PASS_PHRASE'];

    $remote_url = $config['REMOTE_SOCKET_APPLE'];

        // Put your alert message here:
        //$message = 'Helo this is first message.';

        ////////////////////////////////////////////////////////////////////////////////

    $ctx = stream_context_create();

        //stream_context_set_option($ctx, 'ssl', 'local_cert', 'apns-dev.pem');
    stream_context_set_option($ctx, 'ssl', 'local_cert', 'ck.pem');
        //stream_context_set_option($ctx, 'ssl', 'local_cert', 'ck.pem');
    stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

        // Open a connection to the APNS server
    $fp = stream_socket_client(
       $remote_url, $err,
       $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

    if (!$fp)
        exit("Failed to connect: $err $errstr" . PHP_EOL);

        //echo 'Connected to APNS' . PHP_EOL;

        // Create the payload body
    $body['aps'] = array(
     'alert' => $message,
     'sound' => 'default',
     'ji' =>88
     );




        // Encode the payload as JSON
    $payload = json_encode($body);

        // Build the binary notification
    $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;

        // Send it to the server
    $result = fwrite($fp, $msg, strlen($msg));

      /*  if (!$result)
            echo 'Message not delivered' . PHP_EOL;
        else
            echo 'Message successfully delivered' . PHP_EOL;
        */
        // Close the connection to the server
            fclose($fp);


        }



        function old_send_notification_iphone($deviceToken, $message, $sound='default')
        {
         global $config;
         $socketClient = "";

	/*
	init work
	*/
	$certificateFilename ="ck.pem";
	//$certificateFilename =env('DOCUMENT_ROOT').''. Router::url('/') . "app/Lib/PushNotification/apns-dist.pem";

	//echo $certificateFilename;
	//exit;

	$ctx = stream_context_create();
	stream_context_set_option($ctx, 'ssl', 'local_cert', $certificateFilename);
	stream_context_set_option($ctx, 'ssl', 'passphrase', $config['PASS_PHRASE']);

	// Open a connection to the APNS server
	$fp = stream_socket_client($config['REMOTE_SOCKET_APPLE'], $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);

	if (!$fp)
	{
		$fp = (array("code"=>1, "message"=>"Failed to connect: $err $errstr" . PHP_EOL));
	}

	/*
	init work
	*/


	if (is_array($fp)) {
		//CakeLog::write('debug', 'Couldn\'t connect to socket client' . PHP_EOL . print_r($socketClient));
		return ;
	}

	// Create the payload body
	$body['aps'] = array(
		'alert' => $message,
		'sound' => $sound
     );

	// Encode the payload as JSON
	$payload = json_encode($body);

	// Build the binary notification
	$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;

	// Send it to the server
	$result = fwrite($socketClient, $msg, 8192);
	fclose($socketClient);
	//self::abort($socketClient);

	if (!$result) {
		//CakeLog::write('debug', 'Error Code 2: Message not delivered' . PHP_EOL);
		return 2;
	}
	else {
		//CakeLog::write('debug', 'Message successfully delivered' . PHP_EOL);
		return 0;
	}
}

function send_notification_android($registatoin_ids, $message) {
    global $config;
        // Set POST variables
    $url = $config["REMOTE_SOCKET_GOOGLE"];

    $fields = array(
        'registration_ids' => $registatoin_ids,
        'data' => $message,
        );

		//print_r($fields);die;
    $headers = array(
        'Authorization: key=' . $config['google_key'],
        'Content-Type: application/json'
        );
        // Open connection
    $ch = curl_init();

        // Set the url, number of POST vars, POST data
    curl_setopt($ch, CURLOPT_URL, $url);

    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Disabling SSL Certificate support temporarly
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        // Execute post
    $result = curl_exec($ch);
    if ($result === FALSE) {
        die('Curl failed: ' . curl_error($ch));
    }

        // Close connection
    curl_close($ch);
        //echo $result;
}

function getProfile($params){
	global $app,$db,$config;
    $info = array();
    $user_id = "";

    $target_id = $params[0];

    if(count($params) > 1)
    {
        $user_id = $params[1];
    }


    $sql = "SELECT
    (select count(*) from user_events where user_id=u.id and is_checkedIn=1) as checkins,
    (select count(*) from followers where user_id=u.id) as follower,
    (select count(*) from followers where follower_id=u.id) as following,
    u.* FROM users u where u.id=:id and u.is_active=1";
    try{
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":id", $target_id);
        $stmt->execute();
        //$stmt   = $db->query($sql);
        $info  = $stmt->fetch(PDO::FETCH_NAMED);

        if(is_array($info))
        {
            if($user_id != "")
            {
                $following = getFollowingInternal($user_id);
                if(count($following) > 0)
                {
                    foreach($following as $follow)
                    {
                        if($follow['id'] == $target_id)
                        {
                            $info['is_followed'] = true;
                            break;
                        }
                    }

                    if(!isset($info['is_followed']))
                    {
                        $info['is_followed'] = false;
                    }
                }
                else
                {
                    $info['is_followed'] = false;
                }

                $followers = getFollowerInternal($user_id);
                if(count($followers) > 0)
                {
                    foreach($followers as $follow)
                    {
                        if($follow['id'] == $target_id)
                        {
                            $info['is_follower'] = true;
                            break;
                        }
                    }

                    if(!isset($info['is_follower']))
                    {
                        $info['is_follower'] = false;
                    }
                }
                else
                {
                    $info['is_follower'] = false;
                }

            }



            $response["header"]["error"] = "0";
            $response["header"]["message"] = $config["message_success_en"];
            $response["header"]["message_arabic"] = $config["message_success_ar"];
            $response["body"] = $info;
        }
        else
        {
            $response["header"]["error"] = "1";
            $response["header"]["message"] = $config["message_user_not_exist_en"];
            $response["header"]["message_arabic"] =  $config["message_user_not_exist_ar"];
        }

    }
    catch(PDOException $e){
        $response["header"]["error"] = "1";
        $response["header"]["message"] = $e->getMessage();
        $response["header"]["message_arabic"] = $config["message_error_ar"];
    }



    $app->response()->header("Content-Type", "application/json");
    echo json_encode($response);

}

function checkBabyFolder($user_id)
{
    global $db,$config;

    $sql = "select * from babies where user_id=:user_id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam("user_id", $user_id);
    $stmt->execute();
    //$stmt   = $db->query($sql);
    $data  = $stmt->fetch(PDO::FETCH_NAMED);

    if(is_array($data) && count($data))
    {
        $baby_id = $data['baby_id'];
        if(!file_exists("images/$baby_id"))
        {
            createBabyFolders($baby_id);
        }
    }
}

function logout()
{
    global $app, $db, $response, $config;
    $req = $app->request(); // Getting parameter with names
    $userId = $req->headers('Userid');
    $token = $req->headers('Token');    

    if($userId != '' && $token != '' )
    {
        $sql = "UPDATE devices SET token='' WHERE user_id='$userId' AND token='$token'";
        
        try{
            $stmt = $db->prepare($sql);
            $result = $stmt->execute();

            if($result)
            {
                $response["header"]["error"] = "0";
                $response["header"]["message"] = $config["message_success_en"];
                $response["header"]["message_arabic"] = $config["message_success_ar"];
            }
            else
            {
                $response["header"]["error"] = "1";
                $response["header"]["message"] = $config["message_incorrect_username_en"];
                $response["header"]["message_arabic"] = $config["message_incorrect_username_ar"];
            }
        }
        catch(PDOException $e)
        {
            $response["header"]["error"] = "1";
            $response["header"]["message"] = $config["message_incorrect_username_en"];
            $response["header"]["message_arabic"] = $config["message_incorrect_username_ar"];
        }    
    }
    else
    {
        $response["header"]["error"] = "1";
        $response["header"]["message"] = $config["message_incorrect_username_en"];
        $response["header"]["message_arabic"] = $config["message_incorrect_username_ar"];
    }

    $app->response()->header("Content-Type", "application/json");
    echo json_encode($response);

}

function login(){
    global $app, $db, $response,$config;

    $req = $app->request(); // Getting parameter with names
    $device_id = $req->params('device_id'); // Getting parameter with names
    $device_type = $req->params('device_type'); // Getting parameter with names
    $lang = $req->params('lang'); // Getting parameter with names
    //$data = array();
    $lang = ($lang != '') ? $lang : 0;

    $email = $req->params('email'); // Getting parameter with names
    $password = $req->params('password'); // Getting parameter with names

    $sql = "SELECT * FROM users where email=:email ";
    // $sql = "SELECT
    //     (select count(*) from user_events where user_id=u.id and is_checkedIn=1) as checkins,
    //     (select count(*) from followers where user_id=u.id) as follower,
    //     (select count(*) from followers where follower_id=u.id) as following,
    //     u.* FROM users u where u.username=:username and u.is_active=1";

    try{
        $stmt = $db->prepare($sql);
        $stmt->bindParam("email", $email);
        $stmt->execute();
        //$stmt   = $db->query($sql);
        $data  = $stmt->fetch(PDO::FETCH_NAMED);
        $token = "";
        if(is_array($data) && count($data))
        {
            if($data["password"] == MD5($password))// && $data['verified'] == 1
            {
                checkBabyFolder($data['user_id']);

                if($device_type != "")//$device_id != ""
                {
                    $sql = "select count(*) from devices where user_id=:user_id and type=:device_type and lang=:lang";

                    $stmt = $db->prepare($sql);
                    $stmt->bindParam(":user_id", $data['user_id']);
                    $stmt->bindParam(":device_type", $device_type);
                    $stmt->bindParam(":lang", $lang);
                    $stmt->execute();

                    $present = $stmt->fetchColumn();

                    $token = bin2hex(openssl_random_pseudo_bytes(16));

                    if($present != false)
                    {
                        //update

                        $sql = "UPDATE devices set uid='$device_id',lang=$lang,token='$token' WHERE user_id=:user_id and type=:device_type and lang=:lang";

                        $stmt = $db->prepare($sql);

                        $stmt->bindParam(":user_id", $data['user_id']);
                        $stmt->bindParam(":device_type", $device_type);
                        $stmt->bindParam(":lang", $lang);
                        
                        $stmt->execute();
                    }
                    else
                    {
                        //insert
                        $sql = "insert into devices (user_id,uid,`type`,lang,token) values (:user_id,:device_id,:device_type,:lang,:token)";

                        $stmt = $db->prepare($sql);

                        $stmt->bindParam(":user_id", $data['user_id']);
                        $stmt->bindParam(":device_type", $device_type);
                        $stmt->bindParam(":device_id", $device_id);
                        $stmt->bindParam(":lang", $lang);
                        $stmt->bindParam(":token", $token);

                        $stmt->execute();

                    }
                }

                $response["header"]["error"] = "0";
                $response['header']["token"] = $token;
                $response["header"]["message"] = $config["message_success_en"];
                $response["header"]["message_arabic"] = $config["message_success_ar"];
            }
            /*elseif($data["password"] == MD5($password) && $data['verified'] == 0)
            {
                $response["header"]["error"] = "1";
                $response["header"]["message"] = "Email address not verified";
            }*/
            else
            {
                $data = array();
                $response["header"]["error"] = "1";
                $response["header"]["message"] = $config["message_incorrect_username_en"];
                $response["header"]["message_arabic"] = $config["message_incorrect_username_ar"];
            }

        }
        else
        {
            $response["header"]["error"] = "1";
            $response["header"]["message"] = $config["message_user_not_exist_en"];
            $response["header"]["message_arabic"] = $config["message_user_not_exist_ar"];
        }


    }
    catch(PDOException $e){
        $response["header"]["error"] = "1";
        $response["header"]["message"] = $e->getMessage();
        $response["header"]["message_arabic"] = $config["message_error_ar"];
    }



    $response["body"] = $data;

    $app->response()->header("Content-Type", "application/json");
    echo json_encode($response);

}

function insertNotification($data)
{
    global $db,$config;

    $from = (isset($data["from"])) ? $data["from"] : 0;
    $to = (isset($data["to"])) ? $data["to"] : 0;
    $message = $data["message"];
    $datetime = date("Y-m-d h:i:s");
    $event_id = $data["event_id"];

    $sql = "INSERT INTO notifications (`from`,`to`,`message`,`datetime`,event_id) VALUES (:from,:to,:message,:datetime,:event_id)";

    $stmt = $db->prepare($sql);
    $stmt->bindParam(":from", $from);
    $stmt->bindParam(":to", $to);
    $stmt->bindParam(":message", $message);
    $stmt->bindParam(":datetime", $datetime);
    $stmt->bindParam(":event_id", $event_id);
    $stmt->execute();

    $devices = get_user_device_id($to);

    if($devices != false)
    {
        foreach($devices as $device)
        {
            if($device['type'] == 0)
            {
                //iphone notification here
                send_notification_iphone($device['uid'],$message);
            }
            else
            {
                //android notification here
                send_notification_android(array($device['uid']), $message);
            }
        }
    }



}

function rand_string( $length ) {

    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    return substr(str_shuffle($chars),0,$length);

}

function signup() {
	global $app, $db, $response,$config;
	$user = array();

	$req = $app->request(); // Getting parameter with names
	$first_name = $req->params('first_name'); // Getting parameter with names
    $last_name = $req->params('last_name'); // Getting parameter with names
    $email = $req->params('email'); // Getting parameter with names
    $password = md5($req->params('password')); // Getting parameter with names
    $dob= $req->params('dob');
    $gender= $req->params('gender');
    $user_image = '';

    if(userAvailable($email))
    {
        $sql = "INSERT INTO users (first_name,last_name,email,password,dob,image,gender)
        values
        (:first_name,:last_name,:email,:password,:dob,:image,:gender)";

        if(isset($_FILES['file']))
        {
            $uploaddir = 'images/';
            $file = basename($_FILES['file']['name']);
            $protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';

            $uploadfile = $uploaddir . $file;

            if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
                $user_image = $uploadfile;
                $path = substr($_SERVER['REQUEST_URI'],0,stripos($_SERVER['REQUEST_URI'], "index.php"));
                $user_image = $protocol.$_SERVER['SERVER_NAME'].$path.$user_image;
            } else {
                $response["header"]["error"] = "1";
                $response["header"]["message"] = $config["message_error_en"];
                $response["header"]["message_arabic"] = $config["message_error_ar"];
            }
        }

        if(count($response) == 0)
        {
            try{
                $stmt = $db->prepare($sql);
                $stmt->bindParam(":first_name", $first_name);
                $stmt->bindParam(":last_name", $last_name);
                $stmt->bindParam(":email", $email);
                $stmt->bindParam(":password", $password);
                $stmt->bindParam(":dob", $dob);
                $stmt->bindParam(":gender", $gender);
                $stmt->bindParam(":image", $user_image);
                $stmt->execute();

                $email_data = array('from'=>$config['admin_email'],'to'=>$email,'subject'=>'Danone - Please verify your email', 'message'=>'Your verification code is '.substr($password,0,6));
                //sendEmail($email_data );

                $user["user_id"] = $db->lastInsertId();
                $response["body"] = $user;
                $response["header"]["error"] = "0";
                $response["header"]["message"] = $config["message_success_en"];
                $response["header"]["message_arabic"] = $config["message_success_ar"];

            }
            catch(PDOException $e)
            {
                $response["header"]["error"] = "1";
                $response["header"]["message"] = $e->getMessage();
                $response["header"]["message_arabic"] = $config["message_error_ar"];
            }
        }
    }
    else
    {
        $response["header"]["error"] = "1";
        $response["header"]["message"] = $config["message_user_exist_en"];
        $response["header"]["message_arabic"] = $config["message_user_exist_ar"];
    }


    $app->response()->header("Content-Type", "application/json");
    echo json_encode($response);

}

function getTotalMonths($date)
{
    $interval = date_diff(date_create(), date_create($date));
    $total_months = ($interval->y *12) + $interval->m;
    return $total_months;
}

function setBabyProfile() {
    global $app, $db, $response,$config;
    $user = array();

    $req = $app->request(); // Getting parameter with names
    $first_name = $req->params('first_name'); // Getting parameter with names
    $user_id = $req->params('user_id'); // Getting parameter with names
    $weight = $req->params('weight'); // Getting parameter with names
    $height = $req->params('height'); // Getting parameter with names
    $dob= $req->params('dob');
    $gender= $req->params('gender');
    $user_image = '';

    if((bool)strtotime($dob) == false)
    {
        $response["header"]["error"] = "1";
        $response["header"]["message"] = $config["message_invalid_date_error_en"];
        $response["header"]["message_arabic"] = $config["message_invalid_date_error_ar"];
        $app->response()->header("Content-Type", "application/json");
        echo json_encode($response);
        return;
    }

    //if child is older than 12 months
    $total_months = getTotalMonths($dob);
    if($total_months < 12)
    {
        $response["header"]["error"] = "1";
        $response["header"]["message"] = $config["message_12_month_error_en"];
        $response["header"]["message_arabic"] = $config["message_12_month_error_ar"];
        $app->response()->header("Content-Type", "application/json");
        echo json_encode($response);
        return;   
    }    
    

    if(babyAvailable($user_id))
    {
        $sql = "INSERT INTO babies (user_id,first_name,image,dob,weight,height,gender)
        values
        (:user_id,:first_name,:image,:dob,:weight,:height,:gender)";

        if(isset($_FILES['file']))
        {
            $uploaddir = 'images/';
            $file = basename($_FILES['file']['name']);
            $protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';

            $uploadfile = $uploaddir . $file;

            if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
                $user_image = $uploadfile;
                $path = substr($_SERVER['REQUEST_URI'],0,stripos($_SERVER['REQUEST_URI'], "index.php"));
                $user_image = $protocol.$_SERVER['SERVER_NAME'].$path.$user_image;
            } else {
                $response["header"]["error"] = "1";
                $response["header"]["message"] = $config["message_error_en"];
                $response["header"]["message_arabic"] = $config["message_error_ar"];
            }
        }

        if(count($response) == 0)
        {
            try{
                $stmt = $db->prepare($sql);
                $stmt->bindParam(":user_id", $user_id);
                $stmt->bindParam(":first_name", $first_name);
                $stmt->bindParam(":dob", $dob);
                $stmt->bindParam(":weight", $weight);
                $stmt->bindParam(":height", $height);
                $stmt->bindParam(":gender", $gender);
                $stmt->bindParam(":image", $user_image);
                $stmt->execute();

                $user["baby_id"] = $db->lastInsertId();
                createBabyFolders($user["baby_id"]);
                $response["body"] = $user;
                $response["header"]["error"] = "0";
                $response["header"]["message"] = $config["message_success_en"];
                $response["header"]["message_arabic"] = $config["message_success_ar"];

            }
            catch(PDOException $e)
            {
                $response["header"]["error"] = "1";
                $response["header"]["message"] = $e->getMessage();
                $response["header"]["message_arabic"] = $config["message_error_ar"];
            }
        }
    }
    else
    {
        $response["header"]["error"] = "1";
        $response["header"]["message"] = $config["message_already_have_baby_en"];
        $response["header"]["message_arabic"] = $config["message_already_have_baby_ar"];
    }


    $app->response()->header("Content-Type", "application/json");
    echo json_encode($response);

}

function deletePhotoInMilestone() {
    global $app, $db, $response,$config;

    $req = $app->request(); // Getting parameter with names
    $baby_milestone_id = $req->params('baby_milestone_id'); // Getting parameter with names
    $image = '';

    $sql = "SELECT * FROM baby_milestones where baby_milestone_id = ".$baby_milestone_id;
    $delete_sql = "delete FROM baby_milestones where baby_milestone_id = ".$baby_milestone_id;

    try{
        
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $data  = $stmt->fetch(PDO::FETCH_NAMED);

        if(is_array($data) && count($data))
        {

            $stmt = $db->prepare($delete_sql);
       	    $stmt->execute();

            if($data["image"])
            {
      			$image_path = $data['image'];
       			
       			 if (file_exists($image_path)) {
	   			 unlink($image_path);
 				 }     			
       	    }
       }

        $response["header"]["error"] = "0";
        $response["header"]["message"] = $config["message_successfully_removed_en"];
        $response["header"]["message_arabic"] = $config["message_successfully_removed_ar"];

        }
        catch(PDOException $e)
        {
            $response["header"]["error"] = "1";
            $response["header"]["message"] = $e->getMessage();
            $response["header"]["message_arabic"] = $config["message_error_ar"];
        }
    

    $app->response()->header("Content-Type", "application/json");
    echo json_encode($response);

}

function updatePhotoInMilestone() {
    global $app, $db, $response,$config;

    $req = $app->request(); // Getting parameter with names
    $baby_id = $req->params('baby_id'); // Getting parameter with names
    $milestone_id= $req->params('milestone_id');
    $date= $req->params('date');
    $caption= $req->params('caption');
    $image = '';

    $sql = "INSERT INTO baby_milestones (baby_id,date,milestone_id,caption,image)
    values
    (:baby_id,:date,:milestone_id,:caption,:image)";

    if(isset($_FILES['file']))
    {
        $uploaddir = "images/$baby_id/$milestone_id/";
        $file = basename($_FILES['file']['name']);
        $protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';

        $uploadfile = $uploaddir . $file;

        if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
            $user_image = $uploadfile;
            $path = substr($_SERVER['REQUEST_URI'],0,stripos($_SERVER['REQUEST_URI'], "index.php"));
            $user_image = $protocol.$_SERVER['SERVER_NAME'].$path.$user_image;
        } else {
            $response["header"]["error"] = "1";
            $response["header"]["message"] = $config["message_error_en"];
            $response["header"]["message_arabic"] = $config["message_error_ar"];
        }
    }

    if(count($response) == 0)
    {
        try{
            $stmt = $db->prepare($sql);
            $stmt->bindParam(":baby_id", $baby_id);
            $stmt->bindParam(":date", $date);
            $stmt->bindParam(":milestone_id", $milestone_id);
            $stmt->bindParam(":caption", $caption);
            $stmt->bindParam(":image", $user_image);
            $stmt->execute();

            $response["header"]["error"] = "0";
            $response["header"]["message"] = $config["message_success_en"];
            $response["header"]["message_arabic"] = $config["message_success_ar"];

        }
        catch(PDOException $e)
        {
            $response["header"]["error"] = "1";
            $response["header"]["message"] = $e->getMessage();
            $response["header"]["message_arabic"] = $config["message_error_ar"];
        }
    }


    $app->response()->header("Content-Type", "application/json");
    echo json_encode($response);

}

function setAlbumImage() {
    global $app, $db, $response,$config;

    $req = $app->request(); // Getting parameter with names
    $baby_id = $req->params('baby_id'); // Getting parameter with names
    $milestone_id= $req->params('milestone_id');
    $image = '';

    if(isset($_FILES['file']))
    {
        $uploaddir = "images/$baby_id/$milestone_id/";
        $file = basename($_FILES['file']['name']);
        $protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';

        $uploadfile = $uploaddir . $file;

        if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
            $user_image = $uploadfile;
            $path = substr($_SERVER['REQUEST_URI'],0,stripos($_SERVER['REQUEST_URI'], "index.php"));
            $user_image = $protocol.$_SERVER['SERVER_NAME'].$path.$user_image;
        } else {
            $response["header"]["error"] = "1";
            $response["header"]["message"] = $config["message_error_en"];
            $response["header"]["message_arabic"] = $config["message_error_ar"];
        }
    }

    $sql = "select count(*) from album_images where baby_id=:baby_id and milestone_id=:milestone_id";

    $stmt = $db->prepare($sql);
    $stmt->bindParam(":baby_id", $baby_id);
    $stmt->bindParam(":milestone_id", $milestone_id);
    $stmt->execute();

    $present = $stmt->fetchColumn();



    if($present != false)
    {
        //update
        $sql = "update album_images set image=:image where baby_id=:baby_id and milestone_id=:milestone_id";
    }
    else
    {
        //insert
        $sql = "insert into album_images (baby_id,milestone_id,image) values (:baby_id,:milestone_id,:image)";
    }

    if(count($response) == 0)
    {
        try{
            $stmt = $db->prepare($sql);
            $stmt->bindParam(":baby_id", $baby_id);
            $stmt->bindParam(":milestone_id", $milestone_id);
            $stmt->bindParam(":image", $user_image);
            $stmt->execute();

            $response["header"]["error"] = "0";
            $response["header"]["message"] = $config["message_success_en"];
            $response["header"]["message_arabic"] = $config["message_success_ar"];

        }
        catch(PDOException $e)
        {
            $response["header"]["error"] = "1";
            $response["header"]["message"] = $e->getMessage();
            $response["header"]["message_arabic"] = $config["message_error_ar"];
        }
    }


    $app->response()->header("Content-Type", "application/json");
    echo json_encode($response);

}

function askExpert() {
    global $app, $db, $response,$config;

    $req = $app->request(); // Getting parameter with names
    $user_id = $req->params('user_id'); // Getting parameter with names
    $baby_id = $req->params('baby_id'); // Getting parameter with names
    $email = $req->params('email');
    $date= date('Y-m-d');
    $subject= $req->params('subject');
    $message= $req->params('message');

    $sql = "INSERT INTO ask_expert (user_id,baby_id,date,email,subject,message)
    values
    (:user_id,:baby_id,:date,:email,:subject,:message)";

        try{
            $stmt = $db->prepare($sql);
            $stmt->bindParam(":user_id", $user_id);
            $stmt->bindParam(":baby_id", $baby_id);
            $stmt->bindParam(":date", $date);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":subject", $subject);
            $stmt->bindParam(":message", $message);
            $stmt->execute();

            $subject = "DADONE - ASK EXPERT ".$subject;
            sendEmail(array("to"=>$config["admin_email"],"from"=>$email,"subject"=>$subject,"message"=>$message));

            $response["header"]["error"] = "0";
            $response["header"]["message"] = $config["message_success_en"];
            $response["header"]["message_arabic"] = $config["message_success_ar"];

        }
        catch(PDOException $e)
        {
            $response["header"]["error"] = "1";
            $response["header"]["message"] = $e->getMessage();
            $response["header"]["message_arabic"] = $config["message_error_ar"];
        }


    $app->response()->header("Content-Type", "application/json");
    echo json_encode($response);

}


function createBabyFolders($baby_id)
{
    global $db,$config;
    $milestones = array();
    if(!file_exists("images/$baby_id"))
    {
        mkdir("images/$baby_id");
    }

    $sql = "select * from milestones";
    $stmt   = $db->query($sql);
    $milestones  = $stmt->fetchAll(PDO::FETCH_NAMED);

    foreach($milestones as $milestone)
    {
        if(!file_exists("images/$baby_id/".$milestone['milestone_id']))
        {
            mkdir("images/$baby_id/".$milestone['milestone_id']);
        }

    }

}

function editBabyProfile() {
    global $app, $db, $response,$config;

    $req = $app->request(); // Getting parameter with names
    $first_name = $req->params('first_name'); // Getting parameter with names
    $user_id = $req->params('user_id'); // Getting parameter with names
    $baby_id = $req->params('baby_id'); // Getting parameter with names
    $weight = $req->params('weight'); // Getting parameter with names
    $height = $req->params('height'); // Getting parameter with names
    $dob= $req->params('dob');
    $gender= $req->params('gender');
    $user_image = '';

    if((bool)strtotime($dob) == false)
    {
        $response["header"]["error"] = "1";
        $response["header"]["message"] = $config["message_invalid_date_error_en"];
        $response["header"]["message_arabic"] = $config["message_invalid_date_error_ar"];
        $app->response()->header("Content-Type", "application/json");
        echo json_encode($response);
        return;
    }   

    //if child is older than 12 months
    $total_months = getTotalMonths($dob);
    if($total_months < 12)
    {
        $response["header"]["error"] = "1";
        $response["header"]["message"] = $config["message_12_month_error_en"];
        $response["header"]["message_arabic"] = $config["message_12_month_error_ar"];
        $app->response()->header("Content-Type", "application/json");
        echo json_encode($response);
        return;   
    }  

    if(!babyAvailable($user_id))
    {
        if(isset($_FILES['file']))
        {
            $uploaddir = 'images/';
            $file = basename($_FILES['file']['name']);
            $protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';

            $uploadfile = $uploaddir . $file;

            if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
                $user_image = $uploadfile;
                $path = substr($_SERVER['REQUEST_URI'],0,stripos($_SERVER['REQUEST_URI'], "index.php"));
                $user_image = $protocol.$_SERVER['SERVER_NAME'].$path.$user_image;
            } else {
                $response["header"]["error"] = "1";
                $response["header"]["message"] = $config["message_error_en"];
                $response["header"]["message_arabic"] = $config["message_error_ar"];
            }
        }

        if(count($response) == 0)
        {
            if($user_image){
         $userImageText = " ,image='$user_image' ";
         }else{
           $userImageText = "";
       }
        $sql = "UPDATE babies SET
               first_name=:first_name,
               weight=:weight,
               height=:height,
               dob=:dob,
               gender=:gender
               ".$userImageText."
               WHERE user_id=:user_id AND baby_id=:baby_id";

            try{
                $stmt = $db->prepare($sql);
                $stmt->bindParam(":user_id", $user_id);
                $stmt->bindParam(":baby_id", $baby_id);
                $stmt->bindParam(":first_name", $first_name);
                $stmt->bindParam(":dob", $dob);
                $stmt->bindParam(":weight", $weight);
                $stmt->bindParam(":height", $height);
                $stmt->bindParam(":gender", $gender);
                // if($user_image)
                // {
                //     $stmt->bindParam(":baby_image", $user_image);
                // }

                $stmt->execute();

                //$user["baby_id"] = $db->lastInsertId();
                //$response["body"] = $user;
                $response["header"]["error"] = "0";
                $response["header"]["message"] = $config["message_success_en"];
                $response["header"]["message_arabic"] = $config["message_success_ar"];

            }
            catch(PDOException $e)
            {
                $response["header"]["error"] = "1";
                $response["header"]["message"] = $e->getMessage();
                $response["header"]["message_arabic"] = $config["message_error_ar"];
            }
        }
    }
    else
    {
        $response["header"]["error"] = "1";
        $response["header"]["message"] = $config["message_baby_not_present_en"];
        $response["header"]["message_arabic"] = $config["message_baby_not_present_ar"];
    }


    $app->response()->header("Content-Type", "application/json");
    echo json_encode($response);

}


function getFeeds($user_id)
{
    global $app, $db, $response,$config;
    $feed = array();
    $sql = "select floor(DATEDIFF(CURDATE(),dob)/30) as day from babies where user_id=$user_id";

    try{
        $stmt   = $db->query($sql);
        $days  = $stmt->fetchAll(PDO::FETCH_NAMED);

        if(is_array($days) && count($days) > 0)
        {
            $day = $days[0]['day'];
            $sql = "select f.feed_id,f.from,f.to,f.feed,f.intro,f.feed_ar,f.intro_ar,f.milestone_id,m.milestone_name ,m.milestone_name_ar from feeds f left join milestones m on f.milestone_id = m.milestone_id where (($day between `from` and `to`) OR (`from` <= $day)) and is_active=1 order by feed_id desc ";
            $stmt   = $db->query($sql);
            $feed  = $stmt->fetchAll(PDO::FETCH_NAMED);

        }
        else
        {
            $response["header"]["error"] = "1";
            $response["header"]["message"] = $config["message_add_baby_en"];
            $response["header"]["message_arabic"] = $config["message_add_baby_ar"];
        }

        $response["body"] = $feed;
        $response["header"]["error"] = "0";
        $response["header"]["message"] = $config["message_success_en"];
        $response["header"]["message_arabic"] = $config["message_success_ar"];

    }
    catch(PDOException $e)
    {
        $response["header"]["error"] = "1";
        $response["header"]["message"] = $e->getMessage();
        $response["header"]["message_arabic"] = $config["message_error_ar"];
    }



    $app->response()->header("Content-Type", "application/json");
    echo json_encode($response);
}

function getGrowthTracker()
{
    global $app, $db, $response,$config;
    $req = $app->request(); // Getting parameter with names
    $baby_id = $req->params('baby_id'); // Getting parameter with names
    $user_id = $req->params('user_id'); // Getting parameter with names
    $type = $req->params('type'); // Getting parameter with names
    $feed = array();
    $sql = "select floor(DATEDIFF(CURDATE(),dob)/30) as month,gender as g from babies where user_id=$user_id";

    try{
        $stmt   = $db->query($sql);
        $month  = $stmt->fetchAll(PDO::FETCH_NAMED);

        if(is_array($month) && count($month) > 0)
        {
            $m = $month[0]['month'];
            $g = $month[0]['g'];
            $sql = "select * from tracks where gender=$g and type=$type";//age  between $m-5 and $m+5 and limit 10
            $stmt   = $db->query($sql);
            $feed  = $stmt->fetchAll(PDO::FETCH_NAMED);

        }
        else
        {
            $response["header"]["error"] = "1";
            $response["header"]["message"] = $config["message_no_data_en"];
            $response["header"]["message_arabic"] = $config["message_no_data_ar"];
        }

        $response["body"] = $feed;
        $response["header"]["error"] = "0";
        $response["header"]["message"] = $config["message_success_en"];
        $response["header"]["message_arabic"] = $config["message_success_ar"];

    }
    catch(PDOException $e)
    {
        $response["header"]["error"] = "1";
        $response["header"]["message"] = $e->getMessage();
        $response["header"]["message_arabic"] = $config["message_error_ar"];
    }



    $app->response()->header("Content-Type", "application/json");
    echo json_encode($response);
}


function getGrowthTrackers($user_id,$weight,$height,$date)
{
	global $db,$config;
    //global $app, $db, $response;
    //$req = $app->request(); // Getting parameter with names

    $feed = array();

    $sql = "select floor(DATEDIFF('".$date."',dob)/30) as month,gender as g from babies where user_id=$user_id";

    try{
        $stmt   = $db->query($sql);
        $month  = $stmt->fetchAll(PDO::FETCH_NAMED);

        if(is_array($month) && count($month) > 0)
        {
            $m = $month[0]['month'];
            $g = $month[0]['g'];
            $sql = "select * from tracks where age = $m and gender=$g";
            $stmt   = $db->query($sql);
            $feed  = $stmt->fetchAll(PDO::FETCH_NAMED);
            $messageHeight = "";
			$messageHeightArabic = "";
            $messageWeight = "";
			$messageWeightArabic = "";

			$genderText = $g==0?"his":"her";

			foreach ($feed as $feedItem){

				// Length
				if($feedItem['type']==1){

					// BELOW AVERAGE PERCENTILE
					if($height <= $feedItem['p25']){

                        // $messageHeight = "is smaller than some children ".$genderText." age.";4
                        if($g == 0)
                        {
                            $messageHeight = $config["message_male_smaller_en"];
                            $messageHeightArabic = $config["message_male_smaller_ar"];
                        }   
                        else
                        {
                            $messageHeight = $config["message_female_smaller_en"];
                            $messageHeightArabic = $config["message_female_smaller_ar"];
                        } 
                        

					}

					if($height >= $feedItem['p25'] && $height <= $feedItem['p75']){

						if($g == 0)
                        {
                            $messageHeight = $config["message_male_similar_en"];
                            $messageHeightArabic = $config["message_male_similar_ar"];
                        }   
                        else
                        {
                            $messageHeight = $config["message_female_similar_en"];
                            $messageHeightArabic = $config["message_female_similar_ar"];
                        }

					}

					if($height >= $feedItem['p75']){

						if($g == 0)
                        {
                            $messageHeight = $config["message_male_bigger_en"];
                            $messageHeightArabic = $config["message_male_bigger_ar"];
                        }   
                        else
                        {
                            $messageHeight = $config["message_female_bigger_en"];
                            $messageHeightArabic = $config["message_female_bigger_ar"];
                        }

					}

				}

				// Width

				if($feedItem['type']==2){

					// BELOW AVERAGE PERCENTILE
					if($weight <= $feedItem['p25']){

						if($g == 0)
                        {
                            $messageWeight = $config["message_male_smaller_en"];
                            $messageWeightArabic = $config["message_male_smaller_ar"];
                        }   
                        else
                        {
                            $messageWeight = $config["message_female_smaller_en"];
                            $messageWeightArabic = $config["message_female_smaller_ar"];
                        }

					}

					if($weight >= $feedItem['p25'] && $weight <= $feedItem['p75']){

						if($g == 0)
                        {
                            $messageWeight = $config["message_male_similar_en"];
                            $messageWeightArabic = $config["message_male_similar_ar"];
                        }   
                        else
                        {
                            $messageWeight = $config["message_female_similar_en"];
                            $messageWeightArabic = $config["message_female_similar_ar"];
                        }

					}

					if($weight >= $feedItem['p75']){

						if($g == 0)
                        {
                            $messageWeight = $config["message_male_bigger_en"];
                            $messageWeightArabic = $config["message_male_bigger_ar"];
                        }   
                        else
                        {
                            $messageWeight = $config["message_female_bigger_en"];
                            $messageWeightArabic = $config["message_female_bigger_ar"];
                        }

					}

				}
			}


        }
		$arr = array();
        $arr['weight']=$messageWeight;
		$arr['weight_arabic']=$messageWeightArabic;
        $arr['height']=$messageHeight;
		$arr['height_arabic']=$messageHeightArabic;

		return $arr;
    }
    catch(PDOException $e)
    {
        $response["header"]["error"] = "1";
        $response["header"]["message"] = $e->getMessage();
        $response["header"]["message_arabic"] = $config["message_error_ar"];
    }



    $app->response()->header("Content-Type", "application/json");
    echo json_encode($response);
}

function updateBabyGrowth() {
    global $app, $db, $response,$config;
    $user = array();

    $req = $app->request(); // Getting parameter with names
    $baby_id = $req->params('baby_id'); // Getting parameter with names
    $user_id = $req->params('user_id'); // Getting parameter with names
    $weight = $req->params('weight'); // Getting parameter with names
    $height = $req->params('height'); // Getting parameter with names
    $date= $req->params('date');
    
    if((bool)strtotime($date) == false)
    {
        $response["header"]["error"] = "1";
        $response["header"]["message"] = $config["message_invalid_date_error_en"];
        $response["header"]["message_arabic"] = $config["message_invalid_date_error_ar"];
        $app->response()->header("Content-Type", "application/json");
        echo json_encode($response);
        return;
    }

    $month = date("m",strtotime($date));
    $year = date("Y",strtotime($date));



    if(babyGrowthDataAvailable($user_id, $baby_id, $date))
    {
        $sql = "UPDATE growth set user_id=:user_id, baby_id=:baby_id, date=:date, height=:height, weight=:weight
                WHERE user_id=:user_id
                AND baby_id=:baby_id
                AND MONTH(date)=$month
                AND YEAR(date)=$year";
    }
    else
    {
        $sql = "INSERT INTO growth (user_id,baby_id,date,weight,height)
                values
                (:user_id,:baby_id,:date,:weight,:height)";
    }




    try{
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->bindParam(":baby_id", $baby_id);
        $stmt->bindParam(":date", $date);
        $stmt->bindParam(":weight", $weight);
        $stmt->bindParam(":height", $height);
        $stmt->execute();

        $user["growth_id"] = $db->lastInsertId();

        if(date("m") == $month && date("Y") == $year)
        {
            updateBabyProfile($user_id,$baby_id,$weight,$height,$date);
        }


        $message = getGrowthTrackers($user_id,$weight,$height,$date);

        $response["body"] = $message;
        $response["header"]["error"] = "0";
        $response["header"]["message"] = $config["message_success_en"];
        $response["header"]["message_arabic"] = $config["message_success_ar"];

    }
    catch(PDOException $e)
    {
        $response["header"]["error"] = "1";
        $response["header"]["message"] = $e->getMessage();
        $response["header"]["message_arabic"] = $config["message_error_ar"];
    }



    $app->response()->header("Content-Type", "application/json");
    echo json_encode($response);

}


function updateBabyProfile($user_id,$baby_id,$weight,$height)
{
    global $db,$config;


    $sql = "UPDATE babies set weight=:weight,height=:height where user_id=:user_id and baby_id=:baby_id";


    try{
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->bindParam(":baby_id", $baby_id);
        $stmt->bindParam(":weight", $weight);
        $stmt->bindParam(":height", $height);
        $stmt->execute();


    }
    catch(PDOException $e)
    {

    }

}

function getBabyGrowth() {
    global $app, $db, $response,$config;

    $user = array();

    $req = $app->request(); // Getting parameter with names
    $user_id = $req->params('user_id'); // Getting parameter with names
    $from_date = $req->params('from_date'); // Getting parameter with names
    $to_date = $req->params('to_date'); // Getting parameter with names

	$message = array();
	$message = getLastGrowthValueOfUser($user_id);

    $sql = "SELECT * FROM growth WHERE user_id=$user_id ";

    if($from_date != "")
    {
        $sql .= " AND date between '$from_date' ";
        $to_date = ($to_date != "") ? $to_date : date("Y-m-d");
        $sql .= " AND '$to_date' ";
    }

    $sql .= " ORDER BY date ASC";


    try{
        $stmt   = $db->query($sql);
        $growth  = $stmt->fetchAll(PDO::FETCH_NAMED);

        $response["body"]['values'] = $growth;
        $response["body"]['message'] = $message;

        $response["header"]["error"] = "0";
        $response["header"]["message"] = $config["message_success_en"];
        $response["header"]["message_arabic"] = $config["message_success_ar"];

    }
    catch(PDOException $e)
    {
        $response["header"]["error"] = "1";
        $response["header"]["message"] = $e->getMessage();
        $response["header"]["message_arabic"] = $config["message_error_ar"];
    }



    $app->response()->header("Content-Type", "application/json");
    echo json_encode($response);

}

function getLastGrowthValueOfUser($user_id){

	global $app, $db, $response,$config;
    $sql = "SELECT * FROM growth WHERE user_id=$user_id order by date desc limit 1";

    try{

        $stmt   = $db->query($sql);
        $growth  = $stmt->fetchAll(PDO::FETCH_NAMED);

        $message = array();

        if(count($growth) > 0)
	        $message = getGrowthTrackers($user_id,$growth[0]['weight'],$growth[0]['height'],$growth[0]['date']);

		return $message;
    }
    catch(PDOException $e)
    {

    	return array();
       // $response["header"]["error"] = "1";
       // $response["header"]["message"] = $e->getMessage();
    }


}



function editProfile() {
	global $app, $db, $response,$config;

	$req = $app->request(); // Getting parameter with names
    $first_name = $req->params('first_name'); // Getting parameter with names
    $email = $req->params('email'); // Getting parameter with names
    $last_name = $req->params('last_name'); // Getting parameter with names
    $dob= $req->params('dob');
    $gender= $req->params('gender');
    $user_id= $req->params('user_id');
    $user_image = '';

    $useravailable = userAvailable($email);


    if(!$useravailable)
    {
        if(isset($_FILES['file']))
        {
            $uploaddir = 'images/';
            $file = basename($_FILES['file']['name']);
            $protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';

            $uploadfile = $uploaddir . $file;

            if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
                $user_image = $uploadfile;
                $path = substr($_SERVER['REQUEST_URI'],0,stripos($_SERVER['REQUEST_URI'], "index.php"));
                $user_image = $protocol.$_SERVER['SERVER_NAME'].$path.$user_image;
            } else {
                $response["header"]["error"] = "1";
                $response["header"]["message"] = $config["message_error_en"];
                $response["header"]["message_arabic"] = $config["message_error_ar"];
            }
        }

        if($user_image){
         $userImageText = " ,image=:user_image ";
     }else{
       $userImageText = "";
   }

   $sql = "UPDATE users SET
   first_name=:first_name,
   last_name=:last_name,
   dob=:dob,
   gender=:gender
   ".$userImageText."
   WHERE user_id=:user_id";

   if(count($response) == 0)
   {
    try{
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->setAttribute(PDO::ATTR_EMULATE_PREPARES,true);
        $stmt = $db->prepare($sql);
        $datetime = date("Y-m-d h:i:s");
        $stmt->bindParam(":first_name", $first_name);
        $stmt->bindParam(":last_name", $last_name);
        $stmt->bindParam(":dob", $dob);
        $stmt->bindParam(":gender", $gender);
        if($user_image){
         $stmt->bindParam(":user_image", $user_image);
     }
     $stmt->bindParam(":user_id",$user_id);
     $stmt->execute() ;

     $response["header"]["error"] = "0";
     $response["header"]["message"] = $config["message_success_en"];
     $response["header"]["message_arabic"] = $config["message_success_ar"];

 }
 catch(PDOException $e)
 {
    $response["header"]["error"] = "1";
    $response["header"]["message"] = $e->getMessage();
    $response["header"]["message_arabic"] = $config["message_error_ar"];
}
}
}
else
{
    $response["header"]["error"] = "1";
    $response["header"]["message"] = $config["message_user_not_exist_en"];
    $response["header"]["message_arabic"] = $config["message_user_not_exist_ar"];
}


$app->response()->header("Content-Type", "application/json");
echo json_encode($response);

}

function parms($string,$data) {
    $indexed=$data==array_values($data);
    foreach($data as $k=>$v) {
        if(is_string($v)) $v="'$v'";
        if($indexed) $string=preg_replace('/\?/',$v,$string,1);
        else $string=str_replace(":$k",$v,$string);
    }
    return $string;
}

function userAvailable($email)
{
	global $db,$config;

	$sql = "SELECT * FROM users WHERE email=:email and is_active=1 limit 1";


	try{
		$stmt = $db->prepare($sql);
        $stmt->bindParam(":email", $email);
        $result = $stmt->execute();
        $info  = $stmt->fetch(PDO::FETCH_NAMED);

        if($stmt->rowCount() > 0)
        {
           return false;
       }
       else
       {
           return true;
       }
   }
   catch(PDOException $e)
   {
		//debug($e->getMessage(),1);
      return false;
  }
}

function babyAvailable($user_id)
{
    global $db,$config;

    $sql = "SELECT * FROM babies WHERE user_id=:user_id limit 1";


    try{
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":user_id", $user_id);
        $result = $stmt->execute();
        $info  = $stmt->fetch(PDO::FETCH_NAMED);

        if($stmt->rowCount() > 0)
        {
            return false;
        }
        else
        {
            return true;
        }
    }
    catch(PDOException $e)
    {
        //debug($e->getMessage(),1);
        return false;
    }
}

function babyGrowthDataAvailable($user_id, $baby_id, $date)
{
    global $db,$config;

    $month = date("m",strtotime($date));
    $year = date("Y",strtotime($date));



    $sql = "SELECT * FROM growth WHERE user_id=:user_id and baby_id=:baby_id and MONTH(date) = :month and YEAR(date) = :year limit 1";


    try{
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->bindParam(":baby_id", $baby_id);
        $stmt->bindParam(":month", $month);
        $stmt->bindParam(":year", $year);
        $result = $stmt->execute();
        $info  = $stmt->fetch(PDO::FETCH_NAMED);

        if($stmt->rowCount() > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    catch(PDOException $e)
    {
        //debug($e->getMessage(),1);
        return false;
    }
}

function imgSave()
{
    global $db, $app, $response,$config;

    if(!isset($_FILES['file']))
    {
        $response["header"]["error"] = "1";
        $response["header"]["message"] = $e->getMessage();
        $response["header"]["message_arabic"] = $config["message_error_ar"];
    }
    else
    {
        $uploaddir = 'images/';
        $file = basename($_FILES['file']['name']);
        $uploadfile = $uploaddir . $file;

        if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
            $response["header"]["error"] = "0";
            $response["header"]["message"] = $uploadfile;
        } else {
            $response["header"]["error"] = "1";
            $response["header"]["message"] = $config["message_error_en"];
            $response["header"]["message_arabic"] = $config["message_error_ar"];
        }
    }

}



function sendMessage()
{
    global $app ,$db, $response,$config;
    $req = $app->request();
    $message = $req->params('message');
    $from = $req->params('from');
    $to = $req->params('to');


    try{

        $sql = "INSERT INTO messages (message,`from`,`to`,`datetime`) values (:message,:from,:to,:datetime)";
        $stmt = $db->prepare($sql);
        $date = date("Y-m-d h:i:s");
        $stmt->bindParam(":message", $message);
        $stmt->bindParam(":from", $from);
        $stmt->bindParam(":to", $to);
        $stmt->bindParam(":datetime", $date);
        $stmt->execute();
        $response["header"]["error"] = "0";
        $response["header"]["message"] = $config["message_success_en"];
        $response["header"]["message_arabic"] = $config["message_success_ar"];


    }
    catch(PDOException $e){
        $response["header"]["error"] = "1";
        $response["header"]["message"] = $e->getMessage();
        $response["header"]["message_arabic"] = $config["message_error_ar"];
    }

    $app->response()->header("Content-Type", "application/json");
    echo json_encode($response);
}

function updatePassword()
{
    global $app ,$db, $response,$config;
    $req = $app->request();
    $user_id = $req->params('user_id');
    $old_password = $req->params('old_password');
    $new_password = $req->params('new_password');

    $sql = "SELECT * FROM users WHERE user_id=:user_id";

    try{

        $stmt = $db->prepare($sql);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();

        $data = (array)$stmt->fetchObject();

        if(is_array($data) && count($data) > 0)
        {

            if($data['password'] != MD5($old_password))
            {
                $response["header"]["error"] = "1";
                $response["header"]["message"] = $config["message_password_not_match_en"];
                $response["header"]["message_arabic"] = $config["message_password_not_match_ar"];
            }
            else
            {
                $temp_password = MD5($new_password);
                //echo $new_password;
                $sql = "UPDATE users set password='$temp_password' WHERE user_id=:user_id";

                $stmt = $db->prepare($sql);

                $stmt->bindParam(":user_id", $user_id);

                $stmt->execute();
                $response["header"]["error"] = "0";
                $response["header"]["message"] = $config["message_success_en"];
                $response["header"]["message_arabic"] = $config["message_success_ar"];
            }
        }
        else
        {
            $response["header"]["error"] = "1";
            $response["header"]["message"] = $config["message_error_en"];
            $response["header"]["message_arabic"] = $config["message_error_ar"];
        }

    }
    catch(PDOException $e){
        $response["header"]["error"] = "1";
        $response["header"]["message"] = $e->getMessage();
        $response["header"]["message_arabic"] = $config["message_error_ar"];
    }

    $app->response()->header("Content-Type", "application/json");
    echo json_encode($response);

}

function sendEmail($data)
{
    $to = $data['to'];
    $from = $data["from"];
    $subject = $data['subject'];
    $message = $data['message'];
    $headers = "From: $from" . "\r\n";

    mail($to, $subject, $message,$headers);
}

function verify($email,$code)
{
    global $app ,$db, $response,$config;


    $sql = "SELECT * FROM users WHERE email=:email AND SUBSTRING(password from 1 for 6)=:password";

    try{

        $stmt = $db->prepare($sql);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $code);
        $stmt->execute();

        $data = $stmt->fetchColumn();

        if($data != false)
        {

            $sql = "UPDATE users set verified=1 WHERE email=:email";

            $stmt = $db->prepare($sql);

            $stmt->bindParam(":username", $email);

            $stmt->execute();
            $response["header"]["error"] = "0";
            $response["header"]["message"] = $config["message_success_en"];
            $response["header"]["message_arabic"] = $config["message_success_ar"];

        }
        else
        {
            $response["header"]["error"] = "1";
            $response["header"]["message"] = $config["message_error_en"];
            $response["header"]["message_arabic"] = $config["message_error_ar"];
        }

    }
    catch(PDOException $e){
        $response["header"]["error"] = "1";
        $response["header"]["message"] = $e->getMessage();
        $response["header"]["message_arabic"] = $config["message_error_ar"];
    }

    $app->response()->header("Content-Type", "application/json");
    echo json_encode($response);

}

function forgotPassword()
{
    global $app ,$db, $response,$config;
    $req = $app->request();
    $email = $req->params('email');

    $sql = "SELECT count(*) FROM users WHERE email=:email";

    try{

        $stmt = $db->prepare($sql);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        $result = $stmt->fetchColumn();

        if($result > 0)
        {


            $temp_password = rand_string(8);
            $md5 = md5($temp_password);
                //echo $new_password;
            $sql = "UPDATE users set password='$md5' WHERE email=:email";

            $stmt = $db->prepare($sql);

            $stmt->bindParam(":email", $email);

            $stmt->execute();

				//email work here
            $subject = 'Danone - Your password has been changed successfully';
            $message = 'Your temporary password is '.$temp_password;
            $email = array('to'=>$email, 'from'=>$config["admin_email"],'subject'=>$subject, 'message'=>$message);
            sendEmail($email);

            $response["header"]["error"] = "0";
            $response["header"]["message"] = $config["message_success_en"];
            $response["header"]["message_arabic"] = $config["message_success_ar"];

        }
        else
        {
            $response["header"]["error"] = "1";
            $response["header"]["message"] = $config["message_user_not_exist_en"];
            $response["header"]["message_arabic"] = $config["message_user_not_exist_ar"];
        }

    }
    catch(PDOException $e){
        $response["header"]["error"] = "1";
        $response["header"]["message"] = $e->getMessage();
        $response["header"]["message_arabic"] = $config["message_error_ar"];
    }

    $app->response()->header("Content-Type", "application/json");
    echo json_encode($response);

}


// POST route
$app->post(
    '/post',
    function () {

		$req = $app->request(); // Getting parameter with names
    $paramName = $req->params('name'); // Getting parameter with names
    $paramEmail = $req->params('email'); // Getting parameter with names

    echo 'This is a POST route';
}
);

// PUT route
$app->put(
    '/put',
    function () {
        echo 'This is a PUT route';
    }
    );

// PATCH route
$app->patch('/patch', function () {
    echo 'This is a PATCH route';
});

// DELETE route
$app->delete(
    '/delete',
    function () {
        echo 'This is a DELETE route';
    }
    );

/**
 * Step 4: Run the Slim application
 *
 * This method should be called last. This executes the Slim application
 * and returns the HTTP response to the HTTP client.
 */
$app->run();