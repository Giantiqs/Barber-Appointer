<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Appointments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        .bg-image {
            height: 100vh;
            background-image: url('imgs/bgadmin.jpg');
            background-size: cover;
            background-repeat: no-repeat;
        }
    </style>
</head>

<body class="bg-image">
    <?php
    session_start();

    $current_barber = $_SESSION["username"];
    $barber_id = $_SESSION["user_id"];
    ?>

    <div class="container-fluid">

        <nav class="navbar navbar-expand-sm bg-dark shadow navbar-dark fixed-top py-3">
            <div class="container-fluid">

                <button class="btn btn-dark" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasWithBothOptions" aria-controls="offcanvasWithBothOptions"><i class="bi bi-list"></i></button>
                <a class="navbar-brand text-light mx-3" href="#">APPOINTMENTS</a>
                <div class="offcanvas offcanvas-start bg-dark text-white" data-bs-scroll="true" tabindex="-1" id="offcanvasWithBothOptions" aria-labelledby="offcanvasWithBothOptionsLabel">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title ms-3 mt-3" id="offcanvasWithBothOptionsLabel">APPOINTMENTS</h5>
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

    <br /><br /><br />

    <div class="container my-5">


        <div class="col py-2 shadow rounded mx-5 card">

            <div class="col">
                <div class="col pt-2 mx-4">
                    <h3 class="text-start">Your Appointments</h3>
                </div>
            </div>
            <hr style="border-top: 2px solid #000000;">
            <div class="row align-items-end">

                <div class="col mx-2 ps-4">
                    <form method="GET" class="col mx-2">
                        <div class="row">
                            <div class="col mt-4">
                                <input type="text" class="rounded p-1" name="search_item">
                            </div>
                            <div class="col">
                                <button type="submit" class="btn btn-dark mt-4 px-4">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col mx-2">

                </div>
                <form method="POST" class="col mx-2">
                    <div class="row">
                        <div class="col">
                            <label for="sort_field">Sort by:</label>
                            <select class="form-control" name="sort_field" id="sort_field">
                                <option value="date_time">Date time</option>
                                <option value="status">Status</option>
                                <option value="fname">First Name</option>
                                <option value="lname">Last Name</option>
                            </select>
                        </div>
                        <div class="col">
                            <label for="sort_dir">Sort direction:</label>
                            <select class="form-control" name="sort_dir" id="sort_dir">
                                <option value="ASC">Ascending</option>
                                <option value="DESC">Descending</option>
                            </select>
                        </div>
                        <div class="col">
                            <button type="submit" class="btn btn-dark mt-4 px-4">Sort</button>
                        </div>
                    </div>
                </form>
            </div>

            <hr style="border-top: 2px solid #000000;">

            <div class="table-responsive" style="height: 500px;">
                <table class="table"">
                    <thead>
                        <tr>
                            <th>DATE TIME</th>
                            <th>STATUS</th>
                            <th>CUSTOMER</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php

                        $curr_user = $_SESSION["username"];
                        $user_id = $_SESSION["user_id"];

                        $connection = new mysqli("localhost", "root", "", "barber_database");

                        if ($connection->connect_error) {
                            die("Connection failed: " . $connection->connect_error);
                        }

                        $query = $search_term = "";

                        if ($_SERVER["REQUEST_METHOD"] == "POST") {
                            $sort_field = isset($_POST['sort_field']) ? $_POST['sort_field'] : 'appointment_id';
                            $sort_dir = isset($_POST['sort_dir']) ? $_POST['sort_dir'] : 'ASC';
                            $search_term = isset($_POST['search_term']) ? $_POST['search_term'] : '';
                            $query = "SELECT appointments.appointment_id, appointments.date_time, appointments.status, users.fname, users.lname 
                            FROM appointments 
                            INNER JOIN users ON appointments.user_id = users.user_id 
                            WHERE (status = 'pending' and appointments.barber_id = '$user_id') AND (date_time LIKE '%$search_term%' OR status LIKE '%$search_term%' OR fname LIKE '%$search_term%' OR lname LIKE '%$search_term%')
                            ORDER BY `$sort_field` $sort_dir
                            ";
                        } else if (isset($_GET['search_item'])) {
                            $search_term = $_GET['search_item'];
                            $query = "SELECT appointments.appointment_id, appointments.date_time, appointments.status, users.fname, users.lname FROM appointments 
                                INNER JOIN users ON appointments.user_id = users.user_id 
                                WHERE (status = 'pending' and appointments.barber_id = '$user_id') AND (date_time LIKE '%$search_term%' OR 
                                status LIKE '%$search_term%' OR 
                                fname LIKE '%$search_term%' OR 
                                lname LIKE '%$search_term%')";
                        } else {
                            $query = "SELECT appointments.appointment_id, appointments.date_time, appointments.status, users.fname, users.lname FROM appointments 
                            INNER JOIN users ON appointments.user_id = users.user_id 
                            WHERE status = 'pending' and appointments.barber_id = '$user_id'";
                        }

                        $result = $connection->query($query);

                        if (!$result) {
                            die("Invalid query: " . $connection->error);
                        }

                        while ($row = $result->fetch_assoc()) {
                            echo "
                            <tr>
                            <td>$row[date_time]</td>
                            <td>$row[status]</td>
                            <td>$row[fname] $row[lname]</td>
                            <td>
                                <a class='fs-4 bi-calendar-check-fill mx-1' style='color: darkgreen' href='/appointment_system/confirm_appointment.php?appointment_id=$row[appointment_id]'></a>
                            </td>
                            </tr>
                            ";
                        }

                        ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>

</html>