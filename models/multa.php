<?php
date_default_timezone_set('America/Guayaquil');

require_once 'conexion.php'; 

$conexion = new conexion();
$conn = $conexion->conectar();

if ($conn === false) {
    die(json_encode(['error' => 'Error al conectar con la base de datos']));
}

// Leer el cuerpo de la solicitud y decodificar el JSON
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Obtener los parámetros del JSON decodificado
$valor = $data['valor'] ?? null;
$tipomulta = $data['tipomulta'] ?? null;
$cedulaEmpleado = $data['cedulaEmpleado'] ?? null;

// Asegúrate de que los parámetros no estén vacíos
if (!empty($valor) && !empty($tipomulta) && !empty($cedulaEmpleado)) {
    $fechaActual = date('Y-m-d');

    // Consultar la tabla asistencia para obtener el id_asis correspondiente
    $sqlAsistencia = "SELECT id_asis FROM asistencia WHERE fecha = '$fechaActual' AND ced_emp_per = '$cedulaEmpleado'";
    $resultAsistencia = $conn->query($sqlAsistencia);

    if ($resultAsistencia === false) {
        die(json_encode(['error' => 'Error al ejecutar la consulta de asistencia: ' . $conn->error]));
    }

    if ($resultAsistencia->num_rows > 0) {
        $rowAsistencia = $resultAsistencia->fetch_assoc();
        $idAsis = $rowAsistencia['id_asis'];

        // Insertar la multa en la tabla multas
        $queryMulta = "INSERT INTO multas (id_asis_per, tipo_multa, multa) VALUES ('$idAsis', '$tipomulta', '$valor')";
        if ($conn->query($queryMulta) === true) {
            echo json_encode(['message' => 'Multa registrada correctamente']);
        } else {
            echo json_encode(['error' => 'Error al registrar la multa: ' . $conn->error]);
        }
    } else {
        echo json_encode([
            'error' => 'No se encontró el registro de asistencia',
            'valor' => $valor,
            'tipomulta' => $tipomulta,
            'cedulaEmpleado' => $cedulaEmpleado,
            'fecha'=>$fechaActual
        ]);
    }
} else {
    echo json_encode(['error' => 'Faltan parámetros']);
}
?>
