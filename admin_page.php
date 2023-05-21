<?php

session_start();
$current_admin = $_SESSION["username"];
$barber_id = $_SESSION["user_id"];

$connection = new mysqli("localhost", "root", "", "barber_database");

$total_earnings = 0.0;

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

$result = $connection->query("SELECT payments.amount FROM payments INNER JOIN appointments ON appointments.payment_id = payments.payment_id WHERE appointments.status = 'confirmed'");

if (!$result) {
    die("Invalid query: " . $connection->error);
}

while ($row = $result->fetch_assoc()) {
    $total_earnings += $row["amount"];
}

$result2 = $connection->query("SELECT DATE_FORMAT(date, '%M') as mo, sum(amount) as earnings FROM payments WHERE date IS NOT NULL GROUP BY mo ORDER BY mo");

if (!$result2) {
    die("Connection failed: " . $connection->connect_error);
}

$months = array();
$monthly_earnings = array();

while ($row = $result2->fetch_array()) {
    $months[] = $row["mo"];
    $monthly_earnings[] = $row["earnings"];
}

$json_months = json_encode($months);
$json_earnings = json_encode($monthly_earnings);

$result3 = $connection->query("SELECT CONCAT_WS(' ', users.fname, users.lname) AS barber, SUM(payments.amount) as earning
FROM payments 
INNER JOIN appointments ON appointments.payment_id = payments.payment_id
INNER JOIN users ON users.user_id = appointments.barber_id
WHERE appointments.status = 'confirmed' 
GROUP BY barber;");

if (!$result3) {
    die("Connection failed: " . $connection->connect_error);
}

$barbers = array();
$barber_earnings = array();

while ($row = $result3->fetch_array()) {
    $barbers[] = $row["barber"];
    $barber_earnings[] = $row["earning"];
}

$json_barber = json_encode($barbers);
$json_barber_earnings = json_encode($barber_earnings);

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

$result5 = $connection->query("SELECT AVG(earnings) as average
FROM (
    SELECT DATE_FORMAT(date, '%M') AS mo, AVG(amount) AS earnings 
    FROM payments 
    WHERE date IS NOT NULL 
    GROUP BY mo 
    ORDER BY mo
) AS subquery");

$ave = $result5->fetch_assoc();

$avg = $ave["average"];

$result6 = $connection->query("select count(*) as count from users where role = 'customer'");

$c = $result6->fetch_assoc();

$user_count = $c["count"];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Page</title>
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

    <div class="container-fluid">

        <nav class="navbar navbar-expand-sm bg-dark shadow navbar-dark fixed-top py-3">
            <div class="container-fluid">

                <button class="btn btn-dark" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasWithBothOptions" aria-controls="offcanvasWithBothOptions"><i
                        class="bi bi-list"></i></button>
                <a class="navbar-brand text-light mx-3" href="#">DASHBOARD</a>
                <div class="offcanvas offcanvas-start bg-dark text-white" data-bs-scroll="true" tabindex="-1"
                    id="offcanvasWithBothOptions" aria-labelledby="offcanvasWithBothOptionsLabel">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title ms-3 mt-3" id="offcanvasWithBothOptionsLabel">DASHBOARD</h5>
                        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                            aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <hr>
                        <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start ms-3"
                            id="menu">
                            <li class="nav-item">
                                <a href="admin_page.php" class="nav-link align-middle px-0">
                                    <i class="fs-4 bi-house-fill"></i> <span
                                        class="ms-1 d-none d-sm-inline text-light">Home</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="user_accounts.php" class="nav-link align-middle px-0">
                                    <i class="fs-4 bi-person-circle"></i> <span
                                        class="ms-1 d-none d-sm-inline text-light">User Accounts</span>
                                </a>
                            </li>
                            <li>
                                <a href="appointments.php" class="nav-link px-0 align-middle">
                                    <i class="fs-4 bi-calendar-check-fill"></i> <span
                                        class="ms-1 d-none d-sm-inline text-light">Appointments</span> </a>
                            </li>
                            <li>
                                <a href="services.php" class="nav-link px-0 align-middle">
                                    <i class="fs-4 bi-check-circle-fill"></i> <span
                                        class="ms-1 d-none d-sm-inline text-light">Services</span> </a>
                            </li>
                            <li>
                                <a href="payments.php" class="nav-link px-0 align-middle">
                                    <i class="fs-4 bi-cash-stack"></i> <span
                                        class="ms-1 d-none d-sm-inline text-light">Payments</span> </a>
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
                                        <?php echo $current_admin; ?>
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

    <br /><br /><br /><br />

    <div class="container">
        <div class="row mt-2 py-3 text-light">
            <div class="col rounded shadow m-2 p-3 card" style="background-color: #2d0e63;">
                <h5 class="my-4 ms-2">Total earnings: P<?php echo $total_earnings ?>
                </h5>
            </div>
            <div class="col rounded shadow m-2 p-3 card" style="background-color: #1a6890;">
                <h5 class="my-4 ms-2">Annual Average: P<?php echo $avg ?>
                </h5>
            </div>
            <div class="col rounded shadow m-2 p-3 card" style="background-color: #b35722;">
                <h5 class="my-4 ms-2">Users: <?php echo $user_count ?>
                </h5>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row px-2">
            <div class="col bg-light rounded shadow p-3 text-dark card p-5">
                <h3 class="text-start mx-3">Monthly Earnings</h3>
                <canvas id="myChart" style="height: 300px;" class="mx-4 mb-3"></canvas>
                <script>
                    var ctx = document.getElementById('myChart').getContext('2d');
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
        <div class="row mt-2 py-3">
            <div class="col bg-light rounded shadow m-2 p-3 text-dark card">
                <h3 class="text-start mx-3">Barbers Earnings</h3>
                <canvas id="myChart2" class="mx-3"></canvas>
                <script>
                    var ctx = document.getElementById('myChart2').getContext('2d');
                    var earnings = JSON.parse('<?php echo $json_barber_earnings; ?>');
                    var barber = JSON.parse('<?php echo $json_barber; ?>');
                    var clrs = ["#141414", "#212121", "#333333", "#444444", "#555555", "#666666", "#777777", "#888888", "#999999", "#AAAAAA"];

                    var myChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: barber,
                            datasets: [{
                                data: earnings,
                                backgroundColor: clrs,
                                borderColor: "#151515",
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

            <div class="col bg-light rounded shadow m-2 p-3 text-dark card">
                <h3 class="text-start mx-3">Barbers Services</h3>
                <canvas id="myChart3" class="mx-3"></canvas>
                <script>
                    var ctx = document.getElementById('myChart3').getContext('2d');
                    var status_count = JSON.parse('<?php echo $json_service; ?>');
                    var statuses = JSON.parse('<?php echo $json_barbers2; ?>');
                    var colors = ["#2C3E50", "#34495E", "#1F3A93", "#512E5F", "#4D5656", "#3E4651", "#36393D", "#282828", "#1E272C", "#2B303A"];

                    var myChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: statuses,
                            datasets: [{
                                data: status_count,
                                backgroundColor: colors,
                                borderColor: '#151515',
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