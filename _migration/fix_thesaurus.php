<?php

	include('util.php');

	$patch_id = "fix thesaurus Ludotheques";
	$simulation = TRUE;
	$debug = FALSE;
	
	define('THESAURUS_MOT_CLEF', "Mots cl�s ");
	
	global $dbh;
	
	echo "<h1>Lancement patch " . $patch_id . " </h1>";
	
	/*
	 *   correction du thesaurus pour pouvoir donner la formation
	 *   F�vrier 2017 
	 * */
	
	
	// 1. on delete les thesaurus 3 et 4
	
	deleteThesaurus(2);
	deleteThesaurus(3);
	deleteThesaurus(4);
	
	// 2. on recree le thesaurus MOT CLEF

	$thesaurusID = isThesaurusExisting(THESAURUS_MOT_CLEF);
	
	if ($thesaurusID === FALSE) {
	// on va cr�er le th�saurus et cr�er les entr�es
	$thesaurusID = importThesaurus(THESAURUS_MOT_CLEF, "fr_FR");
	
	$DictionnaryTerms = array(
	"Comp�tences" => array("Action Tri","Argumentation","Classement","Discrimination auditive","Discrimination olfactive","Discrimination tactile ","Discrimination visuelle","Ecriture/gestion graphique","Enfilage ","Expression corporelle","Expression orale ","Fractions","G�om�trie","Gestion de donn�es","Grandeurs","Heure","La�age","Langue ","Lat�ralit�","Lecture","Logique","Math�matiques","M�moire","Mesures","Nombre","Op�rations math�matiques","Organisation spatiale ","Orthographe","Psychomotricit�","Quantit�s","R�flexes","R�flexion","Rep�rage spatio-temporel","Vocabulaire"),
	"Contexte d'utilisation" => array("Ambiance","Anniversaire","Jeux � deux ","Jeux d'eau","Jeux d'�quipes","Jeux d'ext�rieur","Jeux dans le noir ","Jeux de cartes","Jeux familiaux","Jeux magn�tiques","Jeux pour 1 joueur","Jeux pour 6 joueurs ou +","Jeux pour joueurs avertis ","Jeux surdimensionn�s"),
	"M�canismes" => array("Adresse","Affrontement","Blocage","Bluff","Capture","Casse-t�te","Choix simultan�s ","Collections/familles","Commerce","Connaissance","Connexion de tuiles ","Conqu�te","Construction","Coop�ration","Course","Cr�ativit�","Deck building","D�duction","D�fausse","D�fi","Dessin","Draft","Echange/troc","Empilement","Ench�res","Enigme","Equilibre","Gestion","Imagination","Improvisation","Jeux de lancer","Jeux de majorit�","Jeux de paris","Jeux de plis ","N�gociation","Objectif secret ","Observation","Observation","Paires","Parcours","Placement","Prise de risques ","Programmation","Questions r�ponses","Rapidit�","R�les","Stop ou encore ","Strat�gie"),
	"Th�mes" => array("Abstrait ","Afrique","Agriculture","Alimentation","Alphabet","Animaux aquatiques ","Animaux de la ferme ","Animaux domestiques","Animaux sauvages","Antiquit�","arbre","Argent","Arm�e","Art","Asie ","Aventure","Aviation","Bande desssin�e ","Bateau","Belgique","Bijou","Billes","Campagne ","Carnaval","Casino","Chanson","Ch�teau","Chevalier ","Chiffre","Cin�ma","Cirque","Code ","Code de la route","Contes","Continent","Corps humain","Couleurs","Cuisine","Culture g�n�rale","D�guisement","D�sert","D�tective","D�tective","D�nette","Dinosaures","Disney","Docteur","Dragon","Eau","Ecole","Ecologie","Economie","Egypte ","Elevage ","Emotions","Engrenages","enqu�te","enqu�te","Espace","Europe","Exploration","Famille","Fantastique","Fant�me","Far west","F�e","Ferme","flore","For�t","Formes","fruits","Garage","G�ographie","Guerre","Halloween","Histoire","Ile","Indiens","Insectes","Jardin","Jungle","Label Ludo","Labyrinthe","L�gendes","L�gendes","Litt�rature","Loto","Loup","Magasin","Magicien","Maison","Marionnettes","M�dical","Mer","M�t�o","M�tiers","Mime","Mine","Miroir","Monde ","Monstre","Montagne","Musique","Mythologie","Nature","Oeuf","Oiseaux","Orient","Outils","P�ques","Perles","Peur","Pirates","Poissons","Police","Pompier","Pr�histoire","Prince(sse)","Reptiles","Robots","Rythmes","Saisons","Sciences","Secret","S�curit�","Sens ","Sorcier","Sport","Symbole","T�l�vision","Temps (dur�e)","Terre","Th��tre","Train","Transport","Tr�sor","Vampire","V�tements","Ville","Voitures","Voyage","Zombie")
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
	// pour chaque mot cl� libre, on recree le lien avec le thesaurus mot cle
	
	$listNotices = getAllNotices();
	
	
	foreach ($listNotices as $notice)
	{
	
	$motsCles =  explode(';', $notice['index_l']);
	
	$order = 0;
	foreach ($motsCles as $motCle) {
	$categoryId = importCategoryThesaurus($motCle, THESAURUS_MOT_CLEF, "fr_FR"); // la fonction teste l'existence du th�saurus, le cr�e si il n'existe pas
	if ($categoryId === FALSE) {
	echo "<br>Erreur de Th�saurus <br>";
	break;
	} else {				
	importCategoryNotice($notice['notice_id'], $categoryId, $order);
	$order++;
	}
	}	
	}
	
	
	echo 'FIN DU PATCH ' . $patch_id . '<br>';
	

?>