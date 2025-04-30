<?php
session_start();

// Distrugge tutte le variabili di sessione
$_SESSION = [];

// Distrugge la sessione
session_destroy();

// Reindirizza alla pagina di login (o dove vuoi)
header("Location: ../../../pagine_visualizzate/principali/login.php");
exit;
?>
