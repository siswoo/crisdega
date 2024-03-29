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
                         <a href="columnas.php?id=1&descripcion=ME&especial=0&titulo=171">
                            <button type="button" class="btn btn-primary">171</button>
                        </a>
                        <a href="columnas.php?id=1&descripcion=ME&especial=2349&titulo=171?">
                            <button type="button" class="btn btn-primary">171 ?</button>
                        </a>
                        <a href="columnas.php?id=2&descripcion=ME&especial=2350&titulo=186?">
                            <button type="button" class="btn btn-primary">186 ?</button>
                        </a>
                        <a href="columnas.php?id=3&descripcion=ME&especial=2351&titulo=187?">
                            <button type="button" class="btn btn-primary">187 ?</button>
                        </a>
                        <a href="columnas.php?id=4&descripcion=ME&especial=2352&titulo=188?">
                            <button type="button" class="btn btn-primary">188 ?</button>
                        </a>
                        <a href="columnas.php?id=4&descripcion=ME&especial=2353&titulo=189?">
                            <button type="button" class="btn btn-primary">189 ?</button>
                        </a>
                    ';
                    echo '
                        <a href="columnas.php?id=1&descripcion=ME&especial=2354">
                            <button type="button" class="btn btn-primary">BODEGA ME</button>
                        </a>
                        <a href="columnas.php?id=2&descripcion=MP&especial=2355">
                            <button type="button" class="btn btn-primary">BODEGA MP</button>
                        </a>
                        <a href="columnas.php?id=3&descripcion=PT&especial=2356">
                            <button type="button" class="btn btn-primary">BODEGA PT</button>
                        </a>
                        <a href="columnas.php?id=4&descripcion=PP&especial=2357">
                            <button type="button" class="btn btn-primary">BODEGA PP</button>
                        </a>
                    ';
                    ?>
                </div>
            </div>
        </div>
    </nav>
</div>
