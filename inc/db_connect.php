<?php
/* DB connection setup file */
if(!defined('APP_ROOT')) {
	exit('No direct script access allowed');
}

try {
    $pdo = new PDO('mysql:host=localhost;dbname=assessment', 'assessment', 'assessment');
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}