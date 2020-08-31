<?php

require_once 'matano.php';

class Model extends Matano
{


}//end class

$db = new Model('host', 'user', 'password', 'database');

// Use $conn for prepared statements instead of the above
$conn = $db->connect();
