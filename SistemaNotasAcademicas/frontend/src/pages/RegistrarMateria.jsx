import React, { useState } from "react";
import { useNavigate } from "react-router-dom";

export default function RegistrarMateria() {
  const [nombre, setNombre] = useState("");
  const navigate = useNavigate();

  const handleSubmit = async (e) => {
    e.preventDefault();
    const res = await fetch("http://localhost:4000/materias", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ nombre }),
    });
    if (res.ok) {
      alert("Materia registrada");
      navigate("/materias");
    } else {
      alert("Error al registrar materia");
    }
  };

  return (
    <div style={{ padding: "2rem" }}>
      <h2>Registrar Materia</h2>
      <form onSubmit={handleSubmit}>
        <input
          value={nombre}
          onChange={(e) => setNombre(e.target.value)}
          placeholder="Nombre de la materia"
          required
        />
        <button type="submit">Registrar</button>
      </form>
    </div>
  );
}
