-- **************************************************************************************
-- Script: 001
-- Description: Parametres : droits_explr_localises = 0
-- Date: 01/04/2016
-- Author: FFI

-- set script vars

CREATE TABLE IF NOT EXISTS dbmigrationlog (
  ScriptNumber varchar(10) NOT NULL,
  ScriptComment varchar(255) NOT NULL
);


INSERT INTO dbmigrationlog VALUES ('0001', 'Parameter droits_explr_localises');

UPDATE parametres SET valeur_param = "0" WHERE  type_param = "pmb" and sstype_param="droits_explr_localises" ;
