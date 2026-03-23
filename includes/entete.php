<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>L'Écho des Temps - Actualités</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header>
    <div class="header-content">
        <a href="accueil.php" class="logo">◆ NEWS ◆</a>
        <nav>
            <a href="accueil.php">Accueil</a>
            <?php 
                if(!isset($_SESSION['utilisateur'])){
                    echo '<a href="connexion.php">Connexion</a>';
                } else {
                    echo '<a href="editeur.php">Mon Espace</a>';
                    echo '<a href="deconnexion.php">Déconnexion</a>';
                }
            ?>
        </nav>
    </div>
</header>
<main class="main-container">