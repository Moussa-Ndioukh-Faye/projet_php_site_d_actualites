<?php
require_once 'config/connexion.php';
include 'includes/entete.php';
include 'includes/menu.php';

$articles = $pdo->query("SELECT * FROM articles ORDER BY date_publication DESC")->fetchAll();
?>

<h1> Les dernières actualités</h1>

<?php if (empty($articles)): ?>
    <p>Aucun article pour le moment.</p>
<?php else: ?>
    <?php foreach($articles as $article): ?>
        <div class="article-card">
            <h2>
                <a href="detail.php?id=<?php echo $article['id']; ?>">
                    <?php echo htmlspecialchars($article['titre']); ?>
                </a>
            </h2>
            <div class="article-date">
               <?php echo date('d/m/Y', strtotime($article['date_publication'])); ?>
            </div>
            <p><?php echo htmlspecialchars($article['description_courte']); ?></p>
            <a href="detail.php?id=<?php echo $article['id']; ?>" class="btn">
                Lire la suite 
            </a>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
 <link rel="stylesheet" href="style.css">
<?php include 'includes/pied.php'; ?>