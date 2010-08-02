drop table if exists account;
create table account (
  id 				int NOT NULL primary key auto_increment ,
  username			varchar(256) NOT NULL UNIQUE,
  password			varchar(256) NOT NULL, 
  first_name			varchar(256),
  last_name			varchar(256)
);

drop table if exists account_privilege;
create table account_privilege (
  account_id			int NOT NULL primary key auto_increment,
  privilege			int,
  FOREIGN KEY(account_id) 	references account(id) 
);
