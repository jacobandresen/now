drop table if exists account;
create table account (
  id 				int NOT NULL primary key auto_increment ,
  username			varchar(256) NOT NULL UNIQUE,
  password			varchar(256) NOT NULL, 
  first_name			varchar(256),
  last_name			varchar(256)
);

drop table if exists account_setting;
create table account_setting (
  id				int NOT NULL primary key auto_increment,
  account_id			int,
  setting_name			varchar(256), 
  name				varchar(256),
  value				varchar(256),
  FOREIGN KEY(account_id) 	references account(id) 
);

drop table if exists account_login_rights;
create table account_login_rights (
  account_id			int NOT NULL primary key auto_increment,
  privilege			int,
  FOREIGN KEY(account_id) 	references account(id) 
);
