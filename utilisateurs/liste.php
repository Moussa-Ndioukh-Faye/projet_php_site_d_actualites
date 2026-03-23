<?php
session_start();
if(empty($_SESSION['utilisateur']) || $_SESSION['utilisateur']['role'] !== 'administrateur'){
    header('Location: ../connexion.php');
    exit();
}

require_once '../config/connexion.php';
include '../includes/entete_pageinterne.php';

$utilisateurs = $pdo->query("SELECT * FROM utilisateurs ORDER BY role, nom")->fetchAll();
?>

<h1>Gestion des utilisateurs</h1>

<div style="margin-bottom: 2rem; display: flex; gap: 1rem; flex-wrap: wrap;">
    <a href="ajouter.php" class="btn btn-primary"><i class="material-icons">add</i> Nouvel utilisateur</a>
    <a href="../admin.php" class="btn btn-secondary"><i class="material-icons">arrow_back</i> Retour</a>
</div>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Login</th>
            <th>Rôle</th>
            <th><i class="material-icons" style="font-size: 16px; vertical-align: middle;">calendar_today</i> Date création</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($utilisateurs as $u): ?>
        <tr>
            <td><?= $u['id'] ?></td>
            <td><?= htmlspecialchars($u['nom']) ?></td>
            <td><?= htmlspecialchars($u['prenom']) ?></td>
            <td><?= htmlspecialchars($u['login']) ?></td>
            <td><?= $u['role'] ?></td>
            <td><?= date('d/m/Y', strtotime($u['date_creation'])) ?></td>
            <td>
                <a href="modifier.php?id=<?= $u['id'] ?>" class="btn btn-secondary" style="font-size: 0.85rem;"><i class="material-icons">edit</i> Modifier</a>
                <?php if($u['id'] != $_SESSION['utilisateur']['id']): ?>
                    <a href="supprimer.php?id=<?= $u['id'] ?>" class="btn btn-danger" style="font-size: 0.85rem;" onclick="return confirm('Supprimer cet utilisateur ?')"><i class="material-icons">delete</i> Supprimer</a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include '../includes/pied.php'; ?>