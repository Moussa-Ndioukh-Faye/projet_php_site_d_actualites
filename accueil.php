<?php
if(isset($_GET['page']) && !empty($_GET['page'])){
    $pagecourant =(int) strip_tags($_GET['page']);
}else{
    $pagecourant = 1;
}
require_once 'config/connexion.php';
include 'includes/entete.php';
include 'includes/menu.php';
include 'articles/liste.php';
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
            <nav>
                <?php if($pages > 1): ?>
                    <div class="pagination">
                        <?php for($i = 1; $i <= $pages; $i++): ?>
                            <a href="?page=<?php echo $i; ?>" 
                            <?php echo ($i == $pagecourant) ? 'class="active"' : ''; ?>>
                                <?php echo $i; ?>
                            </a>
                        <?php endfor; ?>
                    </div>
                <?php endif; ?>
            </nav>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
 
<?php include 'includes/pied.php'; ?>