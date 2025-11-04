<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Buscador de Productos - Supabase</title>
<script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js@2"></script>
<style>
body {
  font-family: Arial, sans-serif;
  background: #4f80cbff;
  margin: 40px;
}
h1 {
  color: #0b3954;
}
#buscador {
  width: 100%;
  max-width: 400px;
  padding: 10px;
  font-size: 16px;
  border: 2px solid #0b3954;
  border-radius: 8px;
}
#sugerencias {
  list-style: none;
  padding: 0;
  margin-top: 10px;
  max-width: 400px;
}
#sugerencias li {
  background: white;
  border: 1px solid #ccc;
  padding: 8px;
  border-radius: 6px;
  margin-top: 4px;
  cursor: pointer;
  transition: 0.2s;
}
#sugerencias li:hover {
  background: #0b3954;
  color: white;
}
</style>
</head>
<body>
<h1>Buscador de Productos (Supabase)</h1>

<input type="text" id="buscador" placeholder="Escribe el nombre del producto...">
<ul id="sugerencias"></ul>

<script>
  // 🧠 Aquí pon tus credenciales REALES:
  const SUPABASE_URL = "https://fbanomxpufqaybvqaajh.supabase.co"; // <--- tu URL
  const SUPABASE_ANON_KEY = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImZiYW5vbXhwdWZxYXlidnFhYWpoIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NjA5MDYyMTIsImV4cCI6MjA3NjQ4MjIxMn0.aLaY3wynzzens90mm_lnJ_p4r_kVxI1U-xM7K78Eg2E"; // <--- tu anon key
  
  const supabase = window.supabase.createClient(SUPABASE_URL, SUPABASE_ANON_KEY);

  const input = document.getElementById('buscador');
  const lista = document.getElementById('sugerencias');
  let debounceTimer;

  input.addEventListener('keyup', () => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(buscarProductos, 300);
  });

  async function buscarProductos() {
    const query = input.value.trim();
    lista.innerHTML = '';

    if (!query) return;

    try {
      const { data, error } = await supabase
        .from('productos') // 👈 nombre exacto de tu tabla
        .select('nombre')
        .ilike('nombre', `%${query}%`)
        .limit(10);

      if (error) throw error;

      if (!data || data.length === 0) {
        lista.innerHTML = '<li>No se encontraron resultados</li>';
        return;
      }

      data.forEach(p => {
        const li = document.createElement('li');
        li.textContent = p.nombre;
        li.onclick = () => {
          input.value = p.nombre;
          lista.innerHTML = '';
        };
        lista.appendChild(li);
      });
    } catch (err) {
      console.error('Error al conectar con Supabase:', err);
      lista.innerHTML = '<li>Error al conectar con la base de datos</li>';
    }
  }
</script>
</body>
</html>
