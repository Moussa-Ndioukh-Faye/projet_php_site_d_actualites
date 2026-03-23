<?php
session_start();
if(empty($_SESSION['utilisateur']) || !in_array($_SESSION['utilisateur']['role'], ['editeur', 'administrateur'])){
    header('Location: ../connexion.php');
    exit();
}

require_once '../config/connexion.php';
include '../includes/entete_pageinterne.php';

if(!isset($_GET['id'])){
    echo '<div class="error">ID manquant</div>';
    include '../includes/pied.php';
    exit;
}

$id = (int) $_GET['id'];

// récupérer la catégorie
$stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
$stmt->execute([$id]);
$categorie = $stmt->fetch();

if(!$categorie){
    echo '<div class="error">Catégorie introuvable</div>';
    include '../includes/pied.php';
    exit;
}

$erreur = "";
$succes = "";

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $nom = trim($_POST['nom']);
    $description = trim($_POST['description']);

    if(empty($nom)){
        $erreur = "Le nom est obligatoire.";
    } else {
        $stmt = $pdo->prepare("UPDATE categories SET nom = ?, description = ? WHERE id = ?");
        try {
            $stmt->execute([$nom, $description, $id]);
            $succes = "Catégorie modifiée avec succès.";
        } catch(PDOException $e){
            $erreur = "Erreur : nom déjà utilisé.";
        }
    }
}
?>

<div style="margin-bottom: 2rem;">
    <a href="liste.php" class="btn btn-secondary"><i class="material-icons">arrow_back</i> Retour</a>
</div>

<h1>Modifier une catégorie</h1>

<div style="max-width: 600px; margin: 0 auto;">
    <?php if($erreur): ?>
        <div class="error"><?= htmlspecialchars($erreur) ?></div>
    <?php endif; ?>

    <?php if($succes): ?>
        <div class="success">
            <i class="material-icons">check_circle</i> <?= htmlspecialchars($succes) ?>
            <br><br>
            <a href="liste.php" class="btn btn-primary"><i class="material-icons">arrow_back</i> Retour aux catégories</a>
        </div>
    <?php else: ?>
        <form method="POST">
            <div class="form-group">
                <label for="nom">Nom de la catégorie <span style="color: var(--danger);">*</span></label>
                <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($categorie['nom']) ?>" placeholder="Nom" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="4" placeholder="Description..."><?= htmlspecialchars($categorie['description']) ?></textarea>
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <input type="submit" class="btn btn-primary" value="&#xe161; Enregistrer" style="flex: 1;">
                <a href="liste.php" class="btn btn-secondary" style="flex: 1; display: flex; align-items: center; justify-content: center;"><i class="material-icons">cancel</i> Annuler</a>
            </div>
        </form>
    <?php endif; ?>
</div>

<?php include '../includes/pied.php'; ?>