<?php


require_once __DIR__ . '/database.php';
require_once __DIR__ . '/fonctions.php';

if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: accueil.php');
    exit;
}

if (!isset($_SESSION['jeu'])) {
    chargerPartieSauvegardee($pdo, (int) $_SESSION['utilisateur_id']);
}

if (!isset($_POST['btnRecommencer']) && verifierFinPartie() !== null) {
    $_SESSION['raisonFinPartie'] = verifierFinPartie();
    header('Location: finPartie.php');
    exit;
}

$utilisateurId = (int) $_SESSION['utilisateur_id'];

require_once __DIR__ . '/boutons.php';

$statistiques = chargerStatistiquesUtilisateur();

$sante = $statistiques['sante'];
$energie = $statistiques['energie'];
$faim = $statistiques['faim'];
$nourriture = $statistiques['nourriture'];
$materiaux = $statistiques['materiaux'];
$etatAbrisBis = $statistiques['etatAbrisBis'];
$numeroJour = $statistiques['numeroJour'];
$message = $_SESSION['message'] ?? '';
unset($_SESSION['message']);

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
    <p>L’intelligence artificielle a envahi le monde. Vous incarnez un survivant qui doit rester en vie pendant 30 jours, en gérant ses ressources et en prenant les bonnes décisions.</p>
    <div class="jeu">
        <div class="jeuDonnees">
            <p class="numeroJour">Jour N° <?= htmlspecialchars((string) $numeroJour, ENT_QUOTES, 'UTF-8') ?>/30</p>
            <p>Santé : <?= htmlspecialchars((string) $sante, ENT_QUOTES, 'UTF-8') ?>/100</p>
            <p>Energie : <?= htmlspecialchars((string) $energie, ENT_QUOTES, 'UTF-8') ?>/100</p>
            <p>Faim : <?= htmlspecialchars((string) $faim, ENT_QUOTES, 'UTF-8') ?>/100</p>
            <p>Nourriture : <?= htmlspecialchars((string) $nourriture, ENT_QUOTES, 'UTF-8') ?></p>
            <p>Matériaux de réparation : <?= htmlspecialchars((string) $materiaux, ENT_QUOTES, 'UTF-8') ?></p>
            <p>Etat de l'abri : <?= htmlspecialchars((string) $etatAbrisBis, ENT_QUOTES, 'UTF-8') ?></p>
        </div>

        <div class="jeuBtns">
            <form method="POST" class="btnsJeu">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
                <button type="submit" name="btnReposer"><span class="emote">🛌</span> Se reposer</button>
                <button type="submit" name="btnRecupererMateriaux"><span class="emote">🛠️</span> Aller récupérer des matériaux pour l'abri</button>
                <button type="submit" name="btnReparerAbris"><span class="emote">⛺</span> Réparer votre abri</button>
                <button type="submit" name="btnChercherNourriture"><span class="emote">🥪</span> Aller chercher de la nourriture</button>
                <button type="submit" name="btnManger"><span class="emote">🍽️</span> Manger</button>
                <button type="submit" name="btnJourSuivant"><span class="emote">🌄</span> Jour suivant</button>
                <button type="submit" name="btnSauvegarder" ><span class="emote">💾</span> Sauvegarder l'avancée</button>
                <button type="submit" name="btnRecommencer" onclick="return confirm('Êtes-vous sûr de vouloir recommencer la partie depuis le début ?');"><span class="emote">🔄️</span> Recommencer la partie</button>               
            </form>
        </div>
    </div>
    <?php if ($message !== ''): ?>
        <p><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>

    <form action="deconnexion.php" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir vous déconnecter ?');">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
        <button type="submit" class="btnDeconnexion"><span class="emote">❌</span> Se déconnecter</button>
    </form>

    <a href="regles.php"><button class="btnRegles"><span class="emote">📜</span> Voir les règles du jeu</button></a>
</body>
</html>
