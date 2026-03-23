<?php
session_start();

// Vérification que l'utilisateur est connecté
if(empty($_SESSION['utilisateur'])){
    header('Location: connexion.php');
    exit();
}

// Récupérer le rôle
$role = $_SESSION['utilisateur']['role'];

require_once 'config/connexion.php';
include 'includes/entete.php';
?>

<div class="admin-header">
    <h1>Espace Rédacteur</h1>
    <p>Bienvenue, <?= htmlspecialchars($_SESSION['utilisateur']['prenom'] . ' ' . $_SESSION['utilisateur']['nom']) ?></p>
    <div class="admin-nav">
        <a href="accueil.php" class="btn btn-primary">Accueil</a>
        <a href="deconnexion.php" class="btn btn-danger">Déconnexion</a>
    </div>
</div>

<div class="admin-dashboard">
    <?php if($role === 'administrateur'): ?>
        <div class="info" style="margin-bottom: 2rem;">
            <i class="material-icons" style="vertical-align: middle; font-size: 1rem;">shield_moon</i> Vous êtes administrateur. <a href="admin.php">Accédez au panneau complet →</a>
        </div>
    <?php endif; ?>
    
    <h2>Mes articles</h2>
    
    <div style="margin-bottom: 2rem; display: flex; gap: 1rem; flex-wrap: wrap;">
        <a href="articles/ajouter.php" class="btn btn-primary"><i class="material-icons">add</i> Nouvel article</a>
        <a href="articles/liste.php" class="btn btn-secondary"><i class="material-icons">list</i> Gérer les articles</a>
        <a href="categories/liste.php" class="btn btn-secondary"><i class="material-icons">label</i> Gérer les catégories</a>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Titre</th>
                <th><i class="material-icons" style="font-size: 16px; vertical-align: middle;">calendar_today</i> Date</th>
                <th>Catégorie</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stmt = $pdo->prepare("
                SELECT a.*, c.nom as categorie_nom 
                FROM articles a 
                LEFT JOIN categories c ON a.id_categorie = c.id 
                WHERE a.id_auteur = ?
                ORDER BY a.date_publication DESC
            ");
            $stmt->execute([$_SESSION['utilisateur']['id']]);
            
            while($article = $stmt->fetch()):
            ?>
            <tr>
                <td><?= htmlspecialchars($article['titre']) ?></td>
                <td><?= date('d/m/Y', strtotime($article['date_publication'])) ?></td>
                <td><?= htmlspecialchars($article['categorie_nom'] ?? 'Non catégorisé') ?></td>
                <td>
                    <a href="articles/modifier.php?id=<?= $article['id'] ?>" class="btn btn-secondary" style="font-size: 0.85rem;">Modifier</a>
                    <a href="articles/supprimer.php?id=<?= $article['id'] ?>" class="btn btn-danger" style="font-size: 0.85rem;" onclick="return confirm('Supprimer ?')">Supprimer</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include 'includes/pied.php'; ?>