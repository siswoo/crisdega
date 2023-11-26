<?php
// Incluye el archivo de conexión a la base de datos
include 'conexion.php';

// Recibe el valor de referencia desde la solicitud POST y elimina espacios en blanco alrededor
$referencia1 = isset($_POST['referencia']) ? trim($_POST['referencia']) : '';

if (!empty($referencia1)) {
    $descripcion = $_POST['descripcion'];
    // Consulta preparada para buscar referencias que coincidan parcialmente con la referencia proporcionada
    $sql = "SELECT descripcion, articulo, tipo FROM referencia WHERE descripcion LIKE ? and tipo = '".$descripcion."'";
    
    // Verifica si la consulta preparada se ejecuta correctamente
    if ($stmt = $conexion->prepare($sql)) {
        // Agrega comodines '%' alrededor de la referencia para buscar coincidencias parciales
        $referencia1 = "%$referencia1%";
        // Vincula el valor de referencia a la consulta preparada
        $stmt->bind_param("s", $referencia1);
        // Ejecuta la consulta preparada
        $stmt->execute();
        // Almacena los resultados en memoria para poder contarlos
        $stmt->store_result();

        // Obtiene el número de filas de resultados
        $num_rows = $stmt->num_rows;

        // Inicializa una variable para almacenar el HTML de la tabla de resultados
        $html = '';

        // Verifica si se encontraron resultados en la consulta
        if ($num_rows > 0) {
            // Vincula las columnas de resultados a variables
            $stmt->bind_result($descripcion, $articulo, $tipo);

            // Itera a través de los resultados y genera filas de tabla HTML
            while ($stmt->fetch()) {
                $html .= '<tr>';
                $html .= '<td>' . htmlspecialchars($descripcion) . '</td>'; // Evita problemas de seguridad al mostrar datos
                $html .= '<td>' . htmlspecialchars($articulo) . '</td>';
                $html .= '<td>' . htmlspecialchars($tipo) . '</td>';
                $html .= '</tr>';
            }
        } else {
            // Si no se encontraron resultados, muestra una fila de tabla indicando que no hay resultados
            $html .= '<tr>';
            $html .= '<td colspan="2">Sin Resultados</td>';
            $html .= '</tr>';
        }

        // Cierra la consulta preparada
        $stmt->close();
    } else {
        // Manejo de errores si la consulta preparada falla
        $html = '<tr>';
        $html .= '<td colspan="2">Error en la consulta</td>';
        $html .= '</tr>';
    }
} else {
    // Si no se proporciona una referencia, muestra una fila de tabla indicando que debe ingresarse una referencia
    $html = '<tr>';
    $html .= '<td colspan="2">Por favor, ingrese una referencia</td>';
    $html .= '</tr>';
}

// Devuelve el resultado HTML en formato JSON, asegurándose de que los caracteres Unicode se manejen correctamente
echo json_encode($html, JSON_UNESCAPED_UNICODE);
?>