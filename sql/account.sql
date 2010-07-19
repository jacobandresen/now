create table account (
  id 				int NOT NULL primary key auto_increment,
  username			varchar(256) NOT NULL UNIQUE,
  password			varchar(256) NOT NULL, 
  first_name			varchar(256),
  last_name			varchar(256),
  FOREIGN KEY(login_id) 	references login(id)
);

create table account_setting (
  id				int NOT NULL primary key auto_increment,
  user_id			int,
  setting_name			varchar(256), 
  name				varchar(256),
  value				varchar(256),
  FOREIGN KEY(user_id) 		references user(id)
);

create table account_login_rights (
  account_id			int NOT NULL primary key auto_increment,
  privilege			int,
  FOREIGN KEY(login_id) 	references login(id),
  FOREIGN KEY(account_id) 	references account(id) 
);
