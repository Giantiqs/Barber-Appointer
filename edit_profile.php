<?php

$connection = new mysqli("localhost", "root", "", "barber_database");

$errmsg = "";

if ($_SERVER["REQUEST_METHOD"] == "GET") {

    if (!isset($_GET["user_id"])) {
        header("location: /appointment_system/user_page.php");
        exit;
    }

    $user_id = $_GET["user_id"];

    $statement = $connection->prepare("SELECT * FROM users WHERE user_id = ?");
    $statement->bind_param("i", $user_id);
    $statement->execute();
    $result = $statement->get_result();

    $row = $result->fetch_assoc();

    if (!$row) {
        header("location: /appointment_system/user_page.php");
        exit;
    }

    $username = $row["username"];
    $fname = $row["fname"];
    $lname = $row["lname"];
    $phone = $row["phone"];
    $address = $row["address"];
} else {

    if (!isset($_GET["user_id"])) {
        header("location: /appointment_system/user_page.php");
        exit;
    }

    $user_id = $_GET["user_id"];
    $username = $_POST["username"];
    $fname = $_POST["fname"];
    $lname = $_POST["lname"];
    $phone = $_POST["phone"];
    $address = $_POST["address"];

    if (empty($username) || empty($fname) || empty($lname) || empty($phone) || empty($address)) {
        $errmsg = "Please fill out the the fields";
    }

    $statement = $connection->prepare("SELECT user_id FROM users WHERE username = ?");
    $statement->bind_param("s", $username);
    $statement->execute();
    $result = $statement->get_result();
    $existingUser = $result->fetch_assoc();

    if ($existingUser && $existingUser["user_id"] != $user_id) {
        $errmsg = "Username already exists";
        $row["username"] = $username;
    } else if(empty($username) || empty($fname) || empty($lname) || empty($phone) || empty($address)) {
        $errmsg = "Please fill out the the fields";
    } else {
        $statement = $connection->prepare("UPDATE users SET username = ?, fname = ?, lname = ?, phone = ?, address = ? WHERE user_id = ?");
        $statement->bind_param("sssssi", $username, $fname, $lname, $phone, $address, $user_id);
        $result = $statement->execute();

        if (!$result) {
            $errmsg = "Invalid query " . $connection->error;
            exit;
        }

        header("location: /appointment_system/user_page.php");
        exit;
    }
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
            background-image: url('imgs/divbg.jpg');
            background-size: cover;
            background-repeat: no-repeat;
        }

        header {
            background-color: transparent;
        }

        .glass {
            background-color: rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 10px;
        }

    </style>
</head>

<body class="bg-image">
    <br /><br /><br /><br /><br /><br /><br />
    <div class="container mt-5">

        <div class="row">

            <div class="container col shadow p-5 pb-5 mb-5 glass text-light rounded center card">

                <?php
                if (!empty($errmsg)) {
                    echo "
                        <div class='alert alert-warning alert-dismissible fade show' role='alert'>
                            <strong>$errmsg</strong>
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
                            <input type="text" class="form-control" name="username" value="<?php echo isset($_GET['username']) ? $_GET['username'] : $username; ?>">
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
                            <a class="btn btn-danger" href="/appointment_system/user_page.php" role="button">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>


</html>