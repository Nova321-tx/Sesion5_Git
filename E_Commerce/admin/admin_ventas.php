<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Ventas Manuales - Admin</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <style>
    body { background-color: #f5f8fa; color: #0b3954; font-family: "Poppins", sans-serif; }
    .navbar { background-color: #0b3954; }
    .navbar-brand, .navbar-text { color: white !important; }
    .card { border: none; box-shadow: 0 3px 8px rgba(0,0,0,0.1); border-radius: 12px; }
    .btn-primary { background-color: #0b3954; border: none; }
    .btn-primary:hover { background-color: #133f5f; }
    .table th { background-color: #0b3954; color: white; }
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg">
  <div class="container-fluid">
    <a class="navbar-brand" href="panel.php">🧾 Ventas Manuales</a>
    <div class="navbar-text ms-auto">
      Bienvenido, <?= htmlspecialchars($_SESSION['user']['nombre'] ?? 'Administrador') ?>
    </div>
  </div>
</nav>

<div class="container my-4">
  <div class="row">
    <!-- Formulario -->
    <div class="col-md-5">
      <div class="card p-3">
        <h4 class="mb-3">Registrar Venta Manual</h4>
        <form id="formVenta">
          <div class="mb-2">
            <label class="form-label">NIT</label>
            <input type="text" name="nit" class="form-control" placeholder="Ej: 12345678">
          </div>
          <div class="mb-2">
            <label class="form-label">Razón Social</label>
            <input type="text" name="razon_social" class="form-control" placeholder="Ej: Empresa XYZ SRL">
          </div>

          <!-- Productos -->
          <div class="mb-2">
            <label class="form-label">Agregar Producto</label>
            <select id="productoSelect" class="form-select">
  <option value="">-- Selecciona un producto --</option>
</select>

          </div>
          <div class="mb-2">
            <label>Cantidad</label>
            <input type="number" id="cantidad" class="form-control" value="1" min="1">
          </div>
          <button type="button" id="agregarBtn" class="btn btn-secondary w-100 mb-3">➕ Agregar Producto</button>

          <table class="table table-bordered text-center" id="tablaProductos">
            <thead>
              <tr>
                <th>Producto</th>
                <th>Cant.</th>
                <th>Precio</th>
                <th>Subtotal</th>
                <th>❌</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>

          <h5>Total: Bs <span id="total">0.00</span></h5>

          <div class="mb-2">
            <label class="form-label">Método de Pago</label>
            <select name="metodo_pago" class="form-select">
              <option value="Efectivo">Efectivo</option>
              <option value="Tarjeta">Tarjeta</option>
              <option value="Transferencia">Transferencia</option>
            </select>
          </div>
          <div class="mb-2">
            <label class="form-label">Observaciones</label>
            <textarea name="observaciones" class="form-control" rows="2"></textarea>
          </div>
          <button type="submit" class="btn btn-primary w-100 mt-3">💾 Registrar Venta</button>
        </form>
      </div>
    </div>

    <!-- Historial -->
    <div class="col-md-7">
      <div class="card p-3">
        <h4 class="mb-3">Facturacion de Ventas Manuales</h4>
        <div class="table-responsive">
          <table class="table table-striped text-center" id="tablaVentas">
            <thead>
              <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Total</th>
                <th>Fecha</th>
                <th>PDF</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script>
const API_PRODUCTOS = "../api/productos.php";
const API_VENTAS = "../api/ventas_manuales.php";
const user = JSON.parse(localStorage.getItem('user') || 'null');
const productosSeleccionados = [];

const tablaBody = document.querySelector('#tablaProductos tbody');
const totalEl = document.getElementById('total');

document.getElementById('agregarBtn').addEventListener('click', () => {
    const select = document.getElementById('productoSelect');
    const cantidad = parseInt(document.getElementById('cantidad').value);
    const id = select.value;
    if (!id) return alert('Selecciona un producto');

    const nombre = select.options[select.selectedIndex].dataset.nombre;
    const precio = parseFloat(select.options[select.selectedIndex].dataset.precio);
    const subtotal = cantidad * precio;

    productosSeleccionados.push({ id, nombre, cantidad, precio, subtotal });
    renderProductos();
});

function renderProductos() {
    tablaBody.innerHTML = '';
    let total = 0;
    productosSeleccionados.forEach((p, i) => {
        total += p.subtotal;
        tablaBody.innerHTML += `
            <tr>
                <td>${p.nombre}</td>
                <td>${p.cantidad}</td>
                <td>Bs ${p.precio.toFixed(2)}</td>
                <td>Bs ${p.subtotal.toFixed(2)}</td>
                <td><button class="btn btn-danger btn-sm" onclick="eliminar(${i})">X</button></td>
            </tr>
        `;
    });
    totalEl.textContent = total.toFixed(2);
}

function eliminar(i) {
    productosSeleccionados.splice(i, 1);
    renderProductos();
}


// Registrar venta
document.getElementById("formVenta").addEventListener("submit", async e => {
    e.preventDefault();
    if (productosSeleccionados.length === 0) return alert("Agrega al menos un producto");

    const data = Object.fromEntries(new FormData(e.target).entries());
    data.usuario_id = user?.id || null;
    data.total = parseFloat(totalEl.textContent);
    data.productos = productosSeleccionados;

    try {
        const res = await axios.post(API_VENTAS, data);
        if (res.data.ok) {
            alert(res.data.mensaje);
            e.target.reset();
            productosSeleccionados.length = 0;
            renderProductos();
            cargarVentas();
        } else {
            alert("Error al registrar venta: " + (res.data.mensaje || JSON.stringify(res.data)));
        }
    } catch (err) {
        console.error(err);
        alert("Ocurrió un error al enviar la venta. Revisa la consola.");
    }
});

async function cargarProductos() {
    try {
        const res = await fetch(API_PRODUCTOS);
        const productos = await res.json();

        const select = document.getElementById('productoSelect');
        select.innerHTML = '<option value="">-- Selecciona un producto --</option>';

        productos.forEach(p => {
            const option = document.createElement('option');
            option.value = p.id;
            option.dataset.precio = p.precio;
            option.dataset.nombre = p.nombre;
            option.textContent = `${p.nombre} - Bs ${parseFloat(p.precio).toFixed(2)}`;
            select.appendChild(option);
        });
    } catch (error) {
        console.error("Error cargando productos:", error);
    }
}

// Llamar al cargar la página
cargarProductos();
// Cargar ventas existentes
async function cargarVentas() {
    const res = await fetch(API_VENTAS);
    const ventas = await res.json();
    const tbody = document.querySelector("#tablaVentas tbody");
    tbody.innerHTML = "";

    ventas.reverse().forEach(v => {
        const tr = document.createElement("tr");
        tr.innerHTML = `
            <td>${v.id}</td>
            <td>${v.cliente_nombre || 'Sin nombre'}</td>
            <td>${v.total.toFixed(2)}</td>
            <td>${new Date(v.fecha).toLocaleString()}</td>
            <td><button class="btn btn-outline-secondary btn-sm" onclick='generarPDF(${JSON.stringify(v)})'>🧾 PDF</button></td>
        `;
        tbody.appendChild(tr);
    });
}
cargarVentas();


function generarPDF(v) {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    doc.setFont("helvetica", "bold");
    doc.text("FACTURA DE VENTA", 80, 20);
    doc.setFontSize(10);

    doc.text(`NIT: ${v.cliente_nit || 'Sin NIT'}`, 20, 35);
    doc.text(`Cliente: ${v.cliente_nombre || 'Consumidor Final'}`, 20, 40);
    doc.text(`Fecha: ${new Date(v.fecha).toLocaleString()}`, 20, 45);
    doc.text(`Método: ${v.metodo_pago || 'Efectivo'}`, 20, 50);
    doc.text("-----------------------------------------------------------", 20, 55);

    let y = 65;
    let productos = [];
    try { productos = JSON.parse(v.productos || "[]"); } catch(e){ console.error(e); }

    productos.forEach(p => {
        doc.text(`${p.cantidad}x ${p.nombre}`, 25, y);
        doc.text(`Bs ${p.subtotal.toFixed(2)}`, 160, y);
        y += 6;
    });

    doc.text("-----------------------------------------------------------", 20, y);
    doc.setFont("helvetica", "bold");
    doc.text(`TOTAL: Bs ${v.total.toFixed(2)}`, 140, y + 10);

    doc.save(`Factura_${v.id}.pdf`);
}


cargarVentas();
</script>
</body>
</html>
