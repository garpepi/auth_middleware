<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes
$app->get("/secure",  function ($request, $response, $args) {
 
    $data = ["status" => 1, 'msg' => "This route is secure!"];
 
    return $response->withStatus(200)
        ->withHeader("Content-Type", "application/json")
        ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
});

