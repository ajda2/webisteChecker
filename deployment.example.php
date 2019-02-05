<?php

$global = parse_ini_file('deployment.ini');

$local = [
	'remote'   => 'ftp://ftp.example.com/directory',
	'user'     => '',
	'password' => ''
];

return array_merge($global, $local);