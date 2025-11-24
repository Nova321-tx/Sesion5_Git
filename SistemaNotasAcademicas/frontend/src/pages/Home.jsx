import React from "react";
import { Link } from "react-router-dom";

export default function Home() {
  return (
    <div style={{ padding: "2rem" }}>
      <h1>Sistema de Gestión de Notas Académicas</h1>
      <div style={{ marginTop: "2rem" }}>
        <Link to="/estudiantes" style={{ marginRight: "1rem" }}>Ver Estudiantes</Link>
        <Link to="/registrar-estudiante" style={{ marginRight: "1rem" }}>Registrar Estudiante</Link>
        <Link to="/materias" style={{ marginRight: "1rem" }}>Ver Materias</Link>
        <Link to="/registrar-materia" style={{ marginRight: "1rem" }}>Registrar Materia</Link>
        <Link to="/registrar-nota">Registrar Nota</Link>
      </div>
    </div>
  );
}
