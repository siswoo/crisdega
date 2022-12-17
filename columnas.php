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

<?php
$bodegas_id_get = $_GET["id"];
$bodegas_descripcion_get = $_GET["descripcion"];
?>

<body class="fondo" style="background-image: url(img/imagenes/fondo1.jpg);">
	<div class="col-12 text-center mt-3" style="font-weight: bold; font-size|: 30px; text-transform: uppercase;">Bodega <?php echo $bodegas_descripcion_get; ?></div>
	<div class="row ml-3 mr-3" style="margin-top: 2rem; background-color: white; border-radius: 1rem; padding: 20px 1px 1px 1px;">
		<div class="col-2 text-center mb-3" style="font-weight:bold; font-size: 22px;">Ubicación</div>
		<div class="col-2 text-center mb-3" style="font-weight:bold; font-size: 22px;">Conteo</div>
		<div class="col-2 text-center mb-3" style="font-weight:bold; font-size: 22px;">Referencia</div>
		<div class="col-2 text-center mb-3" style="font-weight:bold; font-size: 22px;">Cantidad</div>
		<div class="col-4 text-center mb-3" style="font-weight:bold; font-size: 22px;">Opciones</div>

		<div class="col-2 text-center mb-3">
			<?php
			$sql2 = "SELECT * FROM ubicacion WHERE bodega_id = " . $bodegas_id_get;
			$proceso2 = mysqli_query($conexion, $sql2);
			$pase1 = 0;
			while ($row2 = mysqli_fetch_array($proceso2)) {
				$ubicacion_id = $row2["id"];
				$ubicacion_descripcion = $row2["descripcion"];
				$sql3 = "SELECT * FROM inventario WHERE ubicacion_id = " . $ubicacion_id;
				$proceso3 = mysqli_query($conexion, $sql3);
				$contador3 = mysqli_num_rows($proceso3);
				if ($contador3 == 0 and $pase1 == 0) {
					$pase1 = 1;
					$ubicacion_id_final = $row2["id"];
					$ubicacion_descripcion_final = $row2["descripcion"];
				}
			}
			echo $ubicacion_descripcion_final;
			?>
		</div>
		<div class="col-2 mb-3">
			<select class="form-control" name="conteo" id="conteo" required>
				<option value="1">C1</option>
			</select>
			<!--<input type="text" name="conteo" id="conteo" value="C1" class="form-control" autocomplete="off" required>-->
		</div>
		<div class="col-2 mb-3">
			<input type="text" name="referencia" id="referencia" value="" class="form-control" placeholder="referencia" autocomplete="off" required>
		</div>
		<div class="col-2 mb-3">
			<input type="text" name="cantidad" id="cantidad" value="" class="form-control" placeholder="cantidad" autocomplete="off" required>
		</div>
		<div class="col-4 text-center mb-3">
			<button type="button" class="btn btn-primary">Copiar</button>
			<button type="button" class="btn btn-success" onclick="registrar1();">Registrar</button>
		</div>
	</div>

	<?php $ubicacion_url = $_SERVER["PHP_SELF"]; ?>
</body>

</html>
<script type="text/javascript" src="js/jquery-3.5.1.min.js"></script>
<script type="text/javascript" src="js/bootstrap.js"></script>
<script type="text/javascript" src="js/sweetalert2.js"></script>


<script type="text/javascript">
	$(document).ready(function() {
		//
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

	function registrar1() {
		var id = <?php echo $ubicacion_id_final; ?>;
		var conteo = $('#conteo').val();
		var referencia = $('#referencia').val();
		var cantidad = $('#cantidad').val();
		$.ajax({
			type: 'POST',
			url: 'script/crud_inventario.php',
			dataType: "JSON",
			data: {
				"id": id,
				"conteo": conteo,
				"referencia": referencia,
				"cantidad": cantidad,
				"condicion": "registrar1",
			},

			success: function(respuesta) {
				console.log(respuesta);
				if (respuesta["estatus"] == "ok") {
					window.location.replace("columnas.php?id=<?php echo $bodegas_id_get; ?>&descripcion=<?php echo $bodegas_descripcion_get; ?>");
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