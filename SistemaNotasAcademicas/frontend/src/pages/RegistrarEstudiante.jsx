import React, { useState } from "react";
import { useNavigate } from "react-router-dom";

export default function RegistrarEstudiante() {
  const [nombre, setNombre] = useState("");
  const [ci, setCi] = useState("");
  const [email, setEmail] = useState("");
  const navigate = useNavigate();

  const handleSubmit = async (e) => {
    e.preventDefault();
    const res = await fetch("http://localhost:4000/estudiantes", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ nombre, ci, email }),
    });
    if (res.ok) {
      alert("Estudiante registrado");
      navigate("/estudiantes");
    } else {
      alert("Error al registrar estudiante");
    }
  };

  return (
    <div style={{ padding: "2rem" }}>
      <h2>Registrar Estudiante</h2>
      <form onSubmit={handleSubmit}>
        <input
          value={nombre}
          onChange={(e) => setNombre(e.target.value)}
          placeholder="Nombre"
          required
        />
        <input
          value={ci}
          onChange={(e) => setCi(e.target.value)}
          placeholder="CI"
          required
        />
        <input
          value={email}
          onChange={(e) => setEmail(e.target.value)}
          placeholder="Email"
          required
        />
        <button type="submit">Registrar</button>
      </form>
    </div>
  );
}
