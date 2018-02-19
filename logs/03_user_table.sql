use dnd;

CREATE TABLE `users` (
	`ID` INT(11) NOT NULL AUTO_INCREMENT,
	`email` VARCHAR(40) NOT NULL COLLATE 'utf8_unicode_ci',
	`pass` varchar(30) NOT NULL COLLATE 'utf8_unicode_ci',
	`fullname` varchar(40) NULL DEFAULT 'No name' COLLATE 'utf8_unicode_ci',
	
	PRIMARY KEY (`ID`),
	UNIQUE INDEX `email` (`email`)
)
COLLATE='utf8_unicode_ci'
ENGINE=MyISAM
AUTO_INCREMENT=10
;

alter table dungeons add uid int(11) NOT NULL DEFAULT 0;

alter table dungeons add index dng_user_id (uid);
alter table dungeons add index dng_name (dname);

INSERT INTO `dnd`.`users` (`email`, `pass`, `fullname`) VALUES ('lorenzo.pedrotti@gmail.com', 'emberlee1', 'Lorenzo Pedrotti');
update dungeons set uid = LAST_INSERT_ID() ;