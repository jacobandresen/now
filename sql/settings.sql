create table user (
  id int NOT NULL primary key auto_increment,
  login 		varchar(256) NOT NULL UNIQUE,
  password 	varchar(256),
  admin     boolean
);

drop table if exists account;
create table account (
  id          int NOT NULL primary key auto_increment,
  user_id     int,
  domain      varchar(256),
  level_limit	int,
  crawl_limit 	int,
  FOREIGN KEY(user_id) references user(id)
);

drop table if exists crawlerfilter;
create table crawlerfilter (
  id            int NOT NULL primary key auto_increment,
  account_id    int,
  name          varchar(256),
  value         LONGTEXT,
  FOREIGN KEY(account_id) references account(id)
);

drop table if exists indexerfilter;
create table indexerfilter (
  id            int NOT NULL primary key auto_increment,
  account_id    int,
  name          varchar(256),
  value         LONGTEXT,
  FOREIGN KEY(account_id) references account(id)
)
;

