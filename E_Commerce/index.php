<?php
// index.php — página pública que muestra productos (no requiere sesión)
?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Tienda - E-commerce</title>
<link rel="stylesheet" href="assets/css/style.css">
<link rel="manifest" href="manifest.json">
<meta name="theme-color" content="#0b3954">

<style>
/* ==== ESTILOS PERSONALIZADOS PARA INDEX ==== */
body {
  font-family: "Segoe UI", sans-serif;
  background-color: #0b3954;
  color: #fff;
  margin: 0;
  padding: 0;
}

/* ENCABEZADO */
.header {
  background-color: #0b3954;
  color: white;
  padding: 15px 30px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  box-shadow: 0 3px 8px rgba(0,0,0,0.3);
}

.header h1 {
  margin: 0;
  font-size: 1.6rem;
  letter-spacing: 0.5px;
}

#user-menu a, #user-menu button {
  color: white;
  text-decoration: none;
  font-weight: 600;
  background: none;
  border: none;
  margin-left: 12px;
  font-size: 0.95rem;
  cursor: pointer;
  transition: color 0.3s ease;
}

#user-menu a:hover, #user-menu button:hover {
  color: #ffbc00;
}

/* CARRUSEL */
.carousel {
  width: 100%;
  height: 220px;
  overflow: hidden;
  position: relative;
  margin-bottom: 25px;
}

.carousel img {
  width: 100%;
  height: 220px;
  object-fit: cover;
  position: absolute;
  top: 0;
  left: 0;
  opacity: 0;
  transition: opacity 0.8s ease-in-out;
}

.carousel img.active {
  opacity: 1;
}

/* BUSCADOR */
.search-bar {
  text-align: center;
  margin: 25px 0;
}

input#q {
  width: 60%;
  padding: 12px;
  border-radius: 10px;
  border: none;
  outline: none;
  transition: all 0.3s ease;
  font-size: 1rem;
}

input#q:focus {
  box-shadow: 0 0 8px rgba(255, 255, 255, 0.6);
}

/* ==== NUEVO ESTILO DE PRODUCTOS CORREGIDO ==== */
#list {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 30px;
  padding: 30px;
}

.box {
  position: relative;
  width: 230px;
  min-height: 320px;
  display: flex;
  justify-content: center;
  align-items: stretch;
  transition: 0.5s;
  z-index: 1;
}

.box::before,
.box::after {
  content: '';
  position: absolute;
  top: 0;
  left: 50px;
  width: 50%;
  height: 100%;
  background: linear-gradient(315deg, #4000ffff, #0abae6ff);
  border-radius: 8px;
  transform: skewX(15deg);
  transition: 0.5s;
}

.box:hover::before,
.box:hover::after {
  transform: skewX(0deg) scaleX(1.3);
}

.box .content {
  position: relative;
  width: 100%;
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(10px);
  box-shadow: 0 5px 15px rgba(0,0,0,0.2);
  border-radius: 12px;
  color: #fff;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  padding: 16px;
  text-align: center;
  z-index: 1;
}

.box img {
  width: 100%;
  height: 140px;
  object-fit: cover;
  border-radius: 10px;
}

.box h2 {
  font-size: 1.1rem;
  margin: 8px 0 5px;
  line-height: 1.2;
}

.box p {
  font-size: 0.9rem;
  opacity: 0.9;
  margin: 4px 0;
}

/* Botón bien posicionado */
.box button {
  background: #00fff7ff;
  border: none;
  padding: 10px 0;
  border-radius: 8px;
  color: #111;
  font-weight: bold;
  cursor: pointer;
  transition: 0.3s;
  width: 100%;
  margin-top: 10px;
}

.box button:hover {
  background: #00b3ffff;
  color: #fff;
}


/* PIE DE PÁGINA */
footer {
  text-align: center;
  background-color: #071f2d;
  color: white;
  padding: 15px;
  font-size: 0.9rem;
  margin-top: 30px;
}
</style>
</head>
<body>

<div class="header">
  <h1>E-Commerce</h1>
  <div id="user-menu"></div>
</div>

<!-- Carrusel -->
<div class="carousel">
  <img src="assets/img/articulo1.jpg" class="active" alt="Promoción 1">
  <img src="assets/img/articulo2.jpg" alt="Promoción 2">
  <img src="assets/img/articulo3.jpg" alt="Promoción 3">
</div>

<!-- Buscador -->
<div class="search-bar">
  <input id="q" placeholder="Buscar productos...">
</div>

<!-- Lista de productos -->
<div id="list"></div>

<footer>
  © 2025 E-Commerce | Todos los derechos reservados
</footer>

<script>
/* ===== CARRUSEL ===== */
const slides = document.querySelectorAll('.carousel img');
let currentSlide = 0;
setInterval(() => {
  slides[currentSlide].classList.remove('active');
  currentSlide = (currentSlide + 1) % slides.length;
  slides[currentSlide].classList.add('active');
}, 3000);

/* ===== MENÚ USUARIO ===== */
function updateNav() {
  const user = JSON.parse(localStorage.getItem('user') || 'null');
  const menu = document.getElementById('user-menu');
  if (user) {
    menu.innerHTML = `Hola, ${user.nombre} |
      <button onclick="logout()">Cerrar sesión</button> |
      <button onclick="goToCart()">🛒</button>`;
  } else {
    menu.innerHTML = `<a href="login.php">Iniciar sesión</a> |
      <a href="registro.php">Registrarse</a> |
      <button onclick="goToCart()">🛒</button>`;
  }
}

function logout() {
  localStorage.removeItem('user');
  updateNav();
}

function goToCart() {
  const user = JSON.parse(localStorage.getItem('user') || 'null');
  if (!user) {
    alert("⚠️ Debes iniciar sesión para acceder al carrito");
    window.location.href = 'login.php';
  } else {
    window.location.href = 'carrito.php';
  }
}

updateNav();

/* ===== PRODUCTOS ===== */
async function cargar() {
  const res = await fetch('api/productos.php');
  const data = await res.json();
  render(data);
}

document.getElementById('q').addEventListener('keyup', e => {
  const q = e.target.value.trim();
  if (!q) return cargar();
  buscar(q);
});

async function buscar(q) {
  const res = await fetch(`api/productos.php?q=${encodeURIComponent(q)}`);
  const data = await res.json();
  render(data);
}

function render(items) {
  const cont = document.getElementById('list');
  cont.innerHTML = '';
  if (!items || !items.length) {
    cont.innerHTML = '<p style="text-align:center;">No se encontraron productos.</p>';
    return;
  }
  items.forEach(p => {
    cont.innerHTML += `
      <div class="box">
        <span></span>
        <div class="content">
          <img src="${p.imagen_url || 'assets/icons/icon-192.png'}" alt="">
          <h2>${p.nombre}</h2>
          <p>${p.descripcion || ''}</p>
          <p><b>$${p.precio}</b></p>
          <button onclick="addCart(${p.id})">Agregar</button>
        </div>
      </div>`;
  });
}

function addCart(id) {
  const cart = JSON.parse(localStorage.getItem('cart') || '[]');
  const item = cart.find(x => x.id === id);
  if (item) item.qty++;
  else cart.push({ id: id, qty: 1 });
  localStorage.setItem('cart', JSON.stringify(cart));
  alert('✅ Producto añadido al carrito');
}

window.onload = cargar;

/* ===== PWA ===== */
if ('serviceWorker' in navigator) {
  navigator.serviceWorker.register('/sw.js')
    .then(() => console.log("✅ Service Worker registrado"))
    .catch(err => console.error("❌ Error al registrar SW:", err));
}
</script>

</body>
</html>
