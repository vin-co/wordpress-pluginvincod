=== Plugin Vincod WordPress ===

Contributors: Vinternet Crew
Tags: wine, wines, vin, vins, vincod, vinternet, plugin vincod
Requires at least: 4.0
Tested up to: 4.7
Stable tag: 4.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==
Plugin permettant l'intégration de vos fiches vincod directement sur votre site Wordpress.

== Installation ==
PHP5 Required.

1. Download, unzip and upload to your WordPress plugins directory
2. Activate the plugin within you WordPress Administration Backend
3. Go to Settings > Vincod
4. Configure the Plugin

== Bibliothèques utilisées ==
Bootstrap 3.3.7
Bootswatch 3.3.7 (Theme Lumen pour l'Admin et Yeti pour le Front)
Ionicons 2.0.1

== Comment modifier les fichiers source ? ==

= Prérequis =
NodeJS LTS+
Bower
Gulp-CLI
Node SASS

= Commandes utiles =
Au téléchargement ou `git clone` du projet, lancer la commande `npm run init` qui installera les librairies requises à la modification du projet.

Les feuilles de style se trouvent dans le dossier `(assets/sass)`. Ne pas modifier directement `(assets/css/admin.css)` et `(assets/css/front.css)` car ils seront écrasés à la prochaine compilation.

Pour pouvoir compiler les fichiers SASS en CSS `(assets/css/admin.css)` et `(assets/css/front.css)` et concaténer les librairies JS en un seul fichier `(assets/js/vendor.js)`, lancer la commande `gulp build`.

Pour pouvoir compiler les fichiers SASS en CSS minifiés `(assets/css/admin.css)` et `(assets/css/front.css)` et concaténer les librairies JS en un seul fichier minifié `(assets/js/vendor.js)`, lancer la commande `gulp dist`.

Pour pouvoir modifier les fichiers SASS avec rafraîchissement automatique du navigateur, lancer la commande `gulp browsersync`.

Pour pouvoir modifier les fichiers SASS sans rafraîchissement automatique du navigateur, lancer la commande `gulp watch`.

En cas de problème avec les dépendances, lancer la commande `npm run refresh` qui supprimera toutes les dépendances et les réinstallera.
