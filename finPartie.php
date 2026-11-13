<?php
require_once __DIR__ . '/fonctions.php';

if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: accueil.php');
    exit;
}

$raison = $_SESSION['raisonFinPartie'] ?? null;
$numeroJour = $_SESSION['jeu']['numeroJour'] ?? 0;

$messages = [
    'sante' => [
        'titre' => 'Partie perdue !',
        'details' => 'Votre santé est tombée à 0/100, vous êtes mort de maladie au jour ' . $numeroJour . '.',
    ],
    'energie' => [
        'titre' => 'Partie perdue !',
        'details' => 'Votre énergie est tombée à 0/100, vous êtes mort d\'épuisement au jour ' . $numeroJour . '.',
    ],
    'faim' => [
        'titre' => 'Partie perdue !',
        'details' => 'Votre faim a atteint 100/100, vous êtes mort de faim au jour ' . $numeroJour . '.',
    ],
    'gagnee' => [
        'titre' => 'Félicitations ' . ($_SESSION['utilisateur_pseudo'] ?? 'joueur') . ' !',
        'details' => 'Vous avez survécu jusqu’au jour 30. Vous avez gagné !',
    ],
];

if (!isset($messages[$raison])) {
    header('Location: jeu.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Après l'invasion de l'IA</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Chelsea+Market&display=swap" rel="stylesheet">
</head>
<body>
    <h1>Après l’invasion de l’IA</h1>

    <h2 class="finPartie">Partie terminée</h2>
    <div class="resultat">
        <p class="msgResultat"><span class="resultatPartie"><?= htmlspecialchars($messages[$raison]['titre'], ENT_QUOTES, 'UTF-8') ?></span><br> <?= htmlspecialchars($messages[$raison]['details'], ENT_QUOTES, 'UTF-8') ?></p>

        <form action="jeu.php" method="POST">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
            <button type="submit" name="btnRecommencer" onclick="return confirm('Recommencer la partie depuis le début ?');">
            🔄️  Commencer une nouvelle partie ?
            </button>
        </form>

        <form action="deconnexion.php" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir vous déconnecter ?');">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
            <button type="submit" class="btnDeconnexion">❌ Se déconnecter</button>
        </form>

    </div>
</body>
</html>
