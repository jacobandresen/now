create sequence account_seq START 1;

create table account (
    account_id                integer  PRIMARY KEY DEFAULT nextval('account_seq'),
    user_name                 varchar(60) NOT NULL UNIQUE,
    password                  varchar(60) NOT NULL,
    first_name                varchar(60),
    last_name                 varchar(60),
    token                     varchar(60),
    last_seen                 date
);

create table role (
   account_id                 int,
   role                       varchar(20),
   foreign key(account_id)    references account(account_id)
);
