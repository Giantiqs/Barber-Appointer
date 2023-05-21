<?php

$connection = new mysqli("localhost", "root", "", "barber_database");

$name = $description = $price = $duration = $errorMessage = $successMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $description = $_POST["description"];
    $price = $_POST["price"];
    $duration = $_POST["duration"];


    if (empty($name) || empty($description) || empty($price) || empty($duration)) {
        $errorMessage = "All fields are required";
    } else {

        $result = $connection->query("INSERT INTO services (name, description, price, duration) 
            VALUES('$name', '$description', '$price', '$duration')");

        if (!$result) {
            $errorMessage = "Invalid query " . $connection->error;
        } else {
            $successMessage = "Service added successfully";
            header("location: /appointment_system/services.php");
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
    <title>Add Service</title>
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

    <br /><br /><br /><br /><br /><br /><br />

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
                        <h5 class="text-center">Add a new service by filling the form below</h5>
                        <br />
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Name</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="name" value="<?php echo $name; ?>">
                            </div>
                        </div>



                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Description</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="description" value="<?php echo $description; ?>">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Duration</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="duration" value="<?php echo $duration; ?>">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Price</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="price" value="<?php echo $price; ?>">
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
                                <button type="submit" class="btn btn-dark">Save</button>
                            </div>
                            <div class="col-sm-3 d-grid">
                                <a class="btn btn-danger" href="/appointment_system/services.php" role="button">Cancel</a>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

</body>

</html>