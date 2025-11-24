import React, { useState, useEffect } from "react";
import { Link } from "react-router-dom";

export default function Estudiantes() {
  const [estudiantes, setEstudiantes] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetch("http://localhost:4000/estudiantes")
      .then(res => res.json())
      .then(data => {
        setEstudiantes(data);
        setLoading(false);
      })
      .catch(err => {
        console.error(err);
        setLoading(false);
      });
  }, []);

  if (loading) return <p>Cargando estudiantes...</p>;

  return (
    <div style={{ padding: "2rem" }}>
      <h2>Estudiantes</h2>
      <Link to="/registrar-estudiante">Registrar Estudiante</Link>
      <ul>
        {estudiantes.length === 0 ? (
          <li>No hay estudiantes</li>
        ) : (
          estudiantes.map(e => (
            <li key={e.id}>
              {e.nombre} - {e.ci} - {e.email}{" "}
              <Link to={`/estudiante/${e.id}`}>Ver Notas</Link>
            </li>
          ))
        )}
      </ul>
    </div>
  );
}
