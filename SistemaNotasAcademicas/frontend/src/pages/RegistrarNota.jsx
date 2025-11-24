import React, { useEffect, useState } from 'react';
import { fetchEstudiantes, fetchMaterias, postNota } from '../api';
import { useNavigate } from 'react-router-dom';

export default function RegistrarNota() {
  const [estudiantes, setEstudiantes] = useState([]);
  const [materias, setMaterias] = useState([]);
  const [form, setForm] = useState({ estudianteId: '', materiaId: '', nota: '' });
  const [error, setError] = useState('');
  const navigate = useNavigate();

  useEffect(() => {
    fetchEstudiantes().then(setEstudiantes).catch(e => setError(e.message));
    fetchMaterias().then(setMaterias).catch(e => setError(e.message));
  }, []);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');
    // Validación front 0-100
    const n = Number(form.nota);
    if (isNaN(n) || n < 0 || n > 100) return setError('La nota debe ser entre 0 y 100');

    try {
      await postNota({
        estudianteId: Number(form.estudianteId),
        materiaId: Number(form.materiaId),
        nota: n
      });
      navigate('/');
    } catch (err) {
      setError(err.message);
    }
  };

  return (
    <div className="container">
      <h1>Registrar Nota</h1>
      {error && <div className="error">{error}</div>}
      <form onSubmit={handleSubmit}>
        <label>Estudiante</label>
        <select value={form.estudianteId} onChange={e => setForm({...form, estudianteId: e.target.value})} required>
          <option value="">-- seleccionar --</option>
          {estudiantes.map(s => <option key={s.id} value={s.id}>{s.nombre}</option>)}
        </select>

        <label>Materia</label>
        <select value={form.materiaId} onChange={e => setForm({...form, materiaId: e.target.value})} required>
          <option value="">-- seleccionar --</option>
          {materias.map(m => <option key={m.id} value={m.id}>{m.nombre}</option>)}
        </select>

        <label>Nota (0 - 100)</label>
        <input type="number" step="0.01" value={form.nota} onChange={e => setForm({...form, nota: e.target.value})} required />

        <button type="submit">Guardar</button>
      </form>
    </div>
  );
}
