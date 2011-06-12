-- 2011, Jacob Andresen <jacob.andresen@gmail.com>
create table collection (
  id                              int NOT NULL primary key auto_increment,
  account_id                      int,
  name                            varchar(256),
  page_limit                      int,
  level_limit                     int,
  seen_documents                  int,
  indexed_documents               int,
  start_url                       varchar(512),
  last_updated                    datetime,
  foreign key(account_id)         references account(id)
);

create table collection_domain (
  id 				  int NOT NULL primary key auto_increment,
  collection_id			  int,
  domain			  varchar(255),
  foreign key(collection_id)      references collection(id)
);

create table document (
  id                              int NOT NULL primary key auto_increment,
  collection_id                   int,
  url                             varchar(256),
  md5                             varchar(20),
  level                           int,
  content_type                    varchar(256),
  retrieved                       timestamp,
  content                         LONGTEXT,
  FOREIGN KEY(collection_id)      references collection(id),
  FULLTEXT(content)
) engine=MyISAM;

create table filter (
  id                              int NOT NULL primary key auto_increment,
  name                            varchar(64),
  path				  varchar(255),
  regex                           varchar(255)
) engine=MyISAM;

create table field (
  id                              int NOT NULL primary key auto_increment,
  document_id                     int,
  name                            varchar(256),
  content                         LONGTEXT,
  foreign key(document_id)        references document(id),
  FULLTEXT(content)
) engine=MyISAM;

create table document_field (
  id				  int NOT NULL primary key auto_increment,
  field_id			  int, 
  filter_id			  int,
  foreign key(field_id)           references field(id),
  foreign key(filter_id)          references filter(id)
) engine=MyISAM;
