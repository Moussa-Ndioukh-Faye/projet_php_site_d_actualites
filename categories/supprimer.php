<?php
session_start();
if(empty($_SESSION['utilisateur']) || !in_array($_SESSION['utilisateur']['role'], ['editeur', 'administrateur'])){
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

$stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
$stmt->execute([$id]);

header("Location: liste.php");
exit();