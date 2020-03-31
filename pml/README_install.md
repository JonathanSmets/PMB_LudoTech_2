Quand vous installez une nouvelle version 
sur une version précédente, vous devez impérativement, 
après la copie des fichiers contenus dans cette archive 
sur le serveur web :

vérifiez que les paramètres contenus dans :
./includes/db_param.inc.php
./opac_css/includes/opac_db_param.inc.php

correspondent à votre configuration (faites une sauvegarde avant !)

En outre :
Vous devez faire la mise à jour du noyau de la base de données.
Rien ne sera perdu.

Connectez-vous de manière habituelle à PML, le style graphique peut 
être différent, voire absent (affichage assez décousu sans couleur ni images)

Passez en Administration > Outils > maj base pour mettre à jour le noyau de
votre base de données.

Une série de messages vous indiqueront les mises à jour successives, 
poursuivez la mise à jour de la base par le lien en bas de page jusqu'à voir 
s'afficher 'Votre base est à jour en version...'

Vous pouvez alors éditer votre compte pour modifier éventuellement 
vos préférences, notamment le style d'affichage.
