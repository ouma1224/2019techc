<?php
function getDB(){
	$pdo = new PDO('mysql:host=database-1.ca1qzt127uj2.us-east-1.rds.amazonaws.com;dbname=SNS', 'admin', 'testpass');
    return $pdo;
}

?>
