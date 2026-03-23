<?php
session_start();
require_once '../config/connexion.php';
include '../includes/entete_pageinterne.php';

/* Sécurité */
if (empty($_SESSION['utilisateur'])) {
    echo '<div class="error">Accès refusé. <a href="../connexion.php">Se connecter</a></div>';
    include '../includes/pied.php';
    exit;
}

$id = $_GET['id'] ?? null;

if (!$id) {
    echo '<div class="error">ID manquant</div>';
    include '../includes/pied.php';
    exit;
}

/* Supprimer */
$stmt = $pdo->prepare("DELETE FROM articles WHERE id = ?");
$stmt->execute([$id]);

header("Location: liste.php");
exit;