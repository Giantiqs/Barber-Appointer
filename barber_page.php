<?php

session_start();

$current_barber = $_SESSION["username"];
$barber_id = $_SESSION["user_id"];

$connection = new mysqli("localhost", "root", "", "barber_database");

$total_earnings = 0.0;

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

$result = $connection->query("SELECT payments.amount FROM payments INNER JOIN appointments ON appointments.payment_id = payments.payment_id WHERE appointments.barber_id = '$barber_id' and appointments.status = 'confirmed'");

if (!$result) {
    die("Invalid query: " . $connection->error);
}

while ($row = $result->fetch_assoc()) {
    $total_earnings += $row["amount"];
}

$resulten = $connection->query("SELECT count(payments.amount) as count FROM payments INNER JOIN appointments ON appointments.payment_id = payments.payment_id WHERE appointments.barber_id = '$barber_id' and appointments.status = 'confirmed'");
$services_count = $resulten->fetch_assoc()['count'];


$result2 = $connection->query("SELECT services.name as service, count(services.name) as count FROM appointments 
INNER JOIN appointment_service on appointments.appointment_id = appointment_service.appointment_id 
INNER JOIN services on appointment_service.service_id = services.service_id 
where appointments.barber_id = '$barber_id' and appointments.status = 'confirmed'
GROUP BY service");

if (!$result2) {
    die("Invalid query: " . $connection->error);
}

$services = array();
$count = array();

while ($row = $result2->fetch_array()) {
    $services[] = $row["service"];
    $count[] = $row["count"];
}

$json_services = json_encode($services);
$json_service_count = json_encode($count);

$result3 = $connection->query("SELECT status, count(status) as count FROM appointments WHERE barber_id = '$barber_id' GROUP BY status");

$status = array();
$status_count = array();

while ($row = $result3->fetch_array()) {
    $status[] = $row["status"];
    $status_count[] = $row["count"];
}

$json_status = json_encode($status);
$json_status_count = json_encode($status_count);

$result4 = $connection->query("SELECT DATE_FORMAT(date, '%M') as mo, sum(amount) as earnings FROM payments 
INNER JOIN appointments ON payments.payment_id = appointments.payment_id
WHERE payments.date IS NOT NULL AND appointments.barber_id = '$barber_id'
GROUP BY mo 
ORDER BY mo");

$months = $earnings = array();

while ($row = $result4->fetch_array()) {
    $months[] = $row["mo"];
    $earnings[] = $row["earnings"];
}

$json_months = json_encode($months);
$json_earnings = json_encode($earnings);

$result5 = $connection->query("SELECT count(*) AS count FROM appointments WHERE barber_id = '$barber_id' AND status = 'pending'");

$pending_count = $result5->fetch_assoc()["count"];

$result4 = $connection->query("SELECT CONCAT_WS(' ', users.fname, users.lname) AS barber, count(payments.amount) as service
FROM payments 
INNER JOIN appointments ON appointments.payment_id = payments.payment_id
INNER JOIN users ON users.user_id = appointments.barber_id
WHERE appointments.status = 'confirmed' 
GROUP BY barber");

if (!$result4) {
    die("Connection failed: " . $connection->connect_error);
}

$barbers2 = array();
$barber_service = array();

while ($row = $result4->fetch_array()) {
    $barbers2[] = $row["barber"];
    $barber_service[] = $row["service"];
}

$json_barbers2 = json_encode($barbers2);
$json_service = json_encode($barber_service);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Barber Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.6.2/dist/chart.min.js"></script>

    <style>
        .bg-image {
            height: auto;
            background-image: url('imgs/bgadmin.jpg');
            background-size: cover;
            background-repeat: no-repeat;
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
                                <a href="barber_page.php" class="nav-link align-middle px-0">
                                    <i class="fs-4 bi-house-fill"></i> <span class="ms-1 d-none d-sm-inline text-light">Home</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="barber_appointments.php" class="nav-link align-middle px-0">
                                    <i class="fs-4 bi-calendar-plus-fill"></i> <span class="ms-1 d-none d-sm-inline text-light">Check Appointments</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="barber_history.php" class="nav-link align-middle px-0">
                                    <i class="fs-4 bi-calendar-fill"></i> <span class="ms-1 d-none d-sm-inline text-light">Your History</span>
                                </a>
                            </li>
                        </ul>
                        <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
                        <h5 class="offcanvas-title ms-3 mt-3" id="offcanvasWithBothOptionsLabel">ACCOUNT</h5>
                        <hr>
                        <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start ms-3" id="menu">
                            <li>
                                <a class="nav-link px-0 align-middle" href="<?php echo '/appointment_system/edit_profile_b.php?user_id=' . $barber_id; ?>">
                                <i class="fs-4 bi-person-gear"></i> <span class="ms-1 d-none d-sm-inline text-light">Edit Profile</span></a>
                            </li>
                            <li>
                                <a class="nav-link px-0 align-middle" href="<?php echo '/appointment_system/change_password_b.php?user_id=' . $barber_id; ?>">
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
                                        <?php echo $current_barber; ?>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end" aria-labelledby="dropdownMenuButton">
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

    <br /><br /><br /><br />

    <div class="container">
        <div class="row mt-2 py-3 text-light">
            <div class="col rounded shadow m-2 p-3 card" style="background-color: #a4390f;">
                <h5 class="my-4 ms-2">Total earnings: P<?php echo $total_earnings ?></h5>
            </div>
            <div class="col rounded shadow m-2 p-3 card" style="background-color: #2d0e63;">
                <h5 class="my-4 ms-2">Total services: <?php echo $services_count ?></h5>
            </div>
            <div class="col rounded shadow m-2 p-3 card" style="background-color: #a4690f;">
                <h5 class="my-4 ms-2">Pending Appointments: <?php echo $pending_count ?></h5>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row px-2">
            <div class="col bg-light rounded shadow p-3 text-dark card" style="height: 400px;">
                <h3 class="text-start">Monthly Earnings</h3>
                <canvas id="myChartLine" style="height: 300px;" class="mx-4 mb-3"></canvas>
                <script>
                    var ctx = document.getElementById('myChartLine').getContext('2d');
                    var earnings = JSON.parse('<?php echo $json_earnings; ?>');
                    var months = JSON.parse('<?php echo $json_months; ?>');

                    var myChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: months,
                            datasets: [{
                                data: earnings,
                                backgroundColor: ['#151515'],
                                borderColor: ['#151515'],
                                borderWidth: 0.4
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    display: false
                                },
                                title: {
                                    display: false
                                }
                            },
                            layout: {
                                padding: {
                                    left: 20,
                                    right: 20,
                                    top: 20,
                                    bottom: 20
                                }
                            }
                        }
                    });
                </script>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row mt-2 text-dark">
            <div class="col rounded shadow m-2 p-3 card">
                <h3 class="text-start">Services Made</h3>
                <canvas id="myChart" class=""></canvas>
                <script>
                    var ctx = document.getElementById('myChart').getContext('2d');
                    var service_count = JSON.parse('<?php echo $json_service_count; ?>');
                    var services = JSON.parse('<?php echo $json_services; ?>');
                    var status

                    var myChart = new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            labels: services,
                            datasets: [{
                                data: service_count,
                                backgroundColor: [
                                    '#a4690f',
                                    '#160fa4',
                                    '#a40f92',
                                    '#a4390f',
                                    '#9ea40f',
                                    '#26a40f'
                                ],
                                borderColor: [
                                    '#151515',
                                    '#151515',
                                    '#151515',
                                    '#151515',
                                    '#151515',
                                    '#151515'
                                ],
                                borderWidth: 0.4
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                },
                                title: {
                                    display: true,
                                }
                            },
                            layout: {
                                padding: {
                                    left: 20,
                                    right: 20,
                                    top: 20,
                                    bottom: 20
                                }
                            }
                        }
                    });
                </script>
            </div>
            <div class="col rounded shadow m-2 p-3 card">
                <h3 class="text-start">Appointment Status</h3>
                <canvas id="myChart2" class=""></canvas>
                <script>
                    var ctx = document.getElementById('myChart2').getContext('2d');
                    var status_count = JSON.parse('<?php echo $json_status_count; ?>');
                    var statuses = JSON.parse('<?php echo $json_status; ?>');
                    var status_colors = {
                        "confirmed": "#26a40f",
                        "cancelled": "#a40f0f",
                        "pending": "#d18d0e"
                    };

                    var colors = [];

                    for (let label of statuses) {
                        colors.push(status_colors[label]);
                    }

                    var myChart = new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            labels: statuses,
                            datasets: [{
                                data: status_count,
                                backgroundColor: colors,
                                borderColor: [
                                    '#151515',
                                    '#151515',
                                    '#151515'
                                ],
                                borderWidth: 0.4
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                },
                                title: {
                                    display: true,
                                }
                            },
                            layout: {
                                padding: {
                                    left: 20,
                                    right: 20,
                                    top: 20,
                                    bottom: 20
                                }
                            }
                        }
                    });
                </script>
            </div>
            <div class="col rounded shadow m-2 p-3 card">
                <h3 class="text-start">Barber Services Count</h3>
                <canvas id="myChart3" class=""></canvas>
                <script>
                    var ctx = document.getElementById('myChart3').getContext('2d');
                    var status_count = JSON.parse('<?php echo $json_service; ?>');
                    var statuses = JSON.parse('<?php echo $json_barbers2; ?>');

                    var myChart = new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            labels: statuses,
                            datasets: [{
                                data: status_count,
                                backgroundColor: [
                                    '#a40f80',
                                    '#0fa49d',
                                    '#a4690f'
                                ],
                                borderColor: [
                                    '#151515',
                                    '#151515',
                                    '#151515'
                                ],
                                borderWidth: 0.4
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'bottom'
                                },
                                title: {
                                    display: false,
                                }
                            },
                            layout: {
                                padding: {
                                    left: 20,
                                    right: 20,
                                    top: 20,
                                    bottom: 20
                                }
                            }
                        }
                    });
                </script>
            </div>
        </div>
    </div>

</body>

</html>