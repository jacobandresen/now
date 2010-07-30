drop table if exists document;
create table document (
  id 				int NOT NULL primary key auto_increment,
  url 		        	varchar(256),
  level           		int,
  contenttype     		varchar(256),
  retrieved 	    		timestamp,
  content 	      		LONGTEXT,
  FULLTEXT(content)
) engine=MyISAM;

drop table if exists collection;
create table collection (
  id 				int NOT NULL primary key auto_increment,
  owner_id			int, 
  name				varchar(256),
  page_limit			int, 
  level_limit			int,
  seen_documents		int,
  indexed_documents		int,
  start_url			varchar(512),
  last_updated			datetime, 
  foreign key(owner_id)       	references account(id)
);

drop table if exists collection_in_domain;
create table collection_in_domain (
  collection_id			int NOT NULL,
  domain			varchar(256),
  foreign key(collection_id)    references collection(id) on delete cascade,
  PRIMARY KEY(collection_id, domain)
);

drop table if exists facet;
create table facet (
  id int 			NOT NULL primary key auto_increment,
  account_id      		int,
  document_id			int,
  collection_id			int,
  name				varchar(256),
  content			LONGTEXT,
  FULLTEXT(content),
  foreign key(document_id) 	references document(id),
  foreign key(account_id)  	references account(id)
) engine=MyISAM;
