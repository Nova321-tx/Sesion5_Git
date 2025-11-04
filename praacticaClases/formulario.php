<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Formulario de Registro</title>
</head>
<body>
    <h1>Registro de Usuario</h1>
    <form action="procesar.php" method="post">
        <label>Nombre:</label><br>
        <input type="text" name="nombre" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>

        <label>Edad:</label><br>
        <input type="number" name="edad" min="1" max="120" required><br><br>

        <button type="submit">Enviar</button>
    </form>
</body>
</html>
