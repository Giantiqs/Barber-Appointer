<?php

$connection = new mysqli("localhost", "root", "", "barber_database");

if ($_SERVER["REQUEST_METHOD"] == "GET") {

    if (!isset($_GET["payment_id"])) {
        header("location: /appointment_system/payments.php");
        exit;
    }

    $payment_id = $_GET["payment_id"];

    $statement = $connection->prepare("SELECT * FROM payments WHERE payment_id = ?");
    $statement->bind_param("i", $payment_id);
    $statement->execute();
    $result = $statement->get_result();

    $row = $result->fetch_assoc();

    if (!$row) {
        header("location: /appointment_system/payments.php");
        exit;
    }

    $payment_method = $row["payment_method"];
    $amount = $row["amount"];
    $date = $row["date"];
    $appointment_id = $row["appointment_id"];
} else {

    if (!isset($_GET["payment_id"])) {
        header("location: /appointment_system/payments.php");
        exit;
    }

    $payment_id = $_GET["payment_id"];
    $payment_method = $_POST["payment_method"];
    $amount = $_POST["amount"];
    $date = $_POST["date"];
    $appointment_id = $_POST["appointment_id"];

    if (empty($payment_method) || empty($amount) || empty($date) || empty($appointment_id)) {
        $_SESSION["errorMessage"] = "Please fill out the fields";
        header("location: /appointment_system/edit_payment.php?payment_id=$payment_id");
        exit;
    }

    $statement = $connection->prepare("UPDATE payments SET payment_method = ?, amount = ?, date = ?, appointment_id = ? WHERE payment_id = ?");
    $statement->bind_param("siisi", $payment_method, $amount, $date, $appointment_id, $payment_id);
    $result = $statement->execute();

    if (!$result) {
        $_SESSION["errorMessage"] = "Invalid query " . $connection->error;
        header("location: /appointment_system/edit_payment.php?payment_id=$payment_id");
        exit;
    }

    header("location: /appointment_system/payments.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Service</title>
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

    <br /><br /><br /><br /><br /><br /><br /><br /><br />

    <div class="container mt-3">
        
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
                            <label class="col-sm-3 col-form-label">Payment Method</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="payment_method" value="<?php echo $payment_method; ?>" />
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Amount</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="amount" value="<?php echo $amount; ?>" />
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Date</label>
                            <div class="col-sm-6">
                                <input type="datetime-local" class="form-control" name="date" value="<?php echo $date; ?>" />
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Appointment_id</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="appointment_id" value="<?php echo $appointment_id; ?>" />
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
                                <a class="btn btn-danger" href="/appointment_system/payments.php" role="button">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>

</html>