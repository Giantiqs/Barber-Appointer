<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About us</title>
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
                            <a class="nav-link" href="features.php"
                                style="color: white; font-family: Arial, Helvetica, sans-serif;">Features</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#"
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

    <div class="container shadow-lg">

        <div class="mx-5">
            <div class="mx-5">
                <div class="row bg-light rounded-4" style="overflow: hidden;">
                    <div class="bg-dark">
                        <h1 class="text-center text-light my-5 ">About us</h1>
                    </div>

                    <div class="col my-5">
                        <br /><br />
                        <pre class="text-start mx-5" style="font-size: 16px;">
        Our barbershop has come a long way since its inception. It has always been a place where customers
        come for a good haircut and a friendly conversation. However, the way we schedule appointments has
        undergone a complete transformation.
            
        We remember the days when making an appointment at our shop meant calling us on the phone or walking
        in and hoping that we had an opening. It was a time-consuming process that didn't always guarantee a
        spot on our busy schedule. But with the advancements in technology, we knew that we had to change
        with the times.
            
        Our website has become the centerpiece of our scheduling process. It allows our customers to browse
        our available time slots and book an appointment with just a few clicks. This has not only made it
        easier for our customers to get the haircut they need but also for us to keep track of our
        appointments and manage our barbers' schedules.
            
        We are also proud to have eliminated the need for paper records. Not only does this make our shop
        more environmentally friendly, but it also means that we have all of our records stored
        electronically. This allows us to quickly access our customers' information and provide them with
        personalized service.
            
        But our commitment to technology doesn't stop there. We are always looking for ways to improve our
        business processes and make our shop more efficient. For example, we recently invested in new
        equipment that allows us to provide more precise haircuts and reduce the time it takes to complete
        each appointment.
            
        We also believe in hiring the best barbers and providing them with the tools they need to succeed.
        By hiring more barbers, we can offer more available time slots for our customers, and by providing
        our barbers with the latest equipment and training, we can ensure that they are delivering the
        highest quality haircuts.
            
        At the end of the day, our goal is to provide our customers with the best possible haircutting
        experience. We believe that by embracing technology and staying on the cutting edge of the industry,
        we can continue to provide the highest level of service for years to come.
                    </pre>
                    </div>

                    <div class="bg-dark p-4"></div>
                </div>
            </div>
        </div>


    </div>

    <br /><br /><br /><br />

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