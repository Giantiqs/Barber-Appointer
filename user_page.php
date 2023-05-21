<?php

date_default_timezone_set('Asia/Manila');

session_start();

$current_user = $_SESSION["username"];
$user_id = $_SESSION["user_id"];
$current_time_stamp = date('m/d/Y h:i');
$connection = new mysqli("localhost", "root", "", "barber_database");
$appointment_date = "";

$result = $connection->query("SELECT date_time FROM appointments WHERE user_id = '$user_id' and status = 'pending' ORDER BY date_time;");

if (!$result) {
    die("Invalid query: " . $connection->error);
}

if ($result->num_rows != 0) {
    $curr_date = $result->fetch_assoc();

    $nearest_date = $curr_date["date_time"];
    $_SESSION["curr_datetime"] = $nearest_date;

    $appointment_date = date("F j, Y g:i a", strtotime($nearest_date));
}

$result2 = $connection->query("SELECT SUM(payments.amount) as total FROM users 
INNER JOIN appointments on users.user_id = appointments.user_id
INNER JOIN payments on appointments.payment_id = payments.payment_id
WHERE users.user_id = '$user_id'");

$total_spent = 0;

while ($row = $result2->fetch_assoc()) {
    $total_spent = $row["total"];
}

$result3 = $connection->query("SELECT services.name AS service, services.description AS descr,COUNT(appointment_service.service_id) AS counts FROM appointment_service 
INNER JOIN services ON appointment_service.service_id = services.service_id
GROUP BY service
ORDER by counts DESC 
LIMIT 1");

$most_picked_row = $result3->fetch_assoc();
$most_picked = $most_picked_row["service"];
$most_picked_count = $most_picked_row["counts"];
$service_desc = $most_picked_row["descr"];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        .bg-image {
            height: auto;
            background-image: url('imgs/divbg.jpg');
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

        .glass {
            background-color: rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 10px;
        }

        input.form-control {
            border: none;
            border-bottom: 1px solid white;
            background-color: transparent;
            color: white;
            border-radius: 0;
        }

        input:focus {
            outline: none;
        }

        .announcement {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            margin-top: 20px;
            overflow: hidden;
            position: relative;
        }

        .announcement:before {
            content: "";
            background-color: rgba(255, 255, 255, 0.1);
            height: 100%;
            left: -25%;
            position: absolute;
            top: 0;
            transform: skewX(-15deg);
            width: 50%;
            z-index: -1;
        }

        .announcement:after {
            content: "";
            background-color: rgba(255, 255, 255, 0.1);
            height: 100%;
            right: -25%;
            position: absolute;
            top: 0;
            transform: skewX(15deg);
            width: 50%;
            z-index: -1;
        }
    </style>
</head>

<body class="bg-image">

    <div class="container-fluid">

        <nav class="navbar navbar-expand-sm bg-dark shadow navbar-dark fixed-top py-3">
            <div class="container-fluid">

                <button class="btn btn-dark" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasWithBothOptions" aria-controls="offcanvasWithBothOptions"><i class="bi bi-list"></i></button>
                <a class="navbar-brand text-light mx-3" href="#">HOME</a>
                <div class="offcanvas offcanvas-start bg-dark text-white" data-bs-scroll="true" tabindex="-1" id="offcanvasWithBothOptions" aria-labelledby="offcanvasWithBothOptionsLabel">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title ms-3 mt-3" id="offcanvasWithBothOptionsLabel">HOME</h5>
                        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <hr>
                        <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start ms-3" id="menu">
                            <li class="nav-item">
                                <a href="user_page.php" class="nav-link align-middle px-0">
                                    <i class="fs-4 bi-house-fill"></i> <span class="ms-1 d-none d-sm-inline text-light">Home</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="appoint.php" class="nav-link align-middle px-0">
                                    <i class="fs-4 bi-calendar-plus-fill"></i> <span class="ms-1 d-none d-sm-inline text-light">Appoint now</span>
                                </a>
                            </li>
                            <li>
                                <a href="history.php" class="nav-link px-0 align-middle">
                                    <i class="fs-4 bi-calendar-check-fill"></i> <span class="ms-1 d-none d-sm-inline text-light">Appointment History</span> </a>
                            </li>
                        </ul>
                        <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
                        <h5 class="offcanvas-title ms-3 mt-3" id="offcanvasWithBothOptionsLabel">ACCOUNT</h5>
                        <hr>
                        <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start ms-3" id="menu">
                            <li>
                                <a class="nav-link px-0 align-middle" href="<?php echo '/appointment_system/edit_profile.php?user_id=' . $user_id; ?>">
                                <i class="fs-4 bi-person-gear"></i> <span class="ms-1 d-none d-sm-inline text-light">Edit Profile</span></a>
                            </li>
                            <li>
                                <a class="nav-link px-0 align-middle" href="<?php echo '/appointment_system/change_password.php?user_id=' . $user_id; ?>">
                                <i class="fs-4 bi-key-fill"></i> <span class="ms-1 d-none d-sm-inline text-light">Change Password</span></a>
                            </li>
                        </ul>
                    </div>
                </div>

                <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#mynavbar">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="mynavbar">
                    <div class="navbar-nav ms-auto">
                        <div class="float-end">
                            <div class="float-end">
                                <div class="dropdown">
                                    <button class="btn btn-success py-auto px-5 dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                        <?php echo $current_user; ?>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                                        <li>
                                            <a class="dropdown-item" href="logout.php">Logout</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </div>


    <br /><br /><br /><br /><br />



    <br /><br />

    <?php

    if ($appointment_date != "") {
        echo '
        <div class="container">
            <div class="row">
                <div id="appointment" class="col shadow p-4 mb-4 glass text-light p-3 mx-3 text-center">
                    <strong>REMINDER: </strong>Your appointment will be on (' . $appointment_date . ') if you want to cancel just click <a class="text-warning" href="cancel_appointment.php">this</a>.
                </div>
            </div>
        </div>
        ';
    }

    ?>
    <div class="announcement bg-dark py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 text-center">
                    <h2 class="mb-3 text-light">Announcement</h2>
                    <p class="lead mb-4 text-light">
                        <?php echo $most_picked; ?> is the hottest service right now. If you are
                        wondering what <?php echo $most_picked; ?> is, <?php echo $most_picked; ?>
                        is the service with a description of "<?php echo $service_desc; ?>".
                        <?php echo $most_picked; ?> was picked by <?php echo $most_picked_count; ?> customers.
                    </p>
                    <a href="appoint.php" class="btn btn-outline-light btn-lg">Appoint now</a>
                </div>
            </div>
        </div>
    </div>

    <br /><br /><br />

    <div class="container">
        <div class="row">
            <div class="col shadow p-4 mb-5 glass rounded text-light p-3 mx-3 text-center">
                <h3 class="text-center">Appoint now!</h3>
                <pre class="mt-4">
Pick any time to get a new cut! Instead of
waiting for other people to get finish
with their cuts.
                </pre>
                <a class="btn btn-light px-3" href="appoint.php">Click me</a>
            </div>
            <div class="col shadow p-4 mb-5 glass rounded text-light p-3 mx-3 text-center">
                <h3>Check your history</h3>
                <pre class="mt-4">
Check your history by pressing the button
below! 
                </pre>
                <a class="btn btn-light mt-3 px-4" href="history.php">History</a>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-sm-3 text-light p-3 text-center"></div>
            <div class="col shadow p-4 mb-5 glass rounded text-light p-3 text-center">
                <div class="p-4">
                    <h3 class="text-center">Having problems?</h3>
                    <pre class="mt-4">
Contact our admin <a href="#">ouradmin@gmail.com</a>
or dial us directly at <a href="#">0987654321</a>.
            </pre>
                </div>
            </div>
            <div class="col-sm-3 text-light p-3 text-center"></div>
        </div>
    </div>

    <footer class="bg-dark text-center text-lg-start mt-5 pt-5">
        <div class="container text-light">
            <div class="row mx-5">
                <div class="col mx-5">
                    <h5 class="text-uppercase text-center">Barber Appointment</h5>
                    <p class="m-5 text-center">
                        Barber Appointment is a website where you can set appointments with the
                        barber of your choice.
                    </p>
                </div>
                <div class="text-center p-3 bg-dark text-light">© 2023 Barber Appointer</div>
    </footer>

</body>

</html>