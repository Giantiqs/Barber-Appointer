<?php
session_start(); 

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

} else {

    if (!isset($_GET["user_id"])) {
        header("location: /appointment_system/user_page.php");
        exit;
    }

    $user_id = $_GET["user_id"];
    $oldPassword = $_POST["old_password"];
    $newPassword = $_POST["new_password"];
    $confirmPassword = $_POST["confirm_password"];

    

    $statement = $connection->prepare("SELECT password FROM users WHERE user_id = ?");
    $statement->bind_param("i", $user_id);
    $statement->execute();
    $result = $statement->get_result();
    $passwordRow = $result->fetch_assoc();
    $password = $passwordRow['password'];

    echo $oldPassword . "\n";
    echo $password . "\n";

    if (empty($oldPassword) || empty($newPassword) || empty($confirmPassword)) {
        $errmsg = "Please fill out all fields";
    } elseif ($oldPassword != $password) {
        $errmsg = "Old password is incorrect";
    } elseif ($newPassword !== $confirmPassword) {
        $errmsg = "New password and confirm password do not match";
    } else {

        $statement = $connection->prepare("UPDATE users SET password = ? WHERE user_id = ?");
        $statement->bind_param("si", $newPassword, $user_id);
        $result = $statement->execute();

        if (!$result) {
            $errmsg = "Invalid query " . $connection->error;
            exit;
        }

        $_SESSION['successMessage'] = "Password changed successfully";
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
        <div class="row justify-content-center">
            <div class="col-6 shadow p-5 pb-5 mb-5 glass text-light rounded center card">
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
                    <div class="mb-3">
                        <label for="old_password" class="form-label">Old Password</label>
                        <input type="password" class="form-control" id="old_password" name="old_password" required>
                    </div>

                    <div class="mb-3">
                        <label for="new_password" class="form-label">New Password</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                    </div>

                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>

                    <div class="mb-3">
                        <div class="d-grid gap-2 col-sm-6 mx-auto">
                            <button type="submit" class="btn btn-dark">Save</button>
                        </div>
                        <div class="d-grid gap-2 col-sm-6 mx-auto mt-2">
                            <a class="btn btn-danger" href="/appointment_system/user_page.php" role="button">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
