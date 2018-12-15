<?php
session_start();
/**
 * Set error reporting in dev mode
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

/**
 * We insert a dummy row in table vote for user 1 (admin)
 * Set to true if you want no dummy data
 */
define('NO_DUMMY_DATA', false);

/**
 * Set DB credentials here
 */
define('DB_HOST', 'localhost');
define('DB_NAME', 'sportspoll');
define('DB_USER', 'root');
define('DB_PASS', 'root');

/**
 * Set log level here for Log class
 */
define('NONE', 0);
define('INFO', 1);
define('WARN', 2);
define('CRITICAL', 3);
define('ALL', 4);

define('ERROR_LOG_LEVEL', ALL);
