<?php
session_start();

// Vérification que l'utilisateur est connecté (éditeur ou admin)
if(empty($_SESSION['utilisateur'])){
    header('Location: ../connexion.php');
    exit();
}

require_once '../config/connexion.php';
include '../includes/entete_pageinterne.php';

// Récupérer toutes les catégories pour le menu déroulant
$categories = $pdo->query("SELECT * FROM categories ORDER BY nom")->fetchAll();

$erreur = '';
$succes = '';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer et nettoyer les données
    $titre = trim($_POST['titre'] ?? '');
    $description_courte = trim($_POST['description_courte'] ?? '');
    $contenu = trim($_POST['contenu'] ?? '');
    $id_categorie = (int)($_POST['id_categorie'] ?? 0);
    $id_auteur = $_SESSION['utilisateur']['id'];
    
    // Validation
    if(empty($titre)) {
        $erreur = 'Le titre est obligatoire.';
    } elseif(empty($description_courte)) {
        $erreur = 'La description courte est obligatoire.';
    } elseif(empty($contenu)) {
        $erreur = 'Le contenu est obligatoire.';
    } elseif($id_categorie <= 0) {
        $erreur = 'Veuillez sélectionner une catégorie.';
    } else {
        // Insertion dans la base de données
        $sql = "INSERT INTO articles (titre, description_courte, contenu, id_categorie, id_auteur) 
                VALUES (:titre, :description_courte, :contenu, :id_categorie, :id_auteur)";
        
        $stmt = $pdo->prepare($sql);
        
        if($stmt->execute([
            ':titre' => $titre,
            ':description_courte' => $description_courte,
            ':contenu' => $contenu,
            ':id_categorie' => $id_categorie,
            ':id_auteur' => $id_auteur
        ])) {
            $succes = 'Article publié avec succès !';
            // Vider le formulaire après succès
            $titre = $description_courte = $contenu = '';
            $id_categorie = 0;
        } else {
            $erreur = 'Erreur lors de la publication.';
        }
    }
}
?>

<div style="margin-bottom: 2rem;">
    <a href="../editeur.php" class="btn btn-secondary"><i class="material-icons">arrow_back</i> Retour à mon espace</a>
</div>

<h1>Publier un nouvel article</h1>

<div style="max-width: 800px; margin: 0 auto;">
    <?php if($erreur): ?>
        <div class="error"><?= htmlspecialchars($erreur) ?></div>
        <a href="../editeur.php" class="btn btn-secondary">Retour à mon espace</a>
    <?php endif; ?>
    
    <?php if($succes): ?>
        <div class="success">
            <?= htmlspecialchars($succes) ?>
            <br><br>
            <a href="ajouter.php" class="btn btn-primary"><i class="material-icons">add</i> Publier un autre article</a>
            <a href="../editeur.php" class="btn btn-secondary"><i class="material-icons">arrow_back</i> Retour à mon espace</a>
        </div>
    <?php else: ?>
        <form method="POST" action="ajouter.php" onsubmit="return validerFormulaire()">
            <div class="form-group">
                <label for="titre">
                    Titre de l'article <span style="color: var(--danger);">*</span>
                </label>
                <input type="text" id="titre" name="titre"
                       value="<?= htmlspecialchars($titre ?? '') ?>" 
                       placeholder="Ex: Les dernières actualités sportives"
                       maxlength="255" required>
            </div>
            
            <div class="form-group">
                <label for="id_categorie">
                    Catégorie <span style="color: var(--danger);">*</span>
                </label>
                <select id="id_categorie" name="id_categorie" required>
                    <option value="">-- Choisir une catégorie --</option>
                    <?php foreach($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" 
                            <?= (isset($id_categorie) && $id_categorie == $cat['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="description_courte">
                    Description courte <span style="color: var(--danger);">*</span>
                </label>
                <textarea id="description_courte" name="description_courte"
                          rows="3" placeholder="Un résumé de l'article en quelques phrases"
                          maxlength="500" required><?= htmlspecialchars($description_courte ?? '') ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="contenu">
                    Contenu de l'article <span style="color: var(--danger);">*</span>
                </label>
                <textarea id="contenu" name="contenu"
                          rows="15" placeholder="Rédigez votre article ici..."
                          required><?= htmlspecialchars($contenu ?? '') ?></textarea>
            </div>
            
            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <input type="submit" class="btn btn-primary" value="&#xe254; Publier l'article" style="flex: 1;">
                <a href="../editeur.php" class="btn btn-secondary" style="flex: 1; display: flex; align-items: center; justify-content: center;"><i class="material-icons">cancel</i> Annuler</a>
        </form>
    <?php endif; ?>
</div>

<script>
function validerFormulaire() {
    let valide = true;
    
    // Récupérer les valeurs
    const titre = document.getElementById('titre').value.trim();
    const description = document.getElementById('description_courte').value.trim();
    const contenu = document.getElementById('contenu').value.trim();
    const categorie = document.getElementById('id_categorie').value;
    
    // Réinitialiser les messages d'erreur
    document.getElementById('err-titre').textContent = '';
    document.getElementById('err-description').textContent = '';
    document.getElementById('err-contenu').textContent = '';
    document.getElementById('err-categorie').textContent = '';
    
    // Validation
    if(titre === '') {
        document.getElementById('err-titre').textContent = 'Le titre est obligatoire';
        valide = false;
    } else if(titre.length < 5) {
        document.getElementById('err-titre').textContent = 'Le titre doit faire au moins 5 caractères';
        valide = false;
    }
    
    if(description === '') {
        document.getElementById('err-description').textContent = 'La description est obligatoire';
        valide = false;
    } else if(description.length < 20) {
        document.getElementById('err-description').textContent = 'La description doit faire au moins 20 caractères';
        valide = false;
    }
    
    if(contenu === '') {
        document.getElementById('err-contenu').textContent = 'Le contenu est obligatoire';
        valide = false;
    } else if(contenu.length < 50) {
        document.getElementById('err-contenu').textContent = 'Le contenu doit faire au moins 50 caractères';
        valide = false;
    }
    
    if(categorie === '') {
        document.getElementById('err-categorie').textContent = 'Veuillez choisir une catégorie';
        valide = false;
    }
    
    return valide;
}

</script>

<?php include '../includes/pied.php'; ?>