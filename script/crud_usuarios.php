<?php
// Incluye el archivo de conexión a la base de datos
include('conexion.php');

$condicion = $_POST['condicion'];
$fecha_inicio = date("Y-m-d");

if ($condicion == 'login') {
    // Recopila los datos de inicio de sesión enviados por POST
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];

    // Hashea la contraseña con MD5 (No recomendado para seguridad, considera usar una solución más segura)
    $password = md5($password);

    // Consulta SQL para verificar las credenciales del usuario
    $sql1 = "SELECT * FROM usuarios WHERE usuario = '$usuario' and password = '$password'";
    $proceso1 = mysqli_query($conexion, $sql1);
    $contador1 = mysqli_num_rows($proceso1);

    // Verifica si se encontró un usuario con las credenciales
    if ($contador1 >= 1) {
        while ($row1 = mysqli_fetch_array($proceso1)) {
            $usuario_id = $row1["id"];
            $usuario_rol = $row1["rol"];
        }

        // Redirige al usuario según su rol
        if ($usuario_rol == 1) {
            $redireccion = "administrador.php";

            // Inicia la sesión (asegúrate de haber llamado a session_start() antes de este punto)
            session_start();
            $_SESSION["inventarios1_usuario_id"] = $usuario_id;

            // Prepara la respuesta JSON con la redirección
            $datos = [
                "estatus" => "ok",
                "redireccion" => $redireccion,
            ];
            echo json_encode($datos);
            exit;
        }
    } else {
        // Prepara la respuesta JSON para credenciales incorrectas
        $datos = [
            "estatus" => "error",
            "msg" => "Credenciales Incorrectas",
        ];
        echo json_encode($datos);
        exit;
    }
}
?>