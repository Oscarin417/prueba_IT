<?php
include_once "Conexion.php";
class User
{
    private $id;
    private $nombres;
    private $apellidos;
    private $correo;
    private $password;
    private $token;

    const TABLA = "users";

    public function __construct(
        $id = null,
        $nombres = null,
        $apellidos = null,
        $correo = null,
        $password = null,
        $token = null
    )
    {
        $this->id = $id;
        $this->nombres = $nombres;
        $this->apellidos = $apellidos;
        $this->correo = $correo;
        $this->password = $password;
        $this->token = $token;
    }//Fin constructor

    //getters
    public function getId()
    {
        return $this->id;
    }
    public function getNombres()
    {
        return $this->nombres;
    }
    public function getApellidos()
    {
        return $this->apellidos;
    }
    public function getCorrero()
    {
        return $this->correo;
    }
    public function getPassword()
    {
        return $this->password;
    }
    public function getToken()
    {
        return $this->token;
    }

    //setters
    public function setId($id)
    {
        $this->id = $id;
    }
    public function setNombres($nombres)
    {
        $this->nombres = $nombres;
    }
    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;
    }
    public function setCorreo($correo)
    {
        $this->correo = $correo;
    }
    public function setPassword($password)
    {
        $this->password = $password;
    }
    public function setToken($token)
    {
        $this->token = $token;
    }

    //Validaciones
    private function validateNombres($nombres)
    {
        return !empty($nombres) && strlen($nombres) <= 50;
    }

    private function validateApellidos($apellidos)
    {
        return !empty($apellidos) && strlen($apellidos) <= 50;
    }

    private function validateCorreo($correo)
    {
        return filter_var($correo, FILTER_VALIDATE_EMAIL) && strlen($correo) <= 100;
    }

    private function validatePassword($password)
    {
        return strlen($password) >= 6; 
    }

    private function validateData()
    {
        if (!$this->validateNombres($this->nombres)) 
        {
            throw new Exception("El nombre no es válido o excede los 50 caracteres.");
        }
        if (!$this->validateApellidos($this->apellidos)) 
        {
            throw new Exception("El apellido no es válido o excede los 50 caracteres.");
        }
        if (!$this->validateCorreo($this->correo)) 
        {
            throw new Exception("El correo no es válido o excede los 100 caracteres.");
        }
        if (!$this->validatePassword($this->password)) 
        {
            throw new Exception("La contraseña debe tener al menos 6 caracteres.");
        }
    }

    private function validateUpdate()
    {
        if (!$this->validateNombres($this->nombres)) 
        {
            throw new Exception("El nombre no es válido o excede los 50 caracteres.");
        }
        if (!$this->validateApellidos($this->apellidos)) 
        {
            throw new Exception("El apellido no es válido o excede los 50 caracteres.");
        }
        if (!$this->validateCorreo($this->correo)) 
        {
           throw new Exception("El correo no es válido o excede los 100 caracteres.");
        }
    }

    //Metodos propios
    //Listar
    public function getAll()
    {
        $sql = "SELECT * FROM " . self::TABLA;
        $con = new Conexion();
        $query = $con->prepare($sql);
        $query->execute();
        $resultados = $query->fetchAll();
        $con = null;

        return $resultados;
    }

    //Obtener un empleado
    public function getOne()
    {
        $sql = "SELECT * FROM " . self::TABLA . " WHERE id = :id";
        $con = new Conexion();
        $query = $con->prepare($sql);
        $query->bindParam(":id", $this->id);
        $query->execute();
        $resultados = $query->fetchAll();
        $con = null;

        return $resultados;
    }

    //Api
    public function showApi()
    {
        $sql = "SELECT id, nombres, apellidos, correo FROM " . self::TABLA;
        $con = new Conexion();
        $query = $con->prepare($sql);
        $query->execute();
        $resultados = $query->fetchAll(PDO::FETCH_ASSOC);
        $con = null;

        return $resultados;
    }

    //Crear
    public function store()
    {
        $this->validateData();
        $this->token = bin2hex(random_bytes(16)); //Genera token unico para el usuario
        $sql = "INSERT INTO " . self::TABLA .
        " VALUES(null, :nombres, :apellidos, :correo, :password, :token)";
        $con = new Conexion();
        $query = $con->prepare($sql);
        $query->bindParam(":nombres", $this->nombres);
        $query->bindParam(":apellidos", $this->apellidos);
        $query->bindParam(":correo", $this->correo);
        $query->bindParam(":password", password_hash($this->password, PASSWORD_BCRYPT));
        $query->bindParam(":token", $this->token);
        $query->execute();
        $con = null;
    }

    //Editar
    public function update()
    {
        $this->validateUpdate();
        $sql = "UPDATE " . self::TABLA .
        " SET nombres=:nombres, apellidos=:apellidos, correo=:correo,
        password=:password WHERE id=:id";
        $con = new Conexion();
        $query = $con->prepare($sql);
        $query->bindParam(":id", $this->id);
        $query->bindParam(":nombres", $this->nombres);
        $query->bindParam(":apellidos", $this->apellidos);
        $query->bindParam(":correo", $this->correo);
        $query->bindParam(":password", password_hash($this->password, PASSWORD_BCRYPT));
        $query->execute();
        $con = null;
    }

    //Eliminar
    public function delete()
    {
        $sql = "DELETE FROM " . self::TABLA . " WHERE id=:id";
        $con = new Conexion();
        $query = $con->prepare($sql);
        $query->bindParam(":id", $this->id);
        $query->execute();
        $con = null;
    }
}
?>