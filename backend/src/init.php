<?php
require_once 'vendor/autoload.php';
require_once 'config/app.php';

use App\Models\Database;

// Clear previous log files if any
\App\Models\Log::clear();

/**
 * Create table task
 */
$task = new \App\Models\Task();
$task->dropTable();
$task->createTable();
printf ("Created table %s. " . PHP_EOL, $task->tableName);
echo PHP_EOL;
