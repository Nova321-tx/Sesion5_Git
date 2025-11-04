<h2>Lista de Libros</h2>
<?php if (empty($libros)): ?>
    <p>No hay libros registrados.</p>
<?php else: ?>
<table>
    <tr>
        <th>Título</th>
        <th>Autor</th>
        <th>Nacionalidad</th>
        <th>Estado</th>
        <th>Acciones</th>
    </tr>
    <?php foreach ($libros as $libro): ?>
    <tr>
        <td><?= htmlspecialchars($libro->getTitulo()) ?></td>
        <td><?= htmlspecialchars($libro->getAutor()->getNombre()) ?></td>
        <td><?= htmlspecialchars($libro->getAutor()->getNacionalidad()) ?></td>
        <td><?= $libro->getEstado() ?></td>
        <td>
            <a href="index.php?accion=prestar&titulo=<?= urlencode($libro->getTitulo()) ?>">Prestar</a> |
            <a href="index.php?accion=devolver&titulo=<?= urlencode($libro->getTitulo()) ?>">Devolver</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<?php endif; ?>
