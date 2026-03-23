<?php
session_start();
require_once '../config/connexion.php';
include '../includes/entete_pageinterne.php';

/* Sécurité */
if (empty($_SESSION['utilisateur'])) {
    echo '<div class="error">Accès refusé. <a href="../connexion.php">Se connecter</a></div>';
    include '../includes/pied.php';
    exit;
}

/* =========================
   TRAITEMENT (POST)
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = $_POST['id'];
    $titre = $_POST['titre'];
    $desc = $_POST['description_courte'];
    $contenu = $_POST['contenu'];
    $categorie = $_POST['id_categorie'];

    if ($id && $titre && $desc && $contenu) {

        $sql = "UPDATE articles 
                SET titre = ?, description_courte = ?, contenu = ?, id_categorie = ?
                WHERE id = ?";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$titre, $desc, $contenu, $categorie, $id]);

        header("Location: liste.php");
        exit;
    } else {
        $erreur = "Tous les champs sont obligatoires.";
    }
}

/* =========================
   AFFICHAGE (GET)
========================= */
$id = $_GET['id'] ?? $_POST['id'] ?? null;

if (!$id) {
    echo '<div class="error">ID manquant</div>';
    include '../includes/pied.php';
    exit;
}

/* Récupérer article */
$stmt = $pdo->prepare("SELECT * FROM articles WHERE id = ?");
$stmt->execute([$id]);
$article = $stmt->fetch();

if (!$article) {
    echo '<div class="error">Article introuvable</div>';
    include '../includes/pied.php';
    exit;
}

/* Catégories */
$categories = $pdo->query("SELECT id, nom FROM categories")->fetchAll();
?>

<div style="margin-bottom: 2rem;">
    <a href="liste.php" class="btn btn-secondary">⬅ Retour</a>
</div>

<h1>Modifier l'article</h1>

<div style="max-width: 800px; margin: 0 auto;">
    <?php if (!empty($erreur)): ?>
        <div class="error"><?= htmlspecialchars($erreur) ?></div>
    <?php endif; ?>

    <form method="POST">
        <input type="hidden" name="id" value="<?= $article['id'] ?>">

        <div class="form-group">
            <label for="titre">Titre <span style="color: var(--danger);">*</span></label>
            <input type="text" id="titre" name="titre" value="<?= htmlspecialchars($article['titre']) ?>" required>
        </div>

        <div class="form-group">
            <label for="description_courte">Description courte <span style="color: var(--danger);">*</span></label>
            <textarea id="description_courte" name="description_courte" rows="3" required><?= htmlspecialchars($article['description_courte']) ?></textarea>
        </div>

        <div class="form-group">
            <label for="contenu">Contenu <span style="color: var(--danger);">*</span></label>
            <textarea id="contenu" name="contenu" rows="15" required><?= htmlspecialchars($article['contenu']) ?></textarea>
        </div>

        <div class="form-group">
            <label for="id_categorie">Catégorie</label>
            <select id="id_categorie" name="id_categorie">
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= ($cat['id'] == $article['id_categorie']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['nom']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <input type="submit" class="btn btn-primary" value="Enregistrer les modifications" style="flex: 1;">
            <a href="liste.php" class="btn btn-secondary" style="flex: 1; display: flex; align-items: center; justify-content: center;">Annuler</a>
        </div>
    </form>
</div>

<?php include '../includes/pied.php'; ?>