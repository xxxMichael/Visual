<?php
// Iniciar la sesión si aún no se ha iniciado
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['logout'])) {
    // Eliminar todas las variables de sesión
    $_SESSION = array();
    session_destroy();
    
    header("Location: ./index.php");
    exit();
}
if (isset($_SESSION['email']) && isset($_SESSION['rol'])) {
    $email = $_SESSION['email'];
    $rol = $_SESSION['rol'];
    
    if ($rol === 'empleado') {
        ?>
        <div>
        <form method="POST" action="">
            <button type="submit" name="logout">Cerrar sesión</button>
        </form>
    </div>
    <?php

    } else {

        header("Location: ./index.php");
        exit();
    }
} else {
    header("Location: ./index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/Gestion.css">

    <title>Registro Jornada</title>
    <style>
        body {
    background-color: #2e302f;
}

        .header-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top:60px;
        
        }

        .header-container h2 {
            margin: 0 10px;
            /* Espacio entre los h2 */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: center;
        }

        /* Estilo específico para Hora Entrada */
        td#hora_entrada_mnn,
        td#hora_entrada_tarde {
            background-color: #c6e48b;
            /* Verde pastel */
        }

        /* Estilo específico para Hora Salida */
        td#hora_salida_mnn,
        td#hora_salida_tarde {
            background-color: #fca5a5;
            /* Rojo pastel */
        }

        button {
            display: block;
            width: 100px;
            padding: 10px;
            margin: 0 auto;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>

</head>

<body>

    <div class="header-container">
        <h2 id="nombreUser">Nombre del Empleado</h2>
        <h2><?php echo htmlspecialchars($email); ?></h2>
    </div>

    <table>
        <tr>
            <td colspan="5" id="fecha"></td>
        </tr>
        <tr>
            <td>Jornada</td>
            <td>Hora Ingreso</td>
            <td>Hora Salida</td>
            <td>Registro Ingreso</td>
            <td>Registro Salida</td>
        </tr>
        <tr id="matutina">
            <td>Matutina</td>
            <td id="hora_entrada_mnn">Hora Entrada</td>
            <td id="hora_salida_mnn">Hora Salida</td>
            <td id="registro_entrada_mnn"></td>
            <td id="registro_salida_mnn"></td>
        </tr>
        <tr id="vespertina">
            <td>Vespertina</td>
            <td id="hora_entrada_tarde">Hora Entrada</td>
            <td id="hora_salida_tarde">Hora Salida</td>
            <td id="registro_entrada_tarde"></td>
            <td id="registro_salida_tarde"></td>
        </tr>
    </table>
    <button id="btn_submit">Cargando...</button>

    <script>
        const btnSubmit = document.getElementById('btn_submit');

        // Función para actualizar la fecha y hora actual
        function updateDateTime() {
            const now = new Date();
            const options = { weekday: 'long', day: 'numeric', month: 'long' };
            const dateTimeString = now.toLocaleDateString('es-EC', options) + ', ' + now.toLocaleTimeString();
            document.getElementById('fecha').innerText = dateTimeString;
        }

        // Función para inicializar la página
        function initializePage() {
            updateDateTime();
            const cedulaEmpleado = '2222222222'; // Aquí debes obtener la cédula del empleado de alguna forma segura

            // Configuración del cuerpo de la solicitud POST
            const formData = new FormData();
            formData.append('cedula', cedulaEmpleado);

            // Opciones para la solicitud fetch
            const fetchOptions = {
                method: 'POST',
                body: formData
            };
            // Hacer una solicitud fetch para obtener las horas de entrada y salida
            fetch('models/obtener_horas.php', fetchOptions)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error al obtener las horas de entrada y salida');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log(data);
                    document.getElementById('nombreUser').textContent = data.nombre + " " + data.apellido;
                    document.getElementById('hora_entrada_mnn').textContent = data.hora_entrada_mnn;
                    document.getElementById('hora_salida_mnn').textContent = data.hora_salida_mnn;
                    document.getElementById('hora_entrada_tarde').textContent = data.hora_entrada_tarde;
                    document.getElementById('hora_salida_tarde').textContent = data.hora_salida_tarde;


                    document.getElementById('hora_entrada_mnn').dataset.value = data.hora_entrada_mnn;
                    document.getElementById('hora_salida_mnn').dataset.value = data.hora_salida_mnn;
                    document.getElementById('hora_entrada_tarde').dataset.value = data.hora_entrada_tarde;
                    document.getElementById('hora_salida_tarde').dataset.value = data.hora_salida_tarde;
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Manejar el error (por ejemplo, mostrar un mensaje al usuario)
                });
        }



        // Función para manejar el click en el botón de registro
        function registrarEntrada() {

        }
        function consultaAsistencia() {
            const cedula = '2222222222'; // Aquí debes obtener la cédula del empleado de alguna manera segura
            const fecha_actual = obtenerFechaActual(); // Función para obtener la fecha actual en el formato necesario (ej. '2024-06-16')
            console.log("fecha act cosnulta" + fecha_actual)
            const formData = new FormData();
            formData.append('cedula', cedula);
            formData.append('fecha_actual', fecha_actual);

            const fetchOptions = {
                method: 'POST',
                body: formData
            };

            fetch('models/consulta_asistencia.php', fetchOptions)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error al obtener las horas de entrada y salida');
                    }
                    return response.json();
                })
                .then(data => {
                    // Actualizar las horas de entrada y salida en la tabla según los datos recibidos
                    document.getElementById('registro_entrada_mnn').textContent = data.hora_entrada_mnn || 'N/A';
                    document.getElementById('registro_salida_mnn').textContent = data.hora_salida_mnn || 'N/A';
                    document.getElementById('registro_entrada_tarde').textContent = data.hora_entrada_tarde || 'N/A';
                    document.getElementById('registro_salida_tarde').textContent = data.hora_salida_tarde || 'N/A';

                    document.getElementById('registro_entrada_mnn').dataset.value = data.hora_entrada_mnn || 'N/A';
                    document.getElementById('registro_salida_mnn').dataset.value = data.hora_salida_mnn || 'N/A';
                    document.getElementById('registro_entrada_tarde').dataset.value = data.hora_entrada_tarde || 'N/A';
                    document.getElementById('registro_salida_tarde').dataset.value = data.hora_salida_tarde || 'N/A';
                    setTimeout(() => {
                        comportamiento();
                    }, 500);
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Manejar el error (por ejemplo, mostrar un mensaje al usuario)
                });

        }




        // Función para obtener la fecha actual en el formato 'YYYY-MM-DD'
        function obtenerFechaActual() {
            const now = new Date();
            const year = now.getFullYear();
            let month = now.getMonth() + 1;
            let day = now.getDate();

            // Ajustar el formato a 'YYYY-MM-DD'
            month = month < 10 ? '0' + month : month;
            day = day < 10 ? '0' + day : day;

            return `${year}-${month}-${day}`;
        }
        initializePage();
        consultaAsistencia();

        // Actualización continua de la fecha y hora
        setInterval(updateDateTime, 1000);


        function comportamiento() {
            // Obtener los valores de los registros y horas de entrada y salida de la jornada matutina
            const entradaMnnValue = document.getElementById('registro_entrada_mnn').dataset.value;
            const salidaMnnValue = document.getElementById('registro_salida_mnn').dataset.value;
            const horaEntradaMnnValue = document.getElementById('hora_entrada_mnn').dataset.value;
            const horaSalidaMnnValue = document.getElementById('hora_salida_mnn').dataset.value;

            // Obtener los valores de los registros y horas de entrada y salida de la jornada vespertina
            const entradaTardeValue = document.getElementById('registro_entrada_tarde').dataset.value;
            const salidaTardeValue = document.getElementById('registro_salida_tarde').dataset.value;
            const horaEntradaTardeValue = document.getElementById('hora_entrada_tarde').dataset.value;
            const horaSalidaTardeValue = document.getElementById('hora_salida_tarde').dataset.value;
            console.log(horaEntradaTardeValue);
            // Obtener la hora actual
            const horaActual = new Date();
            console.log(horaActual);
            const horaActualStr = horaActual.toLocaleTimeString('es-EC', { hour12: false });

            // Convertir la hora actual a formato HH:mm para comparaciones
            const horaActualHHMM = pad(horaActual.getHours()) + ':' + pad(horaActual.getMinutes());
            let horaEntradaMnnDate = new Date('2000-01-01T' + horaEntradaMnnValue); // Utilizamos una fecha ficticia para facilitar la manipulación
            horaEntradaMnnDate.setMinutes(horaEntradaMnnDate.getMinutes() - 15);

            // Formatear la nueva hora en formato HH:mm
            const horaMenos15Minutos = pad(horaEntradaMnnDate.getHours()) + ':' + pad(horaEntradaMnnDate.getMinutes());
            let horaSalidaMnnDate = new Date('2000-01-01T' + horaSalidaMnnValue); // Utilizamos una fecha ficticia para facilitar la manipulación
            horaSalidaMnnDate.setMinutes(horaSalidaMnnDate.getMinutes() + 10);
            const horaSalidaMas10Minutos = pad(horaSalidaMnnDate.getHours()) + ':' + pad(horaSalidaMnnDate.getMinutes());

            // Función para añadir ceros a la izquierda si es necesario
            function pad(number) {
                if (number < 10) {
                    return '0' + number;
                }
                return number.toString();
            }

            let horaEntradaTardeDate = new Date('2000-01-01T' + horaEntradaTardeValue); // Utilizamos una fecha ficticia para facilitar la manipulación
            horaEntradaTardeDate.setMinutes(horaEntradaTardeDate.getMinutes() - 15);
            // Formatear la nueva hora en formato HH:mm
            const horaEntradaTardeMenos15Minutos = pad(horaEntradaTardeDate.getHours()) + ':' + pad(horaEntradaTardeDate.getMinutes());
            console.log(horaEntradaTardeMenos15Minutos);

            let horaSalidaTardeDate = new Date('2000-01-01T' + horaSalidaTardeValue);
            horaSalidaTardeDate.setMinutes(horaSalidaTardeDate.getMinutes() + 10);

            // Formatear la nueva hora en formato HH:mm
            const horaSalidaTardeMas10Minutos = pad(horaSalidaTardeDate.getHours()) + ':' + pad(horaSalidaTardeDate.getMinutes());
            console.log(horaActualStr < horaSalidaMas10Minutos);


            // Verificar si es horario de la mañana (7 am - 1 pm)
            if (horaActual.getHours() >= 6 && horaActual.getHours() <= 13 && horaActualStr < horaSalidaMas10Minutos) { // 14 representa las 2 pm en formato de 24 horas
                console.log();
                if (entradaMnnValue === 'N/A' && horaActualStr >= horaMenos15Minutos && horaActualStr < horaSalidaMnnValue) {
                    console.log(entradaMnnValue + " " + horaActualStr + " " + horaMenos15Minutos + " " + horaSalidaMnnValue + " ");
                    document.getElementById('btn_submit').textContent = 'Registrar entrada';
                    document.getElementById('btn_submit').value = "EntradaManana";

                    return;
                } else if (salidaMnnValue === 'N/A' && horaActualHHMM < horaSalidaMas10Minutos) { /// } else if (salidaTardeValue === 'N/A' &&  horaActualHHMM < horaSalidaTardeMas10Minutos && horaActualHHMM>=horaSalidaTardeValue) {
                    console.log(salidaMnnValue + " " + horaActualHHMM + " " + horaEntradaMnnValue + " " + horaSalidaMas10Minutos + " ");

                    document.getElementById('btn_submit').textContent = 'Registrar salida';
                    document.getElementById('btn_submit').value = "SalidaManana";

                    return;
                } else {
                    document.getElementById('btn_submit').disabled = true;
                    document.getElementById('btn_submit').textContent = 'Acción no disponible';
                    return;
                }

            } else if (horaActual.getHours() >= 13 && horaActual.getHours() < 21) { // 21 representa las 9 pm en formato de 24 horas
                if (entradaTardeValue === 'N/A' && horaActualHHMM >= horaEntradaTardeMenos15Minutos && horaActualHHMM < horaSalidaTardeValue) {
                    console.log("dentro de reg entrad");

                    document.getElementById('btn_submit').textContent = 'Registrar entrada';
                    document.getElementById('btn_submit').value = "EntradaTarde";
                    return;
                } else if (salidaTardeValue === 'N/A' && horaActualHHMM < horaSalidaTardeMas10Minutos && horaActualHHMM >= horaSalidaTardeValue) {  //  si es necesario bloquear el boton de salida hasta que sea la hora         } else if (salidaTardeValue === 'N/A' &&  horaActualHHMM < horaSalidaTardeMas10Minutos && horaActualHHMM>=horaSalidaTardeValue) {

                    console.log(salidaTardeValue + " asxx" + "  as" + horaActualHHMM + "  zz" + horaEntradaTardeValue);

                    document.getElementById('btn_submit').textContent = 'Registrar salida';
                    document.getElementById('btn_submit').value = "SalidaTarde";

                    return;
                } else {
                    document.getElementById('btn_submit').disabled = true;
                    document.getElementById('btn_submit').textContent = 'Acción no disponible';
                    console.log(salidaTardeValue + "  " + horaActualHHMM + "   " + horaSalidaTardeMas10Minutos + "  " + horaSalidaTardeValue);

                    return;
                }

            } else {
                document.getElementById('btn_submit').disabled = true;
                document.getElementById('btn_submit').textContent = 'Fuera de horario';
                return;
            }
        }




        // Función para enviar el registro al servidor
        function enviarRegistro(tipoRegistro, hora, cedulaEmpleado) {
            const urlServidor = 'models/guardar_asistencia.php';

            // Datos a enviar
            const datos = {
                tipoRegistro: tipoRegistro,
                hora: hora,
                cedulaEmpleado: cedulaEmpleado
            };

            // Realizar la solicitud fetch
            fetch(urlServidor, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(datos)
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(response.message);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Registro enviado correctamente:', data);
                    location.reload();

                })
                .catch(error => {
                    console.error('Error al enviar el registro:', error);
                    // Manejo de errores
                });
        }
        function obtenerHoraActual() {
            const ahora = new Date();
            const horas = ahora.getHours().toString().padStart(2, '0');
            const minutos = ahora.getMinutes().toString().padStart(2, '0');
            const segundos = ahora.getSeconds().toString().padStart(2, '0'); //

            const horaActual = `${horas}:${minutos}:${segundos}`;
            return horaActual;
        }
        // Agregar evento click al botón
        btnSubmit.addEventListener('click', function () {
            const valorBoton = btnSubmit.value;
            const hora = obtenerHoraActual();
            const cedulaEmpleado = '2222222222';
            const horac = obtenerHoraActual();
            const horaEntradaMnnValue = document.getElementById('hora_entrada_mnn').dataset.value;
            const entradaMnnValue = document.getElementById('registro_entrada_mnn').dataset.value;
            const salidaMnnValue = document.getElementById('registro_salida_mnn').dataset.value;
            const horaEntradatardeValue = document.getElementById('hora_entrada_tarde').dataset.value;
            const entradaTardeValue = document.getElementById('registro_entrada_tarde').dataset.value;

            switch (valorBoton) {
                case 'EntradaManana':
                    if (horac > horaEntradaMnnValue) {
                        const multa = calcularMulta(horac, horaEntradaMnnValue);
                        enviarmulta(multa, "Atraso", cedulaEmpleado);
                    }
                    enviarRegistro('entradaManana', hora, cedulaEmpleado);
                    break;
                case 'SalidaManana':
                    if (entradaMnnValue === 'N/A') {
                        enviarmulta(64, "Inasistencia", cedulaEmpleado);
                    }
                    enviarRegistro('salidaManana', hora, cedulaEmpleado);
                    break;
                case 'EntradaTarde':

                    if (horac > horaEntradatardeValue) {
                        const multa = calcularMulta(horac, horaEntradatardeValue);
                        enviarmulta(multa, "Atraso", cedulaEmpleado);
                    }
                    if (salidaMnnValue === 'N/A') {
                        enviarmulta(64, "Inasistencia", cedulaEmpleado);
                    }
                    enviarRegistro('entradaTarde', hora, cedulaEmpleado);
                    break;
                case 'SalidaTarde':
                    if (entradaTardeValue === 'N/A') {
                        enviarmulta(64, "Inasistencia", cedulaEmpleado);
                    }
                    enviarRegistro('salidaTarde', hora, cedulaEmpleado);
                    break;
                default:
                    console.log('Acción no definida para este botón.', valorBoton);
                    break;
            }
        });
        function convertirASegundos(hora) {
            const [h, m, s] = hora.split(':').map(Number);
            return h * 3600 + m * 60 + s;
        }

        function convertirAMinutos(segundos) {
            return Math.floor(segundos / 60);
        }
        function calcularMulta(hora1, hora2) {

            const segundos1 = convertirASegundos(hora1);
            const segundos2 = convertirASegundos(hora2);
            const diferenciaSegundos = segundos1 - segundos2;

            // Asegúrate de que la diferencia no sea negativa
            const diferenciaPositivaSegundos = Math.max(0, diferenciaSegundos);

            // Convertir la diferencia en minutos
            const diferenciaMinutos = convertirAMinutos(diferenciaPositivaSegundos);

            // Calcular la multa
            const multa = diferenciaMinutos * 0.25;
            return multa;
        }
        function enviarmulta(valor, tipomulta, cedulaEmpleado) {
            const url = 'models/multa.php';
            const data = {
                valor: valor,
                tipomulta: tipomulta,
                cedulaEmpleado: cedulaEmpleado
            };

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
                .then(response => response.json())
                .then(data => {
                    console.log('Multa enviada correctamente:', data);
                })
                .catch(error => {
                    console.error('Error al enviar la multa:', error);
                });
        }

    </script>   
</body>

</html>