<?php

use Slim\Http\Request;
use Slim\Http\Response;
use \Firebase\JWT\JWT;
use Tuupola\Base62;

use App\Models\Appauth;
date_default_timezone_set("Asia/Bangkok");

// Routes
$app->post('/token', function (Request $request, Response $response, array $args) {
 
    $input = $request->getParsedBody();
	
	$appauth = Appauth::where('app_code',$input['app_code'])->first();
	
	// verify app_code.
    if(empty($appauth)) {
        return $this->response
		->withStatus(400)
		->withJson(['error' => true, 'message' => 'These credentials do not match our records.']);  
    }
 
    // verify password.
    if (!password_verify($input['password'],$appauth->password)) {
        return $this->response
		->withStatus(400)
		->withJson(['error' => true, 'message' => 'These credentials do not match our records. Please check again!']);  
    }
	
    $settings = $this->get('settings'); // get settings array.
    $now = new DateTime();
    $future = new DateTime("+10 minutes");
    $server = $request->getServerParams();
	
	$data = array(
		'id' => $appauth->id, 'app_code' => $appauth->app_code, 'salt' => $appauth->salt,
		'iat' => time(),'exp' => time()+(2*60*60)
	);
    $token = JWT::encode($data, $settings['jwt']['secret'], "HS256");
    return $this->response->withJson(['token' => $token]);
 
});

$app->get("/secure",  function ($request, $response, $args) {
 
    $data = ["status" => 1, 'msg' => "This route is secure!"];
 
    return $response->withStatus(200)
        ->withHeader("Content-Type", "application/json")
        ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
});
