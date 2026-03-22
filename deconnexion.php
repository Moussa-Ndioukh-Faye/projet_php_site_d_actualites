<?php
//--------------------------
//Destruction de la session
//--------------------------
if (session_start() === PHP_SESSION_NONE) session_start();
$_SESSION = [];
session_destroy();
header("Location: accueil.php");
exit();
?>