<?php

if (isset($_GET["appointment_id"])) {
    $appointment_id = $_GET["appointment_id"];

    $connection = new mysqli("localhost", "root", "", "barber_database");

    $connection->query("DELETE FROM appointments WHERE appointment_id = '$appointment_id'");

    $connection->query("DELETE FROM appointment_service WHERE appointment_id = '$appointment_id'");
}

header("location: /appointment_system/appointments.php"); 
exit;

?>