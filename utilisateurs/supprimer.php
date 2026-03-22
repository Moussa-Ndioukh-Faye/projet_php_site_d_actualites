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
    echo '<div class="error">❌ Action interdite. Vous ne pouvez pas vous supprimer vous-même.</div>';
    include '../includes/pied.php';
    exit;
}

$req = $pdo->prepare("SELECT COUNT(*) FROM articles WHERE id_auteur = ?");
$req->execute([$id]);
$count = $req->fetchColumn();

if ($count > 0) {
    echo '<div class="error">❌ Impossible de supprimer : cet utilisateur a des articles.</div>';
    echo '<div style="margin-top: 1rem;"><a href="liste.php" class="btn btn-secondary">⬅ Retour</a></div>';
} else {
    $req = $pdo->prepare("DELETE FROM utilisateurs WHERE id = ?");
    $req->execute([$id]);
    echo '<div class="success">✅ Utilisateur supprimé avec succès !</div>';
    echo '<div style="margin-top: 1rem;"><a href="liste.php" class="btn btn-primary">Voir la liste</a></div>';
}

include '../includes/pied.php';
?>