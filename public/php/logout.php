<?php
// Avvia la sessione per poterla distruggere
session_start();
// Rimuove tutte le variabili di sessione
session_unset();
// Distrugge la sessione
session_destroy();
// Cancella anche il cookie di sessione dal browser per sicurezza
setcookie(session_name(), '', time() - 3600, '/');
// Reindirizza alla homepage
header("Location: index.php");
exit;
?>