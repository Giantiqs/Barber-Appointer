<?php

session_start();

$connection = new mysqli("localhost", "root", "", "barber_database");

$curr_dt = $_SESSION["curr_datetime"];
$curr_user_id = $_SESSION["user_id"];

$stmt = $connection->prepare("UPDATE appointments SET status = ? WHERE date_time = ?");
$status = "cancelled";
$stmt->bind_param("ss", $status, $curr_dt);
$result = $stmt->execute();

header("location: /appointment_system/user_page.php");
?>