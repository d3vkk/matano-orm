<?php

require_once 'matano.php';

class Model extends Matano
{


    /**
     * Add user profile
     *
     * @param  string  $firstname
     * @param  string  $lastname
     * @param  string  $phone
     * @return boolean
     */
    public function addUserProfile($firstname, $lastname, $phone)
    {
        return $this->insert("INSERT INTO users (firstname, lastname, phone) VALUES ('$firstname', '$lastname', '$phone');", 'users');

    }//end addUserProfile()


}//end class

$db = new Model('host', 'user', 'password', 'database');

// Use $conn for prepared statements instead of the above
$conn = $db->connect();
