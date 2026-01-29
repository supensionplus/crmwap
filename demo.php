<?php
// Incluir archivos necesarios
include("include/conn.php");
include("include/function.php");
?>

<!doctype html>
<html lang="es">

<head>

    <!--meta tags-->
    <meta charset="UTF-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="description" content="portfolio template based on HTML5">
    <meta name="keywords" content="onepage, developer, resume, cv, ,personal, portfolio, personal resume, clean, modern">
    <meta name="author" content="MouriTheme">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!--template title-->
    <title>Demo - Sistema de licencias</title>

    <!--Favicon-->
    <link rel="apple-touch-icon" sizes="57x57" href="assets/demo/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="assets/demo/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="assets/demo/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="assets/demo/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="assets/demo/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="assets/demo/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="assets/demo/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="assets/demo/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/demo/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192" href="assets/demo/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/demo/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="assets/demo/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/demo/favicon/favicon-16x16.png">
    <link rel="manifest" href="assets/demo/favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="assets/demo/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">


    <!--Theme fonts-->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700,900%20%7CRubik:400,700,900" rel="stylesheet">

    <!--Font Awesome css-->
    <link rel="stylesheet" href="assets/demo/css/font-awesome.min.css">

    <!--Bootstrap css-->
    <link rel="stylesheet" href="assets/demo/css/bootstrap.min.css">

    <!--line icon css-->
    <link rel="stylesheet" href="assets/demo/css/line-icons.min.css">

    <!--Magnific Popup css-->
    <link rel="stylesheet" href="assets/demo/css/magnific-popup.css">

    <!--Owl carousel css-->
    <link rel="stylesheet" href="assets/demo/css/owl.carousel.css">
    <link rel="stylesheet" href="assets/demo/css/owl.theme.default.css">

    <!--main css-->
    <link rel="stylesheet" href="assets/demo/css/style.css">

    <!--main css-->
    <link rel="stylesheet" href="assets/demo/css/responsive.css">

</head>

<body>

    <!--Preloader starts-->

    <div class="loader_bg">
        <div class="sk-rotating-plane"></div>
    </div>

    <!--Preloader ends-->

    <!--Nav area starts-->

    <div id="nav-area" class="navbar navbar-fixed-top">
        <div class="container">

            <div class="navbar-header">

                <!-- logo for small screen -->
                <a class="navbar-brand visible-xs scrollto" href="#home">
                    <h1><?php echo $app_name ?></h1>
                </a>
                <!--Enter your logo, name or initials-->

            </div>

            <div id="custom-nav" class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li id="logo">
                        <a href="#home" class="scrollto">
                            <h1><?php echo $app_name ?></h1>
                            <!--Enter your logo, name or initials-->
                            <!-- <img src="assets/images/logo.svg" alt="Logo" width="200"> -->
                        </a>
                    </li>
                </ul>
            </div>
            <!--End navbar-collapse -->

        </div>
        <!--End container -->

    </div>

    <!--Nav area ends -->

    <!-- Header starts -->

    <header id="home" class="home-area">
        <div id="particles-js"></div>
        <div class="home-table">
            <div class="home-table-cell">
                <div class="container">

                    <div class="row">

                        <div class="col-sm-12 welcome-text-area text-center">

                            <h1 class="banner-text">
                                Nosotros somos <span><?php echo $app_name ?></span>
                                <!--change the name here-->
                            </h1>
                            <div class="text-affect">
                                <p></p>
                            </div>

                            <p><a href="#demo" class="custom-btn-2">Quiero un Demo</a></p>

                            <div class="mouse-icon bounce">
                                <span class="glyphicon glyphicon-menu-down"></span>
                            </div>

                        </div>

                    </div>

                </div>
            </div>
        </div>

    </header>

    <!--Header Ends -->



    <!--contact area starts-->

    <div id="demo" class="contact-area section-padding">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-title text-center">
                        <h2>Genera tu licencia de prueba</h2>
                        <p>Por favor, completa el siguiente formulario para generar tu licencia de prueba. La licencia se generará de forma inmediata.</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <center>
                    <div class="col-sm-12 col-md-12 m-auto">

                        <!-- contact form-->
                        <form id="generate">

                            <div class="controls">

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="text" id="cname" name="cname" class="form-control" placeholder="Ingresa tu nombre completo *" required="required" data-error="El nombre completo es obligatorio.">
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input id="email" type="email" name="email" class="form-control" placeholder="Ingresa tu correo electrónico *" required="required" data-error="Se requiere un correo electrónico válido.">
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input type="number" class="form-control" id="wnumber" name="wnumber" placeholder="Ingrese el Número de Whatsapp (Con Código de País) *" required="required" data-error="El número de Whatsapp es obligatorio.">
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="mt-1 mb-1" id="response-message"></div>
                                    <div class="col-md-12">
                                        <button type="button" id="generate-license" class="btn btn-send custom-button-4">Generar</button>
                                    </div>
                                </div>
                            </div>

                        </form>

                        <!-- contact form ends-->
                    </div>
                </center>
            </div>
        </div>

    </div>

    <!--contact area ends-->


    <!--Jquery-->
    <script src="assets/demo/js/jquery.min.js"></script>

    <!--Bootstrap js-->
    <script src="assets/demo/js/bootstrap.min.js"></script>

    <!--Typed js-->
    <script src="assets/demo/js/typed.js"></script>

    <!--owl carousel-->
    <script src="assets/demo/js/owl.carousel.js"></script>

    <!--Particles js-->
    <script src="assets/demo/js/particles.js"></script>
    <script src="assets/demo/js/app.js"></script>


    <!--Contact js-->
    <script>
        $(document).ready(function() {
            $("#generate-license").click(function() {
                // Obtener datos del formulario
                var wnumber = $("#wnumber").val();
                var endDate = new Date();
                endDate.setDate(endDate.getDate() + 8);
                endDate = endDate.toISOString().split('T')[0];
                var cname = $("#cname").val();
                var email = $("#email").val();

                // Verificar si hay valores vacíos o nulos
                if (!wnumber || !endDate || !cname || !email) {
                    var errorMessage = "Por favor, complete todos los campos requeridos.";
                    $("#response-message").html(
                        '<div class="bg-danger p-2 text-black mt-2" style="text-align: center;">' +
                        errorMessage +
                        "</div>"
                    );
                    return;
                }

                // Calcular la validez en días desde hoy hasta la fecha de finalización seleccionada
                var today = new Date();
                var endDateObj = new Date(endDate);
                var validity = Math.ceil((endDateObj - today) / (1000 * 60 * 60 * 24)); // Calcular días

                // Enviar solicitud AJAX a tu script PHP
                $.ajax({
                    type: "POST",
                    url: "function/generate-demo.php", // Reemplazar con la ruta correcta a tu script PHP
                    data: {
                        wnumber: wnumber,
                        validity: validity,
                        cname: cname,
                        email: email,
                    },
                    success: function(response) {
                        if (response.status === true) {
                            $("#response-message").html(
                                '<div class="bg-success p-2 text-black mt-2 mb-2" style="text-align: center;">' +
                                response.message +
                                "</div>"
                            );
                        } else {
                            $("#response-message").html(
                                '<div class="bg-danger p-2 text-black mt-2 mb-2" style="text-align: center;">' +
                                response.message +
                                "</div>"
                            );
                        }
                    },
                    error: function() {
                        $("#response-message").html(
                            '<div class="bg-danger p-2 text-black mt-2 mb-2" style="text-align: center;">Ocurrió un error al procesar tu solicitud.</div>'
                        );
                    },
                });
            });
        });
    </script>

    <!--counter up js-->
    <script src="assets/demo/js/waypoints.min.js"></script>
    <script src="assets/demo/js/jquery.counterup.min.js"></script>

    <!--imageloaded js-->
    <script src="assets/demo/js/imagesloaded.min.js"></script>

    <!--magnific popup-->
    <script src="assets/demo/js/jquery.magnific-popup.min.js"></script>

    <!--main js-->
    <script src="assets/demo/js/main.js"></script>


</body>

</html>