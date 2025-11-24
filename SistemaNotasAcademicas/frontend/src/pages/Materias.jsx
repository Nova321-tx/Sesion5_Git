import React, { useState, useEffect } from "react";
import { Link } from "react-router-dom";

export default function Materias() {
  const [materias, setMaterias] = useState([]);

  useEffect(() => {
    fetch("http://localhost:4000/materias")
      .then((res) => res.json())
      .then((data) => setMaterias(data));
  }, []);

  return (
    <div style={{ padding: "2rem" }}>
      <h2>Materias</h2>
      <Link to="/registrar-materia">Registrar Materia</Link>
      <ul>
        {materias.map((m) => (
          <li key={m.id}>{m.nombre}</li>
        ))}
      </ul>
    </div>
  );
}
