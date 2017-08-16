<?php

	include('util.php');

	$patch_id = "fix thesaurus Ludotheques";
	$simulation = TRUE;
	$debug = FALSE;
	
	define('THESAURUS_MOT_CLEF', "Mots clés ");
	
	global $dbh;
	
	echo "<h1>Lancement patch " . $patch_id . " </h1>";
	
	/*
	 *   correction du thesaurus pour pouvoir donner la formation
	 *   Février 2017 
	 * */
	
	
	// 1. on delete les thesaurus 3 et 4
	
	deleteThesaurus(2);
	deleteThesaurus(3);
	deleteThesaurus(4);
	
	// 2. on recree le thesaurus MOT CLEF

	$thesaurusID = isThesaurusExisting(THESAURUS_MOT_CLEF);
	
	if ($thesaurusID === FALSE) {
	// on va créer le thésaurus et créer les entrées
	$thesaurusID = importThesaurus(THESAURUS_MOT_CLEF, "fr_FR");
	
	$DictionnaryTerms = array(
	"Compétences" => array("Action Tri","Argumentation","Classement","Discrimination auditive","Discrimination olfactive","Discrimination tactile ","Discrimination visuelle","Ecriture/gestion graphique","Enfilage ","Expression corporelle","Expression orale ","Fractions","Géométrie","Gestion de données","Grandeurs","Heure","Laçage","Langue ","Latéralité","Lecture","Logique","Mathématiques","Mémoire","Mesures","Nombre","Opérations mathématiques","Organisation spatiale ","Orthographe","Psychomotricité","Quantités","Réflexes","Réflexion","Repérage spatio-temporel","Vocabulaire"),
	"Contexte d'utilisation" => array("Ambiance","Anniversaire","Jeux à deux ","Jeux d'eau","Jeux d'équipes","Jeux d'extérieur","Jeux dans le noir ","Jeux de cartes","Jeux familiaux","Jeux magnétiques","Jeux pour 1 joueur","Jeux pour 6 joueurs ou +","Jeux pour joueurs avertis ","Jeux surdimensionnés"),
	"Mécanismes" => array("Adresse","Affrontement","Blocage","Bluff","Capture","Casse-tête","Choix simultanés ","Collections/familles","Commerce","Connaissance","Connexion de tuiles ","Conquête","Construction","Coopération","Course","Créativité","Deck building","Déduction","Défausse","Défi","Dessin","Draft","Echange/troc","Empilement","Enchères","Enigme","Equilibre","Gestion","Imagination","Improvisation","Jeux de lancer","Jeux de majorité","Jeux de paris","Jeux de plis ","Négociation","Objectif secret ","Observation","Observation","Paires","Parcours","Placement","Prise de risques ","Programmation","Questions réponses","Rapidité","Rôles","Stop ou encore ","Stratégie"),
	"Thèmes" => array("Abstrait ","Afrique","Agriculture","Alimentation","Alphabet","Animaux aquatiques ","Animaux de la ferme ","Animaux domestiques","Animaux sauvages","Antiquité","arbre","Argent","Armée","Art","Asie ","Aventure","Aviation","Bande desssinée ","Bateau","Belgique","Bijou","Billes","Campagne ","Carnaval","Casino","Chanson","Château","Chevalier ","Chiffre","Cinéma","Cirque","Code ","Code de la route","Contes","Continent","Corps humain","Couleurs","Cuisine","Culture générale","Déguisement","Désert","Détective","Détective","Dînette","Dinosaures","Disney","Docteur","Dragon","Eau","Ecole","Ecologie","Economie","Egypte ","Elevage ","Emotions","Engrenages","enquête","enquête","Espace","Europe","Exploration","Famille","Fantastique","Fantôme","Far west","Fée","Ferme","flore","Forêt","Formes","fruits","Garage","Géographie","Guerre","Halloween","Histoire","Ile","Indiens","Insectes","Jardin","Jungle","Label Ludo","Labyrinthe","Légendes","Légendes","Littérature","Loto","Loup","Magasin","Magicien","Maison","Marionnettes","Médical","Mer","Météo","Métiers","Mime","Mine","Miroir","Monde ","Monstre","Montagne","Musique","Mythologie","Nature","Oeuf","Oiseaux","Orient","Outils","Pâques","Perles","Peur","Pirates","Poissons","Police","Pompier","Préhistoire","Prince(sse)","Reptiles","Robots","Rythmes","Saisons","Sciences","Secret","Sécurité","Sens ","Sorcier","Sport","Symbole","Télévision","Temps (durée)","Terre","Théätre","Train","Transport","Trésor","Vampire","Vêtements","Ville","Voitures","Voyage","Zombie")
	);
	
	
	$racineID = getRacineForThesaurus($thesaurusID);
	
	foreach ($DictionnaryTerms as $key => $values) {
	
	$noeudFirstLevelID = add_categ($key,$thesaurusID,$racineID,"fr_FR");
	
	foreach ($values as $value) {
	add_categ($value,$thesaurusID,$noeudFirstLevelID,"fr_FR");
	}
	
	}
	}
	
	// 3. on parcourt toutes les notices
	// pour chaque mot clé libre, on recree le lien avec le thesaurus mot cle
	
	$listNotices = getAllNotices();
	
	
	foreach ($listNotices as $notice)
	{
	
	$motsCles =  explode(';', $notice['index_l']);
	
	$order = 0;
	foreach ($motsCles as $motCle) {
	$categoryId = importCategoryThesaurus($motCle, THESAURUS_MOT_CLEF, "fr_FR"); // la fonction teste l'existence du thésaurus, le crée si il n'existe pas
	if ($categoryId === FALSE) {
	echo "<br>Erreur de Thésaurus <br>";
	break;
	} else {				
	importCategoryNotice($notice['notice_id'], $categoryId, $order);
	$order++;
	}
	}	
	}
	
	
	echo 'FIN DU PATCH ' . $patch_id . '<br>';
	

?>