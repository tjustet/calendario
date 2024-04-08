<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Calendario</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 5px;
            text-align: center;
        }
    </style>
</head>
<body>
    <?php
    include ("conn.php");
    // Función para obtener el nombre del mes
    function nombreMes($mes) {
        $nombresMeses = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];
        return $nombresMeses[$mes];
    }

    // Obtener el mes y año actual
    $mesActual = isset($_GET['mes']) ? $_GET['mes'] : date('n');
    $anioActual = isset($_GET['anio']) ? $_GET['anio'] : date('Y');

    // Obtener el mes y año anterior
    $mesAnterior = $mesActual - 1;
    $anioAnterior = $anioActual;
    if ($mesAnterior == 0) {
        $mesAnterior = 12;
        $anioAnterior--;
    }

    // Obtener el mes y año siguiente
    $mesSiguiente = $mesActual + 1;
    $anioSiguiente = $anioActual;
    if ($mesSiguiente == 13) {
        $mesSiguiente = 1;
        $anioSiguiente++;
    }
    ?>
    <h1 style="font-size:24px"><?php echo nombreMes($mesActual) . ' ' . $anioActual; ?></h1>
    <?php
        $array = [];
        $select = "SELECT * FROM `categoria`";
        $resultado = mysqli_query($conn, $select);
        while ($fila = mysqli_fetch_assoc($resultado)) {
            $array[$fila['categoria']] = $fila['color'];
            echo "<h3 style='color: $fila[color]; font-size:20px'>$fila[categoria]</h3>";
        }
    ?>
    <form action="" method="get">
        <button type="submit" name="mes" value="<?php echo $mesAnterior; ?>"><</button>
        <button type="submit" name="mes" value="<?php echo $mesSiguiente; ?>">></button>
        <input type="hidden" name="anio" value="<?php echo $anioActual; ?>">
    </form>

    <table>
        <tr>
            <th>Lun</th><th>Mar</th><th>Mié</th><th>Jue</th><th>Vie</th><th>Sáb</th><th>Dom</th>
        </tr>

        <?php
        // Obtener el día de la semana en el que empieza el mes
        $primerDiaMes = mktime(0, 0, 0, $mesActual, 1, $anioActual);
        $diaSemana = date('N', $primerDiaMes);

        // Obtener el número de días del mes
        $numDiasMes = cal_days_in_month(CAL_GREGORIAN, $mesActual, $anioActual);

        // Contador para los días
        $contador = 1;

        // Ciclo para las filas del calendario
        for ($i = 1; $i <= 6; $i++) {
            echo '<tr>';
            // Ciclo para las celdas de la fila
            for ($j = 1; $j <= 7; $j++) {
                if ($contador <= $numDiasMes) {
                    // Si es el primer día del mes, determinar en qué columna empieza
                    if ($i == 1 && $j < $diaSemana) {
                        echo '<td></td>';
                    } else {
                        $select = "SELECT * FROM `tareas` WHERE `dia` = $contador and `mes` = $mesActual ORDER BY `hora`";
                        $resultado = mysqli_query($conn, $select);
                        echo '<td>' . $contador . '</br>';
                        while ($fila = mysqli_fetch_assoc($resultado)) {
                            $hora = substr($fila['hora'], 0,5);
                            $color = $array[$fila['categoria']];
                            $id = $fila['ID'];
                            echo "<a style='color: $color;' data-bs-toggle='modal' data-bs-target='#exampleModal$id'>$hora - $fila[tarea]</a></br>";
                        }
                        echo '</td>';
                        $contador++;
                    }
                } else {
                    echo '<td></td>';
                }
            }
            echo '</tr>';
        }
        ?>
    </table>

        <div>
            <form action="conncategoria.php" method="POST">
                <h1 style="font-size:24px">Categorias</h1>
                <input type="text" placeholder="Nombre" name="nombrecategoria" id="nombrecategoria">
                <select name="colorCategoria" id="colorCategoria">
                    <option value="blue">azul</option>
                    <option value="brown">marrón</option>
                    <option value="green">verde</option>
                    <option value="orange">naranja</option>
                    <option value="pink">rosa</option>
                    <option value="violet">púrpura</option>
                    <option value="red">rojo</option>
                    <option value="yellow">amarillo</option>
                </select>
                <button>Agregar Categorias</button>
            </form>
        </div>
        
        <div>
            <form action="conntarea.php" method="POST">
                <h1 style="font-size:24px">Tareas</h1>
                <label for="fecha">Fecha:</label>
                <input type="date" id="fecha" name="fecha">
                <label for="hora">Hora:</label>
                <input type="time" id="hora" name="hora">
                <label for="tarea">Tarea:</label>
                <input type="text" id="tarea" name="tarea">

                <select name="categoria" id="categoria">
                    <?php
                    $select = "SELECT * FROM categoria";
                    $result = mysqli_query($conn, $select);
                    while ($fila = mysqli_fetch_assoc($result)){
                        $categoria = $fila["categoria"];
                        echo "<option value=$categoria>$categoria</option>";
                    }
                    ?>
                </select>
                <label for="descripcion">Descripción:</label>
                <input type="text" id="descripcion" name="descripcion">
                <button>Agregar Tarea</button>

            </form>
        </div>

<?php

$select = "SELECT * FROM `tareas` ORDER BY `hora`";
$resultado = mysqli_query($conn, $select);
while ($fila = mysqli_fetch_assoc($resultado)) {
    $hora = substr($fila['hora'], 0,5);
    $id = $fila['ID'];
    $descripcion = $fila['descripcion'];
    echo "
    <div class='modal fade' id='exampleModal$id' tabindex='-1' aria-labelledby='exampleModalLabel' aria-hidden='true'>
    <div class='modal-dialog'>
        <div class='modal-content'>
        <div class='modal-header'>
            <h1 class='modal-title fs-5' id='exampleModalLabel'>Modal title</h1>
            <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
        </div>
        <div class='modal-body'>
            $descripcion
        </div>
        <div class='modal-footer'>
            
            <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
        </div>
        </div>
    </div>
    </div>

    ";
}

?>
<!-- Modal -->
        
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>
