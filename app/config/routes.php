<?php

return [

	'' => [
		'controller' => 'main',
		'action' => 'index'
	],

	'user/login' => [
		'controller' => 'user',
		'action' => 'login'
	],

	'user/register' => [
		'controller' => 'user',
		'action' => 'register'
	],

	'photo/new' => [
		'controller' => 'photo',
		'action' => 'new'
	],

	'user/restore' => [
		'controller' => 'user',
		'action' => 'restore'
	],

	'user/activation/[\w]+' => [
		'controller' => 'user',
		'action' => 'activation'
	]
];