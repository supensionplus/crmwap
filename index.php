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
$user_type = $_SESSION['user_type'];

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


// Function to get counts based on user type
function getCounts($conn, $user_id, $user_type)
{
    $counts = array();

    if ($user_type === 'admin') {
        // Admin dashboard data
        $totalUsersQuery = "SELECT COUNT(*) AS total_users FROM admin";
        $totalLicensesQuery = "SELECT COUNT(*) AS total_licenses FROM users";
        $totalInactiveLicensesQuery = "SELECT COUNT(*) AS total_inactive_licenses FROM users WHERE status = 'false'";
        $totalActiveLicensesQuery = "SELECT COUNT(*) AS total_active_licenses FROM users WHERE status = 'true'";
        $totalResellersQuery = "SELECT COUNT(*) AS total_resellers FROM admin WHERE user_type = 'reseller'";

        $counts['total_users'] = mysqli_fetch_assoc(mysqli_query($conn, $totalUsersQuery))['total_users'];
        $counts['total_licenses'] = mysqli_fetch_assoc(mysqli_query($conn, $totalLicensesQuery))['total_licenses'];
        $counts['total_inactive_licenses'] = mysqli_fetch_assoc(mysqli_query($conn, $totalInactiveLicensesQuery))['total_inactive_licenses'];
        $counts['total_active_licenses'] = mysqli_fetch_assoc(mysqli_query($conn, $totalActiveLicensesQuery))['total_active_licenses'];
        $counts['total_resellers'] = mysqli_fetch_assoc(mysqli_query($conn, $totalResellersQuery))['total_resellers'];
    } else {
        // Reseller dashboard data
        $totalLicensesQuery = "SELECT COUNT(*) AS total_licenses FROM users WHERE user_id = '$user_id'";
        $totalInactiveLicensesQuery = "SELECT COUNT(*) AS total_inactive_licenses FROM users WHERE user_id = '$user_id' AND status = 'false'";
        $totalActiveLicensesQuery = "SELECT COUNT(*) AS total_active_licenses FROM users WHERE user_id = '$user_id' AND status = 'true'";

        $counts['total_licenses'] = mysqli_fetch_assoc(mysqli_query($conn, $totalLicensesQuery))['total_licenses'];
        $counts['total_inactive_licenses'] = mysqli_fetch_assoc(mysqli_query($conn, $totalInactiveLicensesQuery))['total_inactive_licenses'];
        $counts['total_active_licenses'] = mysqli_fetch_assoc(mysqli_query($conn, $totalActiveLicensesQuery))['total_active_licenses'];
    }

    return $counts;
}

$dashboardData = getCounts($conn, $user_id, $user_type);
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
    <title>Tablero</title>
    <link href="assets/extra-libs/c3/c3.min.css" rel="stylesheet">
    <link href="assets/libs/chartist/dist/chartist.min.css" rel="stylesheet">
    <link href="assets/extra-libs/jvector/jquery-jvectormap-2.0.2.css" rel="stylesheet" />
    <link href="dist/css/style.min.css" rel="stylesheet">
</head>

<body>
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper" data-theme="light" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed" data-boxed-layout="full">

        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <?php include("include/header.php"); ?>
        <!-- ============================================================== -->


        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <?php include("include/sidebar.php"); ?>
        <!-- ============================================================== -->


        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <div class="page-wrapper">

            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <div class="page-breadcrumb">
                <div class="row">
                    <div class="col-7 align-self-center">
                        <h3 class="page-title text-truncate text-dark font-weight-medium mb-1" style="text-transform: capitalize;">Hola, <?php echo $user_name; ?>!</h3>
                        <div class="d-flex align-items-center">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb m-0 p-0">
                                    <li class="breadcrumb-item"><a href="index.php">Tablero</a>
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>

                </div>
            </div>

            <!-- ============================================================== -->

            <!-- ============================================================== -->
            <div class="container-fluid">
                <?php if ($user_type === 'admin') { ?>
                    
                    <div class="row">
                        <div class="col-sm-6 col-lg-3">
                            <div class="card border-end">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <div class="d-inline-flex align-items-center">
                                                <h2 class="text-dark mb-1 font-weight-medium"><?php echo $dashboardData['total_users']; ?></h2>
                                            </div>
                                            <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Vendedores y Administradores
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="card border-end ">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <h2 class="text-dark mb-1 w-100 text-truncate font-weight-medium"><?php echo $dashboardData['total_licenses']; ?></h2>
                                            <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Todas las Licencias
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="card border-end ">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <div class="d-inline-flex align-items-center">
                                                <h2 class="text-dark mb-1 font-weight-medium"><?php echo $dashboardData['total_active_licenses']; ?></h2>
                                            </div>
                                            <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Licencias Activas
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="card ">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <h2 class="text-dark mb-1 font-weight-medium"><?php echo $dashboardData['total_inactive_licenses']; ?></h2>
                                            <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Licencias Inactivas</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card border-end">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <div class="d-inline-flex align-items-center">
                                                <h2 class="text-dark mb-1 font-weight-medium"><?php echo $url_demo ?> <a href="<?php echo $url_demo ?>" target="_blank" rel="noopener noreferrer"><i class="fa fa-link"></i></a></h2>
                                            </div>
                                            <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Link del demo (prueba de la aplicación)
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- <div class="row">
                    <div class="col-lg-4 col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Total Sales</h4>
                                <div id="campaign-v2" class="mt-2" style="height:283px; width:100%;"></div>
                                <ul class="list-style-none mb-0">
                                    <li>
                                        <i class="fas fa-circle text-primary font-10 me-2"></i>
                                        <span class="text-muted">Direct Sales</span>
                                        <span class="text-dark float-end font-weight-medium">$2346</span>
                                    </li>
                                    <li class="mt-3">
                                        <i class="fas fa-circle text-danger font-10 me-2"></i>
                                        <span class="text-muted">Referral Sales</span>
                                        <span class="text-dark float-end font-weight-medium">$2108</span>
                                    </li>
                                    <li class="mt-3">
                                        <i class="fas fa-circle text-cyan font-10 me-2"></i>
                                        <span class="text-muted">Affiliate Sales</span>
                                        <span class="text-dark float-end font-weight-medium">$1204</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Net Income</h4>
                                <div class="net-income mt-4 position-relative" style="height:294px;"></div>
                                <ul class="list-inline text-center mt-5 mb-2">
                                    <li class="list-inline-item text-muted fst-italic">Sales for this month</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title mb-4">Earning by Location</h4>
                                <div class="" style="height:180px">
                                    <div id="visitbylocate" style="height:100%"></div>
                                </div>
                                <div class="row mb-3 align-items-center mt-1 mt-5">
                                    <div class="col-4 text-end">
                                        <span class="text-muted font-14">India</span>
                                    </div>
                                    <div class="col-5">
                                        <div class="progress" style="height: 5px;">
                                            <div class="progress-bar bg-primary" role="progressbar" style="width: 100%"
                                                aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                    <div class="col-3 text-end">
                                        <span class="mb-0 font-14 text-dark font-weight-medium">28%</span>
                                    </div>
                                </div>
                                <div class="row mb-3 align-items-center">
                                    <div class="col-4 text-end">
                                        <span class="text-muted font-14">UK</span>
                                    </div>
                                    <div class="col-5">
                                        <div class="progress" style="height: 5px;">
                                            <div class="progress-bar bg-danger" role="progressbar" style="width: 74%"
                                                aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                    <div class="col-3 text-end">
                                        <span class="mb-0 font-14 text-dark font-weight-medium">21%</span>
                                    </div>
                                </div>
                                <div class="row mb-3 align-items-center">
                                    <div class="col-4 text-end">
                                        <span class="text-muted font-14">USA</span>
                                    </div>
                                    <div class="col-5">
                                        <div class="progress" style="height: 5px;">
                                            <div class="progress-bar bg-cyan" role="progressbar" style="width: 60%"
                                                aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                    <div class="col-3 text-end">
                                        <span class="mb-0 font-14 text-dark font-weight-medium">18%</span>
                                    </div>
                                </div>
                                <div class="row align-items-center">
                                    <div class="col-4 text-end">
                                        <span class="text-muted font-14">China</span>
                                    </div>
                                    <div class="col-5">
                                        <div class="progress" style="height: 5px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 50%"
                                                aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                    <div class="col-3 text-end">
                                        <span class="mb-0 font-14 text-dark font-weight-medium">12%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->
                <?php } else { ?>
                    <div class="row">
                        <div class="col-sm-12 col-lg-4">
                            <div class="card border-end ">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <h2 class="text-dark mb-1 w-100 text-truncate font-weight-medium"><?php echo $dashboardData['total_licenses']; ?></h2>
                                            <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Todas las Licencias
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-lg-4">
                            <div class="card border-end ">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <div class="d-inline-flex align-items-center">
                                                <h2 class="text-dark mb-1 font-weight-medium"><?php echo $dashboardData['total_active_licenses']; ?></h2>
                                            </div>
                                            <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Licencias Activas
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-lg-4">
                            <div class="card ">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <h2 class="text-dark mb-1 font-weight-medium"><?php echo $dashboardData['total_inactive_licenses']; ?></h2>
                                            <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Licencias Inactivas</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php  } ?>
            </div>
            <!-- ============================================================== -->

            <!-- ============================================================== -->
            <!-- footer -->
            <?php include("include/footer.php"); ?>
            <!-- ============================================================== -->

        </div>
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->

    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="dist/js/app-style-switcher.js"></script>
    <script src="dist/js/feather.min.js"></script>
    <script src="assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>
    <script src="dist/js/sidebarmenu.js"></script>
    <!--Custom JavaScript -->
    <script src="dist/js/custom.min.js"></script>
    <!--This page JavaScript -->
    <script src="assets/extra-libs/c3/d3.min.js"></script>
    <script src="assets/extra-libs/c3/c3.min.js"></script>
    <script src="assets/libs/chartist/dist/chartist.min.js"></script>
    <script src="assets/libs/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js"></script>
    <script src="assets/extra-libs/jvector/jquery-jvectormap-2.0.2.min.js"></script>
    <script src="assets/extra-libs/jvector/jquery-jvectormap-world-mill-en.js"></script>
    <script src="dist/js/pages/dashboards/dashboard1.min.js"></script>
</body>

</html>






<!-- <!doctype html>

<html
    lang="es"
    class="light-style layout-menu-fixed layout-compact"
    dir="ltr"
    data-theme="theme-default"
    data-assets-path="assets/"
    data-template="vertical-menu-template-free"
    data-style="light">

<head>
    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Panel CRM</title>

    <meta name="description" content="" />

    <link rel="icon" type="image/x-icon" href="assets/img/favicon/favicon.ico" />

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&ampdisplay=swap"
        rel="stylesheet" />

    <link rel="stylesheet" href="assets/vendor/fonts/remixicon/remixicon.css" />

    <link rel="stylesheet" href="assets/vendor/libs/node-waves/node-waves.css" />

    <link rel="stylesheet" href="assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="assets/css/main.css" />

    <link rel="stylesheet" href="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="assets/vendor/libs/apex-charts/apex-charts.css" />

    <script src="assets/vendor/js/helpers.js"></script>
    <script src="assets/js/config.js"></script>
</head>

<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">

            <?php //include("include/sidebar.php"); 
            ?>

            <div class="layout-page">

                <?php //include("include/header.php"); 
                ?>

                <div class="content-wrapper">

                    <div class="container-xxl flex-grow-1 container-p-y">
                        <div class="row gy-6">
                            <?php //if ($user_type === 'admin') { 
                            ?>
                                <div class="col-lg-12">
                                    <div class="card h-100">
                                        <div class="card-header">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <h5 class="card-title m-0 me-2">Información</h5>
                                            </div>
                                        </div>
                                        <div class="card-body pt-lg-10">
                                            <div class="row g-6">
                                                <div class="col-md-3 col-6">
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar">
                                                            <div class="avatar-initial bg-primary rounded shadow-xs">
                                                                <i class="ri-macbook-line ri-24px"></i>
                                                            </div>
                                                        </div>
                                                        <div class="ms-3">
                                                            <p class="mb-0">Usuarios</p>
                                                            <h5 class="mb-0"><?php //echo $dashboardData['total_users']; 
                                                                                ?></h5>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-6">
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar">
                                                            <div class="avatar-initial bg-success rounded shadow-xs">
                                                                <i class="ri-macbook-line ri-24px"></i>
                                                            </div>
                                                        </div>
                                                        <div class="ms-3">
                                                            <p class="mb-0">Licencias Totales</p>
                                                            <h5 class="mb-0"><?php //echo $dashboardData['total_licenses']; 
                                                                                ?></h5>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-6">
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar">
                                                            <div class="avatar-initial bg-warning rounded shadow-xs">
                                                                <i class="ri-macbook-line ri-24px"></i>
                                                            </div>
                                                        </div>
                                                        <div class="ms-3">
                                                            <p class="mb-0">Licencias Activas</p>
                                                            <h5 class="mb-0"><?php //echo $dashboardData['total_active_licenses']; 
                                                                                ?></h5>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-6">
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar">
                                                            <div class="avatar-initial bg-info rounded shadow-xs">
                                                                <i class="ri-macbook-line ri-24px"></i>
                                                            </div>
                                                        </div>
                                                        <div class="ms-3">
                                                            <p class="mb-0">Licencias Inactivas</p>
                                                            <h5 class="mb-0"><?php //echo $dashboardData['total_inactive_licenses']; 
                                                                                ?></h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <? php // } else { 
                            ?>
                                <div class="col-lg-12">
                                    <div class="card h-100">
                                        <div class="card-header">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <h5 class="card-title m-0 me-2">Información</h5>
                                            </div>
                                        </div>
                                        <div class="card-body pt-lg-10">
                                            <div class="row g-6">
                                                <div class="col-md-3 col-6">
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar">
                                                            <div class="avatar-initial bg-primary rounded shadow-xs">
                                                                <i class="ri-macbook-line ri-24px"></i>
                                                            </div>
                                                        </div>
                                                        <div class="ms-3">
                                                            <p class="mb-0">Total de Licencias Creadas</p>
                                                            <h5 class="mb-0"><?php //echo $dashboardData['total_licenses']; 
                                                                                ?></h5>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-6">
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar">
                                                            <div class="avatar-initial bg-warning rounded shadow-xs">
                                                                <i class="ri-macbook-line ri-24px"></i>
                                                            </div>
                                                        </div>
                                                        <div class="ms-3">
                                                            <p class="mb-0">Licencias Activas</p>
                                                            <h5 class="mb-0"><?php //echo $dashboardData['total_active_licenses']; 
                                                                                ?></h5>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-6">
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar">
                                                            <div class="avatar-initial bg-info rounded shadow-xs">
                                                                <i class="ri-macbook-line ri-24px"></i>
                                                            </div>
                                                        </div>
                                                        <div class="ms-3">
                                                            <p class="mb-0">Licencias Inactivas</p>
                                                            <h5 class="mb-0"><?php //echo $dashboardData['total_inactive_licenses']; 
                                                                                ?></h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php //} 
                            ?>

                        </div>
                    </div>

                    <?php //include("include/footer.php"); 
                    ?>

                    <div class="content-backdrop fade"></div>
                </div>
            </div>
        </div>

        <div class="layout-overlay layout-menu-toggle"></div>
    </div>

    <script src="assets/vendor/libs/jquery/jquery.js"></script>
    <script src="assets/vendor/libs/popper/popper.js"></script>
    <script src="assets/vendor/js/bootstrap.js"></script>
    <script src="assets/vendor/libs/node-waves/node-waves.js"></script>
    <script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="assets/vendor/js/menu.js"></script>

    <script src="assets/vendor/libs/apex-charts/apexcharts.js"></script>

    <script src="assets/js/main.js"></script>

    <script src="assets/js/dashboards-analytics.js"></script>

    <script async defer src="https://buttons.github.io/buttons.js"></script>
</body>

</html> -->