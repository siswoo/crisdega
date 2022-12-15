<?php
	session_start();
	session_destroy();
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
	<title>Crisdega</title>
</head>
<body>

<form id="formulario1" action="#" method="POST">
	<div class="container">
		<div class="row">
			<div class="col-12 text-center" style="margin-top:3rem; font-size: 22px; font-weight: bold; text-transform: uppercase;">Ingresar Datos</div>
			<div class="col-12">
				<label>Usuario</label>
				<input type="text" id="usuario" name="usuario" class="form-control" autocomplete="off" required>
			</div>
			<div class="col-12">
				<label>Clave</label>
				<input type="password" id="password" name="password" class="form-control" required>
			</div>
			<div class="col-12 text-center mt-3">
				<button type="submit" class="btn btn-success" style="font-weight:bold;">Ingresar Usuario</button>
			</div>
		</div>
	</div>
</form>

</body>
</html>
<script type="text/javascript" src="js/jquery-3.5.1.min.js"></script>
<script type="text/javascript" src="js/bootstrap.js"></script>
<script type="text/javascript" src="js/sweetalert2.js"></script>


<script type="text/javascript">
	$("#formulario1").on("submit", function(e){
		e.preventDefault();
		var usuario = $('#usuario').val();
		var password = $('#password').val();
		$.ajax({
			type: 'POST',
			url: 'script/crud_usuarios.php',
			dataType: "JSON",
			data: {
				"usuario": usuario,
				"password": password,
				"condicion": "login",
			},

			success: function(respuesta) {
				if(respuesta["estatus"]=="ok"){
					window.location.href = respuesta["redireccion"];
				}else if(respuesta["estatus"]=="error"){
				Swal.fire({
	 				title: 'Error',
	 				text: respuesta["msg"],
	 				icon: 'error',
	 				position: 'center',
	 				timer: 5000
				});
			}
			},

			error: function(respuesta) {
				console.log(respuesta['responseText']);
			}
		});
  	});
</script>