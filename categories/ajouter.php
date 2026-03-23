<?php
session_start();
if(empty($_SESSION['utilisateur']) || !in_array($_SESSION['utilisateur']['role'], ['editeur', 'administrateur'])){
    header('Location: ../connexion.php');
    exit();
}

require_once '../config/connexion.php';
include '../includes/entete_pageinterne.php';

$erreur = "";
$succes = "";

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $nom = trim($_POST['nom']);
    $description = trim($_POST['description']);

    if(empty($nom)){
        $erreur = "Le nom est obligatoire.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO categories (nom, description) VALUES (?, ?)");
        try {
            $stmt->execute([$nom, $description]);
            $succes = "Catégorie ajoutée avec succès.";
        } catch(PDOException $e){
            $erreur = "Erreur : nom déjà existant.";
        }
    }
}
?>

<div style="margin-bottom: 2rem;">
    <a href="liste.php" class="btn btn-secondary"><i class="material-icons">arrow_back</i> Retour</a>
</div>

<h1>Ajouter une catégorie</h1>

<div style="max-width: 600px; margin: 0 auto;">
    <?php if($erreur): ?>
        <div class="error"><?= htmlspecialchars($erreur) ?></div>
    <?php endif; ?>

    <?php if($succes): ?>
        <div class="success">
            <i class="material-icons">check_circle</i> <?= htmlspecialchars($succes) ?>
            <br><br>
            <a href="ajouter.php" class="btn btn-primary"><i class="material-icons">add</i> Ajouter une autre catégorie</a>
            <a href="../editeur.php" class="btn btn-secondary"><i class="material-icons">arrow_back</i> Retour à mon espace</a>
        </div>
    <?php else: ?>
        <form method="POST">
            <div class="form-group">
                <label for="nom">Nom de la catégorie <span style="color: var(--danger);">*</span></label>
                <input type="text" id="nom" name="nom" placeholder="Ex: Technologie" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="4" placeholder="Décrivez cette catégorie..."></textarea>
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <input type="submit" class="btn btn-primary" value="&#xe145; Ajouter la catégorie" style="flex: 1;">
                <a href="liste.php" class="btn btn-secondary" style="flex: 1; display: flex; align-items: center; justify-content: center;"><i class="material-icons">cancel</i> Annuler</a>
            </div>
        </form>
    <?php endif; ?>
</div>

<?php include '../includes/pied.php'; ?>