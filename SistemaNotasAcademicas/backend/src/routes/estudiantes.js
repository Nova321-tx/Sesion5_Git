module.exports = (prisma) => {
  const express = require("express");
  const router = express.Router();

  // GET /estudiantes -> lista todos los estudiantes
  router.get("/", async (req, res) => {
    try {
      const estudiantes = await prisma.estudiantes.findMany();
      res.json(estudiantes);
    } catch (err) {
      console.error(err);
      res.status(500).json({ error: "Error al obtener estudiantes" });
    }
  });

  // POST /estudiantes -> crear estudiante
  router.post("/", async (req, res) => {
    try {
      const { nombre, ci, email } = req.body;
      const nuevoEstudiante = await prisma.estudiantes.create({
        data: { nombre, ci, email },
      });
      res.status(201).json(nuevoEstudiante);
    } catch (err) {
      console.error(err);
      res.status(500).json({ error: "Error al registrar estudiante" });
    }
  });

  return router;
};
