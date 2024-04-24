<?php

namespace Controllers;

use MVC\Router;
use Model\Propiedad;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\PHPMailer;

class PaginasController
{
    public static function index(Router $router)
    {
        $propiedades = Propiedad::get(3);
        $inicio = true;

        $router->render('paginas/index', [

            'propiedades' => $propiedades,
            'inicio' => $inicio
        ]);
    }

    public static function nosotros(Router $router)
    {
        $router->render('paginas/nosotros');
    }

    public static function propiedades(Router $router)
    {
        $propiedades = Propiedad::all();

        $router->render('paginas/propiedades', [
            'propiedades' => $propiedades
        ]);
    }

    public static function propiedad(Router $router)
    {
        $id = validarORedireccinar('/propiedades');
        $propiedad = Propiedad::find($id);

        $router->render('paginas/propiedad', [
            'propiedad' => $propiedad
        ]);
    }

    public static function blog(Router $router)
    {
        $router->render('paginas/blog', []);
    }

    public static function entrada(Router $router)
    {
        $router->render('paginas/entrada', []);
    }

    public static function contacto(Router $router)
    {
        $mensaje = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $respuestas = $_POST['contacto'];
            //Crear nueva instancia
            $mail = new PHPMailer();

            //Configurar ese SMTP

            $mail->isSMTP();
            $mail->Host = $_ENV['EMAIL_HOST'];
            $mail->SMTPAuth = true;
            $mail->Port = $_ENV['EMAIL_PORT'];
            $mail->Username = $_ENV['EMAIL_USER'];
            $mail->Password = $_ENV['EMAIL_PASS'];

            //Configurar contenido del email
            $mail->setFrom('admin@bienesraices.com'); //wuien envia el mail
            $mail->addAddress('admin@bienesraices.com','BienesRaices.com');//a quien le llega el correo
            $mail->Subject = 'Tienes un nuevo Mensaje';

            //habilitar HTML
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';         

            //Definir el contenido
            $contenido  = '<html>';
            $contenido .= '<p>Tienes un nuevo Mensaje</p>';
            $contenido .= '<p>Nombre: ' . $respuestas['nombre'] . ' </p>';
            
            //ENVIAR DE FORMA CONDICIONAL ALGUNOS CAMPOS de email y telefono
            if($respuestas['contacto']=== 'telefono'){
                $contenido .= '<p>Eligió ser contactado por telefono: </p>';
                $contenido .= '<p>Telefono: ' . $respuestas['telefono'] . ' </p>';
                $contenido .= '<p>Fecha de Contacto: ' . $respuestas['fecha'] . ' </p>';
                $contenido .= '<p>Hora de Contacto: ' . $respuestas['hora'] . ' </p>';
            }else{
                $contenido .= '<p>Eligió ser contactado por email: </p>';
                $contenido .= '<p>Email: ' . $respuestas['email'] . ' </p>';
            }
            
            $contenido .= '<p>Mensaje: ' . $respuestas['mensaje'] . ' </p>';
            $contenido .= '<p>Venta o Compra: ' . $respuestas['tipo'] . ' </p>';
            $contenido .= '<p>Precio o Presupuesto: $' . $respuestas['precio'] . ' </p>';

            $contenido .= '</html>';

            $mail->Body= $contenido;
            $mail->AltBody = 'Esto es texto alternativo sin html';
            //Enviar el email
            if($mail->send()){
                $mensaje = "Mensaje enviado correctamente";
            }else{
                $mensaje = "El mensaje no se pudo enviar";
            }
        
        }

        $router->render('paginas/contacto', [
            'mensaje' => $mensaje
        ]);
    }
}
