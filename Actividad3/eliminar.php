<?php
require_once "supabase.php";
$id = $_GET['id'] ?? null;

if ($id) {
  $res = supabaseRequest("empleados?id=eq.$id", "DELETE");
}

header("Location: empleados.php");
exit;
?>
