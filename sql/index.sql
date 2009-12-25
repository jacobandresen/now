drop table if exists dump;
create table dump (
  account_id 	    int,
  url 		        varchar(256),
  level           int,
  contenttype     varchar(256),
  retrieved	      timestamp,
  content		      LONGTEXT,
  foreign         key(account_id) references account(id)
);

drop table if exists document;
create table document (
  id int NOT      NULL primary key auto_increment,
  account_id      int,
  url 		        varchar(256),
  level           int,
  contenttype     varchar(256),
  retrieved 	    timestamp,
  title 		      TEXT,
  content 	      LONGTEXT,
  md5		          varchar(60),
  FULLTEXT(content),
  foreign key(account_id)  references account(id)
) engine=MyISAM;
