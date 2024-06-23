<?php
class conexion
{
    public function conectar()
    {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbName = "visualfinal";
        $conn = mysqli_connect($servername, $username, $password, $dbName);
        if (!$conn) {
            echo "Error en la conexion";
        } else {
            // echo "Conexion exitosa";
            return $conn;
        }
    }
}

?>