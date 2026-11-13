<?php
require_once __DIR__ . "/database.php";
require_once __DIR__ . "/fonctions.php";

session_start();
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (
        !isset($_POST['csrf_token']) ||
        !isset($_SESSION['csrf_token']) ||
        !hash_equals($_POST['csrf_token'], $_SESSION['csrf_token'])
    ) {
        die ('Requête refusée : jeton csrf invalide');
    }
}

// récuperer les données envoyées par le formulaire de connexion
$pseudo = trim($_POST['pseudo']  ?? '');
$mot_de_passe = trim($_POST['mot_de_passe']  ?? '');

// vérifier si les champs ont été remplis
if ($pseudo === '' || $mot_de_passe === '') {
    $_SESSION['message'] = "Tous les champs sont obligatoires.";
    header('Location: connexion.php');
    exit;
}

// rechercher dans la base de données
$sql = "SELECT utilisateur_id, pseudo, mot_de_passe FROM utilisateur
    WHERE pseudo = :pseudo";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    'pseudo' => $pseudo
]);

$utilisateur = $stmt->fetch();

// vérifier si l'utilisateur existe
if (!$utilisateur) {
    $_SESSION['message'] = "Vérifiez les champs saisis.";
    header('Location: connexion.php');
    exit;
}

// vérifier le mot de passe
if (!password_verify($mot_de_passe, $utilisateur['mot_de_passe'])) {
    $_SESSION['message'] = "Le mot de passe est incorrect.";
    header('Location: connexion.php');
    exit;
}

// régénérer l'id de la session après la vérification du mdp pour pouvoir conserver les données de la session
session_regenerate_id(true);

// enregistrer l'utilisateur dans la session
$_SESSION['utilisateur_id'] = $utilisateur['utilisateur_id'];
$_SESSION['utilisateur_pseudo'] = $utilisateur['pseudo'];
chargerPartieSauvegardee($pdo, (int) $utilisateur['utilisateur_id']);

header('Location: jeu.php');
exit;

?>