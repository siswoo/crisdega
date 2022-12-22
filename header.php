<div id="header">
	<nav class="navbar navbar-expand-lg">
		<div class="container-fluid">
			<a class="navbar-brand" href="administrador.php">
				<img src="img/logo/logos.png" id="logo" alt="Logo" width="30" height="24" class="d-inline-block text-justify">
			</a>
			<div class="row">
				<div class="col-12">
					<?php 
					if($crisdega_usuario_id==1){ ?>
						<a href="administrador.php">
							<button type=" button" class="btn btn-primary">Listado</button>
						</a>
					<?php } ?>
					<?php
					include("script/conexion.php");
					$sql1 = "SELECT * FROM usuarios WHERE id = ".$crisdega_usuario_id;
					$proceso1 = mysqli_query($conexion, $sql1);
					while ($row1 = mysqli_fetch_array($proceso1)){
						$desde = $row1["desde"];
						$hasta = $row1["hasta"];
						$conteo_id = $row1["conteo_id"];
					}
					$sql2 = "SELECT * FROM ubicacion WHERE id = ".$desde;
					$proceso2 = mysqli_query($conexion, $sql2);
					while ($row2 = mysqli_fetch_array($proceso2)){
						$bodegas_id = $row2["bodega_id"];
					}
					$sql3 = "SELECT * FROM bodega WHERE id = ".$bodegas_id;
					$proceso3 = mysqli_query($conexion, $sql3);
					while ($row3 = mysqli_fetch_array($proceso3)){
						$bodegas_descripcion = $row3["descripcion"];
					}
					echo '
						<a href="columnas.php?id=' . $bodegas_id . '&descripcion=' . $bodegas_descripcion . '&especial=0">
							<button type=" button" class="btn btn-primary">' . $bodegas_descripcion . '</button>
						</a>

						<a href="columnas.php?id=1&descripcion=171&especial=2321">
							<button type=" button" class="btn btn-primary">171 ?</button>
						</a>
						<a href="columnas.php?id=2&descripcion=186&especial=2322">
							<button type=" button" class="btn btn-primary">186 ?</button>
						</a>
						<a href="columnas.php?id=3&descripcion=187&especial=2323">
							<button type=" button" class="btn btn-primary">187 ?</button>
						</a>
						<a href="columnas.php?id=4&descripcion=188&especial=2324">
							<button type=" button" class="btn btn-primary">188 ?</button>
						</a>
						
					';
					?>
				</div>
			</div>
		</div>
	</nav>
</div>