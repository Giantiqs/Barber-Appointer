<?php

$connection = new mysqli("localhost", "root", "", "barber_database");

$username = $password = $fname = $lname = $phone = $address = $role = $errorMessage = $successMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $fname = $_POST["fname"];
    $lname = $_POST["lname"];
    $phone = $_POST["phone"];
    $address = $_POST["address"];
    $role = $_POST["role"];

    if (empty($username) || empty($password) || empty($fname) || empty($lname) || empty($phone) || empty($address)) {
        $errorMessage = "All fields are required";
    } else {
        $prep = $connection->prepare("SELECT * FROM users WHERE username = ?");
        $prep->bind_param("s", $username);
        $prep->execute();

        $res = $prep->get_result();

        if ($res->num_rows > 0) {
            $errorMessage = "Username already exists";
        } else {
            $statement = $connection->prepare("INSERT INTO users (username, password, fname, lname, phone, address, schedule, role) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

            if ($role == "barber") {
                $scheds = ["MT", "WTH", "FS"];
                $randomSchedule = $scheds[rand(0, 2)];
                $statement->bind_param("ssssssss", $username, $password, $fname, $lname, $phone, $address, $randomSchedule, $role);
            } else {
                $statement->bind_param("sssssss", $username, $password, $fname, $lname, $phone, $address, $role);
            }

            $result = $statement->execute();

            if (!$result) {
                $errorMessage = "Invalid query " . $connection->error;
            } else {
                $username = $password = "";
                $successMessage = "User added successfully";
                header("location: /appointment_system/user_accounts.php");
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
    <title>Add user</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

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

    <br /><br /><br /><br />

    <div class="container mt-3">
        <div class="row">
            <div class="col">
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
                        <h5 class="text-center">Add a new user by filling the form below</h5>
                        <br />
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Username</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="username" value="<?php echo $username; ?>">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Password</label>
                            <div class="col-sm-6">
                                <input type="password" class="form-control" name="password" value="<?php echo $password; ?>">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">First name</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="fname" value="<?php echo $fname; ?>">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Last name</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="lname" value="<?php echo $lname; ?>">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Phone</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="phone" value="<?php echo $phone; ?>">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Address</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="address" value="<?php echo $address; ?>">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Role</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="role" value="<?php echo $role; ?>">
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
                                <button type="submit" class="btn btn-dark">Add user</button>
                            </div>
                            <div class="col-sm-3 d-grid">
                                <a class="btn btn-danger" href="/appointment_system/user_accounts.php" role="button">Cancel</a>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

</body>

</html>