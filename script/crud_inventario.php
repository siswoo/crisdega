<?php
include('conexion.php');

$condicion = $_POST['condicion'];
$fecha_inicio = date("Y-m-d");

if ($condicion == 'registrar1') {
    $ubicacion_id = $_POST["id"];
    $conteo = $_POST["conteo"];
    $referencia = $_POST["referencia"];
    $cantidad = $_POST["cantidad"];
    $cantidad = $cantidad*1;

    if (empty($referencia) || empty($cantidad)) {
        $datos = [
            "estatus" => "error",
            "msg" => "Debe colocar referencia y cantidad",
        ];
        echo json_encode($datos);
        exit;
    }

    // Consultar la referencia por su descripción
    $sql2 = "SELECT id FROM referencia WHERE descripcion = ?";
    $stmt2 = $conexion->prepare($sql2);
    $stmt2->bind_param("s", $referencia);
    $stmt2->execute();
    $result2 = $stmt2->get_result();
    
    if ($result2->num_rows === 0) {
        $datos = [
            "estatus" => "error",
            "msg" => "Referencia no existente",
        ];
        echo json_encode($datos);
        exit;
    }

    // Obtener el ID de la referencia
    $row2 = $result2->fetch_assoc();
    $referencia_id = $row2["id"];

    // Insertar en la tabla de inventario
    /*
    $sql1 = "INSERT INTO inventario (ubicacion_id, conteo_id, referencia_id, cantidad) VALUES (?, ?, ?, ?)";
    $stmt1 = $conexion->prepare($sql1);
    $stmt1->bind_param("iiii", $ubicacion_id, $conteo, $referencia_id, $cantidad);
    */
    $sql1 = "INSERT INTO inventario (ubicacion_id, conteo_id, referencia_id, cantidad) VALUES ($ubicacion_id, $conteo, $referencia_id, $cantidad)";
    $proceso1 = mysqli_query($conexion,$sql1);

    $datos = [
        "estatus" => "ok",
        "msg" => "Se ha creado satisfactoriamente",
    ];
    echo json_encode($datos);
}
?>
