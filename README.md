# Après l’invasion de l’IA

Jeu de survie en PHP : incarnez un survivant et gérez ses ressources pour tenir jusqu’au 30e jour après l’invasion de l’intelligence artificielle.

## Fonctionnalités

- Création de compte et connexion avec mot de passe haché.
- Partie sauvegardée par joueur dans une base MySQL.
- Gestion de la santé, de l’énergie, de la faim, de la nourriture, des matériaux et de l’état de l’abri.
- Actions de survie : se reposer, chercher de la nourriture ou des matériaux, réparer l’abri et passer au jour suivant.
- Victoire en atteignant le jour 30. La partie est perdue si la santé ou l’énergie atteint 0, ou si la faim atteint 100.

## Installation

1. Créez la base et la table nécessaires en exécutant le script suivant dans MySQL :

   ```sql
   CREATE DATABASE `après_l_invasion_de_l_ia`


   CREATE TABLE Utilisateur(
    utilisateur_id INT,
    pseudo VARCHAR(50) NOT NULL,
    mot_de_passe VARCHAR(50) NOT NULL,
    santé INT NOT NULL,
    énergie INT NOT NULL,
    faim INT NOT NULL,
    nourriture INT NOT NULL,
    matériaux INT NOT NULL,
    état_abris INT NOT NULL,
    numero_jour INT NOT NULL,
    PRIMARY KEY(utilisateur_id),
    UNIQUE(pseudo)
    );

2. Configurez l’hôte, le nom de la base, l’utilisateur et le mot de passe MySQL dans [`database.php`].
3. Démarrez Wamp, puis ouvrez `accueil.php` depuis votre serveur local. Avec le serveur intégré de PHP, depuis le dossier du projet, visitez <http://localhost/Projet-jeu-PHP-invasion-IA/accueil.php>.
4. Créez un compte pour commencer une partie.

## Utilisation

Chaque action de survie fait avancer le jour et modifie les statistiques. Surveillez la faim, qui augmente avec le temps, et réparez l’abri avant qu’il ne soit détruit : un abri détruit aggrave les pertes quotidiennes. La sauvegarde enregistre la partie sans faire avancer le jour. La déconnexion sauvegarde également la progression.