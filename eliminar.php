<?php
include 'config.php';
include 'funciones.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validar Token CSRF
    validarTokenCSRF($_POST['csrf_token']);

    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

    if ($id <= 0) {
        die("ID inválido.");
    }

    try {
        $stmt = $conn->prepare("DELETE FROM users WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            header("Location: index.php");
            exit();
        } else {
            echo "Error al eliminar el usuario.";
        }
    } catch (Exception $e) {
        error_log("Error al eliminar usuario: " . $e->getMessage());
        echo "Ocurrió un error al eliminar el usuario.";
    }
} else {
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    if ($id <= 0) {
        die("ID inválido.");
    }

    // Mostrar confirmación antes de eliminar
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <!-- Meta etiquetas y enlaces a CSS -->
        <meta charset="UTF-8">
        <title>Eliminar Usuario</title>
        <!-- Enlaces a Bootstrap y tus estilos -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <div class="container">
            <h1>Confirmar Eliminación</h1>
            <p>¿Estás seguro de que deseas eliminar este usuario?</p>
            <form method="POST" action="eliminar.php">
                <input type="hidden" name="csrf_token" value="<?= generarTokenCSRF() ?>">
                <input type="hidden" name="id" value="<?= htmlspecialchars($id, ENT_QUOTES, 'UTF-8') ?>">
                <button type="submit" class="btn btn-danger">Sí, eliminar</button>
                <a href="index.php" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js" integrity="sha512-2rNj2KJ+D8s1ceNasTIex6z4HWyOnEYLVC3FigGOmyQCZc2eBXKgOxQmo3oKLHyfcj53uz4QMsRCWNbLd32Q1g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    </body>
    </html>
    <?php
}
?>
