<?php
// Iniciar la sesión o verificar si el usuario ha iniciado sesión previamente
session_start();
if (!isset($_SESSION['inventarios1_usuario_id'])) {
	header("Location: index.php"); // Redirige al usuario a la página de inicio de sesión si no ha iniciado sesión
	exit;
} else {
	$inventarios1_usuario_id = $_SESSION['inventarios1_usuario_id'];
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
	<title>inventarios</title>
</head>

<?php
include("script/conexion.php");
$sql1 = "SELECT * FROM usuarios WHERE id = ".$inventarios1_usuario_id;
$proceso1 = mysqli_query($conexion,$sql1);
while($row1=mysqli_fetch_array($proceso1)){
	$usuarioRol = $row1["rol"];
}

include("header.php");

if($usuarioRol==1){
?>
<body class="fondo" style="background-image: url(img/imagenes/fondo2.jpg);">
	<div class="col-12 text-center mt-3" style="font-weight: bold; font-size: 30px; text-transform: uppercase;">Listado de Inventario</div>
	<div class="col-12 mt-3">
		<a href="exportar1.php">
			<button type="button" class="btn btn-primary">Exportar Datos</button>
		</a>
		<a href="script/salir.php">
			<button type="button" class="btn btn-primary">Cerrar sesión</button>
		</a>
	</div>
	<div class="row ml-3 mr-3" style="margin-top: 2rem;">
		<!-- Elemento para almacenar datos relacionados con DataTables y filtrado -->
		<input type="hidden" name="datatables" id="datatables" data-pagina="1" data-consultasporpagina="10" data-filtrado="" data-bodega="" data-conteo="">
		
		<!-- Configuración de resultados por página -->
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
		
		<!-- Campo de búsqueda -->
		<div class="col-4 form-group form-check">
			<label for="buscarfiltro" style="color:black; font-weight: bold;">Búsqueda</label>
			<input type="text" class="form-control" id="buscarfiltro" autocomplete="off" name="buscarfiltro">
		</div>
		
		<!-- Selección de consulta por bodega -->
		<div class="col-3 form-group form-check">
			<label for="consultaporbodega" style="color:black; font-weight: bold;">Consulta por Bodega</label>
			<select class="form-control" id="consultaporbodega" name="consultaporbodega">
				<option value="">Seleccione</option>
				<?php
				// Obtener la lista de bodegas desde la base de datos (asegúrate de tener la conexión establecida)
				include("script/conexion.php");
				$sql1 = "SELECT * FROM bodega";
				$proceso1 = mysqli_query($conexion, $sql1);
				while ($row1 = mysqli_fetch_array($proceso1)) {
					echo '<option value="' . $row1["id"] . '">' . $row1["descripcion"] . '</option>';
				}
				?>
			</select>
		</div>
		
		<!-- Selección de consulta por conteo -->
		<div class="col-2 form-group form-check">
			<label for="consultaporconteo" style="color:black; font-weight: bold;">Consulta por Conteo</label>
			<select class="form-control" id="consultaporconteo" name="consultaporconteo">
				<option value="">Seleccione</option>
				<?php
				// Obtener la lista de conteos desde la base de datos (asegúrate de tener la conexión establecida)
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
			<button type="button" class="btn btn-info mt-2" onclick="filtrar1();">Filtrar</button>
		</div>

		<div class="col-12" style="background-color: white; border-radius: 1rem; padding: 20px 1px 1px 1px;" id="resultado_table1"></div>
	</div>

</body>
<?php
}else{
	echo '
		<div class="row" style="margin-top:40px;">
			<div class="col-12 text-center" style="font-weight:bold; font-size: 24px;">Bienvenido</div>
		</div>
	';
}
?>

<input type="hidden" id="usuarioRol" name="usuarioRol" value="<?php echo $usuarioRol; ?>">
<?php $ubicacion_url = $_SERVER["PHP_SELF"]; ?>

</html>
<script type="text/javascript" src="js/jquery-3.5.1.min.js"></script>
<script type="text/javascript" src="js/bootstrap.js"></script>
<script type="text/javascript" src="js/sweetalert2.js"></script>

<script type="text/javascript">
	$(document).ready(function() {
		var usuarioRol = $('#usuarioRol').val();
		if(usuarioRol==1){
			filtrar1();
			setInterval(filtrar1, 1000);
		}
	});

	// Función para cambiar la página en DataTables
	function paginacion1(value){
        $('#datatables').attr({'data-pagina':value})
        filtrar1();
    }

	function filtrar1() {
		// Obtener los valores de los filtros y configuración de DataTables
		var input_consultasporpagina = $('#consultasporpagina').val();
		var input_buscarfiltro = $('#buscarfiltro').val();
		var input_consultaporbodega = $('#consultaporbodega').val();
		var input_consultaporconteo = $('#consultaporconteo').val();

		// Establecer los valores en el elemento "datatables"
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

		// Obtener los valores de los filtros y configuración
		var pagina = $('#datatables').attr('data-pagina');
		var consultasporpagina = $('#datatables').attr('data-consultasporpagina');
		var bodega = $('#datatables').attr('data-bodega');
		var conteo = $('#datatables').attr('data-conteo');
		var filtrado = $('#datatables').attr('data-filtrado');
		var ubicacion_url = '<?php echo $ubicacion_url; ?>';

		// Realizar una solicitud AJAX para filtrar los resultados
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
