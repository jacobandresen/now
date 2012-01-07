create sequence account_seq START 1;
create sequence token_seq START 1;

create table account (
  account_id         integer  PRIMARY KEY DEFAULT nextval('account_seq'),
  username           varchar(60) NOT NULL UNIQUE,
  password           varchar(60) NOT NULL,
  first_name         varchar(60),
  last_name          varchar(60)
);

create table token (
  token_id          integer PRIMARY KEY DEFAULT nextval('token_seq'),
  value             varchar(60),
  account_id        integer NOT NULL,
  last_seen	        date,
  FOREIGN KEY       (account_id)     references account(account_id)
);
