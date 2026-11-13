<?php
session_start();

if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: accueil.php');
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
<body class="pageRegles">
    <h1>Après l’invasion de l’IA</h1>

    <h2>Les règles de survie :</h2>

    <p><span class="emote">🏆</span> Condition de victoire :</p>
    <ul><li>Atteindre le jour N°30.</li></ul>
        
    
    <p><span class="emote">💔 </span> Conditions de défaite :</p>
    <ul>
        <li>Santé = 0 -> vous mourrez de maladie,</li>
        <li>Energie = 0 -> vous mourrez d'épuisement,</li>
        <li>Faim = 100 -> vous mourrez de faim.</li>
    </ul>
    <p class="msgAttention"><span class="emote">⚠️</span> Attention, si votre abris est détruit, vous perdrez plus de statistiques chaque jour ! <span class="emote">⚠️</span></p>
    <a href="jeu.php" ><button class="btnRetourJeu"><span class="emote">🧟‍♂️</span> Retourner à la survie</button></a>
</body>
</html>