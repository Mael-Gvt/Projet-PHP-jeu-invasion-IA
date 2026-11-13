<?php

session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
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
    <div class="formAccueil">
        <label for="btnInscription">Vous êtes nouveau ? Créer un compte pour démarer votre aventure !</label>
        <button name="btnInscription"><a href="inscription.php">Créer un compte</a></button><br>
        <label for="btnconnexion">Vous avez déjà un compte ?</label>
        <button name="btnConnexion"><a href="connexion.php">Se connecter</a></button>
    </div>
</body>
</html>