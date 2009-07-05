--
-- this table should be available in the base system
-- (If you are integrating you want to replace this)
create table user (
 id int NOT NULL primary key auto_increment,
 login 		  varchar(256) NOT NULL UNIQUE,
 password 	varchar(256),
 admin      boolean 
);

-- search engine account
-- one user could have several accounts
-- (this is specific to YASE)
-- here we maintain one account for each domain
--
drop table if exists account;
create table account (
 id int NOT NULL primary key auto_increment, 
 user_id      int,
 domain       varchar(256), 
 level_limit	int, 
 crawl_limit 	int,
 FOREIGN KEY(user_id) references user(id)
);

-- maintain settings for each account
--
drop table if exists setting;
create table setting (
 id             int NOT NULL primary key auto_increment, 
 account_id     int,
 section        varchar(256),
 name			 	    varchar(256), 
 value          LONGTEXT,
 FOREIGN KEY(account_id) references account(id)
); 


