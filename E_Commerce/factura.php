<?php
// factura.php
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Mis Facturas</title></head><body>
<h2>Historial de Compras</h2>
<div id="lista"></div>

<script>
async function cargar() {
  const user = JSON.parse(localStorage.getItem('user') || 'null');
  if (!user) { alert('Inicia sesión'); location.href = 'login.php'; return; }

  const res = await fetch('api/ventas.php?usuario_id=' + user.id);
  const data = await res.json();

  const div = document.getElementById('lista');
  div.innerHTML = '';
  data.forEach(v=>{
    div.innerHTML += `<div style="border:1px solid #ccc;margin:8px;padding:8px">
      <h3>Factura #${v.id} — ${new Date(v.fecha).toLocaleString()}</h3>
      <p>Total: <b>$${v.total}</b></p>
      <ul>${(v.venta_detalle||[]).map(d=>`<li>Producto ${d.producto_id} x${d.cantidad} = $${d.subtotal}</li>`).join('')}</ul>
    </div>`;
  });
}
cargar();
</script>
</body></html>
