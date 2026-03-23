<?php
session_start();
if(empty($_SESSION['utilisateur']) || $_SESSION['utilisateur']['role'] !== 'administrateur'){
    header('Location: ../connexion.php');
    exit();
}

require_once '../config/connexion.php';
include '../includes/entete_pageinterne.php';

if(!isset($_GET['id'])){
    die("ID manquant");
}

$id = (int) $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if(!$user){
    die("Utilisateur introuvable");
}

$erreur = "";
$succes = "";

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $login = trim($_POST['login']);
    $role = $_POST['role'];
    $password = $_POST['password'];

    if(empty($nom) || empty($prenom) || empty($login)){
        $erreur = "Champs obligatoires manquants.";
    } else {

        if(!empty($password)){
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE utilisateurs SET nom=?, prenom=?, login=?, password=?, role=? WHERE id=?");
            $params = [$nom, $prenom, $login, $hash, $role, $id];
        } else {
            $stmt = $pdo->prepare("UPDATE utilisateurs SET nom=?, prenom=?, login=?, role=? WHERE id=?");
            $params = [$nom, $prenom, $login, $role, $id];
        }

        try {
            $stmt->execute($params);
            $succes = "Utilisateur modifié avec succès.";
        } catch(PDOException $e){
            $erreur = "Erreur : login déjà utilisé.";
        }
    }
}
?>

<div style="margin-bottom: 2rem;">
    <a href="liste.php" class="btn btn-secondary">⬅ Retour</a>
</div>

<h1>Modifier utilisateur</h1>

<div style="max-width: 600px; margin: 0 auto;">
    <?php if($erreur): ?>
        <div class="error"><?= htmlspecialchars($erreur) ?></div>
    <?php endif; ?>

    <?php if($succes): ?>
        <div class="success">
            ✅ <?= htmlspecialchars($succes) ?>
            <br><br>
            <a href="liste.php" class="btn btn-primary">Retour aux utilisateurs</a>
        </div>
    <?php else: ?>
        <form method="POST">
            <div class="form-group">
                <label for="nom">Nom <span style="color: var(--danger);">*</span></label>
                <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($user['nom']) ?>" required>
            </div>

            <div class="form-group">
                <label for="prenom">Prénom <span style="color: var(--danger);">*</span></label>
                <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($user['prenom']) ?>" required>
            </div>

            <div class="form-group">
                <label for="login">Login <span style="color: var(--danger);">*</span></label>
                <input type="text" id="login" name="login" value="<?= htmlspecialchars($user['login']) ?>" required>
            </div>

            <div class="form-group">
                <label for="password">Nouveau mot de passe (optionnel)</label>
                <input type="password" id="password" name="password" placeholder="Laisser vide pour ne pas changer">
            </div>

            <div class="form-group">
                <label for="role">Rôle</label>
                <select id="role" name="role">
                    <option value="editeur" <?= $user['role']=='editeur'?'selected':'' ?>>Éditeur</option>
                    <option value="administrateur" <?= $user['role']=='administrateur'?'selected':'' ?>>Administrateur</option>
                </select>
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <input type="submit" class="btn btn-primary" value="Modifier" style="flex: 1;">
                <a href="liste.php" class="btn btn-secondary" style="flex: 1; display: flex; align-items: center; justify-content: center;">Annuler</a>
            </div>
        </form>
    <?php endif; ?>
</div>

<?php include '../includes/pied.php'; ?>