<?php
include('conexion.php');
$condicion = $_POST['condicion'];
$fecha_inicio = date("Y-m-d");

if($condicion=='registrar1'){
	$ubicacion_id = $_POST["id"];
	$conteo = $_POST["conteo"];
	$referencia = $_POST["referencia"];
	$cantidad = $_POST["cantidad"];

	if($referencia=='' || $cantidad==''){
		$datos = [
			"estatus"	=> "error",
			"msg"	=> "Debe colocar referencia y cantidad",
		];
		echo json_encode($datos);
		exit;
	}

	$sql2 = "SELECT * FROM referencia WHERE descripcion = '$referencia'";
	$proceso2 = mysqli_query($conexion,$sql2);
	$contador2 = mysqli_num_rows($proceso2);
	if($contador2==0){
		$datos = [
			"estatus"	=> "error",
			"msg"	=> "Referencia no existente",
		];
		echo json_encode($datos);
		exit;
	}else{
		while($row2=mysqli_fetch_array($proceso2)){
			$referencia_id = $row2["id"];
		}
	}
	
	$sql1 = "INSERT INTO inventario (ubicacion_id,conteo_id,referencia_id,cantidad) VALUES ('$ubicacion_id','$conteo','$referencia_id','$cantidad')";
	$proceso1 = mysqli_query($conexion,$sql1);

	$datos = [
		"estatus"	=> "ok",
		"msg"	=> "Se ha creado satisfactoriamente",
	];
	echo json_encode($datos);
}

?>