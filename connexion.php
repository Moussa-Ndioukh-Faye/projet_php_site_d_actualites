<?php
require_once 'config/connexion.php';
if (session_status() === PHP_SESSION_NONE) session_start();

if (isset($_SESSION['utilisateur'])) {
    header('Location: accueil.php');
    exit();
}

$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $mdp   = $_POST['mot_de_passe'] ?? '';

    if (empty($login) || empty($mdp)) {
        $erreur = 'Veuillez remplir tous les champs.';
    } else {
        $stmt = $pdo->prepare('SELECT * FROM utilisateurs WHERE login = :login LIMIT 1');
        $stmt->execute([':login' => $login]);
        $user = $stmt->fetch(); 

        if ($user && password_verify($mdp, $user['mot_de_passe'])) {
            session_regenerate_id(true);
            $_SESSION['utilisateur'] = [
                'id'     => $user['id'],
                'nom'    => $user['nom'],
                'prenom' => $user['prenom'],
                'login'  => $user['login'],
                'role'   => $user['role'],
            ];
            header('Location: accueil.php');
            exit();
        } else {
            $erreur = 'Identifiants incorrects. Veuillez réessayer.';
        }
    }
}

$base_url   = '';
$page_title = 'Connexion';
require_once 'includes/entete.php';
require_once 'includes/menu.php';
?>

<div class="login-wrap">
    <div class="card login-card">
        <div class="card-body">
            <div class="login-logo"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M240-200h120v-240h240v240h120v-360L480-740 240-560v360Zm-80 80v-480l320-240 320 240v480H520v-240h-80v240H160Zm320-350Z"/></svg></div>
            <h1>Connexion</h1>
            <p class="login-sub">Accès réservé aux éditeurs et administrateurs</p>

            <?php if ($erreur): ?>
            <div class="alert alert-danger"><?= e($erreur) ?></div>
            <?php endif; ?>

            <form id="formConnexion" method="POST" action="connexion.php" novalidate>

                <div class="form-group">
                    <label class="form-label" for="login">
                        Identifiant <span class="required">*</span>
                    </label>
                    <input type="text" id="login" name="login" class="form-control"
                           value="<?= e($_POST['login'] ?? '') ?>"
                           placeholder="Votre login" autocomplete="username">
                    <span class="form-error" id="err-login"></span>
                </div>

                <div class="form-group">
                    <label class="form-label" for="mot_de_passe">
                        Mot de passe <span class="required">*</span>
                    </label>
                    <input type="password" id="mot_de_passe" name="mot_de_passe"
                           class="form-control"
                           placeholder="Votre mot de passe" autocomplete="current-password">
                    <span class="form-error" id="err-mdp"></span>
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%">
                    Se connecter
                </button>
            </form>

            <p style="text-align:center;margin-top:1rem;font-size:13px;color:var(--muted)">
                <a href="accueil.php">← Retour à l'accueil</a>
            </p>
        </div>
    </div>
</div>

<script>
document.getElementById('formConnexion').addEventListener('submit', function(event) {
    event.preventDefault();
    let valide = true;

    const login    = document.getElementById('login').value.trim();
    const mdp      = document.getElementById('mot_de_passe').value;
    const errLogin = document.getElementById('err-login');
    const errMdp   = document.getElementById('err-mdp');

    errLogin.textContent = '';
    errMdp.textContent   = '';
    document.getElementById('login').classList.remove('is-invalid');
    document.getElementById('mot_de_passe').classList.remove('is-invalid');

    if (login.length === 0) {
        errLogin.textContent = "L'identifiant est obligatoire.";
        document.getElementById('login').classList.add('is-invalid');
        valide = false;
    }

    if (mdp.length === 0) {
        errMdp.textContent = 'Le mot de passe est obligatoire.';
        document.getElementById('mot_de_passe').classList.add('is-invalid');
        valide = false;
    }

    if (valide) this.submit();
});
</script>

<?php require_once 'includes/pied.php'; ?>