<?php
require_once '../clases/User.php';

try
{
    //Instancia de la clase usuario
    $u = new User();

    //Sanitisacion de datos
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

    $u->setId($id);

    $u->delete();

    //Redireaccionamiento
    header('Location: ./listar.php');
    exit();
}
catch(Exception $e)
{
    //Mensaje de error
    echo "Error: " . $e->getMessage();
    exit();
}
?>