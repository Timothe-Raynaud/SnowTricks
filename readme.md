# Installation

Voici les differentes étapes d'installation du projet en local :
- Lancer la commande : Composer install
- Parameter .env.local pour qu'il ait access à une base de données. 
- Lancer les commandes suivantes en acceptant avec 'yes' lorsque necessaire :
  - php bin/console doctrine:database:create
  - php bin/console doctrine:migrations:migrate
  - php bin/console doctrine:fixtures:load
- Ensuite faire les commande suivantes pour l'installation et la compilation des assets :
  - npm install (node module version 19, faire 'nvm use 19' si necessaire)
  - npm run build

# Livrable

À la racine du projet, vous trouverez un dossier 'livrable' comprenant les different élement demandé pour le livrable 
 - Un dossier diagramme comprenant tous les diagrammes. 
 - Un fichier symfonyInsight avec un lien vers la dernière analyse du projet.
