const BASE = import.meta.env.VITE_API_URL || 'http://localhost:4000';

export async function fetchEstudiantes() {
  const res = await fetch(`${BASE}/estudiantes`);
  if (!res.ok) throw new Error('Error al cargar estudiantes');
  return res.json();
}

export async function fetchMaterias() {
  const res = await fetch(`${BASE}/materias`);
  if (!res.ok) throw new Error('Error al cargar materias');
  return res.json();
}

export async function postNota(payload) {
  const res = await fetch(`${BASE}/notas`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(payload)
  });
  const data = await res.json();
  if (!res.ok) throw new Error(data.errors?.join?.(', ') || data.error || 'Error al guardar nota');
  return data;
}

export async function fetchNotasEstudiante(id) {
  const res = await fetch(`${BASE}/notas/estudiante/${id}`);
  if (!res.ok) throw new Error('Error al obtener notas');
  return res.json();
}
