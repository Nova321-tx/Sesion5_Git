module.exports = (prisma) => {
  const express = require("express");
  const router = express.Router();

  // GET /materias -> lista todas las materias
  router.get("/", async (req, res) => {
    try {
      const materias = await prisma.materias.findMany();
      res.json(materias);
    } catch (err) {
      console.error(err);
      res.status(500).json({ error: "Error al obtener materias" });
    }
  });

  // POST /materias -> crear nueva materia
  router.post("/", async (req, res) => {
    try {
      const { nombre } = req.body;
      const nuevaMateria = await prisma.materias.create({ data: { nombre } });
      res.status(201).json(nuevaMateria);
    } catch (err) {
      console.error(err);
      res.status(500).json({ error: "Error al registrar materia" });
    }
  });

  return router;
};
