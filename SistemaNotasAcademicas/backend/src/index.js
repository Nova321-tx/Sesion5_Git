require('dotenv').config();
const express = require('express');
const bodyParser = require('body-parser');
const cors = require('cors');
const { PrismaClient } = require('@prisma/client');

const prisma = new PrismaClient();
const app = express();
const port = process.env.PORT || 4000;

app.use(cors());
app.use(bodyParser.json());

app.use('/estudiantes', require('./routes/estudiantes')(prisma));
app.use('/materias', require('./routes/materias')(prisma));
app.use('/notas', require('./routes/notas')(prisma));

app.get('/', (req, res) => res.json({ ok: true }));

app.listen(port, () => {
  console.log(`Server running on http://localhost:${port}`);
});
