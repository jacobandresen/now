create sequence account_seq            START 1;
create sequence collection_seq         START 1;
create sequence collection_domain_seq  START 1;
create sequence document_seq           START 1;
create sequence node_seq               START 1;
create sequence attribute_seq          START 1;

create table updates (
   updates_id                       integer PRIMARY KEY NOT NULL UNIQUE,
   description                      varchar(512),
   last_update                      timestamp
);

create table account (
    account_id                      integer  PRIMARY KEY DEFAULT nextval('account_seq'),
    user_name                       varchar(60) NOT NULL UNIQUE,
    password                        varchar(60) NOT NULL,
    first_name                      varchar(60),
    last_name                       varchar(60),
    token                           varchar(60),
    last_seen                       date
);

create table role (
   account_id                       int,
   role                             varchar(20),
   foreign key(account_id)          references account(account_id)
);

create table collection (
    collection_id                   integer PRIMARY KEY DEFAULT nextval('collection_seq'),
    account_id                      integer,
    name                            varchar(512),
    page_limit                      integer,
    level_limit                     integer,
    seen_documents                  integer,
    indexed_documents               integer,
    start_url                       varchar(512),
    last_updated                    date,
    foreign key(account_id)         references account(account_id) on delete cascade
);

create table collection_domain (
    collection_domain_id 	    integer  PRIMARY KEY DEFAULT nextval('collection_domain_seq'),
    collection_id	            integer,
    domain		            varchar(255),
    foreign key(collection_id)      references collection(collection_id)
);

create table document (
    document_id                     integer  PRIMARY KEY DEFAULT nextval('document_seq'),
    collection_id                   integer,
    url                             varchar(512),
    md5                             varchar(255),   
    level                           integer,
    content_type                    varchar(255),
    retrieved                       timestamp,
    content                         TEXT,  
    FOREIGN KEY(collection_id)      references collection(collection_id)
);

create table node (
    node_id                         integer PRIMARY KEY DEFAULT nextval('node_seq'),
    name                            varchar(255),
    content                         TEXT,      
    type                            varchar(255), 
    document_id                     integer,
    FOREIGN KEY(document_id)        references document(document_id) on delete cascade
);
