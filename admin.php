<?php
session_start();

// Vérification que l'utilisateur est connecté ET est administrateur
if(empty($_SESSION['utilisateur']) || $_SESSION['utilisateur']['role'] !== 'administrateur'){
    header('Location: accueil.php');
    exit();
}

require_once 'config/connexion.php';
include 'includes/entete.php';
?>


<div class="admin-header">
    <h1>Panneau d'administration</h1>
    <p>Bienvenue, <?= htmlspecialchars($_SESSION['utilisateur']['prenom'] . ' ' . $_SESSION['utilisateur']['nom']) ?></p>
    <div class="admin-nav">
        <a href="accueil.php" class="btn btn-primary">Accueil</a>
        <a href="articles/ajouter.php" class="btn btn-secondary">+ Nouvel article</a>
        <a href="deconnexion.php" class="btn btn-danger">Déconnexion</a>
    </div>
</div>

<div class="admin-dashboard">
    <h2>Tableau de bord</h2>
    
    <!-- Statistiques -->
    <div class="stats">
        <?php
        // Compter les articles
        $stmt = $pdo->query("SELECT COUNT(*) FROM articles");
        $total_articles = $stmt->fetchColumn();
        
        // Compter les utilisateurs
        $stmt = $pdo->query("SELECT COUNT(*) FROM utilisateurs");
        $total_users = $stmt->fetchColumn();
        
        // Compter les catégories
        $stmt = $pdo->query("SELECT COUNT(*) FROM categories");
        $total_categories = $stmt->fetchColumn();
        ?>
        
        <div class="stat-card">
            <h3>Articles</h3>
            <p class="stat-number"><?= $total_articles ?></p>
        </div>
        
        <div class="stat-card">
            <h3>Utilisateurs</h3>
            <p class="stat-number"><?= $total_users ?></p>
        </div>
        
        <div class="stat-card">
            <h3>Catégories</h3>
            <p class="stat-number"><?= $total_categories ?></p>
        </div>
    </div>
    
    <!-- Actions rapides -->
    <div class="quick-actions">
        <h3>Actions rapides</h3>
        <a href="articles/ajouter.php" class="btn">➕ Nouvel article</a>
        <a href="articles/liste.php" class="btn">📋 Gérer les articles</a>
        <a href="utilisateurs/liste.php" class="btn">👥 Gérer les utilisateurs</a>
        <a href="categories/liste.php" class="btn">🏷️ Gérer les catégories</a>
    </div>
    
    <!-- Derniers articles -->
    <div class="recent-articles">
        <h3>Derniers articles publiés</h3>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Date</th>
                    <th>Auteur</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $pdo->query("
                    SELECT a.*, u.prenom, u.nom 
                    FROM articles a 
                    JOIN utilisateurs u ON a.id_auteur = u.id 
                    ORDER BY a.date_publication DESC 
                    LIMIT 5
                ");
                
                while($article = $stmt->fetch()):
                ?>
                <tr>
                    <td><?= htmlspecialchars($article['titre']) ?></td>
                    <td><?= date('d/m/Y', strtotime($article['date_publication'])) ?></td>
                    <td><?= htmlspecialchars($article['prenom'] . ' ' . $article['nom']) ?></td>
                    <td>
                        <a href="articles/modifier.php?id=<?= $article['id'] ?>">✏️</a>
                        <a href="articles/supprimer.php?id=<?= $article['id'] ?>" onclick="return confirm('Supprimer ?')">🗑️</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/pied.php'; ?>