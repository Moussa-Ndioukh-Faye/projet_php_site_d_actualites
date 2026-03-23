<?php
session_start();
require_once 'config/connexion.php';
include 'includes/entete.php';

$articles = $pdo->query("SELECT * FROM articles ORDER BY date_publication DESC")->fetchAll();

if(isset($_SESSION['utilisateur'])){
    echo '<div class="info" style="margin-bottom: 2rem;"><strong>Bienvenue " '. htmlspecialchars($_SESSION['utilisateur']['prenom']) . '!</strong> Allez à <a href="editeur.php">mon espace</a> pour gérer vos articles.</div>';
}
?>

<h1>Les dernières actualités</h1>

<div class="article-cards-grid">
<?php if (empty($articles)): ?>
    <div class="empty-state">
        <p>Aucun article pour le moment.</p>
        <p>Revenez bientôt !</p>
    </div>
<?php else: ?>
    <?php foreach($articles as $article): ?>
        <div class="article-card">
            <h2>
                <a href="articles/detail.php?id=<?php echo $article['id']; ?>">
                    <?php echo htmlspecialchars($article['titre']); ?>
                </a>
            </h2>
            <div class="article-date">
               <?php echo date('d/m/Y', strtotime($article['date_publication'])); ?>
            </div>
            <p><?php echo htmlspecialchars($article['description_courte']); ?></p>
            <a href="articles/detail.php?id=<?php echo $article['id']; ?>" class="btn btn-primary">
                Lire la suite 
            </a>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
</div>
<?php include 'includes/pied.php'; ?>