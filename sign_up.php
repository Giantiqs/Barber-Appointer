<?php

$connection = new mysqli("localhost", "root", "", "barber_database");

$username = $password = $second_password = $errorMessage = $successMessage = "";
$fname = $lname = $phone = $address = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $second_password = $_POST["second_password"];
    $fname = $_POST["fname"];
    $lname = $_POST["lname"];
    $phone = $_POST["phone"];
    $address = $_POST["address"];

    if (empty($username) || empty($password) || empty($fname) || empty($lname) || empty($phone) || empty($address) ||  empty($phone) || empty($second_password)) {
        $errorMessage = "All fields are required";
    } else if ($password != $second_password) {
        $errorMessage = "Passwords do not match";
    } else if (strlen($password) < 8) {
        $errorMessage = "Password must have at least 8 letters.";
    } else if (!preg_match("/\d/", $password)) {
        $errorMessage = "Password must contain at least 1 number.";
    } else if (!preg_match("/[\'^£$%&*()}{@#~?>
<>,|=_+¬-]/", $password)) {
    $errorMessage = "Password must contain at least one symbol.";
    } else {
    $prep = $connection->prepare("SELECT * FROM users WHERE username = ?");
    $prep->bind_param("s", $username);
    $prep->execute();

    $res = $prep->get_result();

    if ($res->num_rows > 0) {
    $errorMessage = "Username already exists";
    } else {
    $result = $connection->query("INSERT INTO users (username, password, fname, lname, phone, address, role)
    VALUES('$username', '$password', '$fname', '$lname', '$phone', '$address', 'customer')");

    if (!$result) {
    $errorMessage = "Invalid query " . $connection->error;
    } else {
    $username = $password = $second_password = "";
    $successMessage = "User added successfully";
    header("location: /appointment_system/landing.php");
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
        <title>Sign up</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" />
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

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
        </style>
    </head>

    <body class="bg-image">
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
        <br /><br /><br /><br /><br />
        <div class="container mt-3">

            <div class="row">
                <div class="col">
                    <div class="container my-5 col shadow p-5 mb-5 glass rounded p-3 center text-dark">



                        <form method="post">
                            <h5 class="text-center text-light">Please fillout the form below</h5>
                            <br />
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label text-light">Username</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="username"
                                        value="<?php echo $username; ?>">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label text-light">Password</label>
                                <div class="col-sm-6">
                                    <input type="password" class="form-control" name="password"
                                        value="<?php echo $password; ?>">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label text-light">Retype Password</label>
                                <div class="col-sm-6">
                                    <input type="password" class="form-control" name="second_password"
                                        value="<?php echo $second_password; ?>">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label text-light">First name</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="fname" value="<?php echo $fname; ?>">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label text-light">Last name</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="lname" value="<?php echo $lname; ?>">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label text-light">Phone</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="phone" value="<?php echo $phone; ?>">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label text-light">Address</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="address"
                                        value="<?php echo $address; ?>">
                                </div>
                            </div>

                            <br /><br />

                            <div class="row mb-3">
                                <div class="offset-sm-3 col-sm-6 d-grid">
                                    <button type="submit" class="btn btn-light">Sign up</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>

                <div class="col-lg-4 text-light p-3 mx-3 shadow p-5 mb-5 glass rounded center mt-5">
                    <h3 class="text-center">Rules</h3>
                    <p class="mt-4 mx-5">1. Username must be unique</p>
                    <p class="mx-5">2. Password must contain atleast one symbol</p>
                    <p class="mx-5">3. Password must contain atleast one number</p>
                    <p class="mx-5">4. You must retype your password for confirmation</p>
                    <p class="mx-5">5. Password must have at least 8 letters.</p>
                    <br /><br />
                    <hr />
                    <div class="col-7 mt-5 px-3 mx-auto">
                        <a class="btn btn-danger" href="/appointment_system/landing.php" role="button">Cancel
                            Registration</a>
                    </div>
                </div>


            </div>
        </div>

    </body>

    </html>