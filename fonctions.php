<?php

session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (
        !isset($_POST['csrf_token']) ||
        !isset($_SESSION['csrf_token']) ||
        !hash_equals($_POST['csrf_token'], $_SESSION['csrf_token'])
    ) {
        die ('Requête refusée : jeton csrf invalide');
    }
}

function initialiserStatistiquesUtilisateur(): void {

    $_SESSION['jeu'] = [
        'sante' => 100,
        'energie' => 100,
        'faim' => 0,
        'nourriture' => 1,
        'materiaux' => 1,
        'etatAbris' => 3,
        'numeroJour' => 1,
    ];
}

function chargerPartieSauvegardee(PDO $pdo, int $utilisateurId): void {
    $sql = "SELECT `santé`, `énergie`, `faim`, `nourriture`, `matériaux`, `état_abris`, `numero_jour`
            FROM utilisateur
            WHERE utilisateur_id = :utilisateur_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':utilisateur_id' => $utilisateurId]);
    $jeu = $stmt->fetch(PDO::FETCH_ASSOC);

    $_SESSION['jeu'] = [
        'sante' => (int) $jeu['santé'],
        'energie' => (int) $jeu['énergie'],
        'faim' => (int) $jeu['faim'],
        'nourriture' => (int) $jeu['nourriture'],
        'materiaux' => (int) $jeu['matériaux'],
        'etatAbris' => (int) $jeu['état_abris'],
        'numeroJour' => (int) $jeu['numero_jour'],
    ];
    appliquerLimitesStatistiques();
}

function appliquerLimitesStatistiques(): void {
    foreach (['sante', 'energie', 'faim'] as $statistique) {
        $_SESSION['jeu'][$statistique] = max(0, min(100, (int) $_SESSION['jeu'][$statistique]));
    }
}

function verifierFinPartie(): ?string {
    appliquerLimitesStatistiques();

    if ($_SESSION['jeu']['sante'] === 0) {
        return 'sante';
    }

    if ($_SESSION['jeu']['energie'] === 0) {
        return 'energie';
    }

    if ($_SESSION['jeu']['faim'] === 100) {
        return 'faim';
    }

    if ($_SESSION['jeu']['numeroJour'] >= 30) {
        return 'gagnee';
    }

    return null;
}

function sauvegarderPartie(PDO $pdo, int $utilisateurId): void {
    appliquerLimitesStatistiques();
    $jeu = $_SESSION['jeu'];
    $sql = "UPDATE utilisateur SET
                `santé` = :sante,
                `énergie` = :energie,
                `faim` = :faim,
                `nourriture` = :nourriture,
                `matériaux` = :materiaux,
                `état_abris` = :etat_abris,
                `numero_jour` = :numero_jour
            WHERE utilisateur_id = :utilisateur_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':sante' => $jeu['sante'],
        ':energie' => $jeu['energie'],
        ':faim' => $jeu['faim'],
        ':nourriture' => $jeu['nourriture'],
        ':materiaux' => $jeu['materiaux'],
        ':etat_abris' => $jeu['etatAbris'],
        ':numero_jour' => $jeu['numeroJour'],
        ':utilisateur_id' => $utilisateurId,
    ]);
}

function chargerStatistiquesUtilisateur(): array {
    appliquerLimitesStatistiques();
    $jeu = $_SESSION['jeu'];
    if ($jeu['etatAbris'] === 3) {
        $etatAbrisBis = 'Intact';
    } elseif ($jeu['etatAbris'] === 2) {
        $etatAbrisBis = 'Bon état';
    } elseif ($jeu['etatAbris'] === 1) {
        $etatAbrisBis = 'Abimé';
    } else {
        $etatAbrisBis = 'Détruit';
    }

    $jeu['etatAbrisBis'] = $etatAbrisBis;
    return $jeu;
}

function jourSuivant(): void {
    if ($_SESSION['jeu']['etatAbris'] <=0) {
        $_SESSION['jeu']['numeroJour']++;
        $_SESSION['jeu']['sante'] -= 20;
        $_SESSION['jeu']['energie'] -= 20;
        $_SESSION['jeu']['faim'] += 20;

        if (random_int(1, 2) === 1) {
        $_SESSION['jeu']['etatAbris']--;
        }
    }else {
        $_SESSION['jeu']['numeroJour']++;
        $_SESSION['jeu']['sante'] -= 10;
        $_SESSION['jeu']['energie'] -= 10;
        $_SESSION['jeu']['faim'] += 10;

        if (random_int(1, 2) === 1) {
        $_SESSION['jeu']['etatAbris']--;
    }}
}

function chercherNourriture(): void {
    $_SESSION['jeu']['nourriture']++;
    $_SESSION['jeu']['energie'] -= 10;
    $_SESSION['jeu']['faim'] += 10;
    $_SESSION['jeu']['sante'] -= 10;
}

function seReposer(): void {
    $_SESSION['jeu']['energie'] += 50;
    $_SESSION['jeu']['sante'] += 30;
}

function manger(): bool {
    if ($_SESSION['jeu']['nourriture'] <= 0) {
        return false;
    }

    $_SESSION['jeu']['sante'] += 30;
    $_SESSION['jeu']['faim'] -= 50;
    $_SESSION['jeu']['nourriture']--;
    return true;

}

function reparerAbris(): bool {
    if ($_SESSION['jeu']['materiaux'] <= 0) {
        return false;
    }

    $_SESSION['jeu']['etatAbris'] = 3;
    $_SESSION['jeu']['materiaux']--;
    $_SESSION['jeu']['energie'] -= 10;
    $_SESSION['jeu']['faim'] += 10;
    $_SESSION['jeu']['sante'] -= 10;
    return true;
}

function recupererMateriaux(): void {
    $_SESSION['jeu']['materiaux']++;
    $_SESSION['jeu']['energie'] -= 5;
    $_SESSION['jeu']['faim'] += 10;
    $_SESSION['jeu']['sante'] -= 10;
}
