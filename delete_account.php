<?php

if (isset($_GET["user_id"])) {
    $user_id = $_GET["user_id"];

    $connection = new mysqli("localhost", "root", "", "barber_database");

    $connection->query("DELETE FROM users where user_id = '$user_id'");
}

header("location: /appointment_system/user_accounts.php"); 
exit;

?>