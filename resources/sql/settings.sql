--
-- this table should be available in the base system
--
create table user (
 id int NOT NULL primary key auto_increment,
 login 		varchar(256) NOT NULL UNIQUE,
 password 	varchar(256)
);

-- search engine account
-- one user could have several accounts
--
create table account (
 id int NOT NULL primary key auto_increment, 
 user_id    int,
 name           varchar(256) NOT NULL UNIQUE, 
 level_limit	int, 
 crawl_limit 	int,
 FOREIGN KEY(user_id) references user(id)
);

--
-- settings pr account
--
create table setting (
 id             int NOT NULL primary key auto_increment, 
 account_id     int,
 tablename      varchar(256), 
 name		varchar(256), 
 value          LONGTEXT,
 type 		varchar(256),
 FOREIGN KEY(account_id) references account(id)
);

create table crawlerfilter {
 id             int NOT NULL primary key auto_increment, 
 account_id     int,
 name		varchar(256), 
 value          LONGTEXT,
 FOREIGN KEY(account_id) references account(id)
};

create table indexerfilter {
 id             int NOT NULL primary key auto_increment, 
 account_id     int,
 name		varchar(256), 
 value          LONGTEXT,
 FOREIGN KEY(account_id) references account(id)
};



--
-- domains to be crawled for accounts
--
create table domain (
  id int NOT NULL primary key auto_increment,
  account_id   int, 
  name      varchar(256), 
  FOREIGN KEY(account_id) references account(id)
);


