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
$username = $_SESSION['username'] ?? ''; // Usar cadena vacía si no existe
$user_wsp = $_SESSION['wsp'] ?? ''; // Usar cadena vacía si no existe
$user_name = $_SESSION['name'] ?? ''; // Usar cadena vacía si no existe

// Obtener la información del usuario desde la base de datos
$query = $conn->prepare("SELECT * FROM admin WHERE id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    // Asignar valores desde la base de datos
    $user_username = htmlspecialchars($user['username']);
    $user_wsp = htmlspecialchars($user['contact_number']);
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
    <title>Mi perfil</title>
    <link href="assets/extra-libs/c3/c3.min.css" rel="stylesheet">
    <link href="assets/libs/chartist/dist/chartist.min.css" rel="stylesheet">
    <link href="assets/extra-libs/jvector/jquery-jvectormap-2.0.2.css" rel="stylesheet" />
    <link href="dist/css/style.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css">
    <link rel="stylesheet" href="assets/extra-libs/datatables.net-bs4/css/responsive.dataTables.min.css">
</head>

<body>
    <div id="main-wrapper" data-theme="light" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed" data-boxed-layout="full">

        <?php include("include/header.php"); ?>
        <?php include("include/sidebar.php"); ?>

        <div class="page-wrapper">
            <div class="page-breadcrumb">
                <div class="row">
                    <div class="col-7 align-self-center">
                        <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">Mi Perfil</h3>
                    </div>
                </div>
            </div>

            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form id="update">
                                    <div class="form-body">
                                        <!-- ID del usuario (oculto) -->
                                        <div class="row d-none">
                                            <div class="col-md-12">
                                                <div class="form-group mb-3">
                                                    <label class="form-label">ID</label>
                                                    <input type="text" class="form-control" id="id" name="id" value="<?php echo $user_id; ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Nombre -->
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group mb-3">
                                                    <label class="form-label">Mi Nombre</label>
                                                    <input type="text" class="form-control" id="cname" name="cname" value="<?php echo $user_name; ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Número de WhatsApp -->
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group mb-3">
                                                    <label class="form-label">Mi Número de Whatsapp (Con Código de País)</label>
                                                    <input type="number" class="form-control" id="wnumber" name="wnumber" value="<?php echo $user_wsp; ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Nombre de usuario -->
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group mb-3">
                                                    <label class="form-label">Mi Nombre de Usuario</label>
                                                    <input type="text" class="form-control" id="username" name="username" value="<?php echo $user_username; ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Contraseña -->
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group mb-3">
                                                    <label class="form-label">Mi Contraseña</label>
                                                    <input type="password" class="form-control" id="password" name="password">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-actions">
                                        <div class="text-end">
                                            <button type="button" id="update_user" class="btn btn-info">Actualizar Datos</button>
                                        </div>
                                    </div>
                                </form>
                                <div id="response-message"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php include("include/footer.php"); ?>
        </div>
    </div>

    <script src="assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="dist/js/app-style-switcher.js"></script>
    <script src="dist/js/feather.min.js"></script>
    <script src="assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>
    <script src="dist/js/sidebarmenu.js"></script>
    <script src="dist/js/custom.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#update_user').click(function() {
                var id = $('#id').val();
                var cname = $('#cname').val();
                var wnumber = $('#wnumber').val();
                var username = $('#username').val();
                var password = $('#password').val();

                // Crear objeto de datos dinámicamente
                var data = {
                    id: id,
                    cname: cname,
                    wnumber: wnumber,
                    username: username,
                };

                // Agregar contraseña solo si tiene un valor
                if (password.trim() !== '') {
                    data.password = password;
                }

                $.ajax({
                    url: 'function/update_user.php',
                    type: 'POST',
                    data: data,
                    success: function(response) {
                        if (response.status) {
                            $('#response-message').html('<div class="bg-success p-2 text-black mt-2" style="text-align: center;">' + response.message + '</div>');
                        } else {
                            $('#response-message').html('<div class="bg-danger p-2 text-black mt-2" style="text-align: center;">' + response.message + '</div>');
                        }
                    },
                    error: function() {
                        $('#response-message').html('<div class="bg-danger p-2 text-black mt-2" style="text-align: center;">Ocurrió un error.</div>');
                    }
                });
            });
        });
    </script>

</body>

</html>