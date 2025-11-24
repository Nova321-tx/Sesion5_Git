async function validateNotaInput({nota, estudianteId, materiaId}, prisma) {
  const errors = [];
  if (nota === undefined || nota === null || nota === '') {
    errors.push('La nota es obligatoria');
  } else {
    const n = Number(nota);
    if (isNaN(n) || n < 0 || n > 100) {
      errors.push('La nota debe ser un número entre 0 y 100');
    }
  }

  const estudiante = await prisma.estudiantes.findUnique({ where: { id: Number(estudianteId) }});
  if (!estudiante) errors.push('Estudiante no existe');

  const materia = await prisma.materias.findUnique({ where: { id: Number(materiaId) }});
  if (!materia) errors.push('Materia no existe');

  return errors;
}

module.exports = { validateNotaInput };
