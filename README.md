# Project Todo-list
Create a todo-list in PHP where the tasks are saved using MySQL

## Coding
* Application SHOULD be written in PHP following PSR-standard [(https://www.php-fig.org/psr/psr-2/)](https://www.php-fig.org/psr/psr-2/)
* Application MUST be Object-Oriented
* Application SHOULD follow MVC pattern
* Shipped code MUST work as it is
* Files containing classes SHOULD be included using an autoloader 

## Settings
* Settings MUST be stored in a settings file and not in actual code
* The settings file MUST contain at least one setting

## Saving tasks - MySQL
* One class MAY be used as a wrapper for MySQL
* Settings for connection MUST be set in config file and fetched 
* A MySQL dump MUST be included to be able to recreate the database tables
* PDO MAY be used [(http://wiki.hashphp.org/PDO_Tutorial_for_MySQL_Developers)](http://wiki.hashphp.org/PDO_Tutorial_for_MySQL_Developers)

## Functions
* As a user you MUST be able to create, edit and delete a task
* As a user you MUST be able to set a task as done
* When delete a task and set a task as done you MAY be able to regret the action
* A task SHOULD contain a title, description, time of creation, time last update, a duedate, priority and time when task is completed
* As a user MUST be able to sort task by creation date, priority and due date

## Design
* The application SHOULD have a look and feel of a todo-list but the main focus is on PHP
* CSS framework is optional ( for example boostrap )

###### The definition of the words MUST, SHOULD and MAY can be found here [(http://www.ietf.org/rfc/rfc2119.txt)](http://www.ietf.org/rfc/rfc2119.txt)

## Testing
  * To run the php unit tests, inside backend/src run `phpunit ` .
