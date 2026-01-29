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
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon.png">
    <title>Licencias</title>
    <link href="assets/extra-libs/c3/c3.min.css" rel="stylesheet">
    <link href="assets/libs/chartist/dist/chartist.min.css" rel="stylesheet">
    <link href="assets/extra-libs/jvector/jquery-jvectormap-2.0.2.css" rel="stylesheet" />
    <link href="dist/css/style.min.css" rel="stylesheet">

    <link rel="stylesheet" href="assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css">
    <link rel="stylesheet" href="assets/extra-libs/datatables.net-bs4/css/responsive.dataTables.min.css">
</head>

<body>
    <!-- ============================================================== -->
    <!-- Preloader - estilo que puedes encontrar en spinners.css -->
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>
    <!-- ============================================================== -->

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
                        <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">Todas las licencias!</h3>
                        <div class="d-flex align-items-center">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb m-0 p-0">
                                    <li class="breadcrumb-item"><a href="index.php">Tablero</a>
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                    <div class="col-5 align-self-center">
                        <div class="customize-input float-end">
                            <a href="add-license.php" class="custom-select-set form-control bg-white border-0 custom-shadow custom-radius">New <i class=" fas fa-plus"></i></a>
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
                                <div class="table-responsive">
                                    <table id="zero_config" class="table border table-striped table-bordered text-nowrap">
                                        <thead>
                                            <tr>
                                                <th>Nombre</th>
                                                <th>WhatsApp</th>
                                                <th>Licencia</th>
                                                <th>F. Activación</th>
                                                <th>F. Finalización</th>
                                                <th>Días</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $iduser = 0;
                                            if ($_SESSION['user_type'] == 'admin') {
                                                $q = mysqli_query($conn, "SELECT `id`, `customer_name`, `whatsapp_number`, `license_key`, `act_date`, `end_date`, `pc_id`, `status` FROM `users`");
                                            } else {
                                                $u_id = $_SESSION['id'];
                                                $q = mysqli_query($conn, "SELECT `id`, `customer_name`, `whatsapp_number`, `license_key`, `act_date`, `end_date`, `pc_id`, `status` FROM `users` WHERE `user_id` = '$u_id'");
                                            }

                                            while ($row = mysqli_fetch_assoc($q)) {
                                                $iduser++;
                                                $id = $row['id'];
                                                $endDate = new DateTime($row['end_date']);
                                                $today = new DateTime();
                                                $remainingDays = $today->diff($endDate)->days; // Calcular días restantes
                                                echo '<tr>';
                                                echo '<td>' . $row['customer_name'] . '</td>';
                                                echo '<td>' . $row['whatsapp_number'] . '</td>';
                                                echo '<td>' . $row['license_key'] . '</td>';
                                                echo '<td>' . $row['act_date'] . '</td>';
                                                echo '<td contenteditable="true" class="editable-end-date" data-id="' . $id . '">' . $row['end_date'] . '</td>';
                                                echo '<td>' . $remainingDays . '</td>'; // Mostrar días restantes
                                            ?>
                                                <td>
                                                    <form action="function/delete.php" method="post">
                                                        <input type="text" style="display:none;" name="id" value="<?php echo $row['id'] ?>">
                                                        <button type="submit" class="btn btn-danger"><i class="fas fa-trash-alt"></i></button>
                                                        <a href="mod-license.php?id=<?php echo $row['id']; ?>" class="btn btn-secondary"><i class="far fa-edit"></i></a>
                                                    </form>
                                                </td>
                                            <?php
                                                echo '</tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
            </div>
            <!-- Fin contenedor fluido  -->

            <!-- ============================================================== -->
            <!-- pie de página -->
            <?php include("include/footer.php"); ?>
            <!-- ============================================================== -->

        </div>
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->

    <!-- ============================================================== -->
    <!-- Todos los Jquery -->
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

    <!--Plugins de esta página -->
    <script src="assets/extra-libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="assets/extra-libs/datatables.net-bs4/js/dataTables.responsive.min.js"></script>
    <script src="dist/js/pages/datatable/datatable-basic.init.js"></script>
</body>

</html>