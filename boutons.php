<?php
require_once __DIR__ . '/fonctions.php';

if (isset($_POST['btnSauvegarder'])) {
    sauvegarderPartie($pdo, $utilisateurId);
    $_SESSION['message'] = "Votre avancée a été sauvegardée.";
} elseif (isset($_POST['btnRecommencer'])) {
    initialiserStatistiquesUtilisateur();
    unset($_SESSION['raisonFinPartie']);
    sauvegarderPartie($pdo, $utilisateurId);
} elseif (verifierFinPartie() === null) {
    if (isset($_POST['btnJourSuivant'])) {
        jourSuivant();
    } elseif (isset($_POST['btnReposer'])) {
        seReposer();
        jourSuivant();
    } elseif (isset($_POST['btnRecupererMateriaux'])) {
        recupererMateriaux();
        jourSuivant();
    } elseif (isset($_POST['btnReparerAbris']) && reparerAbris()) {
        jourSuivant();
    } elseif (isset($_POST['btnChercherNourriture'])) {
        chercherNourriture();
        jourSuivant();
    } elseif (isset($_POST['btnManger']) && manger()) {
        jourSuivant();
    }
}

appliquerLimitesStatistiques();

if (
    verifierFinPartie() !== null
    && !isset($_POST['btnRecommencer'])
    && !isset($_POST['btnSauvegarder'])
) {
    sauvegarderPartie($pdo, $utilisateurId);
    $_SESSION['raisonFinPartie'] = verifierFinPartie();
    header('Location: finPartie.php');
    exit;
}
