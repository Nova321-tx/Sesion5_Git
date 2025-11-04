// 🔗 Conexión a Supabase
const SUPABASE_URL = "https://phmnvvuohcsqyttsnevd.supabase.co"; // 🔹 Cambia esto
const SUPABASE_KEY = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InBobW52dnVvaGNzcXl0dHNuZXZkIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NjA0MDQxOTQsImV4cCI6MjA3NTk4MDE5NH0._rJHUnp1wEm0D7tcLwUXfIltVLAyxp0rxkpvhEvBs-M";         // 🔹 Cambia esto

const supabase = window.supabase.createClient(SUPABASE_URL, SUPABASE_KEY);

// 🎯 Referencias al DOM
const form = document.getElementById("personaForm");
const tablaBody = document.querySelector("#tablaPersonas tbody");

// 🧠 Cargar todas las personas al iniciar
async function cargarPersonas() {
  const { data, error } = await supabase.from("persona").select("*").order("id", { ascending: true });
  if (error) {
    console.error("Error cargando datos:", error);
    return;
  }

  tablaBody.innerHTML = "";
  data.forEach(p => {
    const fila = `
      <tr>
        <td>${p.id}</td>
        <td>${p.nombre}</td>
        <td>${p.apellido}</td>
        <td>${p.edad}</td>
      </tr>
    `;
    tablaBody.innerHTML += fila;
  });
}

// 🧾 Agregar nueva persona
form.addEventListener("submit", async (e) => {
  e.preventDefault();
  const nombre = document.getElementById("nombre").value.trim();
  const apellido = document.getElementById("apellido").value.trim();
  const edad = parseInt(document.getElementById("edad").value);

  const { error } = await supabase.from("persona").insert([{ nombre, apellido, edad }]);

  if (error) {
    alert("Error al insertar: " + error.message);
  } else {
    alert("Persona agregada con éxito ✅");
    form.reset();
    cargarPersonas();
  }
});

// 🔄 Cargar al iniciar
cargarPersonas();
