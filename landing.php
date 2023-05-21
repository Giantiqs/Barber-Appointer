<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barbs</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


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
    </style>

</head>

<body class="bg-light">

    <script>
        $(window).scroll(function() {
            var middleTop = $(window).height() / 1.094;
            if ($(window).scrollTop() >= middleTop) {
                $('.navbar-nav .nav-link').css('color', 'black');
                $('header').addClass('shadow-sm bg-light');
            } else {
                $('.navbar-nav .nav-link').css('color', 'white');
                $('header').removeClass('shadow-sm bg-light');
            }
        });
    </script>

    <div class="bg-image">

        <header class="fixed-top px-5">
            <nav class=" navbar navbar-expand-lg navbar-dark px-5">
                <div class="container-fluid">
                    <img class="img-fluid" style="width: 70px; height: 65px" src="imgs/logo.png" />
                    <div class="float-center">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link" href="#" style="color: white; font-family: Arial, Helvetica, sans-serif;">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="features.php" style="color: white; font-family: Arial, Helvetica, sans-serif;">Features</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="about.php" style="color: white; font-family: Arial, Helvetica, sans-serif;">About</a>
                            </li>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="login.php" style="color: white; font-family: Arial, Helvetica, sans-serif;">Login</a>
                            </li>
                        </ul>
                    </div>
                    <div class="float-end">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="btn rounded-button text-light bg-dark" href="sign_up.php">Sign up</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>

        <br /><br /><br /><br /><br /><br /><br /><br />

        <div class="container">
            <h5 class="text-center" style="color: white; font-family: Arial, Helvetica, sans-serif;">Barbs.
                An appointment website for you</h5>
            <h1 class="text-center" style="font-size: 100px; color: white; font-family: Arial, Helvetica, sans-serif; font-weight: bold;">
                The Ultimate Salon</h1>
            <h1 class="text-center" style="font-size: 100px; color: white; font-family: Arial, Helvetica, sans-serif; font-weight: bold">
                Experience</h1>
            <div class="d-flex justify-content-center mt-5">
                <a class="btn btn-danger text-light bg-danger ms-4 px-5" href="login.php" style="font-size: 20px;">Login
                    now</a>
            </div>

            <br /><br /><br /><br />

            <div class="container">
                <div class="row mb-2">
                    <div class="col">
                        <img src="imgs/barber2.png" alt="Stylist" style="width: 50px; height: 50px; top: 0; left: 0;">
                        <h3 class="text-start" style="color: white; font-family: Arial, Helvetica, sans-serif;">
                            Professional Stylists</h3>
                        <pre class="mt-4" style="color: white; font-family: Arial, Helvetica, sans-serif;">
    All of the stylists are certified with
    tons of experience that can deliver 
    great service.
                        </pre>
                    </div>
                    <div class="col">
                        <img src="imgs/haircare2.png" alt="Stylist" style="width: 50px; height: 50px; top: 0; left: 0;">
                        <h3 class="text-start" style="color: white; font-family: Arial, Helvetica, sans-serif;">Total
                            Haircare</h3>
                        <pre class="mt-4" style="color: white; font-family: Arial, Helvetica, sans-serif;">
    Your hair will be taken care and
    maintain its shape and health
                        </pre>
                    </div>
                    <div class="col">
                        <img src="imgs/calendar2.png" alt="Stylist" style="width: 50px; height: 50px; top: 0; left: 0;">
                        <h3 class="text-start" style="color: white; font-family: Arial, Helvetica, sans-serif;">Everyday
                            Open</h3>
                        <pre class="mt-4" style="color: white; font-family: Arial, Helvetica, sans-serif;">
    We are always available so do
    not hesitate to appoint.
                            </pre>
                    </div>
                    <div class="col">
                        <img src="imgs/service2.png" alt="Stylist" style="width: 50px; height: 50px; top: 0; left: 0;">
                        <h3 class="text-start" style="color: white; font-family: Arial, Helvetica, sans-serif;">Better
                            Services</h3>
                        <pre class="mt-4" style="color: white; font-family: Arial, Helvetica, sans-serif;">
    We are confident that we will deliver
    the best service for you.
                            </pre>
                    </div>
                </div>
            </div> 
        </div>
    </div>

    <br><br><br><br><br>

    <div class="container">
        <div class="row">
            <div class="col bg-light text-dark p-5 mt-5">

                <div class="">
                    <h3>Welcome to our site</h3>
                    <h1>Barber Appointment</h1>
                    <h1 class="">Website</h3>
                </div>
                <div class="mt-5">
                    <p>A site where you can choose your time and</p>
                    <p>services you want.</p>
                </div>
                <br />
                <div class="mt-3">
                    <p>Don't have an account?</p>
                    <a class="btn rounded-button text-light bg-dark" href="sign_up.php">Register now!</a>
                </div>
            </div>

            <div class="col bg-light text-dark p-5">
                <img class="img-fluid rounded-3" style="width: 500px; height: 500px;" src="imgs/rand.png" />
            </div>

        </div>

    </div>

    <br /><br /><br /><br /><br /><br />

    <div class="container">
        <div class="row">
            <div class="col bg-light text-dark p-3">
                <img class="img-fluid rounded-3" style="width: 600px; height: 450px;" src="data:image/jpeg;base64,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" />
            </div>
            <div class="col bg-light text-dark p-3 ms-5">
                <div class="mt-5 ps-5 ms-3">
                    <h1>Want a new cut?</h1>
                    <h1>Appoint now!</h1>
                </div>
                <div class="mt-5 ps-5 ms-3">
                    <p>Cut down on appointment in your account by</p>
                    <p>pressing <strong>APPOINT NOW</strong> in your page.</p>
                </div>
                <br />
            </div>
        </div>
    </div>

    <br /><br /><br />

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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"></script>
</body>

</html>