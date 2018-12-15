<?php
require_once 'vendor/autoload.php';
require_once 'config/app.php';

use App\Models\Database;

// Clear previous log files if any
\App\Models\Log::clear();

/**
 * Create table event and import all data from json file
 */
$event = new \App\Models\Event();
$event->dropTable();
$event->createTable();
printf ("Created table %s. " . PHP_EOL, $event->tableName);
// Import JSON data
$num = $event->loadJsonFileToTable();
printf ("Imported %d rows from JSON file to table %s. " . PHP_EOL, $num, $event->tableName);
echo PHP_EOL;

/**
 * Create table winner and insert some data
 */
$winner = new \App\Models\Winner();
$winner->dropTable();
$winner->createTable() ;
printf ("Created table %s. " . PHP_EOL, $winner->tableName);
$winner->setAttributes(['name'=>'HOME'])->create();
$winner->setAttributes(['name'=>'DRAW'])->create();
$winner->setAttributes(['name'=>'AWAY'])->create();
printf ("Inserted 3 rows into table %s. " . PHP_EOL, $winner->tableName);
echo PHP_EOL;

/**
 * Create table user and insert default user admin with pass admin
 */
$user = new \App\Models\User();
$user->dropTable();
$user->createTable();
printf ("Created table %s. " . PHP_EOL, $user->tableName);
$attributes = ['username'=>'admin', 'password'=>'admin'];
$user->setAttributes($attributes)->create();
printf ("Inserted admin user into table %s. " . PHP_EOL, $user->tableName);
echo PHP_EOL;

/**
 * Create table vote
 */
$vote = new \App\Models\Vote();
$vote->dropTable();
$vote->createTable();
printf("Created table %s. " . PHP_EOL, $vote->tableName);
$attributes = ['event_id'=>1, 'user_id'=>1, 'winner_id'=>1];
if(! NO_DUMMY_DATA) {
    $vote->setAttributes($attributes)->create();
    printf("Inserted 1 dummy row in table %s. " . PHP_EOL, $vote->tableName);
}
echo PHP_EOL;

/**
 * Create a VIEW for distinct sport ( as category for poll )
 */
$sqlCategoryDrop = "DROP VIEW IF EXISTS category";
Database::connect()->exec($sqlCategoryDrop);
$sqlCategory = "CREATE VIEW category AS SELECT DISTINCT sport from event;";
Database::connect()->exec($sqlCategory);
printf("Created view category. " . PHP_EOL);
echo PHP_EOL;


/**
 * Create a VIEW user_voted_sport which contains category for which user has voted
 * So the user would not poll on category for which he/she has voted already
 */
$sqlUserVotedSportDrop = "DROP VIEW IF EXISTS user_voted_sport";
Database::connect()->exec($sqlUserVotedSportDrop);
$sqlUserVotedSport = "CREATE VIEW user_voted_sport AS SELECT DISTINCT sport, vote.user_id FROM event 
INNER JOIN vote
ON event.id = vote.event_id;";
Database::connect()->exec($sqlUserVotedSport);
printf("Created view user_voted_sport. " . PHP_EOL);
echo PHP_EOL;

