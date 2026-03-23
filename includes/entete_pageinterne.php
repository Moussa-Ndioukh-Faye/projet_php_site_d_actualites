<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>L'Écho des Temps - Actualités</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
<header>
    <div class="header-content">
        <a href="../accueil.php" class="logo">◆ NEWS ◆</a>
        <nav>
            <a href="../accueil.php"><i class="material-icons">home</i> Accueil</a>
            <?php 
                if(!isset($_SESSION['utilisateur'])){
                    echo '<a href="../connexion.php"><i class="material-icons">login</i> Connexion</a>';
                } else {
                    echo '<a href="../editeur.php"><i class="material-icons">account_circle</i> Mon Espace</a>';
                    echo '<a href="../deconnexion.php"><i class="material-icons">logout</i> Déconnexion</a>';
                }
            ?>
        </nav>
    </div>
</header>
<main class="main-container">