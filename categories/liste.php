<?php
session_start();
if(empty($_SESSION['utilisateur']) || !in_array($_SESSION['utilisateur']['role'], ['editeur', 'administrateur'])){
    header('Location: ../connexion.php');
    exit();
}

require_once '../config/connexion.php';
include '../includes/entete_pageinterne.php';

$categories = $pdo->query("SELECT * FROM categories ORDER BY nom")->fetchAll();
?>

<h1>Gestion des catégories</h1>

<div style="margin-bottom: 2rem; display: flex; gap: 1rem; flex-wrap: wrap;">
    <a href="ajouter.php" class="btn btn-primary">➕ Nouvelle catégorie</a>
    <a href="../editeur.php" class="btn btn-secondary">⬅ Retour</a>
</div>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($categories as $c): ?>
        <tr>
            <td><?= $c['id'] ?></td>
            <td><?= htmlspecialchars($c['nom']) ?></td>
            <td><?= htmlspecialchars($c['description']) ?></td>
            <td>
                <a href="modifier.php?id=<?= $c['id'] ?>" class="btn btn-secondary" style="font-size: 0.85rem;">Modifier</a>
                <a href="supprimer.php?id=<?= $c['id'] ?>" class="btn btn-danger" style="font-size: 0.85rem;" onclick="return confirm('Supprimer cette catégorie ?')">Supprimer</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include '../includes/pied.php'; ?>