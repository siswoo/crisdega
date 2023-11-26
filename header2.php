<div id="header">
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="administrador.php">
                <img src="img/logo/logos.png" id="logo" alt="Logo" width="50" height="60" class="d-inline-block text-justify">
            </a>
            <div class="row">
                <div class="col-12">
                    <?php
                    // Verificar si el usuario tiene un rol específico (por ejemplo, el ID 1)
                    if ($inventarios1_usuario_id == 1) { ?>
                        <a href="administrador.php">
                            <button type="button" class="btn btn-primary">Listado</button>
                        </a>
                    <?php } ?>

                    <?php
                    include("script/conexion.php");

                    // Consulta 1: Obtener información del usuario actual
                    $sql1 = "SELECT * FROM usuarios WHERE id = " . $inventarios1_usuario_id;
                    $proceso1 = mysqli_query($conexion, $sql1);

                    while ($row1 = mysqli_fetch_array($proceso1)) {
                        $desde = $row1["desde"];
                        $hasta = $row1["hasta"];
                        $conteo_id = $row1["conteo_id"];
                    }

                    // Consulta 2: Obtener información de la ubicación actual
                    $sql2 = "SELECT * FROM ubicacion WHERE id = " . $desde;
                    $proceso2 = mysqli_query($conexion, $sql2);

                    while ($row2 = mysqli_fetch_array($proceso2)) {
                        $bodegas_id = $row2["bodega_id"];
                    }

                    // Consulta 3: Obtener información de la bodega actual
                    $sql3 = "SELECT * FROM bodega WHERE id = " . $bodegas_id;
                    $proceso3 = mysqli_query($conexion, $sql3);

                    while ($row3 = mysqli_fetch_array($proceso3)) {
                        $bodegas_descripcion = $row3["descripcion"];
                    }

                    // Imprimir botones con enlaces dinámicos
                    echo '
                        <a href="columnas.php?id=' . $bodegas_id . '&descripcion=' . $bodegas_descripcion . '&especial=0">
                            <button type="button" class="btn btn-primary">' . $bodegas_descripcion . '</button>
                        </a>

                        <a href="columnas.php?id=1&descripcion=171&especial=2361">
                            <button type="button" class="btn btn-primary">171 ?</button>
                        </a>
                        <a href="columnas.php?id=2&descripcion=186&especial=2362">
                            <button type="button" class="btn btn-primary">186 ?</button>
                        </a>
                        <a href="columnas.php?id=3&descripcion=187&especial=2363">
                            <button type="button" class="btn btn-primary">187 ?</button>
                        </a>
                        <a href="columnas.php?id=4&descripcion=188&especial=2364">
                            <button type="button" class="btn btn-primary">188 ?</button>
                        </a>
                        <a href="columnas.php?id=4&descripcion=189&especial=2365">
                            <button type="button" class="btn btn-primary">189 ?</button>
                        </a>
                    ';
                    ?>
                </div>
            </div>
        </div>
    </nav>
</div>
