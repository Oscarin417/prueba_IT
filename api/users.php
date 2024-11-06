<?php
header("Content-Type: application/json; charset=UTF-8");
include_once "../clases/User.php";

function authenticate($token)
{
    //verificar si el token existe
    $sql = "SELECT * FROM " . User::TABLA . " WHERE token = :token";
    $con = new Conexion();
    $query = $con->prepare($sql);
    $query->bindParam(':token', $token);
    $query->execute();
    $result = $query->fetch();
    $con = null;

    if($result)
    {
        return true;
    }
    else
    {
        return false;
    }
}

//obtener el metodo HTTP
$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents("php://input"), true);

//respuesta inicialisada
$response = array();

//verificar si se envio el token
$headers = apache_request_headers();
$token = isset($headers['Authorization']) ? $headers['Authorization'] : null;

// Verificar la autenticidad del token
if (!$token || !authenticate($token)) {
    $response['status'] = 'error';
    $response['data'] = 'Token de autenticación inválido o no proporcionado.';
    echo json_encode($response);
    exit;
}


switch($method)
{
    //obtener los datos del usuario
    case 'GET':
        try
        {
            $user = new User();
            $users = $user->showApi();
            if(!empty($users))
            {
                $response['status'] = 'success';
                $response['data'] = $users;
            }
            else
            {
                $response['status'] = 'error';
                $response['message'] = 'No se encontraron usuarios.';
            }
        }
        catch(Exception $e)
        {
            $response['status'] = 'error';
            $response['data'] = $e->getMessage();
        }
        break;
    case 'POST':
        if(isset($input['nombres'], $input['apellidos'], $input['correo'], $input['password']))
        {
            try
            {
                $newUser = new User
                (
                    null,
                    $input['nombres'],
                    $input['apellidos'],
                    $input['correo'],
                    $input['password']
                );
                $newUser->store();
                $response['status'] = 'success';
                $response['message'] = 'Usuario creado exitosamente.';
            }
            catch(Exception $e)
            {
                $response['status'] = 'error';
                $response['data'] = $e->getMessage();
            }
        }
        else
        {
            $response['status'] = 'error';
            $response['message'] = 'Datos incompletos para crear el usuario.';
        }
        break;
    case 'DELETE':
        if(isset($input['id']))
        {
            try
            {
                $userToDelete = new User($input['id']);
                if($userToDelete->getOne())
                {
                    $userToDelete->delete();
                    $response['status'] = 'success';
                    $response['message'] = 'Usuario eliminado exitosamente.';
                }
                else
                {
                    $response['status'] = 'error';
                    $response['message'] = 'No existe un usuario con este ID';
                }
            }
            catch(Exception $e)
            {
                $response['status'] = 'error';
                $response['message'] = $e->getMessage();
            }
        }
        else
        {
            $response['status'] = 'error';
            $response['message'] = 'No se proporcionó un ID para eliminar el usuario.';
        }
        break;
}
//devolver la respuesta en formato json
echo json_encode($response);
?>