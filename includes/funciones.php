<?php

define('TEMPLATES_URL', __DIR__. '/templates');
define('FUNCIONES_URL', __DIR__. 'funciones.php');
define('CARPETA_IMAGENES', $_SERVER['DOCUMENT_ROOT'] . '/imagenes/');

function incluirTemplate (string $nombre, bool $inicio = false){
    //include "./includes/templates/${nombre}.php";
    include TEMPLATES_URL .  "/{$nombre}.php";
}





function debuguear($variable){
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

//Escapa /sanitizar el HTML funcion s
function sanitizar($html) : string{
    $s = htmlspecialchars($html);
    return $s;
}

//validar tipo de contenido

function validarTipoContenido($tipo){
    $tipos = ['vendedor', 'propiedad'];

    return in_array($tipo, $tipos);
}

//Muestra los mensajes
function mostrarNotificacion($codigo){
    $mensaje = '';
    switch ($codigo) {
        case 1:
            $mensaje = 'Creado Correctamente';
            break;
                case 2: $mensaje = 'Actualizado Correctamente';
                    break;
                        case 3: $mensaje = 'Eliminado Correctamente';
                            break;
        default:
        $mensaje = false;  
        break;  
    }
    return $mensaje;
}

function validarORedireccinar(string $url){
    //Validar la URL por ID váildo
    $id = $_GET['id'];
    $id = filter_var($id, FILTER_VALIDATE_INT);

    if (!$id) {
        header("Location:{$url}");
    }
    return $id;
}
