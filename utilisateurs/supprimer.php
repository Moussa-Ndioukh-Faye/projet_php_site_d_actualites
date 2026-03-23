<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
if(empty($_SESSION['utilisateur']) || $_SESSION['utilisateur']['role'] !== 'administrateur'){
    header('Location: ../connexion.php');
    exit();
}

require_once '../config/connexion.php';
include '../includes/entete_pageinterne.php';

if(!isset($_GET['id'])){
    echo '<div class="error">ID manquant</div>';
    include '../includes/pied.php';
    exit;
}

$id = (int) $_GET['id'];

// empêcher suppression de soi-même
if($id == $_SESSION['utilisateur']['id']){
    echo '<div class="error"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/></svg> Action interdite. Vous ne pouvez pas vous supprimer vous-même.</div>';
    include '../includes/pied.php';
    exit;
}

$req = $pdo->prepare("SELECT COUNT(*) FROM articles WHERE id_auteur = ?");
$req->execute([$id]);
$count = $req->fetchColumn();

if ($count > 0) {
    echo '<div class="error"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/></svg> Impossible de supprimer : cet utilisateur a des articles.</div>';
    echo '<div style="margin-top: 1rem;"><a href="liste.php" class="btn btn-secondary"><i class="material-icons">arrow_back</i> Retour</a></div>';
} else {
    $req = $pdo->prepare("DELETE FROM utilisateurs WHERE id = ?");
    $req->execute([$id]);
    echo '<div class="success"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M382-240 154-468l57-57 171 171 367-367 57 57-424 424Z"/></svg> Utilisateur supprimé avec succès !</div>';
    echo '<div style="margin-top: 1rem;"><a href="liste.php" class="btn btn-primary"><i class="material-icons">list</i> Voir la liste</a></div>';
}

include '../includes/pied.php';
?>