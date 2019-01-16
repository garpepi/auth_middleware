<?php

use Slim\Http\Request;
use Slim\Http\Response;
use \Firebase\JWT\JWT;
use Tuupola\Base62;

use App\Models\Appauth;
date_default_timezone_set("Asia/Bangkok");

// Routes
$app->post('/login', function (Request $request, Response $response, array $args) {
 
    $input = $request->getParsedBody();
	
	$auth = Appauth::where('app_code',$input['app_code'])->first();
	
	// verify app_code.
    if(empty($auth)) {
        return $this->response->withJson(['error' => true, 'message' => 'These credentials do not match our records.']);  
    }
 
    // verify password.
    if (!password_verify($input['password'],$auth->salt)) {
        return $this->response->withJson(['error' => true, 'message' => 'These credentials do not match our records. Please check again!']);  
    }
	
    $settings = $this->get('settings'); // get settings array.
    $now = new DateTime();
    $future = new DateTime("+10 minutes");
    $server = $request->getServerParams();
	
	$data = array(
		'id' => $auth->id, 'app_code' => $auth->app_code,
		'iat' => time(),'exp' => time()+(2*60*60)
	);
    $token = JWT::encode($data, $settings['jwt']['secret'], "HS256");
    return $this->response->withJson(['token' => $token]);
 
});
