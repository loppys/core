<?php

$init = [
	'common',
	'licence',
	'api',
	'html',
	'template',
	'database'
];

function init_libs($file)
{
	foreach ($file as $value) {
		if ($value == 'api' || $value == 'database') {
			require $_SERVER['DOCUMENT_ROOT'] . '/core/libs/' . $value . '/process.php';
		}else{
			require $_SERVER['DOCUMENT_ROOT'] . '/core/libs/' . $value . '/' . $value . '.php';
		}
	}
}

init_libs($init);
