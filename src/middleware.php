<?php
// Application middleware

// e.g: $app->add(new \Slim\Csrf\Guard);
// e.g: $app->add(new \Slim\Csrf\Guard);
$container = $app->getContainer();
$secret = $container->get('settings')['jwt']['secret'];

$app->add(new \Tuupola\Middleware\JwtAuthentication([
	"path" => "/",
	"logger" => $container['logger'],
	"secret" => $secret,
	"ignore" => ["/login", "/token"],
	"callback" => function ($request, $response, $arguments) use ($container) {
		$container["jwt"] = $arguments["decoded"];
	},
	"error" => function ($response, $arguments) {
        $data["status"] = "error";
        $data["message"] = $arguments["message"];
        return $response
			->withStatus(401)
            ->withHeader("Content-Type", "application/json")
            ->getBody()->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    },
	"secure" => false,
//	"algorithm" => ["HS256"],
//	"attribute" => "decoded_token_data",
]));
