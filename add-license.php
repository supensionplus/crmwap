<?php
// Iniciar la sesión
session_start();

// Mostrar errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Incluir archivos necesarios
include("include/conn.php");
include("include/function.php");

// Verificar si el usuario está logueado
if (!isset($_SESSION['id'])) {
    // Redirigir al login si no está autenticado
    redirect("login.php");
    exit();
}

// Depurar los datos de la sesión
$user_id = $_SESSION['id'];
$user_name = $_SESSION['name'] ?? ''; // Usar cadena vacía si no existe

// Obtener la información del usuario desde la base de datos
$query = $conn->prepare("SELECT * FROM admin WHERE id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    // Asignar valores desde la base de datos
    $user_name = htmlspecialchars($user['name']);
} else {
    // Si no se encuentra el usuario, redirigir al login
    echo "No se encontró información del usuario.";
    redirect("login.php");
    exit();
}
?>


<!DOCTYPE html>
<html dir="ltr" lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon.png">
    <title>New Licencia</title>
    <link href="assets/extra-libs/c3/c3.min.css" rel="stylesheet">
    <link href="assets/libs/chartist/dist/chartist.min.css" rel="stylesheet">
    <link href="assets/extra-libs/jvector/jquery-jvectormap-2.0.2.css" rel="stylesheet" />
    <link href="dist/css/style.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css">
    <link rel="stylesheet" href="assets/extra-libs/datatables.net-bs4/css/responsive.dataTables.min.css">
</head>

<body>
    <!-- ============================================================== -->
    <!-- Precargador - estilo que puedes encontrar en spinners.css -->
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>
    <!-- ============================================================== -->

    <!-- ============================================================== -->
    <!-- Contenedor principal - estilo que puedes encontrar en pages.scss -->
    <div id="main-wrapper" data-theme="light" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed" data-boxed-layout="full">

        <!-- ============================================================== -->
        <!-- Encabezado de la barra superior - estilo que puedes encontrar en pages.scss -->
        <?php include("include/header.php"); ?>
        <!-- ============================================================== -->


        <!-- ============================================================== -->
        <!-- Barra lateral izquierda - estilo que puedes encontrar en sidebar.scss  -->
        <?php include("include/sidebar.php"); ?>
        <!-- ============================================================== -->


        <!-- ============================================================== -->
        <!-- Contenedor de la página  -->
        <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Miga de pan y alternar barra lateral derecha -->
            <div class="page-breadcrumb">
                <div class="row">
                    <div class="col-7 align-self-center">
                        <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">Nueva licencia!</h3>
                        <div class="d-flex align-items-center">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb m-0 p-0">
                                    <li class="breadcrumb-item"><a href="all-licenses.php">Todas las licencias</a>
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->

            <!-- ============================================================== -->
            <!-- Contenedor fluido  -->
            <div class="container-fluid">
                <!-- ============================================================== -->
                <!-- Iniciar contenido de la página -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form id="generate">
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group mb-3">
                                                    <label class="form-label">Ingrese el Número de Whatsapp (Con Código de País)</label>
                                                    <input type="number" class="form-control" id="wnumber" name="wnumber">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group mb-3">
                                                    <label class="form-label">Ingrese el Nombre del Cliente</label>
                                                    <input type="text" class="form-control" id="cname" name="cname">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group mb-3">
                                                    <label class="form-label">Ingrese el Correo del Cliente</label>
                                                    <input type="email" class="form-control" id="email" name="email">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group mb-3">
                                                    <label class="form-label">Seleccione la Fecha de Finalización</label>
                                                    <input type="date" class="form-control" id="end-date" name="end-date">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-actions">
                                        <div class="text-end">
                                            <button type="button" id="generate-license" class="btn btn-info">Generar</button>
                                        </div>
                                    </div>
                                </form>
                                <div id="response-message"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
            </div>
            <!-- ============================================================== -->

            <!-- ============================================================== -->
            <!-- pie de página -->
            <?php include("include/footer.php"); ?>
            <!-- ============================================================== -->

        </div>
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->

    <!-- ============================================================== -->
    <!-- Todo Jquery -->
    <script src="assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="dist/js/app-style-switcher.js"></script>
    <script src="dist/js/feather.min.js"></script>
    <script src="assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>
    <script src="dist/js/sidebarmenu.js"></script>

    <!--JavaScript personalizado -->
    <script src="dist/js/custom.min.js"></script>

    <!--JavaScript de esta página -->
    <script src="assets/extra-libs/c3/d3.min.js"></script>
    <script src="assets/extra-libs/c3/c3.min.js"></script>
    <script src="assets/libs/chartist/dist/chartist.min.js"></script>
    <script src="assets/libs/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js"></script>
    <script src="assets/extra-libs/jvector/jquery-jvectormap-2.0.2.min.js"></script>
    <script src="assets/extra-libs/jvector/jquery-jvectormap-world-mill-en.js"></script>
    <script src="dist/js/pages/dashboards/dashboard1.min.js"></script>

    <!--Complementos de esta página -->
    <script src="assets/extra-libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="assets/extra-libs/datatables.net-bs4/js/dataTables.responsive.min.js"></script>
    <script src="dist/js/pages/datatable/datatable-basic.init.js"></script>

    <script>
        $(document).ready(function() {
            $('#generate-license').click(function() {
                // Obtener datos del formulario
                var wnumber = $('#wnumber').val();
                var endDate = $('#end-date').val();
                var cname = $('#cname').val();
                var email = $('#email').val();

                // Verificar si hay valores vacíos o nulos
                if (!wnumber || !endDate || !cname || !email) {
                    var errorMessage = "Por favor, complete todos los campos requeridos.";
                    $('#response-message').html('<div class="bg-danger p-2 text-black mt-2" style="text-align: center;">' + errorMessage + '</div>');
                    return;
                }

                // Calcular la validez en días desde hoy hasta la fecha de finalización seleccionada
                var today = new Date();
                var endDateObj = new Date(endDate);
                var validity = Math.ceil((endDateObj - today) / (1000 * 60 * 60 * 24)); // Calcular días

                // Enviar solicitud AJAX a tu script PHP
                $.ajax({
                    type: 'POST',
                    url: 'function/generate-license.php', // Reemplazar con la ruta correcta a tu script PHP
                    data: {
                        wnumber: wnumber,
                        validity: validity,
                        cname: cname,
                        email: email
                    },
                    success: function(response) {
                        if (response.status === true) {
                            $('#response-message').html('<div class="bg-success p-2 text-black mt-2" style="text-align: center;">' + response.message + '</div>');
                        } else {
                            $('#response-message').html('<div class="bg-danger p-2 text-black mt-2" style="text-align: center;">' + response.message + '</div>');
                        }
                    },
                    error: function() {
                        $('#response-message').html('<div class="bg-danger p-2 text-black mt-2" style="text-align: center;">Ocurrió un error al procesar tu solicitud.</div>');
                    }
                });
            });
        });
    </script>
</body>

</html>