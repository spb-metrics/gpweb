-- $CVSHeader$

CREATE DATABASE /*! IF NOT EXISTS */ adodb_sessoes;

USE adodb_sessoes;

DROP TABLE /*! IF EXISTS */ sessoes;

CREATE TABLE /*! IF NOT EXISTS */ sessoes (
	sesskey		CHAR(32)	/*! BINARY */ NOT NULL DEFAULT '',
	expiry		INT(11)		/*! UNSIGNED */ NOT NULL DEFAULT 0,
	expireref	VARCHAR(64)	DEFAULT '',
	data		LONGTEXT	DEFAULT '',
	PRIMARY KEY	(sesskey),
	INDEX expiry (expiry)
);
