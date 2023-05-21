<?php

$connection = new mysqli("localhost", "root", "", "barber_database");

if ($_SERVER["REQUEST_METHOD"] == "GET") {

    if (!isset($_GET["appointment_id"])) {
        header("location: /appointment_system/appointments.php");
        exit;
    }

    $appointment_id = $_GET["appointment_id"];

    $statement = $connection->prepare("SELECT * FROM appointments WHERE appointment_id = ?");
    $statement->bind_param("i", $appointment_id);
    $statement->execute();
    $result = $statement->get_result();

    $row = $result->fetch_assoc();

    if (!$row) {
        header("location: /appointment_system/appointments.php");
        exit;
    }

    $date_time = $row["date_time"];
    $status = $row["status"];
    $user_id = $row["user_id"];
    $barber_id = $row["barber_id"];
    $payment_id = $row["payment_id"];
} else {

    if (!isset($_GET["appointment_id"])) {
        header("location: /appointment_system/appointments.php");
        exit;
    }

    $appointment_id = $_GET["appointment_id"];
    $status = $_POST["status"];
    $user_id = $_POST["user_id"];
    $barber_id = $_POST["barber_id"];
    $payment_id = $_POST["payment_id"];

    if (empty($status) || empty($user_id) || empty($barber_id) || empty($payment_id)) {
        $_SESSION["errorMessage"] = "Please fill out the fields";
        header("location: /appointment_system/edit_appointment.php?appointment_id=$appointment_id");
        exit;
    }

    $statement = $connection->prepare("UPDATE appointments SET status = ?, user_id = ?, barber_id = ?, payment_id = ? WHERE appointment_id = ?");
    $statement->bind_param("siiii", $status, $user_id, $barber_id, $payment_id, $appointment_id);
    $result = $statement->execute();

    if (!$result) {
        $_SESSION["errorMessage"] = "Invalid query " . $connection->error;
        header("location: /appointment_system/edit_appointment.php?appointment_id=$appointment_id");
        exit;
    }

    header("location: /appointment_system/appointments.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Appointment</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        .bg-image {
            height: 100vh;
            background-image: url('imgs/bgadmin.jpg');
            background-size: cover;
            background-repeat: no-repeat;
        }

        header {
            background-color: transparent;
        }

        .rounded-button {
            border-radius: 20px;
            padding: 5px 20px;
            color: #fff;
            font-size: 16px;
        }
    </style>
</head>

<body class="bg-image">

    <br /><br /><br />

    <div class="container mt-3">
    <br /><br /><br /><br /><br />
        <div class="row">
            <div class="col mx-5">
                <div class="container my-5 col shadow p-5 mb-5 bg-body rounded p-3 center">
                    <?php
                    if (!empty($errorMessage)) {
                        echo "
                                    <div class='alert alert-warning alert-dismissible fade show' role='alert'>
                                        <strong>$errorMessage</strong>
                                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                    </div>
                                ";
                    }
                    ?>

                    <form method="post">
                        <br />
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Status</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="status" value="<?php echo $status; ?>" />
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">User ID</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="user_id" value="<?php echo $user_id; ?>" />
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Barber ID</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="barber_id" value="<?php echo $barber_id; ?>" />
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Payment ID</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="payment_id" value="<?php echo $payment_id; ?>" />
                            </div>
                        </div>

                        <br />

                        <?php
                        if (!empty($successMessage)) {
                            echo "
                                        <div class='alert alert-warning alert-dismissible fade show' role='alert'>
                                            <strong>$successMessage</strong>
                                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                        </div>
                                    ";
                        }
                        ?>

                        <div class="row mb-3">
                            <div class="offset-sm-3 col-sm-3 d-grid">
                                <button type="submit" class="btn btn-dark">Save</button>
                            </div>
                            <div class="col-sm-3 d-grid">
                                <a class="btn btn-danger" href="/appointment_system/appointments.php" role="button">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>

</html>