<?php

if (isset($_GET["service_id"])) {
    $service_id = $_GET["service_id"];

    $connection = new mysqli("localhost", "root", "", "barber_database");

    $connection->query("DELETE FROM service WHERE service_id = '$service_id'");
}

header("location: /appointment_system/services.php"); 
exit;

?>