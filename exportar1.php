<?php
include('script/conexion.php');
require 'resources/Spreadsheet/autoload.php';
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'Bodega');
$sheet->setCellValue('B1', 'Ubicación');
$sheet->setCellValue('C1', 'Conteo');
$sheet->setCellValue('D1', 'Referencia');
$sheet->setCellValue('E1', 'Artículo');
$sheet->setCellValue('F1', 'Cantidad');

$spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(20);
$spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(20);
$spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(20);
$spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(20);
$spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(60);
$spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(20);

$fila = 2;

$sql1 = "SELECT inv.id as inv_id, inv.cantidad as inv_cantidad, ubi.descripcion as ubi_descripcion, cont.descripcion as cont_descripcion, ref.descripcion as ref_descripcion, ref.articulo as ref_articulo, ubi.bodega_id as ubi_bodega_id FROM inventario inv 
		INNER JOIN ubicacion ubi 
		ON inv.ubicacion_id = ubi.id 
		INNER JOIN conteo cont 
		ON inv.conteo_id = cont.id 
		INNER JOIN referencia ref 
		ON inv.referencia_id = ref.id";
$consulta1 = mysqli_query($conexion,$sql1);
while($row1 = mysqli_fetch_array($consulta1)) {
	$id = $row1['id'];
	$ubi_bodega_id = $row1["ubi_bodega_id"];

	$sql3 = "SELECT * FROM bodega WHERE id = ".$ubi_bodega_id;
	$proceso3 = mysqli_query($conexion,$sql3);
	while($row3=mysqli_fetch_array($proceso3)){
		$bodega_descripcion = $row3["descripcion"];
	}

	$sheet->setCellValue('A'.$fila, $bodega_descripcion);
	$sheet->setCellValue('B'.$fila, $row1["ubi_descripcion"]);
	$sheet->setCellValue('C'.$fila, $row1["cont_descripcion"]);
	$sheet->setCellValue('D'.$fila, $row1["ref_descripcion"]);
	$sheet->setCellValue('E'.$fila, $row1["ref_articulo"]);
	$spreadsheet->getActiveSheet()->getCell('F'.$fila)->setValue($row1["inv_cantidad"]);
	$spreadsheet->getActiveSheet()->getStyle('F'.$fila)->getNumberFormat()->setFormatCode('00');
	$fila = $fila+1;
}

$fecha_inicio1 = date('Y-m-d');
$writer = new Xlsx($spreadsheet);
$writer->save('Inventario '.$fecha_inicio1.'.xlsx');
header("Location: Inventario ".$fecha_inicio1.".xlsx");

?>