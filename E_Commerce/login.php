<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Iniciar Sesión - E-Commerce</title>
<link rel="stylesheet" href="assets/css/style.css">
<style>
/* ====== FONDO ESTÁTICO ====== */
body {
  margin: 0;
  padding: 0;
  font-family: "Poppins", sans-serif;
  height: 100vh;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  background: url('assets/img/fondo_login.jpg') no-repeat center center/cover;
  color: #1a1a1a;
  overflow: hidden;
}

/* ====== ENCABEZADO ====== */
.header {
  background-color: rgba(11, 57, 84, 0.95);
  color: white;
  padding: 15px 30px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  box-shadow: 0 3px 8px rgba(0,0,0,0.25);
  z-index: 1;
}

.header h1 {
  margin: 0;
  font-size: 1.5rem;
}

.header a {
  color: white;
  text-decoration: none;
  font-weight: 600;
  background-color: #107dac;
  padding: 8px 16px;
  border-radius: 8px;
  transition: background-color 0.3s ease;
}

.header a:hover {
  background-color: #0b3954;
}

/* ====== CONTENEDOR CENTRAL ====== */
.contenedor {
  flex: 1;
  display: flex;
  justify-content: center;
  align-items: center;
}

/* ====== FORMULARIO ====== */
form#f {
  background: rgba(255, 255, 255, 0.96);
  width: 90%;
  max-width: 400px;
  padding: 40px 35px;
  border-radius: 15px;
  box-shadow: 0 4px 25px rgba(0,0,0,0.3);
  text-align: center;
}

h2 {
  color: #0b3954;
  margin-bottom: 25px;
  font-size: 1.5rem;
}

/* ====== CAMPOS ====== */
input, button {
  display: block;
  width: 100%;
  box-sizing: border-box;
  margin: 12px 0;
  padding: 14px;
  border-radius: 8px;
  font-size: 1rem;
  transition: all 0.3s ease;
}

input {
  border: 1px solid #ccc;
  background-color: #fff;
}

input:focus {
  outline: none;
  border-color: #107dac;
  box-shadow: 0 0 8px rgba(11, 57, 84, 0.4);
}

/* ====== BOTÓN ====== */
button {
  background-color: #0b3954;
  color: white;
  border: none;
  cursor: pointer;
  font-weight: 600;
  letter-spacing: 0.5px;
}

button:hover {
  background-color: #107dac;
  transform: translateY(-2px);
}

/* ====== PIE DE PÁGINA ====== */
footer {
  text-align: center;
  background-color: rgba(11, 57, 84, 0.95);
  color: white;
  padding: 12px;
  font-size: 0.9rem;
  position: relative;
  bottom: 0;
  width: 100%;
}
</style>
</head>

<body>

<!-- ENCABEZADO -->
<div class="header">
  <h1>E-Commerce</h1>
  <a href="index.php">Volver al menú principal</a>
</div>

<!-- FORMULARIO CENTRADO -->
<div class="contenedor">
  <form id="f">
    <h2>Iniciar sesión</h2>
    <input id="email" type="email" placeholder="Correo electrónico" required>
    <input id="password" type="password" placeholder="Contraseña" required>
    <button type="submit">Entrar</button>
  </form>
</div>

<!-- PIE DE PÁGINA -->
<footer>
  © 2025 E-Commerce | Todos los derechos reservados
</footer>

<!-- SCRIPT -->
<script>
document.getElementById('f').addEventListener('submit', async e=>{
  e.preventDefault();
  const email = document.getElementById('email').value;
  const password = document.getElementById('password').value;

  const res = await fetch(`api/clientes.php?email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}`);
  const data = await res.json();

  if (data.error) return alert('⚠️ ' + data.error);

  localStorage.setItem('user', JSON.stringify(data));
  if (data.rol === 'admin') window.location.href = 'admin/panel.php';
  else window.location.href = 'index.php';
});
</script>

</body>
</html>
