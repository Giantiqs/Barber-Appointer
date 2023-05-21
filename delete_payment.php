<?php

if (isset($_GET["payment_id"])) {
    $payment_id = $_GET["payment_id"];

    $connection = new mysqli("localhost", "root", "", "barber_database");

    $connection->query("DELETE FROM service WHERE payment_id = '$payment_id'");
}

header("location: /appointment_system/payments.php"); 
exit;

?>