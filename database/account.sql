-- 2011, Jacob Andresen <jacob.andresen@gmail.com>
create table account (
  id                 int NOT NULL primary key auto_increment ,
  username           varchar(256) NOT NULL UNIQUE,
  password           varchar(256) NOT NULL,
  first_name         varchar(256),
  last_name          varchar(256)
);

create table token (
  id                int NOT NULL primary key auto_increment,
  value             varchar(60),
  account_id        int NOT NULL,
  last_seen	    date,
  FOREIGN KEY       (account_id)     references account(id)
);
