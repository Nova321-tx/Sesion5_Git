module.exports = (prisma) => {
  const express = require('express');
  const router = express.Router();
  const { validateNotaInput } = require('../utils/validators');

  // POST /notas -> registrar nota
  router.post('/', async (req, res) => {
    try {
      const { estudianteId, materiaId, nota } = req.body;
      const errors = await validateNotaInput({nota, estudianteId, materiaId}, prisma);
      if (errors.length) return res.status(400).json({ errors });

      const created = await prisma.notas.create({
        data: {
          estudiante_id: Number(estudianteId),
          materia_id: Number(materiaId),
          nota: Number(nota)
        }
      });
      res.status(201).json(created);
    } catch (err) {
      console.error(err);
      res.status(500).json({ error: 'Error al guardar nota' });
    }
  });

  // GET /notas/estudiante/:id -> notas del estudiante + nombre de materia + promedio
  router.get('/estudiante/:id', async (req, res) => {
    try {
      const id = Number(req.params.id);
      const estudiante = await prisma.estudiantes.findUnique({ where: { id }});
      if (!estudiante) return res.status(404).json({ error: 'Estudiante no encontrado' });

      const notas = await prisma.notas.findMany({
        where: { estudiante_id: id },
        include: { materias: true }
      });

      // construir tabla materia + nota
      const materiasNotas = notas.map(n => ({
        id: n.id,
        materiaId: n.materia_id,
        materiaNombre: n.materias.nombre,
        nota: n.nota
      }));

      const promedio = notas.length ? (notas.reduce((s, x) => s + Number(x.nota), 0) / notas.length) : 0;

      res.json({
        estudiante: { id: estudiante.id, nombre: estudiante.nombre },
        notas: materiasNotas,
        promedio: Number(promedio.toFixed(2))
      });
    } catch (err) {
      console.error(err);
      res.status(500).json({ error: 'Error al obtener notas del estudiante' });
    }
  });

  return router;
};
