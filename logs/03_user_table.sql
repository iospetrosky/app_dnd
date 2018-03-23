use dnd;

CREATE TABLE `users` (
	`ID` INT(11) NOT NULL,
	`email` VARCHAR(40) NOT NULL COLLATE 'utf8_unicode_ci',
	`fullname` VARCHAR(40) NULL DEFAULT 'No name' COLLATE 'utf8_unicode_ci',
	PRIMARY KEY (`ID`),
	UNIQUE INDEX `email` (`email`)
)
COLLATE='utf8_unicode_ci'
ENGINE=MyISAM;

alter table dungeons add uid int(11) NOT NULL DEFAULT 0;

alter table dungeons add index dng_user_id (uid);
alter table dungeons add index dng_name (dname);

use iam;

CREATE TABLE `users` (
	`ID` INT(11) NOT NULL AUTO_INCREMENT,
	`username` VARCHAR(40) NULL DEFAULT 'no name' COLLATE 'utf8_unicode_ci',
	`pass` VARCHAR(30) NOT NULL COLLATE 'utf8_unicode_ci',
	`email` VARCHAR(40) NOT NULL COLLATE 'utf8_unicode_ci',
	`fullname` VARCHAR(40) NULL DEFAULT 'No name' COLLATE 'utf8_unicode_ci',
	PRIMARY KEY (`ID`),
	UNIQUE INDEX `email` (`email`),
	UNIQUE INDEX `fullname` (`username`)
)
COLLATE='utf8_unicode_ci'
ENGINE=MyISAM;