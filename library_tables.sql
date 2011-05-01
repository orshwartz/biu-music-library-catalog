GRANT ALL PRIVILEGES ON *.* TO arhip@localhost
IDENTIFIED BY '12345' WITH GRANT OPTION;


CREATE DATABASE library;

use library;

DROP TABLE IF EXISTS media CASCADE;

CREATE TABLE media (
    id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
    eng_name CHAR(100) NOT NULL,
    heb_name CHAR(100) NOT NULL,
    deleted SMALLINT UNSIGNED,
    PRIMARY KEY (id)
);

DROP TABLE IF EXISTS records CASCADE;

create table records(
media_id  SMALLINT UNSIGNED NOT NULL REFERENCES media(id),
id MEDIUMINT UNSIGNED AUTO_INCREMENT,
item_no VARCHAR(100),
item_second_title VARCHAR(200),
second_author VARCHAR(100),
series VARCHAR(100),
composer VARCHAR(100),
composition_formal_name VARCHAR(200),
composition_title VARCHAR(200),
publisher VARCHAR(100),
publisher_place VARCHAR(100),
year YEAR ,
solist VARCHAR(100),
solist2 VARCHAR(100),
solist3 VARCHAR(100),
performance_group VARCHAR(100),
performance_group2 VARCHAR(100),
performance_group3 VARCHAR(100),
orchestra VARCHAR(100),
orchestra2 VARCHAR(100),
orchestra3 VARCHAR(100),
conductor VARCHAR(100),
conductor2 VARCHAR(100),
conductor3 VARCHAR(100),
collection VARCHAR(100),
notes TEXT,
subject VARCHAR(100),
subject2 VARCHAR(100),
subject3 VARCHAR(100),
hebrew_composer VARCHAR(100),
PRIMARY KEY (id)
);

#alter table records add publisher_place VARCHAR(100);

