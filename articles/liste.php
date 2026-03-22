<?php
session_start();
require_once '../config/connexion.php';
include '../includes/entete_pageinterne.php';

if (empty($_SESSION['utilisateur'])) {
    echo '<div class="error">Accès refusé. <a href="../connexion.php">Se connecter</a></div>';
    include '../includes/pied.php';
    exit;
}

$articles = $pdo->query("
    SELECT a.id, a.titre, a.date_publication,
           c.nom AS categorie,
           u.prenom, u.nom AS auteur
    FROM articles a
    LEFT JOIN categories c ON a.id_categorie = c.id
    LEFT JOIN utilisateurs u ON a.id_auteur = u.id
    ORDER BY a.date_publication DESC
")->fetchAll();
?>

<h1>Gestion des articles</h1>

<div style="margin-bottom: 2rem;">
    <a href="../editeur.php" class="btn btn-secondary">⬅ Retour</a>
    <a href="ajouter.php" class="btn btn-primary">➕ Ajouter un article</a>
</div>

<?php if (empty($articles)): ?>
    <div class="empty-state">
        <p>Aucun article disponible.</p>
    </div>
<?php else: ?>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Titre</th>
            <th>Catégorie</th>
            <th>Auteur</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($articles as $a): ?>
        <tr>
            <td><?= $a['id'] ?></td>

            <td><?= htmlspecialchars($a['titre']) ?></td>

            <td><?= htmlspecialchars($a['categorie'] ?? 'N/A') ?></td>

            <td><?= htmlspecialchars(($a['prenom'] ?? '') . ' ' . ($a['auteur'] ?? '')) ?></td>

            <td><?= date('d/m/Y', strtotime($a['date_publication'])) ?></td>

            <td>
                <a href="detail.php?id=<?= $a['id'] ?>" class="btn btn-secondary" style="font-size: 0.85rem; padding: 0.5rem 0.75rem;">Voir</a>
                <a href="modifier.php?id=<?= $a['id'] ?>" class="btn btn-secondary" style="font-size: 0.85rem; padding: 0.5rem 0.75rem;">Modifier</a>
                <a href="supprimer.php?id=<?= $a['id'] ?>" class="btn btn-danger" style="font-size: 0.85rem; padding: 0.5rem 0.75rem;" onclick="return confirm('Supprimer cet article ?')">Supprimer</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?php endif; 
    include '../includes/pied.php';
?>