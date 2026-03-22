<?php
require_once 'config/connexion.php';

if (session_status() === PHP_SESSION_NONE) session_start();

if (isset($_SESSION['utilisateur'])){
    header('Location: accueil.php');
    exit();
}

// Fonction de sécurisation pour l'affichage
if (!function_exists('e')) {
    function e($string) {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
}

$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $mdp = $_POST['mot_de_passe'] ?? '';

    if(empty($login) || empty($mdp)){
        $erreur = 'Veuillez remplir tous les champs.';
    } else {
        try {
            $stmt = $pdo->prepare('SELECT * FROM utilisateurs WHERE login = :login LIMIT 1');
            $stmt->execute([':login' => $login]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($mdp, $user['mot_de_passe'])){
                session_regenerate_id(true);
                $_SESSION['utilisateur'] = [
                    'id'       => $user['id'],
                    'nom'      => $user['nom'],
                    'prenom'   => $user['prenom'],
                    'login'    => $user['login'],
                    'role'     => $user['role'],
                ];
                if($user['role'] == 'administrateur'){
                    header('Location: admin.php');
                    exit();
                } else { 
                    header('Location: editeur.php');
                    exit();
                }
            } else {
                $erreur = 'Identifiants incorrects. Veuillez réessayer.';
            }
        } catch (PDOException $e) {
            $erreur = 'Erreur technique. Veuillez réessayer plus tard.';
            error_log($e->getMessage()); // Log l'erreur technique
        }
    }
}

$base_url = '';
$page_title = 'Connexion';
require_once 'includes/entete.php';
?>

<div style="max-width: 500px; margin: 4rem auto;">
    <div class="card">
        <h1 style="margin-bottom: 0.5rem;">Connexion</h1>
        <p style="color: var(--text-tertiary); margin-bottom: 2rem;">Accès réservé aux éditeurs et administrateurs</p>    
        
        <?php if ($erreur): ?>
            <div class="error"><?= e($erreur) ?></div>
        <?php endif; ?>

        <form id="formConnexion" method="POST" action="connexion.php" novalidate>
            <div class="form-group">
                <label for="login">Identifiant <span style="color: var(--danger);">*</span></label>
                <input type="text" id="login" name="login"
                       value="<?= e($_POST['login'] ?? '') ?>"
                       placeholder="Votre login" autocomplete="username">
            </div>

            <div class="form-group">
                <label for="mot_de_passe">Mot de passe <span style="color: var(--danger);">*</span></label>
                <input type="password" id="mot_de_passe" name="mot_de_passe"
                       placeholder="Votre mot de passe" autocomplete="current-password">
            </div>

            <input type="submit" class="btn btn-primary" value="Se connecter" style="width:100%;">
        </form>

        <p style="text-align:center; margin-top:1.5rem; color: var(--text-tertiary);">
            <a href="accueil.php">← Retour à l'accueil</a>
        </p>
    </div>
</div>

<script>
document.getElementById('formConnexion').addEventListener('submit', function(event) {
    event.preventDefault();
    let valide = true;
    
    const login = document.getElementById('login').value.trim();
    const mdp   = document.getElementById('mot_de_passe').value;
    
    if (login.length === 0 || mdp.length === 0) {
        alert('Veuillez remplir tous les champs.');
        valide = false;
    }
    
    if (valide) this.submit();
});
</script>

<?php require_once 'includes/pied.php'; ?>