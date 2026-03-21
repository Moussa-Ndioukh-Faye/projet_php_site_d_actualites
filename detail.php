<?php
require_once 'config/connexion.php';
include 'includes/entete.php';
include 'includes/menu.php';

$id = $_GET['id'];
$article = $pdo->query("SELECT * FROM articles WHERE id = $id")->fetch();
?>

<article>
    <h1> <?php echo htmlspecialchars($article['titre']); ?></h1>
    
    <div class="article-date">
       Publié le <?php echo date('d/m/Y à H:i', strtotime($article['date_publication'])); ?>
    </div>
    
    <div class="article-contenu">
        <?php echo nl2br(htmlspecialchars($article['contenu'])); ?>
    </div>
    
    <a href="accueil.php" >
        Retour à l'accueil
    </a>
</article>
 <link rel="stylesheet" href="style.css">
<?php include 'includes/pied.php'; ?>