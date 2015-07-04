<?php
 
class HttpBasicAuth extends \Slim\Middleware
{
    /**
     * @var string
     */
    protected $realm;
 
    /**
     * Constructor
     *
     * @param   string  $realm      The HTTP Authentication realm
     */
    public function __construct($realm = 'Protected Area')
    {
        //$this->realm = $realm;
    }
 
    /**
     * Deny Access
     *
     */   
    public function deny_access() {
        global $config;
        $res = $this->app->response();
        $res->status(401);
        $response["header"]["error"] = "1";
        $response["header"]["message"] = $config["message_invalid_token_en"];
        $response["header"]["message_arabic"] = $config["message_invalid_token_ar"];
        $res->header("Content-Type", "application/json");
        echo json_encode($response);
        //$res->header('WWW-Authenticate', sprintf('Basic realm="%s"', $this->realm));        
    }
 
    /**
     * Authenticate 
     *
     * @param   string  $username   The HTTP Authentication username
     * @param   string  $password   The HTTP Authentication password     
     *
     */
    public function authenticate($userId, $token) {
		global $db;

		if(isset($userId) && isset($token)) {
        	$sql = "SELECT * FROM devices WHERE user_id='$userId' AND token='$token'";
			try{
				$stmt = $db->prepare($sql);
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
				return false;
		    }    
        }
        else
        {
        	return false;
        }	
            
    }
 
    /**
     * Call
     *
     * This method will check the HTTP request headers for previous authentication. If
     * the request has already authenticated, the next middleware is called. Otherwise,
     * a 401 Authentication Required response is returned to the client.
     */
    public function call()
    {
    	$req = $this->app->request();
        $res = $this->app->response();
        $currentRoute = $this->app->request()->getPathInfo();
        $public_routes = array("/signup","/login","/forgotPassword");

        if(in_array($currentRoute, $public_routes))
        {
        	$this->next->call();
        }	
        else
        {
        	$userId = $req->headers('Userid');
        	$token = $req->headers('Token');	
        	if ($this->authenticate($userId, $token)) {
            	$this->next->call();
	        } else {
	            $this->deny_access();
	        }
        }	

    }
}