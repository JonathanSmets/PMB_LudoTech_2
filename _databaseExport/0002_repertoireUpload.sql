-- **************************************************************************************
-- Script: 002
-- Description: creation du repertoire upload pour les PDF
-- Date: 07/04/2016
-- Author: FFI

-- set script vars

CREATE TABLE IF NOT EXISTS dbmigrationlog (
  ScriptNumber varchar(10) NOT NULL,
  ScriptComment varchar(255) NOT NULL
);


INSERT INTO dbmigrationlog VALUES ('0002', 'Repertoire upload');

INSERT INTO upload_repertoire (repertoire_id, repertoire_nom, repertoire_url, repertoire_path, repertoire_navigation, repertoire_hachage, repertoire_subfolder, repertoire_utf8) VALUES
(1, 'Documents', '', '/srv/data/html/docnotice/', 0, 0, 20, 0);
 
INSERT INTO upload_repertoire (repertoire_id, repertoire_nom, repertoire_url, repertoire_path, repertoire_navigation, repertoire_hachage, repertoire_subfolder, repertoire_utf8) VALUES
(2, 'vignettes', '', '/srv/data/html/vignettenotice/', 0, 0, 20, 0);
 