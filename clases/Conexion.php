<?php
class Conexion extends PDO
{
    private $type = 'mysql'; //Tipo de base de datos
    private $host = 'localhost'; //Nombre del servidor
    private $data = 'prueba'; //Nombre de la base de datos
    private $user = 'root'; //Nombre del usuario de accesso al servidor
    private $pass = '1234'; //Contraseña del servidor

    public function __construct()
    {
        try
        {
            parent::__construct(
                $this->type . ':host=' . $this->host . ';dbname=' . $this->data,
                $this->user, $this->pass
            );
        }
        catch(PDOException $e)
        {
            echo 'Error: ' . $e->getMessage();
            exit;
        }//Fin catch
    }//Fin constructor
}//Fin class
?>