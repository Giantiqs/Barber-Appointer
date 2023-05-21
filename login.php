<?php

$connection = new mysqli("localhost", "root", "", "barber_database");

$username = $password = $successMessage = $errorMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    if (empty($username) || empty($password)) {
        $errorMessage = "All fields are required";
    } else {
        $prep = $connection->prepare("SELECT * FROM users WHERE username = ?");
        $prep->bind_param("s", $username);
        $prep->execute();

        $res = $prep->get_result();

        if ($res->num_rows == 0) {
            $errorMessage = "Username not found";
        } else {
            $user = $res->fetch_assoc();
            if ($password != $user['password']) {
                $errorMessage = "Wrong password";
            } else {
                session_start();
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                if ($_SESSION['role'] == 'admin') {
                    header("location: /appointment_system/admin_page.php");
                } else if ($_SESSION['role'] == 'barber') {
                    header("location: /appointment_system/barber_page.php");
                } else {
                    header("location: /appointment_system/user_page.php");
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        .gradient {
            background: linear-gradient(-45deg, hsl(240deg 94% 94%) 0%,
                    hsl(335deg 48% 87%) 8%,
                    hsl(16deg 37% 80%) 17%,
                    hsl(54deg 14% 71%) 25%,
                    hsl(32deg 21% 71%) 33%,
                    hsl(17deg 25% 71%) 42%,
                    hsl(0deg 22% 72%) 50%,
                    hsl(0deg 21% 78%) 58%,
                    hsl(0deg 19% 85%) 67%,
                    hsl(0deg 12% 92%) 75%,
                    hsl(0deg 12% 76%) 83%,
                    hsl(0deg 11% 61%) 92%,
                    hsl(0deg 12% 46%) 100%);
            background-size: 400% 400%;
            animation: gradient 9s ease infinite;
            height: 100vh;
        }

        @keyframes gradient {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        .bg-image {
            height: auto;
            background-image: url('imgs/divbg.jpg');
            background-size: cover;
            background-repeat: no-repeat;
        }

        .form-control {
            border: none;
            border-bottom: 1px solid #ccc;
            border-radius: 0;
        }

        .rounded-button {
            border-radius: 20px;
            padding: 5px 20px;
            color: #fff;
            font-size: 16px;
        }
    </style>
</head>

<body class="gradient">
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
    <br /><br /><br /><br /><br /><br />

    <div class="container">
        <div class="row">
            <div class="col bg-image p-5">
                <br /><br /><br /><br /><br /><br /><br />
                <h5 class="text-center" style="color: white; font-family: Arial, Helvetica, sans-serif;">Barber
                    Appointer.
                    A appointment website for you</h5>
                <h1 class="text-center" style="font-size: 60px; color: white; font-family: Arial, Helvetica, sans-serif; font-weight: bold;">
                    The Ultimate Salon</h1>
                <h1 class="text-center" style="font-size: 60px; color: white; font-family: Arial, Helvetica, sans-serif; font-weight: bold">
                    Experience</h1>
            </div>

            <div class="col-md-4 col-sm-12 bg-body p-4 text-center" style="overflow-wrap: break-word;">
    <h1 style="font-family: Arial, Helvetica, sans-serif;">BARBS</h1>
    <h4 style="font-family: Arial, Helvetica, sans-serif;">Welcome to Barbs!</h4>
    
    <br><br><br /><br />
    <div class="mx-auto mt-5">
        <form method="post">
            <div class="row mb-3">
                <div class="col-md-7 mx-auto col-sm-12">
                    <input type="text" class="form-control" name="username" value="<?php echo $username; ?>" placeholder="Enter your username">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-7 mx-auto col-sm-12">
                    <input type="password" class="form-control" name="password" value="<?php echo $password; ?>" placeholder="Enter your password">
                </div>
            </div>

            <br>

            <div class="row mb-3">
                <div class="col-md-7 mx-auto col-sm-12 mt-3">
                    <button type="submit" class="btn rounded-button bg-secondary px-5 mt-auto">Login</button>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-9 mx-auto">
                    <p class="mt-2">New to barbs? Create an account by clicking <a href="sign_up.php" class="text-decoration-none text-danger">this.</a></p>
                </div>
            </div>
        </form>
    </div>
</div>


        </div>
    </div>

</body>

</html>