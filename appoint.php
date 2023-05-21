<?php

session_start();
date_default_timezone_set('Asia/Manila');

function check_appointments($starts, $ends, $curr, $end_t)
{

    for ($i = 0; $i < count($starts); $i++) {
        if (($curr >= strtotime($starts[$i]) && $curr <= strtotime($ends[$i])) || ($end_t >= strtotime($starts[$i]) && $end_t <= strtotime($ends[$i]))) {
            return true;
        }
    }

    return false;
}

$curr_user = $_SESSION["username"];
$user_id = $_SESSION["user_id"];
$connection = new mysqli("localhost", "root", "", "barber_database");
$dt = $haircut = $barber_id = $payment_method = $errorMessage = $successMessage = "";
$current_time_stamp = date('m/d/Y h:i');
$barbers_mt = $barbers_wth = $barbers_fs = $services = array();

$s1 = $connection->query("SELECT user_id, fname, lname FROM users WHERE role = 'barber' AND schedule = 'MT'");

while ($row = $s1->fetch_assoc()) {
    $barbers_mt[] = $row;
}

$s2 = $connection->query("SELECT user_id, fname, lname FROM users WHERE role = 'barber' AND schedule = 'WTH'");

while ($row = $s2->fetch_assoc()) {
    $barbers_wth[] = $row;
}

$s3 = $connection->query("SELECT user_id, fname, lname FROM users WHERE role = 'barber' AND schedule = 'FS'");

while ($row = $s3->fetch_assoc()) {
    $barbers_fs[] = $row;
}

$s4 = $connection->query("SELECT * FROM services");

while ($row = $s4->fetch_assoc()) {
    $services[] = $row;
}

$json_barbers_mt = json_encode($barbers_mt);
$json_barbers_wth = json_encode($barbers_wth);
$json_barbers_fs = json_encode($barbers_fs);
$json_services = json_encode($services);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dt = $_POST["date_time"];
    $payment_method = $_POST["payment_method"];

    if (empty($dt) || !isset($_POST["service_id"]) || !isset($_POST["barber_id"]) || empty($payment_method)) {
        $errorMessage = "All fields are required";
    } else {
        $haircut = $_POST["service_id"];
        $barber_id = $_POST["barber_id"];
        $dt_timestamp = strtotime($dt);
        $current_timestamp = strtotime($current_time_stamp);

        if ($dt_timestamp < $current_timestamp) {
            $errorMessage = "You cannot choose an older date!";
        } else {


            $status = "pending";
            $prep = $connection->query("SELECT date_time, expected_end_time FROM appointments");

            $start_times = $end_times = array();

            while ($row = $prep->fetch_array()) {
                $start_times[] = $row["date_time"];
                $end_times[] = $row["expected_end_time"];
            }

            $res = $connection->query("SELECT duration FROM services WHERE service_id ='$haircut'");
            $duration = $res->fetch_assoc()["duration"];

            $end_time = strtotime($dt);
            $end_time += $duration * 60;

            if (check_appointments($start_times, $end_times, $dt_timestamp, $end_time)) {
                $errorMessage = "This date has been taken.";
            } else {

                $status = 'pending';
                $end = date('Y-m-d H:i:s', $end_time);
                $end_ref = &$end;

                $stmt = $connection->prepare("INSERT INTO appointments (date_time, expected_end_time, status, user_id, barber_id) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssii", $dt, $end_ref, $status, $user_id, $barber_id);
                $result = $stmt->execute();

                if (!$result) {
                    $errorMessage = "Invalid query " . $connection->error;
                } else {
                    $value = $stmt->insert_id;

                    $stmt2 = $connection->prepare("INSERT INTO appointment_service (appointment_id, service_id) VALUES (?, ?)");
                    $stmt2->bind_param("ii", $value, $haircut);
                    $result2 = $stmt2->execute();

                    $res3 = $connection->query("SELECT price FROM services where service_id = '$haircut'");
                    $row2 = $res3->fetch_assoc();
                    $value2 = $row2['price'];

                    $stmt3 = $connection->prepare("INSERT INTO payments (payment_method, amount, appointment_id) VALUES (?, ?, ?)");
                    $stmt3->bind_param("sdi", $payment_method, $value2, $value);
                    $result3 = $stmt3->execute();

                    $payment_id = $stmt3->insert_id;

                    $stmt4 = $connection->prepare("UPDATE appointments SET payment_id = ? WHERE appointment_id = ?");
                    $stmt4->bind_param("ii", $payment_id, $value);
                    $result4 = $stmt4->execute();

                    if (!$result2 || !$result3 || !$result4) {
                        $errorMessage = "Invalid query " . $connection->error;
                    } else {
                        $dt = $haircut = $barber_id = $payment_method = "";
                        $successMessage = "Appointment made successfully";
                        header("location: /appointment_system/user_page.php");
                        exit;
                    }
                }
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appoint</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        .bg-image {
    min-height: 100vh;
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

        select option {
            color: black;
            text-decoration: underline;
            background-color: transparent;
            border: none;
        }
    </style>
</head>

<body class="bg-image">

    <div class="container-fluid">

        <nav class="navbar navbar-expand-sm bg-dark shadow navbar-dark fixed-top py-3">
            <div class="container-fluid">

                <button class="btn btn-dark" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasWithBothOptions" aria-controls="offcanvasWithBothOptions"><i
                        class="bi bi-list"></i></button>
                <a class="navbar-brand text-light mx-3" href="#">SET APPOINTMENT</a>
                <div class="offcanvas offcanvas-start bg-dark text-white" data-bs-scroll="true" tabindex="-1"
                    id="offcanvasWithBothOptions" aria-labelledby="offcanvasWithBothOptionsLabel">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title ms-3 mt-3" id="offcanvasWithBothOptionsLabel">SET APPOINTMENT</h5>
                        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                            aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <hr>
                        <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start ms-3"
                            id="menu">
                            <li class="nav-item">
                                <a href="user_page.php" class="nav-link align-middle px-0">
                                    <i class="fs-4 bi-house-fill"></i> <span
                                        class="ms-1 d-none d-sm-inline text-light">Home</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="appoint.php" class="nav-link align-middle px-0">
                                    <i class="fs-4 bi-calendar-plus-fill"></i> <span
                                        class="ms-1 d-none d-sm-inline text-light">Appoint now</span>
                                </a>
                            </li>
                            <li>
                                <a href="history.php" class="nav-link px-0 align-middle">
                                    <i class="fs-4 bi-calendar-check-fill"></i> <span
                                        class="ms-1 d-none d-sm-inline text-light">Appointment History</span> </a>
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
                                    <button class="btn btn-success py-auto px-5 dropdown-toggle" type="button"
                                        id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                        <?php echo $curr_user; ?>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end"
                                        aria-labelledby="dropdownMenuButton">
                                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </div>
    <br /><br /><br /><br /><br /><br /><br /><br /><br />
    <div class="container mt-3">
        <div class="row">

            <div class="container mt-3">

                <div class="row">

                    <div class="col">
                        <div class="container mb-5 col shadow p-5 mb-5 glass rounded p-3 center card text-light">

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
                                <h5 class="text-center">Please fillout the form below</h5>
                                <br />
                                <div class="row mb-3">
                                    <label class="col-sm-3 col-form-label">Pick the schedule</label>
                                    <div class="col-sm-6">
                                        <input id="date" type="datetime-local" class="form-control" name="date_time"
                                            value="<?php echo $dt; ?>">
                                    </div>
                                </div>


                                <div class="row mb-3">
                                    <label for="barber-dropdown" class="col-sm-3 col-form-label">Select a
                                        service:</label>
                                    <div class="col-sm-6">
                                        <select class="form-control" id="service-dropdown" name="service_id"></select>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="barber-dropdown" class="col-sm-3 col-form-label">Select a
                                        barber:</label>
                                    <div class="col-sm-6">
                                        <select class="form-control" id="barber-dropdown" name="barber_id">

                                            <script>
                                                function update_barbers(date) {

                                                    var select = document.getElementById("barber-dropdown");
                                                    select.innerHTML = "";

                                                    const dayOfWeek = date.getDay();
                                                    let barbers_mt = JSON.parse('<?php echo addslashes($json_barbers_mt); ?>');
                                                    let barbers_wth = JSON.parse('<?php echo addslashes($json_barbers_wth); ?>');
                                                    let barbers_fs = JSON.parse('<?php echo addslashes($json_barbers_fs); ?>');

                                        

                                                    if (dayOfWeek === 1 || dayOfWeek === 2) {
                                                        barbers_mt.forEach(function (barber) {
                                                            var element = document.createElement("option");
                                                            element.value = barber.user_id;
                                                            element.className = "";
                                                            element.textContent = barber.fname + " " + barber.lname;
                                                            element.setAttribute("name", "barber_id");

                                                            select.appendChild(element);
                                                        });
                                                    } else if (dayOfWeek === 3 || dayOfWeek === 4) {
                                                        barbers_wth.forEach(function (barber) {
                                                            var element = document.createElement("option");
                                                            element.value = barber.user_id;
                                                            element.className = "";
                                                            element.textContent = barber.fname + " " + barber.lname;
                                                            element.setAttribute("name", "barber_id");

                                                            select.appendChild(element);
                                                        });
                                                    } else if (dayOfWeek === 5 || dayOfWeek === 6) {
                                                        barbers_fs.forEach(function (barber) {
                                                            var element = document.createElement("option");
                                                            element.value = barber.user_id;
                                                            element.className = "";
                                                            element.textContent = barber.fname + " " + barber.lname;
                                                            element.setAttribute("name", "barber_id");

                                                            select.appendChild(element);
                                                        });
                                                    }

                                                    const select2 = document.getElementById("service-dropdown");
                                                    select2.innerHTML = "";
                                                    let services = JSON.parse('<?php echo addslashes($json_services); ?>');

                                                    services.forEach(function (service) {
                                                        var element2 = document.createElement("option");
                                                        element2.value = service.service_id;
                                                        element2.className = "";
                                                        element2.textContent = service.name;
                                                        element2.setAttribute("name", "service_id");

                                                        select2.appendChild(element2);
                                                    });
                                                }

                                                const dropdown_changer = document.getElementById("date");

                                                dropdown_changer.addEventListener("input", (event) => {

                                                    const selectedDate = new Date(event.target.value.replace("T", " "));
                                                    update_barbers(selectedDate);
                                                })
                                            </script>

                                        </select>
                                    </div>
                                </div>



                                <div class="row mb-3">
                                    <label class="col-sm-3 col-form-label">Pick a payment method</label>
                                    <div class="col-sm-6">
                                        <select class="form-control" id="payment_method" name="payment_method">
                                            <option value="Cash">Cash</option>
                                            <option value="Gcash">Gcash</option>
                                            <option value="PayMaya">PayMaya</option>
                                        </select>
                                    </div>
                                </div>


                                <br /><br />

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
                                        <button type="submit" class="btn btn-light">Confirm</button>
                                    </div>
                                    <div class="col-sm-3 d-grid">
                                        <a class="btn btn-danger" href="/appointment_system/user_page.php"
                                            role="button">Cancel</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>