<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../config/connexion.php';
include '../includes/entete_pageinterne.php';

if(isset($_GET['id'])){
    $identifiant = $_GET['id'];
    $sql = $pdo->prepare("SELECT a.*, u.prenom, u.nom, c.nom AS categorie FROM articles a LEFT JOIN utilisateurs u ON a.id_auteur = u.id LEFT JOIN categories c ON a.id_categorie = c.id WHERE a.id = ?");
    $sql->execute([$identifiant]);
    $result = $sql->fetch();
    
    if(!$result) {
        echo '<div class="error">Article non trouvé.</div>';
        include '../includes/pied.php';
        exit;
    }
} else {
    echo '<div class="error">ID d\'article manquant.</div>';
    include '../includes/pied.php';
    exit;
}
?>

<article class="card" style="margin-bottom: 2rem;">
    <h1><?= htmlspecialchars($result['titre']) ?></h1>
    
    <div style="color: var(--text-tertiary); font-size: 0.9rem; margin-bottom: 1rem;">
        <span>📅 <?= date('d/m/Y', strtotime($result['date_publication'])) ?></span>
        <span style="margin-left: 1rem;">✍️ <?= htmlspecialchars(($result['prenom'] ?? '') . ' ' . ($result['nom'] ?? '')) ?></span>
        <?php if($result['categorie']): ?>
            <span style="margin-left: 1rem;">🏷️ <?= htmlspecialchars($result['categorie']) ?></span>
        <?php endif; ?>
    </div>
    
    <div style="line-height: 1.8; color: var(--text-secondary);">
        <?= nl2br(htmlspecialchars($result['contenu'])) ?>
    </div>
</article>

<div style="margin-bottom: 2rem;">
    <a href="liste.php" class="btn btn-secondary">⬅ Retour aux articles</a>
    <a href="../accueil.php" class="btn btn-secondary">Accueil</a>
</div>

<?php include '../includes/pied.php'; ?>
