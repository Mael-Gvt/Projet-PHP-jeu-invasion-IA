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

// récuperer les données saisies par l'utilisateur
$pseudo = trim($_POST['pseudo'] ?? '');
$mot_de_passe = trim($_POST['mot_de_passe'] ?? '');

// vérifier les informations saisies
if ($pseudo === '' || $mot_de_passe === '') {
    $_SESSION['message'] = "Tous les champs sont obligatoires.";
    header('Location: inscription.php');
    exit;
}

if (strlen($mot_de_passe) < 10) {
    $_SESSION['message'] = "Le mot de passe doit contenir au moins 10 caractères.";
    header('Location: inscription.php');
    exit;
}

$hash = password_hash($mot_de_passe, PASSWORD_DEFAULT);

try {
    $sql = "INSERT INTO utilisateur (pseudo, mot_de_passe, santé, énergie, faim, nourriture, matériaux, état_abris, numero_jour)
    VALUES (:pseudo, :mot_de_passe, :sante, :energie, :faim, :nourriture, :materiaux, :etat_abris, :numero_jour)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':pseudo' => $pseudo,
        ':mot_de_passe' => $hash,
        ':sante' => 100,
        ':energie' => 100,
        ':faim' => 0,
        ':nourriture' => 1,
        ':materiaux' => 1,
        ':etat_abris' => 3,
        ':numero_jour' => 1,
    ]);

    session_regenerate_id(true);
    $_SESSION['utilisateur_id'] = (int) $pdo->lastInsertId();
    $_SESSION['utilisateur_pseudo'] = $pseudo;
    initialiserStatistiquesUtilisateur();
} catch (PDOException $e) {
    if ($e->getCode() === '23000') {
        $_SESSION['message'] = "Ce pseudo est déjà utilisé.";
    } else {
        error_log($e->getMessage());
        $_SESSION['message'] = "Une erreur est survenue lors de l'inscription.";
    }

    header('Location: inscription.php');
    exit;
}

header('Location: jeu.php');
exit;
?>