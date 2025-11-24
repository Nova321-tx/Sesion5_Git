import React from 'react';
import { createRoot } from 'react-dom/client';
import { BrowserRouter, Routes, Route } from 'react-router-dom';

import Home from './pages/Home';
import Estudiantes from './pages/Estudiantes';
import RegistrarEstudiante from './pages/RegistrarEstudiante';
import Materias from './pages/Materias';
import RegistrarMateria from './pages/RegistrarMateria';
import RegistrarNota from './pages/RegistrarNota';
import EstudianteNotas from './pages/EstudianteNotas';

import './styles.css';

function App() {
  return (
    <BrowserRouter>
      <Routes>
        <Route path="/" element={<Home />} />
        <Route path="/estudiantes" element={<Estudiantes />} />
        <Route path="/registrar-estudiante" element={<RegistrarEstudiante />} />
        <Route path="/materias" element={<Materias />} />
        <Route path="/registrar-materia" element={<RegistrarMateria />} />
        <Route path="/registrar-nota" element={<RegistrarNota />} />
        <Route path="/estudiante/:id" element={<EstudianteNotas />} />
      </Routes>
    </BrowserRouter>
  );
}

createRoot(document.getElementById('root')).render(<App />);
