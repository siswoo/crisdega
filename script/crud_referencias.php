<?php
include('conexion.php');
$condicion = $_POST['condicion'];
$fecha_inicio = date("Y-m-d");

if($condicion=='table1'){
	$pagina = $_POST["pagina"];
	$consultasporpagina = $_POST["consultasporpagina"];
	$filtrado = $_POST["filtrado"];
	$bodega = $_POST["bodega"];
	$conteo = $_POST["conteo"];

	if($pagina==0 or $pagina==''){
		$pagina = 1;
	}

	if($consultasporpagina==0 or $consultasporpagina==''){
		$consultasporpagina = 10;
	}

	if($filtrado!=''){
		$filtrado = ' and (ref.descripcion LIKE "%'.$filtrado.'%" or ubi.descripcion LIKE "%'.$filtrado.'%")';
	}

	if($bodega!=''){
		$bodega = ' and (ubi.bodega_id = '.$bodega.')';
	}

	if($conteo!=''){
		$conteo = ' and (cont.id = '.$conteo.')';
	}

	$limit = $consultasporpagina;
	$offset = ($pagina - 1) * $consultasporpagina;

	$sql1 = "SELECT inv.id as inv_id, inv.cantidad as inv_cantidad, ubi.descripcion as ubi_descripcion, cont.descripcion as cont_descripcion, ref.descripcion as ref_descripcion, ref.articulo as ref_articulo, ubi.bodega_id as ubi_bodega_id FROM inventario inv 
		INNER JOIN ubicacion ubi 
		ON inv.ubicacion_id = ubi.id 
		INNER JOIN conteo cont 
		ON inv.conteo_id = cont.id 
		INNER JOIN referencia ref 
		ON inv.referencia_id = ref.id 
		WHERE inv.id != 0 ".$filtrado." ".$bodega." ".$conteo;

	$sql2 = "SELECT inv.id as inv_id, inv.cantidad as inv_cantidad, ubi.descripcion as ubi_descripcion, cont.descripcion as cont_descripcion, ref.descripcion as ref_descripcion, ref.articulo as ref_articulo, ubi.bodega_id as ubi_bodega_id FROM inventario inv 
		INNER JOIN ubicacion ubi 
		ON inv.ubicacion_id = ubi.id 
		INNER JOIN conteo cont 
		ON inv.conteo_id = cont.id 
		INNER JOIN referencia ref 
		ON inv.referencia_id = ref.id 
		WHERE inv.id != 0 ".$filtrado." ".$bodega." ".$conteo." ORDER BY inv.id ASC LIMIT ".$limit." OFFSET ".$offset;
	
	$proceso1 = mysqli_query($conexion,$sql1);
	$proceso2 = mysqli_query($conexion,$sql2);
	$conteo1 = mysqli_num_rows($proceso1);
	$paginas = ceil($conteo1 / $consultasporpagina);

	$html = '';

	$html .= '
		<div class="col-12">
	        <table class="table table-bordered">
	            <thead>
	            <tr>
					<th class="text-center">Bodega</th>
					<th class="text-center">Ubicación</th>
					<th class="text-center">Conteo</th>
					<th class="text-center">Referencia</th>
					<th class="text-center">Artículo</th>
					<th class="text-center">Cantidad</th>
	            </tr>
	            </thead>
	            <tbody>
	';
	if($conteo1>=1){
		while($row2 = mysqli_fetch_array($proceso2)) {
			$ubi_bodega_id = $row2["ubi_bodega_id"];
			$sql3 = "SELECT * FROM bodega WHERE id = ".$ubi_bodega_id;
			$proceso3 = mysqli_query($conexion,$sql3);
			while($row3=mysqli_fetch_array($proceso3)){
				$bodega_descripcion = $row3["descripcion"];
			}

			$html .= '
		                <tr id="">
		                    <td style="text-align:center;">'.$bodega_descripcion.'</td>
		                    <td style="text-align:center;">'.$row2["ubi_descripcion"].'</td>
		                    <td style="text-align:center;">'.$row2["cont_descripcion"].'</td>
		                    <td style="text-align:center;">'.$row2["ref_descripcion"].'</td>
		                    <td style="text-align:center;">'.$row2["ref_articulo"].'</td>
		                    <td style="text-align:center;">'.$row2["inv_cantidad"].'</td>
							</td>
		                </tr>
			';
		}
	}else{
		$html .= '<tr><td colspan="6" class="text-center" style="font-weight:bold;font-size:20px;">Sin Resultados</td></tr>';
	}

	$html .= '
	            </tbody>
	        </table>
	        <nav>
	            <div class="row">
	                <div class="col-xs-12 col-sm-4 text-center">
	                    <p>Mostrando '.$consultasporpagina.' de '.$conteo1.' Datos disponibles</p>
	                </div>
	                <div class="col-xs-12 col-sm-4 text-center">
	                    <p>Página '.$pagina.' de '.$paginas.' </p>
	                </div> 
	                <div class="col-xs-12 col-sm-4">
			            <nav aria-label="Page navigation" style="float:right; padding-right:2rem;">
							<ul class="pagination">
	';
	
	if ($pagina > 1) {
		$html .= '
								<li class="page-item">
									<a class="page-link" onclick="paginacion1('.($pagina-1).');" href="#">
										<span aria-hidden="true">Anterior</span>
									</a>
								</li>
		';
	}

	$diferenciapagina = 3;
	
	/*********MENOS********/
	if($pagina==2){
		$html .= '
		                		<li class="page-item">
			                        <a class="page-link" onclick="paginacion1('.($pagina-1).');" href="#">
			                            '.($pagina-1).'
			                        </a>
			                    </li>
		';
	}else if($pagina==3){
		$html .= '
			                    <li class="page-item">
			                        <a class="page-link" onclick="paginacion1('.($pagina-2).');" href="#"">
			                            '.($pagina-2).'
			                        </a>
			                    </li>
			                    <li class="page-item">
			                        <a class="page-link" onclick="paginacion1('.($pagina-1).');" href="#"">
			                            '.($pagina-1).'
			                        </a>
			                    </li>
	';
	}else if($pagina>=4){
		$html .= '
		                		<li class="page-item">
			                        <a class="page-link" onclick="paginacion1('.($pagina-3).');" href="#"">
			                            '.($pagina-3).'
			                        </a>
			                    </li>
			                    <li class="page-item">
			                        <a class="page-link" onclick="paginacion1('.($pagina-2).');" href="#"">
			                            '.($pagina-2).'
			                        </a>
			                    </li>
			                    <li class="page-item">
			                        <a class="page-link" onclick="paginacion1('.($pagina-1).');" href="#"">
			                            '.($pagina-1).'
			                        </a>
			                    </li>
		';
	} 

	/*********MAS********/
	$opcionmas = $pagina+3;
	if($paginas==0){
		$opcionmas = $paginas;
	}else if($paginas>=1 and $paginas<=4){
		$opcionmas = $paginas;
	}
	
	for ($x=$pagina;$x<=$opcionmas;$x++) {
		$html .= '
			                    <li class="page-item 
		';

		if ($x == $pagina){ 
			$html .= '"active"';
		}

		$html .= '">';

		$html .= '
			                        <a class="page-link" onclick="paginacion1('.($x).');" href="#"">'.$x.'</a>
			                    </li>
		';
	}

	if ($pagina < $paginas) {
		$html .= '
			                    <li class="page-item">
			                        <a class="page-link" onclick="paginacion1('.($pagina+1).');" href="#"">
			                            <span aria-hidden="true">Siguiente</span>
			                        </a>
			                    </li>
		';
	}

	$html .= '

						</ul>
					</nav>
				</div>
	        </nav>
	    </div>
	';

	$datos = [
		"estatus"	=> "ok",
		"html"	=> $html,
	];
	echo json_encode($datos);
}

?>