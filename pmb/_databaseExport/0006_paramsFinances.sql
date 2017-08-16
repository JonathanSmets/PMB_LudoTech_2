-- **************************************************************************************
-- Script: 0005
-- Description: paramètres divers
-- Date: 21/06/2016
-- Author: FFI

-- set script vars

CREATE TABLE IF NOT EXISTS dbmigrationlog (
  ScriptNumber varchar(10) NOT NULL,
  ScriptComment varchar(255) NOT NULL
);

INSERT INTO dbmigrationlog VALUES ('0006', 'Parametres Finances');

UPDATE parametres SET valeur_param = "2" WHERE  type_param = "pmb" and sstype_param="gestion_amende" ;

UPDATE parametres SET valeur_param = "1" WHERE  type_param = "pmb" and sstype_param="gestion_financiere" ;

UPDATE parametres SET valeur_param = "1" WHERE  type_param = "pmb" and sstype_param="gestion_financiere_caisses" ;

UPDATE parametres SET valeur_param = "2" WHERE  type_param = "pmb" and sstype_param="gestion_tarif_prets" ;

UPDATE parametres SET valeur_param = "7" WHERE  type_param = "pmb" and sstype_param="gestion_financiere_periode" ;

UPDATE parametres SET valeur_param = "1" WHERE  type_param = "pmb" and sstype_param="allow_extend_fee" ;
