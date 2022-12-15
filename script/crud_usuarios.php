<?php
include('conexion.php');
$condicion = $_POST['condicion'];
$fecha_inicio = date("Y-m-d");

if($condicion=='login'){
	$usuario = $_POST['usuario'];
	$password = $_POST['password'];
	$password = md5($password);

	$sql1 = "SELECT * FROM usuarios WHERE usuario = '$usuario' and password = '$password'";
	$proceso1 = mysqli_query($conexion,$sql1);
	$contador1 = mysqli_num_rows($proceso1);
	if($contador1>=1){

		while($row1=mysqli_fetch_array($proceso1)){
			$usuario_id = $row1["id"];
			$usuario_rol = $row1["rol"];
		}

		if($usuario_rol==1){
			$redireccion = "administrador.php";
			session_start();
			$_SESSION["crisdega_usuario_id"] = $usuario_id;
			$datos = [
				"estatus"	=> "ok",
				"redireccion"	=> $redireccion,
			];
			echo json_encode($datos);
			exit;
		}
	}else{
		$datos = [
			"estatus"	=> "error",
			"msg"	=> "Credenciales Incorrectas",
		];
		echo json_encode($datos);
		exit;
	}
}

?>