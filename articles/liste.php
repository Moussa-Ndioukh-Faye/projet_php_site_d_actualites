<?php 
require_once 'connexion.php';
$pagecourant = isset($pagecourant) ? (int)$pagecourant : 1;
$parpage = 5;
$sql = 'SELECT COUNT(*) AS nb_articles FROM articles';
$query = $pdo->prepare($sql);
$query->execute();
$result = $query -> fetch();
$nbarticle = (int) $result['nb_articles'];
$pages = ceil($nbarticle / $parpage);

$premier = ($pagecourant - 1) * $parpage;
$sql = 'SELECT * FROM articles ORDER BY date_publication DESC LIMIT :premier, :parpage';
$query = $pdo->prepare($sql);
$query->bindValue(':premier', $premier, PDO::PARAM_INT);
$query->bindValue(':parpage', $parpage, PDO::PARAM_INT);
$query->execute();
$articles = $query->fetchAll();
?>