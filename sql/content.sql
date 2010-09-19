drop table if exists collection;
create table collection (
  id 				int NOT NULL primary key auto_increment,
  parent_id			int, 
  name				varchar(256),
  page_limit			int, 
  level_limit			int,
  seen_documents		int,
  indexed_documents		int,
  start_url			varchar(512),
  last_updated			datetime, 
  foreign key(parent_id)       	references account(id)
);

-- Where the document has been stored
drop table if exists domain;
create table domain (
  id				int NOT NULL primary key auto_increment, 
  name				varchar(256),
  parent_id			int,
  foreign key(parent_id)	references collection(id)
);

-- original content  of document
drop table if exists document;
create table document (
  id 				int NOT NULL primary key auto_increment,
  parent_id			int, 
  url 		        	varchar(256),
  md5				varchar(20), 
  level           		int,
  content_type     		varchar(256),
  retrieved 	    		timestamp,
  content 	      		LONGTEXT,
  FOREIGN KEY(parent_id)        REFERENCES collection(id),
  FULLTEXT(content)
) engine=MyISAM;

-- for filtering pages from index
drop table if exists filter;
create table filter (
  id				int NOT NULL primary key auto_increment,
  name				varchar(64), 
  parent_id			int,
  regex				varchar(256),
  foreign key(parent_id)	references domain(id) 
);

-- for extracting content from pages to facet
drop table if exists extractor;
create table extractor (
  id				int NOT NULL primary key auto_increment,
  name				varchar(64),
  parent_id			int,
  page_regex			varchar(256),
  regex				varchar(256),
  foreign key(parent_id)	references domain(id)			
);

-- extracted content
drop table if exists facet;
create table facet (
  id int 			NOT NULL primary key auto_increment,
  parent_id			int,
  name				varchar(256),
  content			LONGTEXT,
  FULLTEXT(content),
  foreign key(parent_id) 	references document(id)
) engine=MyISAM;
