use dnd;

drop table users;

create table dng_masters (
    ID INT(11) NOT NULL,
    notes varchar(250) default 'No notes',
    PRIMARY KEY (ID)
)
COLLATE='utf8_unicode_ci'
ENGINE=MyISAM;