<?php
session_start();
if(empty($_SESSION['utilisateur']) || $_SESSION['utilisateur']['role'] !== 'administrateur'){
    header('Location: ../connexion.php');
    exit();
}

require_once '../config/connexion.php';
include '../includes/entete_pageinterne.php';

$erreur = "";
$succes = "";

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $login = trim($_POST['login']);
    $password = $_POST['password'];
    $role = $_POST['role'];

    if(empty($nom) || empty($prenom) || empty($login) || empty($password)){
        $erreur = "Tous les champs sont obligatoires.";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, prenom, login, mot_de_passe, role, date_creation) VALUES (?, ?, ?, ?, ?, NOW())");

        try {
            $stmt->execute([$nom, $prenom, $login, $hash, $role]);
            $succes = "Utilisateur ajouté avec succès.";
        } catch(PDOException $e){
            $erreur = "Erreur : " . $e->getMessage();
        }
    }
}
?>

<div style="margin-bottom: 2rem;">
    <a href="liste.php" class="btn btn-secondary"><i class="material-icons">arrow_back</i> Retour</a>
</div>

<h1>Ajouter un utilisateur</h1>

<div style="max-width: 600px; margin: 0 auto;">
    <?php if($erreur): ?>
        <div class="error"><?= htmlspecialchars($erreur) ?></div>
    <?php endif; ?>

    <?php if($succes): ?>
        <div class="success">
            <i class="material-icons">check_circle</i> <?= htmlspecialchars($succes) ?>
            <br><br>
            <a href="liste.php" class="btn btn-primary"><i class="material-icons">add</i> Ajouter un autre utilisateur</a>
            <a href="../admin.php" class="btn btn-secondary"><i class="material-icons">arrow_back</i> Retour</a>
        </div>
    <?php else: ?>
        <form method="POST">
            <div class="form-group">
                <label for="nom">Nom <span style="color: var(--danger);">*</span></label>
                <input type="text" id="nom" name="nom" placeholder="Nom de l'utilisateur" required>
            </div>

            <div class="form-group">
                <label for="prenom">Prénom <span style="color: var(--danger);">*</span></label>
                <input type="text" id="prenom" name="prenom" placeholder="Prénom" required>
            </div>

            <div class="form-group">
                <label for="login">Login <span style="color: var(--danger);">*</span></label>
                <input type="text" id="login" name="login" placeholder="Identifiant pour la connexion" required>
            </div>

            <div class="form-group">
                <label for="password">Mot de passe <span style="color: var(--danger);">*</span></label>
                <input type="password" id="password" name="password" placeholder="Mot de passe" required>
            </div>

            <div class="form-group">
                <label for="role">Rôle</label>
                <select id="role" name="role">
                    <option value="editeur">Éditeur</option>
                    <option value="administrateur">Administrateur</option>
                </select>
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <input type="submit" class="btn btn-primary" value="&#xe145; Ajouter l'utilisateur" style="flex: 1;">
                <a href="liste.php" class="btn btn-secondary" style="flex: 1; display: flex; align-items: center; justify-content: center;"><i class="material-icons">cancel</i> Annuler</a>
            </div>
        </form>
    <?php endif; ?>
</div>

<?php include '../includes/pied.php'; ?>