<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Panel de Administración - E-Commerce</title>
<style>
body {
  margin: 0;
  font-family: "Segoe UI", sans-serif;
  background: #f4f6f8;
  display: flex;
  height: 100vh;
  overflow: hidden;
}

/* ====== Sidebar ====== */
.sidebar {
  width: 220px;
  background: #0b3954;
  color: white;
  display: flex;
  flex-direction: column;
  position: fixed;
  height: 100%;
  padding-top: 20px;
}
.sidebar h2 {
  text-align: center;
  font-size: 20px;
  margin-bottom: 30px;
}
.sidebar a {
  color: white;
  padding: 14px 20px;
  text-decoration: none;
  display: block;
  transition: background 0.3s;
}
.sidebar a:hover,
.sidebar a.active { background: #1565c0; }

/* ====== Main content ====== */
.main {
  flex: 1;
  margin-left: 220px;
  display: flex;
  flex-direction: column;
  height: 100%;
}

/* ====== Topbar ====== */
.topbar {
  background: white;
  padding: 15px 25px;
  border-bottom: 1px solid #ccc;
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.topbar h1 {
  font-size: 20px;
  margin: 0;
  color: #0b3954;
}

/* ====== Content area ====== */
.content {
  flex: 1;
  padding: 20px;
  overflow-y: auto;
}

/* ====== Cards for dashboard ====== */
.cards {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 15px;
  margin-bottom: 20px;
}
.card {
  background: white;
  border-radius: 10px;
  padding: 15px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  text-align: center;
}
.card h3 { margin: 5px 0; color: #0b3954; }

iframe { width: 100%; height: 100%; border: none; }

#btnLogout {
  background: #e74c3c;
  color: white;
  padding: 8px 15px;
  border-radius: 6px;
  border: none;
  cursor: pointer;
  font-size: 14px;
  transition: background 0.3s;
}
#btnLogout:hover { background: #c0392b; }
</style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
  <h2>Admin</h2>
  <a href="#" class="active" onclick="loadSection('inicio', this)">🏠 Inicio</a>
  <a href="#" onclick="loadSection('productos', this)">📦 Gestionar Productos</a>
  <a href="#" onclick="loadSection('clientes', this)">👥 Clientes</a>
  <a href="#" onclick="loadSection('facturacion', this)">🧾 Facturación Paypal</a>
  <a href="#" onclick="loadSection('admin_ventas', this)">💰 Ventas Manuales</a>
</div>

<!-- Main area -->
<div class="main">
  <div class="topbar">
    <h1>Panel de Administración - E-Commerce</h1>
    <button id="btnLogout">Cerrar sesión</button>
  </div>

  <div class="content" id="content">
    <h2>Bienvenido al Panel</h2>
    <div class="cards">
      <div class="card" id="totalVentas"><h3>Ventas Totales</h3><p>-</p></div>
      <div class="card" id="facturacion"><h3>Facturación</h3><p>-</p></div>
      <div class="card" id="clientes"><h3>Clientes</h3><p>-</p></div>
      <div class="card" id="productos"><h3>Productos</h3><p>-</p></div>
    </div>
    <canvas id="grafico" width="400" height="200"></canvas>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// === Protección: solo admin puede acceder ===
const user = JSON.parse(localStorage.getItem('user') || 'null');
if (!user || user.rol !== 'admin') {
  alert('⚠️ Acceso denegado. Solo administradores pueden ingresar.');
  window.location.href = '../login.php';
}

// === Cargar dashboard ===
async function cargarDashboard() {
  try {
    const rVentas = await fetch('../api/ventas.php?resumen=true');
    const resumen = await rVentas.json();

    document.querySelector('#totalVentas p').textContent = resumen.total_ventas || 0;
    document.querySelector('#facturacion p').textContent =
      '$' + (resumen.facturacion_total ? parseFloat(resumen.facturacion_total).toFixed(2) : '0.00');

    const rProd = await fetch('../api/productos.php');
    const productos = await rProd.json();
    document.querySelector('#productos p').textContent = productos.length;

    const rUsers = await fetch('../api/clientes.php');
    const usuarios = await rUsers.json();
    document.querySelector('#clientes p').textContent = usuarios.length;

    const ctx = document.getElementById('grafico').getContext('2d');
    const dias = Object.keys(resumen.ventas_por_dia || {});
    const valores = Object.values(resumen.ventas_por_dia || {});
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: dias,
        datasets: [{ label: 'Ventas por día', data: valores, backgroundColor: '#0b3954' }]
      },
      options: { scales: { y: { beginAtZero: true } } }
    });

  } catch (err) {
    console.error('Error al cargar el dashboard:', err);
  }
}

// === Cargar secciones ===
function loadSection(section, el) {
  document.querySelectorAll('.sidebar a').forEach(a => a.classList.remove('active'));
  el.classList.add('active');
  const content = document.getElementById('content');
  if (section === 'inicio') {
    content.innerHTML = `
      <h2>Bienvenido al Panel</h2>
      <div class="cards">
        <div class="card" id="totalVentas"><h3>Ventas Totales</h3><p>-</p></div>
        <div class="card" id="facturacion"><h3>Facturación</h3><p>-</p></div>
        <div class="card" id="clientes"><h3>Clientes</h3><p>-</p></div>
        <div class="card" id="productos"><h3>Productos</h3><p>-</p></div>
      </div>
      <canvas id="grafico" width="400" height="200"></canvas>`;
    cargarDashboard();
  } else {
    content.innerHTML = `<iframe src="${section}.php"></iframe>`;
  }
}

// === Cerrar sesión ===
document.getElementById('btnLogout').addEventListener('click', () => {
  localStorage.removeItem('user');
  window.location.href = '../index.php';
});

cargarDashboard();
</script>
</body>
</html>
