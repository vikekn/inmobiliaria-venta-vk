<?php

namespace Controllers;

use MVC\Router;
use Model\Propiedad;
use Model\Vendedor;
use Intervention\Image\ImageManagerStatic as Image;

class PropiedadController
{

    public static function index(Router $router)
    {
        $propiedades = Propiedad::all();
        $vendedores = Vendedor::all();
        $resultado = $_GET['resultado'] ?? null;
        $router->render('propiedades/admin', [
            'propiedades' => $propiedades,
            'vendedores' => $vendedores,
            'resultado' => $resultado
        ]);
    }

    public static function crear(Router $router)
    {
        $propiedad = new Propiedad();
        $vendedores = Vendedor::all();
        //arreglo con mensaje de errores
        $errores = Propiedad::getErrores();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //Crea una nueva Instancia
            $propiedad = new Propiedad($_POST['propiedad']);

            /** SUBIDA DE ARCHIVOS */
            // Generar un nombre único
            $nombreImagen = md5(uniqid(rand(), true)) . ".jpg";

            //Setear imagen
            // Realiza un resize a la imagen
            if ($_FILES['propiedad']['tmp_name']['imagen']) {
                $image = Image::make($_FILES['propiedad']['tmp_name']['imagen'])->fit(800, 600);
                $propiedad->setImagen($nombreImagen);
            }


            //Validar
            $errores = $propiedad->validar();

            // Revisar que el array de errores este vacio
            if (empty($errores)) {


                //Crear la carpeta para subir imagenes
                if (!is_dir(CARPETA_IMAGENES)) {
                    mkdir(CARPETA_IMAGENES);
                }
                //Guarda la imagen en el servidor
                $image->save(CARPETA_IMAGENES . $nombreImagen);

                //Guarda en la BD
                $propiedad->guardar();
            }
        }

        $router->render('propiedades/crear', [
            'propiedad' => $propiedad,
            'vendedores' => $vendedores,
            'errores' => $errores
        ]);
    }

    public static function actualizar(Router $router)
    {
        $id = validarORedireccinar('/admin');
        //Obtener datos de la propiedad
        $propiedad = Propiedad::find($id);
        //Consulta para obtener todos los vendedores
        $vendedores = Vendedor::all();
        //arreglo con mensaje de errores
        $errores = Propiedad::getErrores();

        //ejecutar el código despues de que el usuario envia el formulario
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //Asignar los atributos
            $args = $_POST['propiedad'];
            $propiedad->sincronizar($args);
            //Validar
            $errores = $propiedad->validar();
            /** SUBIDA DE ARCHIVOS */
            // Generar un nombre único
            $nombreImagen = md5(uniqid(rand(), true)) . ".jpg";

            //Setear imagen
            // Realiza un resize a la imagen
            if ($_FILES['propiedad']['tmp_name']['imagen']) {
                $image = Image::make($_FILES['propiedad']['tmp_name']['imagen'])->fit(800, 600);
                $propiedad->setImagen($nombreImagen);
            }

            // Revisar que el array de errores este vacio
            if (empty($errores)) {

                //Guarda la imagen en el servidor
                if ($_FILES['propiedad']['tmp_name']['imagen']) {
                    $image->save(CARPETA_IMAGENES . $nombreImagen);
                }

                //Guarda en la BD
                $propiedad->guardar();
            }
        }

        $router->render('propiedades/actualizar', [
            'propiedad' => $propiedad,
            'vendedores' => $vendedores,
            'errores' => $errores
        ]);
    }

    public static function eliminar()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //Validar ID
            $id = $_POST['id'];
            $id = filter_var($id, FILTER_VALIDATE_INT);

            if ($id) {
                $tipo = $_POST['tipo'];

                if (validarTipoContenido($tipo)) {
                    //compara que vamos a eliminar
                        $propiedad = Propiedad::find($id);
                        $propiedad->eliminar();
                }
            }
        }
    }
}
