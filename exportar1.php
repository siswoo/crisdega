<?php
include('script/conexion.php');
require 'resources/Spreadsheet/autoload.php';
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

function ejecutarConsulta($conexion, $sql) {
    $consulta = mysqli_query($conexion, $sql);
    if (!$consulta) {
        throw new Exception("Error en la consulta: " . mysqli_error($conexion));
    }
    return $consulta;
}

function escribirEncabezados($sheet, $encabezados) {
    $columna = 'A';
    foreach ($encabezados as $encabezado) {
        $sheet->setCellValue($columna . '1', $encabezado);
        $columna++;
    }
}

try {
    $spreadsheet = new Spreadsheet();

    // Consulta 1
    $sql1 = "SELECT inv.id as inv_id, inv.cantidad as inv_cantidad, ubi.descripcion as ubi_descripcion, cont.descripcion as cont_descripcion, ref.descripcion as ref_descripcion, ref.articulo as ref_articulo, ubi.bodega_id as ubi_bodega_id, bodega.descripcion as bodega_descripcion FROM inventario inv 
    INNER JOIN ubicacion ubi 
    ON inv.ubicacion_id = ubi.id 
    INNER JOIN conteo cont 
    ON inv.conteo_id = cont.id 
    INNER JOIN referencia ref 
    ON inv.referencia_id = ref.id
    INNER JOIN bodega
    ON ubi.bodega_id = bodega.id
    WHERE cont.id = 1";
    $consulta1 = ejecutarConsulta($conexion, $sql1);

    $sheet1 = $spreadsheet->createSheet();
    $sheet1->setTitle('Articulo x Ub C1');
    $encabezados1 = ['Bodega', 'Ubicación', 'Conteo', 'Referencia', 'Artículo', 'Cantidad'];
    escribirEncabezados($sheet1, $encabezados1);
    $datos1 = array();

    // Procesar datos y llenar $datos1
    $fila = 2;
    while ($row = mysqli_fetch_assoc($consulta1)) {
        $datos1[] = [
            $row['bodega_descripcion'],
            $row['ubi_descripcion'],
            $row['cont_descripcion'],
            $row['ref_descripcion'],
            $row['ref_articulo'],
            $row['inv_cantidad'],
        ];
    }

    // Escribir los datos en la hoja
    foreach ($datos1 as $filaDatos) {
        $sheet1->fromArray($filaDatos, null, 'A' . $fila);
        $fila++;
    }

    // Consulta 2
    $sql2 = "SELECT inv.id as inv_id, inv.cantidad as inv_cantidad, ubi.descripcion as ubi_descripcion, cont.descripcion as cont_descripcion, ref.descripcion as ref_descripcion, ref.articulo as ref_articulo, ubi.bodega_id as ubi_bodega_id, bodega.descripcion as bodega_descripcion FROM inventario inv 
    INNER JOIN ubicacion ubi 
    ON inv.ubicacion_id = ubi.id 
    INNER JOIN conteo cont 
    ON inv.conteo_id = cont.id 
    INNER JOIN referencia ref 
    ON inv.referencia_id = ref.id
    INNER JOIN bodega
    ON ubi.bodega_id = bodega.id
    WHERE cont.id = 2";
    $consulta2 = ejecutarConsulta($conexion, $sql2);

    $sheet2 = $spreadsheet->createSheet();
    $sheet2->setTitle('Articulo x Ub C2');
    $encabezados2 = ['Bodega', 'Ubicación', 'Conteo', 'Referencia', 'Artículo', 'Cantidad'];
    escribirEncabezados($sheet2, $encabezados2);
    $datos2 = array();

    // Procesar datos y llenar $datos2
    $fila = 2;
    while ($row = mysqli_fetch_assoc($consulta2)) {
        $datos2[] = [
            $row['bodega_descripcion'],
            $row['ubi_descripcion'],
            $row['cont_descripcion'],
            $row['ref_descripcion'],
            $row['ref_articulo'],
            $row['inv_cantidad'],
        ];
    }

    // Escribir los datos en la hoja
    foreach ($datos2 as $filaDatos) {
        $sheet2->fromArray($filaDatos, null, 'A' . $fila);
        $fila++;
    }

    // Consulta 3
    $sql3 = "SELECT t2.descripcion as Referencia1, t2.articulo AS articulo1, SUM(t1.cantidad) as cantidad1 FROM inventario t1
    INNER JOIN referencia t2
    ON t1.referencia_id=t2.id 
    GROUP BY t2.descripcion,t2.articulo";
    $consulta3 = ejecutarConsulta($conexion, $sql3);

    $sheet3 = $spreadsheet->createSheet();
    $sheet3->setTitle('Consulta 3');
    $encabezados3 = ['Referencia', 'Artículo', 'Cantidad'];
    escribirEncabezados($sheet3, $encabezados3);
    $datos3 = array();

    // Procesar datos y llenar $datos3
    $fila = 2;
    while ($row = mysqli_fetch_assoc($consulta3)) {
        $datos3[] = [
            $row['Referencia1'],
            $row['articulo1'],
            $row['cantidad1'],
        ];
    }

    // Escribir los datos en la hoja
    foreach ($datos3 as $filaDatos) {
        $sheet3->fromArray($filaDatos, null, 'A' . $fila);
        $fila++;
    }

    // Consulta 4
    $sql4 = "SELECT t2.descripcion as conteo, t3.descripcion as referencia2, t3.articulo as articulo2, SUM(t1.cantidad) AS cantidad2 FROM inventario t1
    INNER JOIN conteo t2
    ON t1.conteo_id=t2.id
    INNER JOIN referencia t3
    ON t1.referencia_id=t3.id
    WHERE t2.id=1
    GROUP BY t3.descripcion,t3.articulo,t2.descripcion";
    $consulta4 = ejecutarConsulta($conexion, $sql4);

    $sheet4 = $spreadsheet->createSheet();
    $sheet4->setTitle('Total Art C1');
    $encabezados4 = ['Conteo', 'Referencia', 'Artículo', 'Cantidad'];
    escribirEncabezados($sheet4, $encabezados4);
    $datos4 = array();

    // Procesar datos y llenar $datos4
    $fila = 2;
    while ($row = mysqli_fetch_assoc($consulta4)) {
        $datos4[] = [
            $row['conteo'],
            $row['referencia'],
            $row['articulo'],
            $row['cantidad'],
        ];
    }

    // Escribir los datos en la hoja
    foreach ($datos4 as $filaDatos) {
        $sheet4->fromArray($filaDatos, null, 'A' . $fila);
        $fila++;
    }

    // Consulta 5
    $sql5 = "SELECT t2.descripcion as conteo, t3.descripcion as referencia, t3.articulo as articulo, SUM(t1.cantidad) AS cantidad FROM inventario t1
    INNER JOIN conteo t2
    ON t1.conteo_id=t2.id
    INNER JOIN referencia t3
    ON t1.referencia_id=t3.id
    WHERE t2.id=2
    GROUP BY t3.descripcion,t3.articulo,t2.descripcion";
    $consulta5 = ejecutarConsulta($conexion, $sql5);

    $sheet4 = $spreadsheet->createSheet();
    $sheet4->setTitle('Total Art C2');
    $encabezados5 = ['Conteo', 'Referencia', 'Artículo', 'Cantidad'];
    escribirEncabezados($sheet4, $encabezados5);
    $datos5 = array();

    // Procesar datos y llenar $datos4
    $fila = 2;
    while ($row = mysqli_fetch_assoc($consulta5)) {
        $datos5[] = [
            $row['conteo'],
            $row['referencia'],
            $row['articulo'],
            $row['cantidad'],
        ];
    }

    // Escribir los datos en la hoja
    foreach ($datos5 as $filaDatos) {
        $sheet5->fromArray($filaDatos, null, 'A' . $fila);
        $fila++;
    }
    
    // Guardar el archivo Excel
    $fecha_inicio1 = date('Y-m-d');
    $writer = new Xlsx($spreadsheet);
    $filename = 'Inventario ' . $fecha_inicio1 . '.xlsx';
    $writer->save($filename);

    header("Location: $filename");
} catch (mysqli_sql_exception $e) {
    echo "Error de MySQL: " . $e->getMessage();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
} finally {
    if ($conexion) {
        mysqli_close($conexion);
    }
}
?>
