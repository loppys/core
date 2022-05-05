<?php

return [
	'structure' => [
		'core' => 'ROOT:/vendor/vengine/core/',
		'pages' => 'CONFIG:pages/routes.php',
		'coreConfig' => 'CORE:src/config/',
		'api' => 'CORE:src/_api/',
		'uApi' => 'WWW:_api/'
	],
	'defaults' => [
		'Core' => [
			'closed' => false,
			'require' => [
				'project' => ['coreConfig' => 'project.config.php'],
				'pages' => ['coreConfig' => 'routes.php']
			]
		],
	]
];
