ALTER TABLE `dng_tiles`
	CHANGE COLUMN `tcode` `tcode` VARCHAR(8) NULL DEFAULT NULL COLLATE 'utf8_unicode_ci';
	
CREATE index idx_dngtiles_tcode on dng_tiles (tcode);
CREATE index idx_dngtiles_dcode on dng_tiles (dcode);

ALTER TABLE `tiles`
	CHANGE COLUMN `tcode` `tcode` VARCHAR(8) NULL DEFAULT NULL COLLATE 'utf8_unicode_ci';


    