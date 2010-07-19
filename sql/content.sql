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
  id int			NOT NULL primary key auto_increment,
  name				varchar(256),
  seen_documents		int,
  indexed_documents		int,
  start_url			varchar(512)  
);

drop table if exists collection_in_domain;
create table collection_in_domain (
  collection_id			id,
  domain			varchar(256)
  foreign key(collection_id)    references collection(id)
);

drop table if exists account_collection;
create table account_collection (
  account_id			int,
  collection_id			int,
  foreign key(account_id)	references account(id),
  foreign key(collection_id)	references colleciton(id)
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
