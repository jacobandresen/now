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

drop table if exists document;
create table document (
  id 				int NOT NULL primary key auto_increment,
  owner_id			int, 
  collection_id			int, 
  url 		        	varchar(256),
  md5				varchar(20), 
  level           		int,
  contenttype     		varchar(256),
  retrieved 	    		timestamp,
  content 	      		LONGTEXT,
  FOREIGN KEY(owner_id)	 	REFERENCES account(id),
  FOREIGN KEY(collection_id)    REFERENCES collection(id),
  FULLTEXT(content)
) engine=MyISAM;



drop table if exists domain;
create table domain (
  id				int NOT NULL primary key auto_increment, 
  name				varchar(256),
  collection_id			int,
  foreign key(collection_id)	references collection(id)
);

drop table if exists filter;
create table filter (
  id				int NOT NULL primary key auto_increment,
  name				varchar(12), 
  domain_id			int,
  regex				varchar(256),
  foreign key(domain_id)	references domain(id) 
);

drop table if exists facet;
create table facet (
  id int 			NOT NULL primary key auto_increment,
  owner_id      		int,
  document_id			int,
  collection_id			int,
  name				varchar(256),
  content			LONGTEXT,
  FULLTEXT(content),
  foreign key(document_id) 	references document(id),
  foreign key(owner_id)  	references account(id)
) engine=MyISAM;
