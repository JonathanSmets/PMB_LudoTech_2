# Petites & Moyennes Ludothèques (PML)
Ce logiciel de gestion de ludothèques offre notamment les fonctionnalités suivantes :
- gestion des notices, avec partage via Z39.50
- gestion des exemplaires
- gestion des emprunteurs
- catalogue en ligne

# Architecture
Le logiciel est conçu selon une architecture trois tiers classiques :
- un serveur de données, en MySQL
- le serveur d'application web de votre choix, prenant en charge PHP
- une interface web, en PHP.
<br/>L'installation sur votre serveur d'application est donc accessible à tout-e professionnel-le du web, et aucune installation sur vos postes de travail n'est nécessaire.

# Origines
PML est un fork de PMB LudoTech (https://github.com/cocof-cirb/pmb_ludoTech) de la COCOF/Ludéo, v4.2 (03/2020).
<br/>Il est né suite au besoin de faire évoluer le logiciel de façon indépendante.
<br/>PMB LudoTech est lui-même un fork de PMB (https://forge.sigb.net/projects/pmb/files) de SIGB, v4.2.6 (08/2017).
<br/>Il s'agissait alors d'un logiciel de gestion de bibliothèque.
<br/>Ces deux entités ne sont pas affiliées avec PML, qui est géré par le collectif Ludilab (https://www.ludilab.eu)
