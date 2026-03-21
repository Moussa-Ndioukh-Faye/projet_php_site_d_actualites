<?php

require_once 'config/connexion.php';

if (session_status() === PHP_SESSION_NONE) session_start();

if (isset($_SESSION['utilisateur'])){
    header('Location: accueil.php');
    exit();
}

$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $mdp = $_POST['mot_de_passe'] ?? '';

    //validation PHP cote serveur
   if(empty($login) || empty($mdp)){
    $erreur = 'Veuiller remplir tous les champs.';
   } else {
          // Requête préparée — anti injection SQL
        $stmt = $pdo->prepare('SELECT * FROM utilisateurs WHERE login = :login LIMIT 1');
        $stmt->execute([':login' => $login]);
        $stmt = $stmt->fetch();

        if ($user && password_verify($mdp, $user['mot_de_passe'])){
            //connexion reussie
            session_regenerate_id(true); //Previent la fixation de session
            $_SESSION['utilisateur'] = [
                'id'       => $user['id'],
                'nom'      => $user['nom'],
                'prenom'   => $user['prenom'],
                'login'    => $user['login'],
                'role'     => $user['role'],
            ];
            header('Location: accueil.php');
            exit();
            } else {
                //Message generique - ne pas preciser si c'est le login ou le mdp
                $erreur = 'Identifiants incorrects. Veuillez reessayer.';
            }
   }
    
}

$base_url ='';
$page_title = 'connexion';
require_once 'includes/entete.php';
?>
<nav class="main-nav">
    <div class="container nav-inner">
        <ul class="nav-links">
            <li><a href="accueil.php"> Accueil</li>
        </ul>
    </div>
</nav>
<div class="login-wrap">
    <div class="card-body">
         <div class="login-logo">......fermer......</div>
            <h1>Connexion</h1>
            <p class="login-sub">Accès réservé aux éditeurs et administrateurs</p>    
            

             <?php if ($erreur): ?>
            <div class="alert alert-danger"><?= e($erreur) ?></div>
            <?php endif; ?>

             <form id="formConnexion" method="POST" action="connexion.php" novalidate>
                <div class="form-group">
                    <label class="form-label" for="login">Identifiant <span class="required">*</span></label>
                    <input type="text" id="login" name="login" class="form-control"
                           value="<?= e($_POST['login'] ?? '') ?>"
                           placeholder="Votre login" autocomplete="username">
                    <span class="form-error" id="err-login"></span>
                </div>
 
                <div class="form-group">
                    <label class="form-label" for="mot_de_passe">Mot de passe <span class="required">*</span></label>
                    <input type="password" id="mot_de_passe" name="mot_de_passe" class="form-control"
                           placeholder="Votre mot de passe" autocomplete="current-password">
                    <span class="form-error" id="err-mdp"></span>
                </div>
 
                <button type="submit" class="btn btn-primary" style="width:100%">Se connecter</button>
            </form>
 
            <p style="text-align:center;margin-top:1rem;font-size:13px;color:var(--muted)">
                <a href="accueil.php">← Retour à l'accueil</a>
            </p>
        </div>
    </div>
</div>
</div></main>

<script>
    document.getElementById('formConnexion').addEventListener('submit', function(event) {
    event.preventDefault();
    let valide = true;
 
    const login = document.getElementById('login').value.trim();
    const mdp   = document.getElementById('mot_de_passe').value;
    const errLogin = document.getElementById('err-login');
    const errMdp   = document.getElementById('err-mdp');
 
    errLogin.textContent = '';
    errMdp.textContent   = '';
    document.getElementById('login').classList.remove('is-invalid');
    document.getElementById('mot_de_passe').classList.remove('is-invalid');
 
    if (login.length === 0) {
        errLogin.textContent = 'L\'identifiant est obligatoire.';
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