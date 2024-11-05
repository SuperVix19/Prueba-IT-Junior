<?php
include 'config.php';
include 'funciones.php';

try {
    $stmt = $conn->prepare("SELECT * FROM users");
    $stmt->execute();
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    error_log("Error al obtener usuarios: " . $e->getMessage());
    die("Ocurrió un error al obtener la lista de usuarios.");
}
?>

<!DOCTYPE html>
<html lang="es_MX">
<head>
    <!-- Meta etiquetas y enlaces a CSS -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prueba IT Junior</title>
    <!-- Enlaces a Bootstrap y tus estilos -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/main.css">
</head>
<body>
    <!-- Sección del Formulario -->
    <section id="formulario">
        <div class="container-fluid sin-padding">
            <div class="seccion-formulario container-xl">
                <div class="titulo">
                    <h1>Formulario</h1>
                </div>
                <form class="row g-3 needs-validation" novalidate method="POST" action="crear.php" id="formulario">
                    <input type="hidden" name="csrf_token" value="<?= generarTokenCSRF() ?>">
                    <div class="col-xl-6 col-md-6 col-sm-6 col-12">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                        <div class="invalid-feedback">
                            Por favor, ingresa tu nombre.
                        </div>
                    </div>
                    <div class="col-xl-6 col-md-6 col-sm-6 col-12">
                        <label for="apellido" class="form-label">Apellido</label>
                        <input type="text" class="form-control" id="apellido" name="apellido" required>
                        <div class="invalid-feedback">
                            Por favor, ingresa tu apellido.
                        </div>
                    </div>
                    <div class="col-xl-6 col-md-6 col-sm-6 col-12">
                        <label for="email" class="form-label">Correo electrónico</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                        <div class="invalid-feedback">
                            Por favor, ingresa un correo electrónico válido.
                        </div>
                    </div>
                    <div class="col-xl-6 col-md-6 col-sm-6 col-12">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                        <div class="invalid-feedback">
                            Por favor, ingresa una contraseña.
                        </div>
                    </div>
                    <div class="col-12">
                        <button class="btn btn-primary" type="submit">Enviar</button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Sección de la Tabla -->
    <section id="tabla">
        <div class="container-fluid sin-padding">
            <div class="seccion-tabla container-xl">
                <div class="titulo">
                    <h1>Tabla de usuarios</h1>
                </div>
                <div class="table-responsive">
                    <table class="table table-light table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nombre</th>
                                <th scope="col">Apellido</th>
                                <th scope="col">Correo</th>
                                <th scope="col">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($usuarios as $usuario): ?>
                            <tr>
                                <th scope="row"><?= htmlspecialchars($usuario['id'], ENT_QUOTES, 'UTF-8') ?></th>
                                <td><?= htmlspecialchars($usuario['nombre'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($usuario['apellido'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($usuario['email'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td>
                                    <a href="editar.php?id=<?= urlencode($usuario['id']) ?>" class="btn btn-warning">Editar</a>
                                    <a href="eliminar.php?id=<?= urlencode($usuario['id']) ?>" class="btn btn-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar este usuario?')">Eliminar</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js" integrity="sha512-2rNj2KJ+D8s1ceNasTIex6z4HWyOnEYLVC3FigGOmyQCZc2eBXKgOxQmo3oKLHyfcj53uz4QMsRCWNbLd32Q1g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <!-- Tu archivo de validaciones -->
    <script src="assets/js/validaciones.js"></script>
</body>
</html>
