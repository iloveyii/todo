<?php
    require_once 'vendor/autoload.php';
    require_once 'config/app.php';

    // Clear previous log files if any
    \App\Models\Log::clear();

    /**
     * Create table task
     */
    $task = new \App\Models\Task();
    $task->dropTable();
    $task->createTable();
    printf("Created table %s. " . PHP_EOL, $task->tableName);
    echo PHP_EOL;

    /**
     * Create table user and insert default user admin with pass admin
     */
    $user = new \App\Models\User();
    $user->dropTable();
    $user->createTable();
    printf("Created table %s. " . PHP_EOL, $user->tableName);
    $attributes = ['username' => 'admin', 'password' => 'admin'];
    $user->setAttributes($attributes)->create();
    printf("Inserted admin user into table %s. " . PHP_EOL, $user->tableName);
    echo PHP_EOL;
