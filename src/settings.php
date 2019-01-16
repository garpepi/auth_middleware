<?php
return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // Database connection settings
         "db" => [
            'driver' => 'mysql',
            'host' => 'localhost',
            'database' => 'auth_app_adiinter',
            'username' => 'root',
            'password' => '',
            'collation' => 'utf8_unicode_ci',
            'charset' => 'utf8',
            'prefix' => ''
        ],
		"jwt" => [
            'secret' => 'supersecretkeyyoushouldnotcommittogithb'
        ],
		"logger" => [
			'name' => 'monolog',
			'path' => '../logs/app.log',
			'level' => 1
		]
    ],
];