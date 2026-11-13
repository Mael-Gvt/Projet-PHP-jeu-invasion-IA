<?php
require_once __DIR__ . "/database.php";
require_once __DIR__ . "/fonctions.php";

session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Méthode non autorisée');
}

if (
    !isset($_POST['csrf_token']) ||
    !isset($_SESSION['csrf_token']) ||
    !hash_equals($_POST['csrf_token'], $_SESSION['csrf_token'])
) {
    http_response_code(403);
    exit('Requête refusée : jeton csrf invalide');
}

if (isset($_SESSION['utilisateur_id'], $_SESSION['jeu'])) {
    sauvegarderPartie($pdo, (int) $_SESSION['utilisateur_id']);
}

$_SESSION = [];

if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params['path'],
        $params['domain'],
        $params['secure'],
        $params['httponly']
    );
}

session_destroy();

header('Location: accueil.php');
exit;
?>