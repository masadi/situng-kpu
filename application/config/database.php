<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$active_group = 'postgresql';
$query_builder = TRUE;

$db['mysql'] = array(
	'dsn'	=> '',
	'hostname' => 'localhost',
	'username' => 'your_database_username',
	'password' => 'your_database_password',
	'database' => 'your_database_name',
	'dbdriver' => 'mysqli',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => (ENVIRONMENT !== 'production'),
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);
$db['postgresql'] = array(
	'dsn'	=> 'pgsql:host=localhost;port=5432;dbname=your_database_name',
	'hostname' => 'localhost:5432',
	'username' => 'your_database_username',
	'password' => 'your_database_password',
	'database' => '',
	'dbdriver' => 'pdo',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => (ENVIRONMENT !== 'production'),
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt'  => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);
