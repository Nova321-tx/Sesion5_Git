<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Admin - Productos</title>
<style>
body {
  font-family: Arial, sans-serif;
  margin: 20px;
  background: #f4f6f8;
}
h2 {
  color: #0b3954;
  text-align: center;
}
form {
  background: white;
  padding: 15px;
  border-radius: 10px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  margin-bottom: 20px;
}
form input {
  margin: 6px 4px;
  padding: 8px;
  border-radius: 6px;
  border: 1px solid #ccc;
  width: calc(33% - 10px);
}
form button {
  background: #0b3954;
  color: white;
  border: none;
  padding: 10px 15px;
  border-radius: 6px;
  cursor: pointer;
  margin-left: 4px;
}
form button:hover {
  background: #1565c0;
}
table {
  border-collapse: collapse;
  width: 100%;
  background: white;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
td, th {
  border: 1px solid #ccc;
  padding: 8px;
  text-align: left;
}
th {
  background: #0b3954;
  color: white;
}
img {
  border-radius: 6px;
}
.actions button {
  background: none;
  border: none;
  cursor: pointer;
  font-size: 18px;
}
.actions button:hover {
  opacity: 0.7;
}
</style>
</head>
<body>

<h2>Gestión de Productos</h2>

<form id="form">
  <input type="hidden" id="id">
  <input id="nombre" placeholder="Nombre" required>
  <input id="descripcion" placeholder="Descripción" required>
  <input id="precio" placeholder="Precio" required type="number" step="0.01">
  <input id="stock" placeholder="Stock" type="number">
  <input id="categoria" placeholder="Categoría">
  <input id="imagen_url" placeholder="URL de la imagen (https://...)" required>
  <button>Guardar</button>
</form>

<table id="tabla"></table>

<script>
async function cargar() {
  const res = await fetch('../api/productos.php');
  const data = await res.json();
  const t = document.getElementById('tabla');
  t.innerHTML = `<tr>
    <th>ID</th><th>Nombre</th><th>Descripción</th><th>Precio</th><th>Stock</th>
    <th>Categoría</th><th>Imagen</th><th>Acciones</th>
  </tr>`;

  data.forEach(p => {
    // Escapar comillas en textos para evitar errores en onclick
    const nombre = p.nombre?.replace(/'/g, "\\'");
    const descripcion = p.descripcion?.replace(/'/g, "\\'");
    const categoria = p.categoria?.replace(/'/g, "\\'");
    const imagen = p.imagen_url?.replace(/'/g, "\\'");

    t.innerHTML += `<tr>
      <td>${p.id}</td>
      <td>${p.nombre}</td>
      <td>${p.descripcion || '-'}</td>
      <td>$${p.precio}</td>
      <td>${p.stock ?? 0}</td>
      <td>${p.categoria || '-'}</td>
      <td>${p.imagen_url ? `<img src="${p.imagen_url}" width="50">` : ''}</td>
      <td class="actions">
        <button onclick="edit(${p.id}, '${nombre}', '${descripcion}', ${p.precio}, ${p.stock}, '${categoria}', '${imagen}')">✏️</button>
        <button onclick="del(${p.id})">🗑️</button>
      </td>
    </tr>`;
  });
}

document.getElementById('form').addEventListener('submit', async e => {
  e.preventDefault();
  const id = document.getElementById('id').value;

  const data = {
    nombre: document.getElementById('nombre').value.trim(),
    descripcion: document.getElementById('descripcion').value.trim(),
    precio: parseFloat(document.getElementById('precio').value),
    stock: parseInt(document.getElementById('stock').value) || 0,
    categoria: document.getElementById('categoria').value.trim(),
    imagen_url: document.getElementById('imagen_url').value.trim()
  };

  const method = id ? 'PATCH' : 'POST';
  const url = '../api/productos.php' + (id ? `?id=${id}` : '');

  try {
    const res = await fetch(url, {
      method,
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data)
    });

    const result = await res.json();

    if (res.ok) {
      alert('✅ Guardado correctamente');
      document.getElementById('form').reset();
      document.getElementById('id').value = '';
      await cargar();
    } else {
      alert('❌ Error: ' + (result.error || 'No se pudo guardar'));
    }
  } catch (err) {
    alert('⚠️ Error de conexión o servidor');
    console.error(err);
  }
});

function edit(id, nombre, descripcion, precio, stock, categoria, imagen_url) {
  document.getElementById('id').value = id;
  document.getElementById('nombre').value = nombre;
  document.getElementById('descripcion').value = descripcion;
  document.getElementById('precio').value = precio;
  document.getElementById('stock').value = stock;
  document.getElementById('categoria').value = categoria;
  document.getElementById('imagen_url').value = imagen_url;
  alert('📝 Modo edición activado');
}

async function del(id) {
  if (!confirm('¿Eliminar producto?')) return;
  const res = await fetch('../api/productos.php?id=' + id, { method: 'DELETE' });
  if (res.ok) {
    alert('🗑️ Producto eliminado correctamente');
    await cargar();
  } else {
    alert('❌ Error al eliminar');
  }
}

cargar();
</script>
</body>
</html>
