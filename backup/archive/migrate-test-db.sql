CREATE DATABASE db_migration;

USE db_migration

CREATE TABLE users(
    id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    email VARCHAR(20) NOT NULL,
    password VARCHAR(30) NOT NULL,
    username VARCHAR(20) NOT NULL,
    names VARCHAR(30) NOT NULL
)ENGINE=InnoDB COLLATE=utf8_unicode_ci;

insert into users (email, password, username, names) values ('wofowsoj@lemi.nz','oswotiadbegsonva', 'esta' ,'Esta Kutch');
insert into users (email, password, username, names) values ('mojut@ri.dz','ineudojvelwoesfi', 'nyasia' ,'Nyasia, Wyman');
insert into users (email, password, username, names) values ('cevuj@ikujes.es','kizfeubcekbertes', 'ross','Ross Lebsack');
insert into users (email, password, username, names) values ('cehletbof@fer.sd','zeruzpocnajogzoe', 'shawna','Shawna Wolf');
insert into users (email, password, username, names) values ('wacahuw@ero.tw','cidgifadciicvebl', 'buddy', 'Buddy Dare');
