<?php
session_start();
if (!isset($_SESSION['crisdega_usuario_id'])) {
	header("Location: index.php");
	exit;
}
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
	<link rel="stylesheet" href="css/estilos.css">
	<link rel="icon" type="image/x-icon" href="img/favicon/inspeccion.png">
	<title>Crisdega</title>
</head>


<div id="header">
	<nav class="navbar navbar-expand-lg">
		<div class="container-fluid">
			<a class="navbar-brand" href="administrador.php">
				<img src="img/logo/logos.png" id="logo" alt="Logo" width="30" height="24" class="d-inline-block text-justify">
			</a>
			<div class="row">
				<div class="col-12">
					<a href="administrador.php">
						<button type=" button" class="btn btn-primary">Listado</button>
					</a>
					<?php
					include("script/conexion.php");
					$sql1 = "SELECT * FROM bodega";
					$proceso1 = mysqli_query($conexion, $sql1);
					while ($row1 = mysqli_fetch_array($proceso1)) {
						$bodegas_id = $row1["id"];
						$bodegas_descripcion = $row1["descripcion"];
						echo '
						<a href="columnas.php?id=' . $bodegas_id . '&descripcion=' . $bodegas_descripcion . '">
							<button type=" button" class="btn btn-primary">' . $bodegas_descripcion . '</button>
						</a>
					';
					}
					?>
				</div>
			</div>
		</div>
	</nav>
</div>

<body class="fondo" style="background-image: url(img/imagenes/fondo1.jpg);">
	<div class="col-12 text-center mt-3" style="font-weight: bold; font-size: 30px; text-transform: uppercase;">Listado de Inventario</div>
	<div class="col-12 mt-3">
		<a href="exportar1.php" target="_blank">
			<button type="button" class="btn btn-primary">Exportar Datos</button>
		</a>
	</div>
	<div class="row ml-3 mr-3" style="margin-top: 2rem;">
		<input type="hidden" name="datatables" id="datatables" data-pagina="1" data-consultasporpagina="10" data-filtrado="" data-bodega="" data-conteo="">
		<div class="col-3 form-group form-check">
			<label for="consultasporpagina" style="color:black; font-weight: bold;">Resultados por página</label>
			<select class="form-control" id="consultasporpagina" name="consultasporpagina">
				<option value="10">10</option>
				<option value="20">20</option>
				<option value="30">30</option>
				<option value="40">40</option>
				<option value="50">50</option>
				<option value="100">100</option>
			</select>
		</div>
		<div class="col-4 form-group form-check">
			<label for="buscarfiltro" style="color:black; font-weight: bold;">Busqueda</label>
			<input type="text" class="form-control" id="buscarfiltro" autocomplete="off" name="buscarfiltro">
		</div>
		<div class="col-3 form-group form-check">
			<label for="consultaporbodega" style="color:black; font-weight: bold;">Consulta por Bodega</label>
			<select class="form-control" id="consultaporbodega" name="consultaporbodega">
				<option value="">Seleccione</option>
				<?php
				include("script/conexion.php");
				$sql1 = "SELECT * FROM bodega";
				$proceso1 = mysqli_query($conexion, $sql1);
				while ($row1 = mysqli_fetch_array($proceso1)) {
					echo '<option value="' . $row1["id"] . '">' . $row1["descripcion"] . '</option>';
				}
				?>
			</select>
		</div>
		<div class="col-2 form-group form-check">
			<label for="consultaporconteo" style="color:black; font-weight: bold;">Consulta por Conteo</label>
			<select class="form-control" id="consultaporconteo" name="consultaporconteo">
				<option value="">Seleccione</option>
				<?php
				$sql2 = "SELECT * FROM conteo";
				$proceso2 = mysqli_query($conexion, $sql2);
				while ($row2 = mysqli_fetch_array($proceso2)) {
					echo '<option value="' . $row2["id"] . '">' . $row2["descripcion"] . '</option>';
				}
				?>
			</select>
		</div>
		<div class="col-2" style="display: none;">
			<br>
			<button type="button" class="btn btn-info mt-2" onclick="filtrar1();">Filter</button>
		</div>

		<div class="col-12" style="background-color: white; border-radius: 1rem; padding: 20px 1px 1px 1px;" id="resultado_table1"></div>
	</div>

	<?php $ubicacion_url = $_SERVER["PHP_SELF"]; ?>
</body>

</html>
<script type="text/javascript" src="js/jquery-3.5.1.min.js"></script>
<script type="text/javascript" src="js/bootstrap.js"></script>
<script type="text/javascript" src="js/sweetalert2.js"></script>


<script type="text/javascript">
	$(document).ready(function() {
		filtrar1();
		setInterval(filtrar1, 1000);
	});

	function filtrar1() {
		var input_consultasporpagina = $('#consultasporpagina').val();
		var input_buscarfiltro = $('#buscarfiltro').val();
		var input_consultaporbodega = $('#consultaporbodega').val();
		var input_consultaporconteo = $('#consultaporconteo').val();

		$('#datatables').attr({
			'data-consultasporpagina': input_consultasporpagina
		})
		$('#datatables').attr({
			'data-filtrado': input_buscarfiltro
		})
		$('#datatables').attr({
			'data-bodega': input_consultaporbodega
		})
		$('#datatables').attr({
			'data-conteo': input_consultaporconteo
		})

		var pagina = $('#datatables').attr('data-pagina');
		var consultasporpagina = $('#datatables').attr('data-consultasporpagina');
		var bodega = $('#datatables').attr('data-bodega');
		var conteo = $('#datatables').attr('data-conteo');
		var filtrado = $('#datatables').attr('data-filtrado');
		var ubicacion_url = '<?php echo $ubicacion_url; ?>';

		$.ajax({
			type: 'POST',
			url: 'script/crud_referencias.php',
			dataType: "JSON",
			data: {
				"pagina": pagina,
				"consultasporpagina": consultasporpagina,
				"bodega": bodega,
				"conteo": conteo,
				"filtrado": filtrado,
				"link1": ubicacion_url,
				"condicion": "table1",
			},

			success: function(respuesta) {
				//console.log(respuesta);

				if (respuesta["estatus"] == "ok") {
					$('#resultado_table1').html(respuesta["html"]);
				} else if (respuesta["estatus"] == "error") {
					Swal.fire({
						title: 'Error',
						text: respuesta["msg"],
						icon: 'error',
						showConfirmButton: true,
					})
				}
			},

			error: function(respuesta) {
				console.log(respuesta['responseText']);
			}
		});
	}
</script>