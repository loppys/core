<?php

return [
	'structure' => [
		'core' => 'PROJECT:vendor/vengine/core/',
		'modules' => 'CORE:src/Modules/',
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
