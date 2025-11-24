import React, { useEffect, useState } from 'react';
import { fetchNotasEstudiante } from '../api';
import { useParams } from 'react-router-dom';

export default function EstudianteNotas() {
  const { id } = useParams();
  const [data, setData] = useState(null);
  const [error, setError] = useState('');

  useEffect(() => {
    fetchNotasEstudiante(id).then(setData).catch(e => setError(e.message));
  }, [id]);

  if (error) return <div className="error">{error}</div>;
  if (!data) return <div>Cargando...</div>;

  return (
    <div className="container">
      <h1>Notas de {data.estudiante.nombre}</h1>
      <table>
        <thead>
          <tr><th>Materia</th><th>Nota</th></tr>
        </thead>
        <tbody>
          {data.notas.map(n => (
            <tr key={n.id}>
              <td>{n.materiaNombre}</td>
              <td>{n.nota}</td>
            </tr>
          ))}
        </tbody>
      </table>
      <p><strong>Promedio: </strong>{data.promedio}</p>
    </div>
  );
}
