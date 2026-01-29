<?php
include("include/conn.php");
include("include/function.php");

// Verificar si el usuario ha iniciado sesión
if (cekSession()) {
    redirect("index.php");
    exit(); // Asegurarse de que no se ejecute más código después de la redirección
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
	<title>Ingreso</title>
	<link href="dist/css/style.min.css" rel="stylesheet">
</head>

<body>
	<div class="main-wrapper">
		<!-- ============================================================== -->
		<!-- Preloader - estilo que puedes encontrar en spinners.css -->
		<!-- ============================================================== -->
		<div class="preloader">
			<div class="lds-ripple">
				<div class="lds-pos"></div>
				<div class="lds-pos"></div>
			</div>
		</div>

		<!-- ============================================================== -->
		<!-- Caja de inicio de sesión.scss -->
		<!-- ============================================================== -->
		<div class="auth-wrapper d-flex no-block justify-content-center align-items-center position-relative"
			style="background:url(assets/images/big/auth-bg.jpg) no-repeat center center;">
			<div class="auth-box row">
				<div class="col-lg-7 col-md-5 modal-bg-img" style="background-image: url(assets/images/imglogin.png);">
				</div>
				<div class="col-lg-5 col-md-7 bg-white">
					<div class="p-3">
						<div class="text-center">
							<img src="assets/images/logo.svg" alt="logo" width="100%">
						</div>
						<form class="mt-1" id="login-form">
							<div class="row">
								<div class="col-lg-12">
									<div class="form-group mb-3">
										<label class="form-label text-dark" for="uname">Usuario</label>
										<input class="form-control" id="username" name="username" type="text"
											placeholder="Ingrese su usuario">
									</div>
								</div>
								<div class="col-lg-12">
									<div class="form-group mb-3">
										<label class="form-label text-dark" for="pwd">Contraseña</label>
										<input class="form-control" id="password" name="password" type="password"
											placeholder="Ingrese su contraseña">
									</div>
								</div>
								<div class="col-lg-12 text-center">
									<button type="submit" value="Ingresar" class="btn w-100 btn-dark">Ingresar</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- ============================================================== -->
	<!-- Todo el JavaScript requerido -->
	<!-- ============================================================== -->
	<script src="assets/libs/jquery/dist/jquery.min.js "></script>
	<!-- Bootstrap tether Core JavaScript -->
	<script src="assets/libs/popper.js/dist/umd/popper.min.js "></script>
	<script src="assets/libs/bootstrap/dist/js/bootstrap.min.js "></script>
	<script>
		$(".preloader ").fadeOut();
	</script>
	<script>
		$(document).ready(function() {
			$('#login-form').submit(function(e) {
				e.preventDefault(); // Previene el envío predeterminado del formulario

				// Obtener los datos del formulario
				var formData = {
					username: $('#username').val().trim(),
					password: $('#password').val()
				};

				// Validar que los campos no estén vacíos
				if (!formData.username || !formData.password) {
					alert('Por favor, complete ambos campos.');
					return;
				}

				// Enviar una solicitud AJAX POST al script PHP
				$.ajax({
					type: 'POST',
					url: 'function/check-login.php', // Ruta al script PHP
					data: formData,
					dataType: 'text', // Asegurarse de manejar la respuesta como texto
					success: function(response) {
						if (response.trim() === 'success') {
							window.location.href = 'index.php'; // Redirigir en caso de éxito
						} else {
							alert('Inicio de sesión fallido. Por favor, verifique su usuario y contraseña.');
						}
					},
					error: function(xhr, status, error) {
						console.error('Error en la solicitud AJAX:', error);
						alert('Ocurrió un problema con el servidor. Inténtelo de nuevo más tarde.');
					}
				});
			});
		});
	</script>

</body>

</html>