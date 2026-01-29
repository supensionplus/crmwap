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
  <title>Vendedores</title>
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
            <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">Todos los Vendedores!</h3>
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
              <a href="add-reseller.php" class="custom-select-set form-control bg-white border-0 custom-shadow custom-radius">New <i class=" fas fa-plus"></i></a>
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
                        <th>Usuario</th>
                        <th>Contacto</th>
                        <th>Estado</th>
                        <th>F. Activación</th>
                        <th>F. Finalización</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $iduser = 0;
                      $q = mysqli_query($conn, "SELECT `id`,`username`, `name`, `contact_number`, `password`, `user_type`,`start_date`, `expired_date`, `status` FROM `admin` WHERE `user_type` = 'reseller'");
                      while ($row = mysqli_fetch_assoc($q)) {
                        $iduser++;
                        $id = $row['id'];
                        echo '<tr>';
                        echo '<td>' . $row['username'] . '</td>';
                        echo '<td>' . $row['name'] . '</td>';
                        echo '<td>' . $row['contact_number'] . '</td>';
                        echo '<td>' . $row['status'] . '</td>';
                        echo '<td>' . $row['start_date'] . '</td>';
                        echo '<td>' . $row['expired_date'] . '</td>';
                        echo '<td>
                          <div class="dropdown sub-dropdown">
                            <button class="btn btn-secondary btn-min-width text-white dropdown-toggle" type="button"
                              id="dd1" data-bs-toggle="dropdown" aria-haspopup="true"
                              aria-expanded="false">
                              Actualizar<i data-feather="more-vertical"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dd1">
                              <a class="dropdown-item activate-btn" href="#" data-id="' . $id . '">Activar</a>
                              <a class="dropdown-item deactivate-btn" href="#" data-id="' . $id . '">Desactivar</a>
                              <a class="dropdown-item delete-btn" href="#" data-id="' . $id . '">Eliminar</a>
                            </div>
                          </div>
                        </td>';
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

  <script>
    $(document).ready(function() {
      $(document).on('click', '.activate-btn', function() {
        var id = $(this).data('id');
        updateResellerStatus(id, 'true');
      });

      $(document).on('click', '.deactivate-btn', function() {
        var id = $(this).data('id');
        updateResellerStatus(id, 'false');
      });

      $(document).on('click', '.delete-btn', function() {
        var id = $(this).data('id');
        deleteReseller(id);
      });
    });

    function updateResellerStatus(id, status) {
      $.ajax({
        url: 'function/update_status.php', // Actualiza esta URL a tu script PHP
        type: 'POST',
        data: {
          id: id,
          status: status
        },
        success: function(response) {
          alert(response.message);
          // Opcionalmente, refresca la página o actualiza la vista de la tabla
        }
      });
    }

    function deleteReseller(id) {
      if (confirm("¿Estás seguro de que deseas eliminar este vendedor?")) {
        $.ajax({
          url: 'function/delete_reseller.php', // Actualiza esta URL a tu script PHP
          type: 'POST',
          data: {
            id: id
          },
          success: function(response) {
            alert(response.message);
            // Opcionalmente, refresca la página o actualiza la vista de la tabla
          }
        });
      }
    }
  </script>
</body>

</html>