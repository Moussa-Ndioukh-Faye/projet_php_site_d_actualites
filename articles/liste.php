<?php 
require_once 'connexion.php';
$articles = 'SELECT * FROM articles ORDER BY date_publication DESC;';

$query = $pdo->prepare($articles);

$query ->execute();

$articles = query->fetchAll(PDO::FETCH_ASSOC);
?>