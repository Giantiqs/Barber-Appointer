<?php

date_default_timezone_set('Asia/Manila');

$date_now = date("Y-m-d");

if (isset($_GET["appointment_id"])) {
    $aid = $_GET["appointment_id"];
    $connection = new mysqli("localhost", "root", "", "barber_database");

    $res = $connection->query("SELECT payment_id FROM payments WHERE appointment_id = '$aid' ORDER BY payment_id DESC LIMIT 1");
    $row = $res->fetch_assoc();
    $payment_id = $row['payment_id'];

    $connection->query("UPDATE appointments SET status = 'confirmed' WHERE appointment_id = '$aid'");
    $connection->query("UPDATE payments SET date = '$date_now' WHERE payment_id = '$payment_id'");
}

header("location: /appointment_system/barber_appointments.php");
exit;
?>
