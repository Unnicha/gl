<?php defined('BASEPATH') OR exit('No direct script access allowed');

function perusahaan($db_name) 
{
	$db_name = 'gl_'.$db_name;
	
	$config_app['hostname'] = 'localhost';
	$config_app['username'] = 'root';
	$config_app['password'] = '';
	$config_app['database'] = $db_name;
	$config_app['dbdriver'] = 'mysqli';
	$config_app['dbprefix'] = '';
	$config_app['pconnect'] = FALSE;
	$config_app['db_debug'] = TRUE;
	
	return $config_app;
}