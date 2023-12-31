<h1 class="nombre-pagina">Panel de Administración</h1>

<?php include_once __DIR__ . '/../templates/barra.php' ?>

<h2>Buscar Citas</h2>

<div class="busqueda">
    <form class="formulario">
        <div class="campo">
            <label for="fecha">Fecha</label>
            <input 
                type="date"
                name="fecha"
                id="fecha"
                value="<?php echo $fecha ?>"
            />
        </div>
    </form>
</div>

<?php
    if ( count($citas) == 0 ) :
        echo '<h2>No hay citas en esta fecha</h2>';
    endif
?>

<div class="citas-admin">
    <ul class="citas">
        <?php 
        $idCita = -1;
        foreach( $citas as $key => $cita): 
            $actual = $cita->id;    // ID de la cita actual.
            $proximo = $citas[$key + 1]->id;    // ID de la sig. cita.

            if ( $idCita != $cita->id): // Si continua siendo la misma cita
                $total = 0;             // Se reinicia el total a pagar de la cita.
        ?>
        <li>
                <p>ID: <span><?php echo $cita->id ?></span></p>
                <p>Hora: <span><?php echo $cita->hora ?></span></p>
                <p>Cliente: <span><?php echo $cita->cliente ?></span></p>
                <p>Email: <span><?php echo $cita->email ?></span></p>
                <p>Teléfono: <span><?php echo $cita->telefono ?></span></p>
                
                <h3>Servicios</h3>
        <?php
                $idCita = $cita->id;    // Se actualiza el valor del ID.
            endif;
        ?>
                <p class="servicio"><?php echo $cita->servicio . ' ' . $cita->precio ?></p>
        <?php
                $total += $cita->precio;

                if ($actual !== $proximo):  // Si terminan de listar los servicios de la cita.
        ?>
                    <p>Total: $ <span> <?php echo $total ?></span></p>

                    <form action="/api/eliminar" method="POST">
                        <input type="hidden" name="id" value="<?php echo $cita->id ?>">
                        <input type="submit" class="boton-eliminar" value="Eliminar">
                    </form>
        <?php
                endif;
        endforeach; 
        ?>
        </li>
    </ul>
</div>
<!-- 
<?php
    $script = "<script src='build/js/buscador.js'></script>"
?> -->