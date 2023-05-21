<?php

$connection = new mysqli("localhost", "root", "", "barber_database");

if (!isset($_GET["user_id"])) {
    header("location: /appointment_system/user_accounts.php");
    exit;
}

$user_id = $_GET["user_id"];

$statement = $connection->prepare("SELECT * FROM users WHERE user_id = ?");
$statement->bind_param("i", $user_id);
$statement->execute();
$result = $statement->get_result();

$row = $result->fetch_assoc();

if (!$row) {
    header("location: /appointment_system/user_accounts.php");
    exit;
}

$username = $row["username"];
$password = $row["password"];
$fname = $row["fname"];
$lname = $row["lname"];
$phone = $row["phone"];
$address = $row["address"];
$schedule = $row["schedule"];
$curr_role = $row["role"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!isset($_GET["user_id"])) {
        header("location: /appointment_system/user_accounts.php");
        exit;
    }

    $user_id = $_GET["user_id"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $fname = $_POST["fname"];
    $lname = $_POST["lname"];
    $phone = $_POST["phone"];
    $address = $_POST["address"];
    $schedule = $_POST["schedule"];
    $role = $_POST["role"];

    if ($role == "barber") {
        if ($curr_role == "barber") {
            $statement = $connection->prepare("UPDATE users SET username = ?, password = ?, fname = ?, lname = ?, phone = ?, address = ?, schedule = ?, role = ? WHERE user_id = ?");
            $statement->bind_param("ssssssssi", $username, $password, $fname, $lname, $phone, $address, $schedule, $role, $user_id);
            $result = $statement->execute();

            if (!$result) {
                $_SESSION["errorMessage"] = "Invalid query " . $connection->error;
                header("location: /appointment_system/edit_user.php?user_id=$user_id");
                exit;
            }
        } else {
            $scheds = ["MT", "WTH", "FS"];
            $random_schedule = $scheds[rand(0, 2)];

            $statement = $connection->prepare("UPDATE users SET username = ?, password = ?, fname = ?, lname = ?, phone = ?, address = ?, schedule = ?, role = ? WHERE user_id = ?");
            $statement->bind_param("ssssssssi", $username, $password, $fname, $lname, $phone, $address, $random_schedule, $role, $user_id);
            $result = $statement->execute();

            if (!$result) {
                $_SESSION["errorMessage"] = "Invalid query " . $connection->error;
                header("location: /appointment_system/edit_user.php?user_id=$user_id");
                exit;
            }
        }
    } else if ($role == "customer" || $role == "admin") {
        $none = null;
        $statement = $connection->prepare("UPDATE users SET username = ?, password = ?, fname = ?, lname = ?, phone = ?, address = ?, schedule = ?, role = ? WHERE user_id = ?");
        $statement->bind_param("ssssssssi", $username, $password, $fname, $lname, $phone, $address, $none, $role, $user_id);
        $result = $statement->execute();

        if (!$result) {
            $_SESSION["errorMessage"] = "Invalid query " . $connection->error;
            header("location: /appointment_system/edit_user.php?user_id=$user_id");
            exit;
        }
    } else {
        $statement = $connection->prepare("UPDATE users SET username = ?, password = ?, fname = ?, lname = ?, phone = ?, address = ?, schedule = ?, role = ? WHERE user_id = ?");
        $statement->bind_param("ssssssssi", $username, $password, $fname, $lname, $phone, $address, $schedule, $role, $user_id);
        $result = $statement->execute();

        if (!$result) {
            $_SESSION["errorMessage"] = "Invalid query " . $connection->error;
            header("location: /appointment_system/edit_user.php?user_id=$user_id");
            exit;
        }
    }

    header("location: /appointment_system/user_accounts.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
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
    <br /><br /><br /><br /><br /><br /><br />
    <div class="container mt-5">

        <div class="row">

            <div class="container col shadow px-5 pb-5 mb-5 bg-body rounded center card">

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
                        <label class="col-sm-3 col-form-label">Username</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="username" value="<?php echo $username; ?>">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">Password</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="password" value="<?php echo $password; ?>">
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

                    <?php
                    if ($curr_role == "barber") {
                        echo "
                                                <div class='row mb-3'>
                                                <label class='col-sm-3 col-form-label'>Schedule</label>
                                                <div class='col-sm-6'>
                                                    <input type='text' class='form-control' name='schedule' value='$schedule'>
                                                </div>
                                            </div>
                                                ";
                    }
                    ?>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">Role</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="role" value="<?php echo $curr_role; ?>">
                        </div>
                    </div>

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