<?php
class EnlacesPaginas
{
    public static function enlacesPaginasModel($enlacesModel)
    {  //para cargar las paginas
        if ($enlacesModel == "empleados" || $enlacesModel == "servicios" || $enlacesModel == "contactanos"|| $enlacesModel == "GestionInOut") {
            $module = "views/interfaces/" . $enlacesModel . ".php";
        } else {
            $module = "views/interfaces/inicio.php";  //para cargar inicialmente a la ventana inicio
        }
        return $module;
    }

}

?>