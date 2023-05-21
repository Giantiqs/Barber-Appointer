<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Features</title>
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

<body class="bg-image">

    <script>
        $(window).scroll(function () {
            var middleTop = $(window).height() / 5.80;
            if ($(window).scrollTop() >= middleTop) {

                $('header').removeClass('shadow-sm bg-light');
            } else {
                $('.navbar-nav .nav-link').css('color', 'black');
                $('header').addClass('shadow-sm bg-light');
            }
        });
    </script>



    <header class="fixed-top px-5">
        <nav class=" navbar navbar-expand-lg navbar-dark px-5">
            <div class="container-fluid">
                <img class="img-fluid" style="width: 70px; height: 65px" src="imgs/logo.png" />
                <div class="float-center">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="landing.php"
                                style="color: white; font-family: Arial, Helvetica, sans-serif;">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#"
                                style="color: white; font-family: Arial, Helvetica, sans-serif;">Features</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="about.php"
                                style="color: white; font-family: Arial, Helvetica, sans-serif;">About</a>
                        </li>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php"
                                style="color: white; font-family: Arial, Helvetica, sans-serif;">Login</a>
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

    <br /><br /><br /><br /><br /><br /><br /><br /><br />

    <div class="container mt-5">

        <div class="row bg-light rounded-4" style="overflow: hidden;">
            <div class="bg-dark mb-5">
                <h1 class="text-center text-light my-5 ">Features</h1>
            </div>

            <div class="col shadow p-4 mb-5 bg-body rounded text-dark p-3 mx-2">
                <h3 class="text-center">Time Flexibility</h3>
                <pre class="mt-4">
    Pick any time to get a new cut! Instead of
    waiting for other people to get finish
    with their cuts.
                    </pre>
            </div>
            <div class="col shadow p-4 mb-5 bg-body rounded text-dark p-3 mx-2">
                <h3 class="text-center">Pick any barber</h3>
                <pre class="mt-4">
    You can pick barbers but keep in mind 
    that each of them has their own 
    schedule.
                    </pre>
            </div>
            <div class="col shadow p-4 mb-5 bg-body rounded text-dark p-3 mx-2">
                <h3 class="text-center">Manage your appointments</h3>
                <pre id="barbers" class="mt-4">
    Cancel your appointment anytime. There
    will be a reminder in your page when 
    a appointment is near.
                    </pre>
            </div>

            <div class="col shadow p-4 mb-5 bg-body rounded text-dark p-3 mx-2">
                <h3 class="text-center">Time Flexibility</h3>
                <pre class="mt-4">
    Pick any time to get a new cut! Instead of
    waiting for other people to get finish
    with their cuts.
                    </pre>
            </div>
            <div class="col shadow p-4 mb-5 bg-body rounded text-dark p-3 mx-2">
                <h3 class="text-center">Pick any barber</h3>
                <pre class="mt-4">
    You can pick barbers but keep in mind 
    that each of them has their own 
    schedule.
                    </pre>
            </div>
            <div class="col shadow p-4 mb-5 bg-body rounded text-dark p-3 mx-2">
                <h3 class="text-center">Manage your appointments</h3>
                <pre id="barbers" class="mt-4">
    Cancel your appointment anytime. There
    will be a reminder in your page when 
    a appointment is near.
                    </pre>
            </div>
            
        </div>

    </div>


    <br /><br />

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