<?php

use Slim\Http\Request;
use Slim\Http\Response;
use \Firebase\JWT\JWT;
use Tuupola\Base62;

use App\Models\Users;

date_default_timezone_set("Asia/Bangkok");

// Routes
$app->post('/auth', function (Request $request, Response $response, array $args) {
 
	// Request Should have
	/*
		app_code
		email
		password
	*/
	
    $input = $request->getParsedBody();
	//\Illuminate\Database\Capsule\Manager::connection()->enableQueryLog();
	
	$auth =Users::join('auth', 'users.id', '=', 'auth.user_id')
			->join('applications', 'auth.app_id', '=', 'applications.id')
			->where('users.email', $input['email'])
			->where('applications.app_code', $input['app_code'])
			->where('users.status', 'active')
			->where('applications.status', 'active')
			->where('auth.status', 'active')
			->select('users.*', 'auth.type', 'applications.name as app_name', 'applications.url as app_url', 'applications.salt as app_salt')
			->first();
	
	//print_r(\Illuminate\Database\Capsule\Manager::connection()->getQueryLog());die();
	
	// verify app_code.
    if(empty($auth)) {
        return $this->response
		->withStatus(400)
		->withJson(['error' => true, 'message' => 'These credentials do not match our records.']);  
    }
 
    // verify password.
    if (!password_verify($input['password'].$auth->app_salt,$auth->password)) {
        return $this->response
		->withStatus(400)
		->withJson(['error' => true, 'message' => 'These credentials do not match our records. Please check again!']);  
    }
	
    $settings = $this->get('settings'); // get settings array.
    $now = new DateTime();
    $future = new DateTime("+10 minutes");
    $server = $request->getServerParams();
	
	$data = array(
		'id' => $auth->id, 'app_code' => $auth->app_code,
		'iat' => time(),'exp' => time()+(2*60*60)
	);
    //$token = JWT::encode($data, $settings['jwt']['secret'], "HS256");
    return $this->response->withJson(['response' => 'ok']);
 
});
