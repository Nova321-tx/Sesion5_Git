<!-- includes/sidebar.php -->
<aside class="sidebar">
  <h2>Sistema de Empleados</h2>
  <nav>
    <a href="index.php" class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>">🏠 Inicio</a>
    <a href="empleados.php" class="<?= basename($_SERVER['PHP_SELF']) == 'empleados.php' ? 'active' : '' ?>">📋 Listado</a>
    <a href="registrar.php" class="<?= basename($_SERVER['PHP_SELF']) == 'registrar.php' ? 'active' : '' ?>">➕ Registrar</a>
  </nav>
</aside>
