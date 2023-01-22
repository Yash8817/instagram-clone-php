<?php

use App\Models\User;

function check($field , $data)
{
    $database = new Database();
    $db = $database->getConnection();

    $stmt = $db->prepare("select * from user where :field = :data");
    $stmt->bindParam(':field', $field);
    $stmt->bindParam(':data', $data);

    $stmt->execute();
    
    $users = $stmt->fetchAll();
    print_r($stmt);
    if ( count($users) > 0 ) {
        echo 1;
    }
    else
    {
        echo 0;
    }
}
