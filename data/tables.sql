drop table if exists document;
drop table if exists dump;
drop table if exists store;
drop table if exists filter;
drop table if exists domain;
drop table if exists user;

create table user (
 id int NOT NULL primary key auto_increment,
 login 		varchar(256) NOT NULL UNIQUE,
 password 	varchar(256),
 level_limit	int, 
 crawl_limit 	int
);

create table domain (
 id             int NOT NULL primary key auto_increment, 
 user_id        int,
 urlbase   	varchar(256),
 foreign key(user_id) references user(id),
 UNIQUE(user_id,urlbase)
);

create table filter (
 id                     int NOT NULL primary key auto_increment,
 domain_id                int,
 name                   varchar(256),
 value                  TEXT, 
 foreign key(domain_id) references domain(id),
 UNIQUE(domain_id, name)
);

create table store (
 id             int NOT NULL primary key auto_increment, 
 storename      varchar(256), 
 owner          int,
 name		varchar(256), 
 value          varchar(256)
);

create table dump (
 user_id 	int, 
 url 		varchar(256),
 level          int, 
 retrieved	timestamp,
 html		text,
 foreign key(user_id) references user(id)
);

create table document (
 id int NOT NULL primary key auto_increment,
 user_id 	int, 
 domain_id      int,
 url 		varchar(256),
 retrieved 	timestamp, 
 level          int, 
 title 		text,
 content 	text,
 md5		varchar(60), 
 FULLTEXT(content), 
 foreign key(user_id) references user(id)
) engine=MyISAM;
