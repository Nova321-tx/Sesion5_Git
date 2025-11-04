<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Carrito 🛒</title>
<style>
  /* ====== ESTILOS GENERALES ====== */
  body {
    font-family: "Poppins", sans-serif;
    margin: 0;
    background: url('img/fondo-tienda.jpg') no-repeat center center fixed;
    background-size: cover;
    color: #fff;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
  }

  /* ====== ENCABEZADO ====== */
  header {
    background-color: #0b3954;
    text-align: center;
    padding: 15px;
    box-shadow: 0 3px 8px rgba(0,0,0,0.3);
  }

  header h1 {
    margin: 0;
    font-size: 26px;
    color: #ffcc00;
  }

  header button {
    background-color: #ffcc00;
    border: none;
    padding: 10px 15px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: bold;
    margin-top: 10px;
    transition: 0.3s;
  }

  header button:hover {
    background-color: #ffaa00;
  }

  /* ====== CONTENIDO PRINCIPAL ====== */
  main {
    flex: 1;
    background: rgba(11, 57, 84, 0.9);
    width: 90%;
    max-width: 900px;
    margin: 40px auto;
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0 0 20px rgba(255,255,255,0.15);
  }

  h2 {
    text-align: center;
    color: #ffcc00;
    margin-bottom: 20px;
  }

  table {
    width: 100%;
    border-collapse: collapse;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 10px;
    overflow: hidden;
  }

  th, td {
    padding: 10px;
    text-align: center;
    border: 1px solid rgba(255,255,255,0.3);
  }

  th {
    background-color: rgba(255,255,255,0.2);
    color: #ffcc00;
  }

  td {
    color: #fff;
  }

  /* ====== BOTONES ====== */
  button {
    background-color: #ffcc00;
    border: none;
    padding: 10px 15px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: bold;
    transition: 0.3s;
    color: #0b3954;
  }

  button:hover {
    background-color: #ffaa00;
  }

  .actions {
    display: flex;
    justify-content: center;
    gap: 15px;
    margin-top: 20px;
  }

  #paypal-button-container {
    margin-top: 90px;
    text-align: center;
    width: 50%;
    height: 150px;
    justify-content: center;  /* centra horizontalmente */
    align-items: center;      /* centra verticalmente si hay espacio */
  }

  /* ====== PIE DE PÁGINA ====== */
  footer {
    background-color: #0b3954;
    color: #fff;
    text-align: center;
    padding: 15px;
    font-size: 14px;
    margin-top: auto;
  }

  footer p {
    margin: 5px 0;
  }
</style>

<!-- SDK de PayPal Sandbox -->
<script src="https://www.paypal.com/sdk/js?client-id=AYXpCyBz1qYStkbVMiKnpRZy6JsXQWaTmvt8FFF0uI_w_6GqMDWKpI22gDQHfz5vT7JLGsg_lYhHhua8&currency=USD"></script>
</head>
<body>

<header>
  <h1>🛍️ Mi Tienda Online</h1>
  <button onclick="window.location.href='index.php'">🏠 Volver al menú principal</button>
</header>

<main>
  <h2>Tu Carrito de Compras 🛒</h2>
  <div id="cart"></div>
  <p style="text-align:center; font-size:18px;">Total: $<span id="total">0</span></p>

  <div class="actions">
    <button onclick="window.location.href='index.php'">🛒 Comprar más artículos</button>
  </div>

  <div style="display:flex; justify-content:center; margin-top:20px;">
    <div id="paypal-button-container"></div>
  </div>

</main>

<footer>
  <p>© 2025 Mi Tienda Online - Todos los derechos reservados.</p>
  <p>Desarrollado por Moisés Teran</p>
</footer>

<script>
let productos = [];

async function cargarProductos() {
  const res = await fetch('api/productos.php');
  productos = await res.json();
}

async function render() {
  const cart = JSON.parse(localStorage.getItem('cart') || '[]');
  const container = document.getElementById('cart');
  const totalEl = document.getElementById('total');

  if (!cart.length) {
    container.innerHTML = '<p style="text-align:center;">🛒 Tu carrito está vacío</p>';
    totalEl.innerText = '0.00';
    document.getElementById('paypal-button-container').innerHTML = '';
    return;
  }

  let html = '<table><tr><th>Producto</th><th>Precio</th><th>Cantidad</th><th>Subtotal</th><th>Acciones</th></tr>';
  let total = 0;

  cart.forEach((item, index) => {
    const p = productos.find(prod => prod.id == item.id);
    if (p) {
      const subtotal = p.precio * item.qty;
      total += subtotal;
      html += `<tr>
        <td>${p.nombre}</td>
        <td>$${p.precio.toFixed(2)}</td>
        <td>${item.qty}</td>
        <td>$${subtotal.toFixed(2)}</td>
        <td>
          <button onclick="addQty(${item.id})">➕</button>
          <button onclick="subQty(${item.id})">➖</button>
          <button onclick="removeItem(${index})">❌</button>
        </td>
      </tr>`;
    }
  });

  html += '</table>';
  container.innerHTML = html;
  totalEl.innerText = total.toFixed(2);

  const user = JSON.parse(localStorage.getItem('user') || 'null');
  if (user && user.rol === 'cliente') {
    renderPaypal(total);
  } else {
    document.getElementById('paypal-button-container').innerHTML = '';
  }
}

// Quitar producto
function removeItem(index) {
  const cart = JSON.parse(localStorage.getItem('cart') || '[]');
  cart.splice(index, 1);
  localStorage.setItem('cart', JSON.stringify(cart));
  render();
}

// Aumentar cantidad
function addQty(id) {
  const cart = JSON.parse(localStorage.getItem('cart') || '[]');
  const item = cart.find(i => i.id == id);
  if (item) item.qty++;
  localStorage.setItem('cart', JSON.stringify(cart));
  render();
}

// Disminuir cantidad
function subQty(id) {
  const cart = JSON.parse(localStorage.getItem('cart') || '[]');
  const item = cart.find(i => i.id == id);
  if (item) {
    item.qty--;
    if (item.qty <= 0) {
      const index = cart.indexOf(item);
      cart.splice(index, 1);
    }
  }
  localStorage.setItem('cart', JSON.stringify(cart));
  render();
}

// Pagar con registro tradicional
function checkout() {
  const user = JSON.parse(localStorage.getItem('user') || 'null');
  if (!user || user.rol !== 'cliente') {
    alert('⚠️ Debes iniciar sesión como cliente para pagar');
    location.href = 'login.php';
    return;
  }

  const cart = JSON.parse(localStorage.getItem('cart') || '[]');
  if (!cart.length) return alert('🛒 Carrito vacío');

  fetch('api/ventas.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      usuario_id: user.id,
      items: cart,
      total: parseFloat(document.getElementById('total').innerText)
    })
  })
    .then(res => res.json())
    .then(data => {
      alert('✅ Compra registrada, total: $' + data.total);
      localStorage.removeItem('cart');
      render();
    })
    .catch(err => {
      console.error(err);
      alert('❌ Error al procesar la venta');
    });
}

// PayPal
function renderPaypal(total) {
  paypal.Buttons({
    createOrder: function (data, actions) {
      return actions.order.create({
        purchase_units: [{ amount: { value: total.toFixed(2) } }]
      });
    },
    onApprove: function (data, actions) {
      return actions.order.capture().then(function () {
        const user = JSON.parse(localStorage.getItem('user'));
        const cart = JSON.parse(localStorage.getItem('cart') || '[]');

        fetch('api/ventas.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            usuario_id: user.id,
            items: cart,
            total: parseFloat(total.toFixed(2))
          })
        })
          .then(res => res.json())
          .then(data => {
            alert('✅ Compra realizada con PayPal: $' + data.total);
            localStorage.removeItem('cart');
            render();
          })
          .catch(() => alert('❌ Error al registrar la venta'));
      });
    }
  }).render('#paypal-button-container');
}

// Inicialización
cargarProductos().then(render);
</script>
</body>
</html>
