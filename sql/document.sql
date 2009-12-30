drop table if exists document;
create table document (
  id int NOT      	NULL primary key auto_increment,
  account_id      	int,
  url 		        varchar(256),
  level           	int,
  contenttype     	varchar(256),
  retrieved 	    	timestamp,
  content 	      	LONGTEXT,
  FULLTEXT(content),
  foreign key(account_id)  references account(id)
) engine=MyISAM;

create table index_info (
  id int		NOT NULL primary key auto_increment,
  account_id		int,
  document_id		int,
  md5		        varchar(60),
  updated		timestamp,  
  foreign key(document_id) references document(id),
  foreign key(account_id)  references account(id)
);

drop table if exists facet;
create table facet (
  id int 		NOT NULL primary key auto_increment,
  account_id      	int,
  document_id		int,
  name			varchar(256),
  content		LONGTEXT,
  FULLTEXT(content),
  foreign key(document_id) references document(id),
  foreign key(account_id)  references account(id)
) engine=MyISAM;
