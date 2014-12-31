create table role (
    id                              serial,
    rights                          integer,
    name                            varchar(20) NOT NULL UNIQUE
);

insert into role(rights,name) values(100 , 'user');
insert into role(rights,name) values(90  , 'lead');
insert into role(rights,name) values(80  , 'agency');
insert into role(rights,name) values(20  , 'robot');
insert into role(rights,name) values(10  , 'staff');
insert into role(rights,name) values(1   , 'root');

create table account (
    id                              serial,
    username                        varchar(60) NOT NULL UNIQUE,
    password                        varchar(60) NOT NULL,
    firstname                       varchar(60),
    lastname                        varchar(60),
    roleid                          integer,
    foreign key(roleid)             references role(id)
);

insert into account(username, password) values('root','toor');

create table token (
    id                              serial,
    accountid                       integer,
    expires                         date,
    foreign key(accountid)          references account(id)
);

