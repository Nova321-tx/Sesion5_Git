// assets/js/main.js

// --- Autenticación ---
function getUser() {
  return JSON.parse(localStorage.getItem("user") || "null");
}

function setUser(user) {
  localStorage.setItem("user", JSON.stringify(user));
}

function logout() {
  localStorage.removeItem("user");
  location.href = "login.php";
}

// --- Carrito ---
function getCart() {
  return JSON.parse(localStorage.getItem("cart") || "[]");
}

function saveCart(cart) {
  localStorage.setItem("cart", JSON.stringify(cart));
  updateCartCount();
}

function addToCart(product) {
  const cart = getCart();
  const found = cart.find(p => p.id === product.id);
  if (found) {
    found.qty++;
  } else {
    cart.push({ ...product, qty: 1 });
  }
  saveCart(cart);
  alert("Producto agregado al carrito 🛒");
}

function removeFromCart(id) {
  const cart = getCart().filter(p => p.id !== id);
  saveCart(cart);
}

function clearCart() {
  saveCart([]);
}

function updateCartCount() {
  const cart = getCart();
  const count = cart.reduce((a, b) => a + b.qty, 0);
  const el = document.querySelector("#cart-count");
  if (el) el.textContent = count;
}

// --- Cargar productos en la tienda ---
async function loadProducts() {
  const grid = document.querySelector("#productos");
  if (!grid) return;

  const res = await fetch("api/productos.php");
  const data = await res.json();

  grid.innerHTML = data.map(p => `
    <div class="card">
      <img src="${p.imagen_url || 'assets/icons/icon-192.png'}" alt="${p.nombre}">
      <h3>${p.nombre}</h3>
      <p>$${p.precio.toFixed(2)}</p>
      <button onclick='addToCart(${JSON.stringify(p)})'>Agregar 🛒</button>
    </div>
  `).join("");
}

document.addEventListener("DOMContentLoaded", () => {
  updateCartCount();
  loadProducts();
});
