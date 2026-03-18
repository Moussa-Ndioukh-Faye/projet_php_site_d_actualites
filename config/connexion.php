<?php
//-----------------------
//parametre de connexion
//-----------------------
$host = 'localhost'; //Adresse du serveur MySQL
$dbname = 'site_actualite'; //Nom de la base de donnees 
$user = 'root'; //Nom utilisateur
$password = ''; //Mot de passe

//--------------------------------------
//options PDO pour securite et encodage
//--------------------------------------

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Gestion des erreurs
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Mode de récupération associatif
    PDO::ATTR_EMULATE_PREPARES => false, // Désactiver l'émulation des requêtes préparées
];

try {
    $dsn = 'mysql:host=localhost;dbname=site_actualite;charset=utf8mb4';
    $pdo = new PDO($dsn, $user, $password);
}catch(PDOException $e) {
        die('<div style="padding:20px;background:#fee;border:1px solid red;font-family:monospace">
        <strong>Erreur de connexion à la base de données :</strong><br>' .
        htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') .
        '</div>');
}

// Fonction raccourcie pour échapper les sorties HTML (anti-XSS)
function e(string $str): string {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

?>