-- **************************************************************************************
-- Script: 003
-- Description: grille pour les notices
-- Date: 13/04/2016
-- Author: LC

-- set script vars

CREATE TABLE IF NOT EXISTS dbmigrationlog (
  ScriptNumber varchar(10) NOT NULL,
  ScriptComment varchar(255) NOT NULL
);

INSERT INTO dbmigrationlog VALUES ('0003', 'Grille notices');


UPDATE parametres SET valeur_param = "1" WHERE type_param = "pmb" and sstype_param="form_editables";

--
-- Contenu de la table `grilles`
--

DROP TABLE IF EXISTS `grilles`;
CREATE TABLE `grilles` (
  `grille_typdoc` char(2) NOT NULL DEFAULT 'a',
  `grille_niveau_biblio` char(1) NOT NULL DEFAULT 'm',
  `grille_localisation` mediumint(8) NOT NULL DEFAULT '0',
  `descr_format` longtext,
  PRIMARY KEY (`grille_typdoc`,`grille_niveau_biblio`,`grille_localisation`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `grilles`
--

LOCK TABLES `grilles` WRITE;
/*!40000 ALTER TABLE `grilles` DISABLE KEYS */;
INSERT INTO `grilles` VALUES ('r','m',0,'<formpage relative=\'yes\'>\n  <etirable id=\'el0Child\' visible=\'yes\'  order=\'1\' />\n  <etirable id=\'el1Child\' visible=\'yes\'  order=\'2\' />\n  <etirable id=\'el2Child\' visible=\'yes\'  order=\'3\' />\n  <etirable id=\'el3Child\' visible=\'yes\'  order=\'4\' />\n  <etirable id=\'el4Child\' visible=\'yes\'  order=\'5\' />\n  <etirable id=\'el5Child\' visible=\'yes\'  order=\'6\' />\n  <etirable id=\'el6Child\' visible=\'yes\'  order=\'7\' />\n  <etirable id=\'el7Child\' visible=\'no\'  order=\'8\' />\n  <etirable id=\'el8Child\' visible=\'yes\'  order=\'9\' />\n  <etirable id=\'el9Child\' visible=\'yes\'  order=\'10\' />\n  <etirable id=\'el11Child\' visible=\'yes\'  order=\'11\' />\n  <etirable id=\'el10Child\' visible=\'yes\'  order=\'12\' />\n  <movable id=\'el0Child_0\' visible=\'yes\' parent=\'el0Child\'/>\n  <movable id=\'el0Child_1\' visible=\'yes\' parent=\'el0Child\'/>\n  <movable id=\'el0Child_2\' visible=\'no\' parent=\'el0Child\'/>\n  <movable id=\'el0Child_3\' visible=\'no\' parent=\'el0Child\'/>\n  <movable id=\'el0Child_4\' visible=\'no\' parent=\'el0Child\'/>\n  <movable id=\'el1Child_0\' visible=\'yes\' parent=\'el1Child\'/>\n  <movable id=\'el1Child_2\' visible=\'yes\' parent=\'el1Child\'/>\n  <movable id=\'el1Child_3\' visible=\'no\' parent=\'el1Child\'/>\n  <movable id=\'el2Child_0\' visible=\'yes\' parent=\'el2Child\'/>\n  <movable id=\'el2Child_1\' visible=\'no\' parent=\'el2Child\'/>\n  <movable id=\'el2Child_3\' visible=\'no\' parent=\'el2Child\'/>\n  <movable id=\'el2Child_4\' visible=\'no\' parent=\'el2Child\'/>\n  <movable id=\'el2Child_7\' visible=\'yes\' parent=\'el2Child\'/>\n  <movable id=\'el3Child_0\' visible=\'yes\' parent=\'el3Child\'/>\n  <movable id=\'el4Child_0\' visible=\'no\' parent=\'el4Child\'/>\n  <movable id=\'el4Child_1\' visible=\'no\' parent=\'el4Child\'/>\n  <movable id=\'el4Child_2\' visible=\'no\' parent=\'el4Child\'/>\n  <movable id=\'el4Child_3\' visible=\'no\' parent=\'el4Child\'/>\n  <movable id=\'el4Child_4\' visible=\'no\' parent=\'el4Child\'/>\n  <movable id=\'move_nbr_min_joueurs\' visible=\'yes\' parent=\'el4Child\'/>\n  <movable id=\'move_nbr_max_joueurs\' visible=\'yes\' parent=\'el4Child\'/>\n  <movable id=\'move_age_min\' visible=\'yes\' parent=\'el4Child\'/>\n  <movable id=\'move_age_max\' visible=\'yes\' parent=\'el4Child\'/>\n  <movable id=\'move_duree_moy\' visible=\'yes\' parent=\'el4Child\'/>\n  <movable id=\'el5Child_0\' visible=\'no\' parent=\'el5Child\'/>\n  <movable id=\'el5Child_1\' visible=\'no\' parent=\'el5Child\'/>\n  <movable id=\'el5Child_2\' visible=\'no\' parent=\'el5Child\'/>\n  <movable id=\'move_principe_jeu\' visible=\'yes\' parent=\'el5Child\'/>\n  <movable id=\'move_analyse_pedagogique\' visible=\'yes\' parent=\'el5Child\'/>\n  <movable id=\'move_regles_jeu\' visible=\'yes\' parent=\'el5Child\'/>\n  <movable id=\'move_contenu_jeu\' visible=\'yes\' parent=\'el5Child\'/>\n  <movable id=\'el6Child_0\' visible=\'yes\' parent=\'el6Child\'/>\n  <movable id=\'el6Child_1\' visible=\'no\' parent=\'el6Child\'/>\n  <movable id=\'el6Child_2\' visible=\'yes\' parent=\'el6Child\'/>\n  <movable id=\'el7Child_0\' visible=\'no\' parent=\'el7Child\'/>\n  <movable id=\'el7Child_1\' visible=\'yes\' parent=\'el7Child\'/>\n  <movable id=\'el8Child_0\' visible=\'yes\' parent=\'el8Child\'/>\n  <movable id=\'el8Child_1\' visible=\'yes\' parent=\'el8Child\'/>\n  <movable id=\'move_regles\' visible=\'yes\' parent=\'el9Child\'/>\n  <movable id=\'move_message\' visible=\'yes\' parent=\'el9Child\'/>\n  <movable id=\'el11Child_0\' visible=\'no\' parent=\'el11Child\'/>\n  <movable id=\'el10Child_4\' visible=\'yes\' parent=\'el10Child\'/>\n  <movable id=\'el10Child_0\' visible=\'yes\' parent=\'el10Child\'/>\n  <movable id=\'el10Child_7\' visible=\'yes\' parent=\'el10Child\'/>\n  <movable id=\'el10Child_1\' visible=\'yes\' parent=\'el10Child\'/>\n  <movable id=\'el10Child_2\' visible=\'yes\' parent=\'el10Child\'/>\n  <movable id=\'el10Child_3\' visible=\'yes\' parent=\'el10Child\'/>\n</formpage>');
 
