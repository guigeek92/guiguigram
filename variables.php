<?php
require_once(__DIR__ . '/mysql.php');
require_once(__DIR__ . '/databaseconnect.php');

// Récupération des variables à l'aide du client MySQL
$usersStatement = $mysqlClient->prepare('SELECT * FROM users');
$usersStatement->execute();
$users = $usersStatement->fetchAll();


$publisql = $mysqlClient->prepare('SELECT * FROM publication');
$publisql->execute();
$publi = $publisql->fetchAll();

$recipesStatement = $mysqlClient->prepare('SELECT * FROM publication WHERE is_enabled is TRUE');
$recipesStatement->execute();
$recipes = $recipesStatement->fetchAll();