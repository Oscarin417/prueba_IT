<?php
require_once '../clases/User.php';

try 
{
    // Instancia de la clase User
    $u = new User();

    // Sanitización de datos recibidos de POST
    $nombres = filter_input(INPUT_POST, 'nombres', FILTER_SANITIZE_SPECIAL_CHARS);
    $apellidos = filter_input(INPUT_POST, 'apellidos', FILTER_SANITIZE_SPECIAL_CHARS);
    $correo = filter_input(INPUT_POST, 'correo', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // Validación para asegurarse de que los campos requeridos están presentes
    if (!$nombres || !$apellidos || !$correo || !$password) {
        throw new Exception("Todos los campos son obligatorios.");
    }

    $u->setNombres($nombres);
    $u->setApellidos($apellidos);
    $u->setCorreo($correo);
    $u->setPassword($password);

    $u->store();

    // Redireccionamiento
    header("Location: ./listar.php");
    exit();
} 
catch (Exception $e) 
{
    //Mensaje de error
    echo "Error: " . $e->getMessage();
    exit();
}
?>
