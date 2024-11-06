<?php
require_once '../clases/User.php';
$usuario = new User();

$id = $_GET['id'];
$usuario->setId($id);
$registro = $usuario->getOne();
?>
<!DOCTYPE html>
<html lang="es-mx">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar usuario</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/all.min.css">
</head>
<body>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <h1 class="text-center">Editar usuario</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <form id="usuarioForm" action="./update.php" method="post">
                                <?php foreach($registro as $r){ ?>
                                    <input type="hidden" name="id" value="<?=$r['id']?>">
                                    <div class="mb-3">
                                        <label for="nombres" class="form-label">Nombre(s)</label>
                                        <input id="nombres" type="text" name="nombres" class="form-control" value="<?=$r['nombres']?>">
                                    </div>
                                    <div class="mb-3">
                                        <label for="apellidos" class="form-label">Apellidos</label>
                                        <input id="apellidos" type="text" name="apellidos" class="form-control" value="<?=$r['apellidos']?>">
                                    </div>
                                    <div class="mb-3">
                                        <label for="correo" class="form-label">Correo</label>
                                        <input id="correo" type="email" name="correo" class="form-control" value="<?=$r['correo']?>">
                                    </div>
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <input id="password" type="password" name="password" class="form-control">
                                    </div>
                                    <button type="submit" class="btn btn-success w-100">
                                        <i class="fas fa-check"></i>
                                        Guardar
                                    </button>
                                    <a href="./listar.php" class="btn btn-danger w-100 mt-2">
                                        <i class="fas fa-x"></i>
                                        Cancelar
                                    </a>
                                <?php } ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="../assets/js/jquery-3.7.1.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
    <script src="../assets/js/all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function(){
            $('#usuarioForm').on("submit", function(event){
                let isValid = true
                const nombres = $("#nombres").val().trim()
                const apellidos = $('#apellidos').val().trim()
                const correo = $('#correo').val().trim()
                const password = $('#password').val().trim()

                if (nombres === "")
                {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'El campo Nombre(s) es obligatorio'
                    })
                    isValid = false
                }

                if (apellidos === "")
                {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'El campo Apellidos es obligatorio'
                    })
                    isValid = false
                }

                const correoRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
                if (!correoRegex.test(correo))
                {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Ingrese un correo valido'
                    })
                    isValid = false
                }

                if (!isValid)
                {
                    event.preventDefault()
                }
            })
        })
    </script>
</body>
</html>
