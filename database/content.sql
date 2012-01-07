create sequence collection_seq START 1;
create sequence collection_domain_seq START 1;
create sequence document_seq START 1;

create table collection (
  collection_id                   integer PRIMARY KEY DEFAULT nextval('collection_seq'),
  account_id                      integer,
  name                            varchar(256),
  page_limit                      integer,
  level_limit                     integer,
  seen_documents                  integer,
  indexed_documents               integer,
  start_url                       varchar(512),
  last_updated                    date,
  foreign key(account_id)         references account(account_id)
);

create table collection_domain (
  id 				                      integer  PRIMARY KEY DEFAULT nextval('collection_domain_seq'), 
  collection_id			              integer,
  domain			                    varchar(255),
  foreign key(collection_id)      references collection(collection_id)
);

create table document (
  document_id                     integer  PRIMARY KEY DEFAULT nextval('document_seq'),
  collection_id                   integer,
  url                             varchar(256),
  md5                             varchar(20),
  level                           integer,
  content_type                    varchar(256),
  retrieved                       timestamp,
  content                         TEXT,
  FOREIGN KEY(collection_id)      references collection(collection_id)
);

create table facet (
  facet_id                        integer PRIMARY KEY DEFAULT nextval('facet_seq'),
  document_id                     integer,
  name                            varchar(256),
  content                         varchar(256) 
)
