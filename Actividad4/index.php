<?php
require_once "supabase.php";

// Obtener comentarios (ordenados por fecha descendente)
$response = supabaseRequest("comentarios?order=fecha.desc", "GET");
$comentarios = $response['body'] ?? [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Blog - Sistema de Comentarios Dinámicos</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>

  <div class="container">
    <h1>📝 Blog - Sistema de Comentarios Dinámicos</h1>

    <!-- 🔹 Formulario de comentarios -->
    <form id="comentarioForm" class="formulario">
      <input type="text" id="nombre_usuario" placeholder="Tu nombre" required>
      <textarea id="comentario" placeholder="Escribe tu comentario..." required></textarea>
      <button type="submit">💬 Enviar Comentario</button>
    </form>

    <p id="mensaje" class="mensaje"></p>

    <!-- 🔹 Área de comentarios -->
    <div id="comentarios" class="comentarios">
      <?php foreach ($comentarios as $c): ?>
        <div class="comentario">
          <strong><?= htmlspecialchars($c['nombre_usuario']) ?></strong>
          <p><?= htmlspecialchars($c['comentario']) ?></p>
          <small><?= date("d/m/Y H:i", strtotime($c['fecha'])) ?></small>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

<script>
// Envío dinámico (sin recargar página)
const form = document.getElementById('comentarioForm');
const msg = document.getElementById('mensaje');
const contenedor = document.getElementById('comentarios');

form.addEventListener('submit', async (e) => {
  e.preventDefault();

  const nombre = document.getElementById('nombre_usuario').value.trim();
  const comentario = document.getElementById('comentario').value.trim();

  if (!/^[A-Za-zÁÉÍÓÚáéíóúñÑ\s]+$/.test(nombre)) {
    msg.textContent = "⚠️ El nombre solo puede contener letras.";
    msg.style.color = "red";
    return;
  }

  const datos = new FormData();
  datos.append('nombre_usuario', nombre);
  datos.append('comentario', comentario);

  const resp = await fetch('insertar.php', {
    method: 'POST',
    body: datos
  });

  const resultado = await resp.json();

  if (resultado.success) {
    msg.textContent = "✅ Comentario agregado con éxito.";
    msg.style.color = "green";

    // Agregar dinámicamente sin recargar
    const nuevo = document.createElement('div');
    nuevo.classList.add('comentario');
    nuevo.innerHTML = `
      <strong>${nombre}</strong>
      <p>${comentario}</p>
      <small>Ahora mismo</small>
    `;
    contenedor.prepend(nuevo);
    form.reset();
  } else {
    msg.textContent = "❌ Error al enviar el comentario.";
    msg.style.color = "red";
  }
});
</script>

</body>
</html>
