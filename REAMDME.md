# Version symfony 5 de Serpico

## Installation la première fois
* Pour [Api Platform](https://api-platform.com/)
    ```
    composer req api
    ```
(Cette commande requiert symfony flex).
* Setter l'environement : 
  Créer un fichier ```.env```, et le remplir comme suit : 
  >APP_ENV=dev
  >APP_SECRET=258aedbedec809788b3a2672d2d41a18
  >\# MAILER_DSN=smtp://localhost
  >###< symfony/mailer ###
  >###> doctrine/doctrine-bundle ###
  >DATABASE_URL=mysql://_login_:_mdp_@_bddUrl_/_nomBase_?serverVersion=_serveurVersion_
  >CORS_ALLOW_ORIGIN=^https?: //(localhost|127\.0\.0\.1)(:[0-9]+)?$

(**TODO** : gérer le bug du smiley, retirer l'espace entre : et /)
* Installer les dépendances :
    ```
    composer install
    ```

## La base de donnée
Cette partie suppose que tu possèdes une base vide qui tourne, et correctement settée dans le ```.env```.
* Créer le schéma :
    ```
    php bin/console doctrine:migrations:migrate
    ```
(Il faut tester, mais normalement c'est bon)
* Peupler la base : çà se fait à partir des fixtures
    ```
    php bin/console doctrine:fixtures:load
    y
    ```
## L'architecture du projet
#### config : 
Contient tous les .yaml de configuration

#### Deprecated
contient des vielles entité (à supprimer une fois la migra faite)

#### Public :
Contient les CSS, les JS, build et bundles. Correspond en gros au webapp de silex.

#### src

Pour jetbrains
SET GLOBAL time_zone = '+8:00';

## Lancer le serveur de test
    ```
    php -S 127.0.0.1:8111 -t public
    ```

## A tester
```
symfony deploy
```