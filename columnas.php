<?php
/**
 * Archivo PHP para gestionar la página principal de un sistema de inventario.
 *
 * Este archivo verifica la sesión del usuario, conecta a la base de datos, y muestra formularios y datos relacionados con el inventario.
 * También permite registrar elementos en el inventario y realiza consultas dinámicas a la base de datos.
 *
 * @package Sistema de Inventario
 */

// Inicia la sesión o reanuda una sesión existente.
session_start();

// Verifica si no existe la variable de sesión 'inventarios_usuario_id'.
if (!isset($_SESSION['inventarios1_usuario_id'])) {
    // Si no existe, redirige al usuario a la página de inicio (index.php) y termina la ejecución del script.
    header("Location: index.php");
    exit;
} else {
    // Si la variable de sesión 'inventarios_usuario_id' existe, la asigna a una variable local.
    $inventarios1_usuario_id = $_SESSION['inventarios1_usuario_id'];
}

// Establece la conexión a la base de datos. Asegúrate de reemplazar los valores de conexión adecuados.
//$conexion = mysqli_connect("localhost", "root", "", "inventarios1");
include("script/conexion.php");

// Verifica si la conexión a la base de datos fue exitosa.
if (!$conexion) {
    // Si la conexión falla, muestra un mensaje de error y termina la ejecución del script.
    die("Error al conectar con la base de datos: " . mysqli_connect_error());
}

// Consulta para obtener el conteo_id del usuario actual.
$sqlUsuario = "SELECT conteo_id FROM usuarios WHERE id = $inventarios1_usuario_id";
$resultadoUsuario = mysqli_query($conexion, $sqlUsuario);

// Verifica si la consulta fue exitosa.
if ($resultadoUsuario) {
    // Obtiene la fila de resultados como un arreglo asociativo.
    $filaUsuario = mysqli_fetch_assoc($resultadoUsuario);
    // Asigna el valor de 'conteo_id' a la variable local 'conteo_id'.
    $conteo_id = $filaUsuario['conteo_id'];
} else {
    // Si la consulta falla, asigna un valor predeterminado (C1) a 'conteo_id'.
    $conteo_id = 'C1';
}

// Consulta para obtener la descripción del conteo.
$sqlDescripcionConteo = "SELECT descripcion FROM conteo WHERE id = $conteo_id";
$resultadoDescripcionConteo = mysqli_query($conexion, $sqlDescripcionConteo);

// Verifica si la consulta fue exitosa.
if ($resultadoDescripcionConteo) {
    // Obtiene la fila de resultados como un arreglo asociativo.
    $filaDescripcionConteo = mysqli_fetch_assoc($resultadoDescripcionConteo);
    // Asigna el valor de 'descripcion' a la variable local 'conteo_descripcion'.
    $conteo_descripcion = $filaDescripcionConteo['descripcion'];
} else {
    // Si la consulta falla, puedes definir un valor predeterminado si es necesario.
    $conteo_descripcion = 'Descripción Predeterminada';
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
    <title>INVENTARIO</title>

    <style>
        table,
        th,
        td {
            border: 1px solid;
        }

        table {
            width: 50%;
            border-collapse: collapse;
        }
    </style>
</head>

<?php include("header.php"); ?>

<?php
// Obtiene los valores pasados por GET desde la URL.
$bodegas_id_get = $_GET["id"];
$bodegas_descripcion_get = $_GET["descripcion"];
$especial = $_GET["especial"];
@$titulo = $_GET["titulo"];
if($titulo==""){
    $titulo = $bodegas_descripcion_get;
}
?>

<body class="fondo" style="background-image: url(img/imagenes/fondo1.jpg);">
    <div class="col-12 text-center mt-3" style="font-weight: bold; font-size|: 30px; text-transform: uppercase;">Bodega <?php echo $titulo; ?></div>
    <a href="script/salir.php">
        <button type="button" class="btn btn-primary">Cerrar sesion</button>
    </a>
    <div class="row ml-3 mr-3" style="margin-top: 2rem; background-color: white; border-radius: 1rem; padding: 20px 1px 1px 1px;">
        <div class="col-2 text-center mb-3" style="font-weight:bold; font-size: 22px;">Ubicación</div>
        <div class="col-2 text-center mb-3" style="font-weight:bold; font-size: 22px;">Conteo</div>
        <div class="col-2 text-center mb-3" style="font-weight:bold; font-size: 22px;">Referencia</div>
        <div class="col-2 text-center mb-3" style="font-weight:bold; font-size: 22px;">Cantidad</div>
        <div class="col-4 text-center mb-3" style="font-weight:bold; font-size: 22px;">Opciones</div>

        <?php
        // Verificación de si se trata de una situación especial o no.
        $especial_descripcion = $bodegas_descripcion_get;

        if ($especial != 0) {
            // Si es una situación especial, se obtiene información adicional.
            $sql6 = "SELECT * FROM ubicacion WHERE id = " . $especial;
            $proceso6 = mysqli_query($conexion, $sql6);
            while ($row6 = mysqli_fetch_array($proceso6)) {
                $especial_descripcion = $row6["descripcion"];
            }
            $ubicacion_id_final = $especial;
            // Se muestra el formulario correspondiente.
            echo '
            <!-- Formulario para situación especial -->
            <div class="col-2 text-center mb-3">' . $especial_descripcion . ' </div>
            <div class="col-2 text-center mb-3">
                <select class="form-control" name="conteo" id="conteo" required>
                    <option value="' . $conteo_id . '">' . $conteo_descripcion . '</option>
                </select>
            </div>
            <div class="col-2 mb-3">
                <input type="text" name="referencia" id="referencia" value="" class="form-control" placeholder="referencia" autocomplete="off" required>
            </div>
            '; 
            ?>
            <div class="col-2 mb-3">
                <input type="text" name="cantidad" id="cantidad" value="" class="form-control" placeholder="cantidad" autocomplete="off" required>
            </div>
            <?php
            echo '
            <div class="col-4 text-center mb-3">
                <button type="button" class="btn btn-primary" onclick="copiar1();">Copiar</button>
                <button type="button" class="btn btn-success" onclick="registrar1();">Registrar</button>
            </div>
        ';
        } else { ?>
            <!-- Formulario para situación estándar -->
            <div class="col-2 text-center mb-3">
                <?php
                // Consulta para obtener la ubicación disponible para el usuario.
                $sql2 = "SELECT * FROM ubicacion WHERE bodega_id =  $bodegas_id_get and  id >= $desde and id <= $hasta";
                $proceso2 = mysqli_query($conexion, $sql2);
                $pase1 = 0;
                while ($row2 = mysqli_fetch_array($proceso2)) {
                    $ubicacion_id = $row2["id"];
                    $ubicacion_descripcion = $row2["descripcion"];
                    $sql3 = "SELECT * FROM inventario WHERE ubicacion_id =  $ubicacion_id and conteo_id = $conteo_id";
                    $proceso3 = mysqli_query($conexion, $sql3);
                    $contador3 = mysqli_num_rows($proceso3);
                    if ($contador3 == 0 and $pase1 == 0) {
                        $pase1 = 1;
                        $ubicacion_id_final = $row2["id"];
                        $ubicacion_descripcion_final = $row2["descripcion"];
                    }
                }
                if ($ubicacion_id_final >= $hasta) {
                    echo '';
                } else {
                    echo $ubicacion_descripcion_final;
                } ?>
            </div>
            <?php
            if ($ubicacion_id_final >= $hasta) {
                echo '<div class="col-12 text-center mb-3">El usuario no tiene cupo</div>';
            } else { ?>
                <div class="col-2 mb-3">
                    <?php
                    // Consulta para obtener la descripción del conteo actual.
                    $sql2 = "SELECT * FROM conteo WHERE id = " . $conteo_id;
                    $proceso2 = mysqli_query($conexion, $sql2);
                    while ($row2 = mysqli_fetch_array($proceso2)) {
                        $conteo_descripcion = $row2["descripcion"];
                    }
                    ?>
                    <select class="form-control" name="conteo" id="conteo" required>
                        <option value="<?php echo $conteo_id; ?>"><?php echo $conteo_descripcion; ?></option>
                    </select>
                </div>
                <div class="col-2 mb-3">
                    <input type="text" name="referencia" id="referencia" value="" class="form-control" placeholder="referencia" required>
                </div>
                <div class="col-2 mb-3">
                    <input type="number" name="cantidad" id="cantidad" value="" class="form-control" placeholder="cantidad" autocomplete="off" required>
                </div>
                <div class="col-4 text-center mb-3">
                    <button type="button" class="btn btn-primary" onclick="copiar1();">Copiar</button>
                    <button type="button" class="btn btn-success" onclick="registrar1();">Registrar</button>
                </div>

    <?php }
    }
    ?>
    </div>

    <!-- Tabla para mostrar resultados -->

    <?php $ubicacion_url = $_SERVER["PHP_SELF"]; ?>
    <input type="text" value="" id="output" style="background: transparent;border: none; color: transparent;">

    <table>
        <thead>
            <th>Referencia</th>
            <th>Articulo</th>
        </thead>

        <tbody id="content">

        </tbody>
    </table>

</body>

</html>

<input type="hidden" id="descripcion_hidden" name="descripcion_hidden" value="<?php echo $_GET['descripcion']; ?>">

<!-- Inclusión de archivos JavaScript -->

<script type="text/javascript" src="js/jquery-3.5.1.min.js"></script>
<script type="text/javascript" src="js/bootstrap.js"></script>
<script type="text/javascript" src="js/sweetalert2.js"></script>

<script type="text/javascript">
    // Funciones JavaScript para el funcionamiento de la página
    $(document).ready(function() {
        //
    });

    // Función para filtrar elementos en la página.
    function filtrar1() {
        // Obtener valores de filtros y parámetros de la tabla.
        var input_consultasporpagina = $('#consultasporpagina').val();
        var input_buscarfiltro = $('#buscarfiltro').val();
        var input_consultaporbodega = $('#consultaporbodega').val();
        var input_consultaporconteo = $('#consultaporconteo').val();

        // Asignar valores a los atributos de la tabla.
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

        // Obtener valores de la tabla.
        var pagina = $('#datatables').attr('data-pagina');
        var consultasporpagina = $('#datatables').attr('data-consultasporpagina');
        var bodega = $('#datatables').attr('data-bodega');
        var conteo = $('#datatables').attr('data-conteo');
        var filtrado = $('#datatables').attr('data-filtrado');
        var ubicacion_url = '<?php echo $ubicacion_url; ?>';

        // Realizar una solicitud AJAX para obtener resultados filtrados.
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
                // Manejo de la respuesta exitosa.
                if (respuesta["estatus"] == "ok") {
                    $('#resultado_table1').html(respuesta["html"]);
                } else if (respuesta["estatus"] == "error") {
                    // Manejo de errores.
                    Swal.fire({
                        title: 'Error',
                        text: respuesta["msg"],
                        icon: 'error',
                        showConfirmButton: true,
                    })
                }
            },

            error: function(respuesta) {
                // Manejo de errores de la solicitud AJAX.
                console.log(respuesta['responseText']);
            }
        });
    }

    // Función para registrar elementos en el inventario.
    function registrar1() {
        // Obtener valores del formulario.
        var id = <?php echo $ubicacion_id_final; ?>;
        var conteo = $('#conteo').val();
        var referencia = $('#referencia').val();
        var cantidad = $('#cantidad').val();

        // Realizar una solicitud AJAX para registrar el elemento en el inventario.
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
                // Manejo de la respuesta exitosa.
                console.log(respuesta);
                if (respuesta["estatus"] == "ok") {
                    // Redireccionar a la página de columnas con los parámetros apropiados.
                    window.location.replace("columnas.php?id=<?php echo $bodegas_id_get; ?>&descripcion=<?php echo $bodegas_descripcion_get; ?>&especial=<?php echo $especial; ?>");
                } else if (respuesta["estatus"] == "error") {
                    // Manejo de errores.
                    Swal.fire({
                        title: 'Error',
                        text: respuesta["msg"],
                        icon: 'error',
                        showConfirmButton: true,
                    })
                }
            },

            error: function(respuesta) {
                // Manejo de errores de la solicitud AJAX.
                console.log(respuesta['responseText']);
            }
        });
    }

    // Función para copiar el contenido de referencia y cantidad.
    function copiar1() {
        // Obtener elementos de referencia y cantidad.
        var copyText = document.getElementById("referencia");
        var copyText2 = document.getElementById("cantidad");
        // Crear un elemento de salida oculto y copiar el contenido combinado.
        var output = document.getElementById("output");
        output.value = copyText.value + " " + copyText2.value;
        console.log(output.value);
        output.select();
        document.execCommand("copy");
    }

    // Obtener datos relacionados con la referencia en tiempo real.
    getData()

    document.getElementById("referencia").addEventListener("keyup", getData)

    function getData() {
        // Obtener el valor del campo de referencia.
        let input = document.getElementById("referencia").value
        let content = document.getElementById("content")
        var descripcion = $('#descripcion_hidden').val();
        let url = "script/load.php";
        let formaData = new FormData()
        formaData.append('referencia', input)
        formaData.append('descripcion', descripcion)

        // Realizar una solicitud AJAX para obtener datos relacionados con la referencia.
        fetch(url, {
                method: "POST",
                body: formaData
            }).then(response => response.json())
            .then(data => {
                content.innerHTML = data
            }).catch(err => console.log(err));
    }
</script>
