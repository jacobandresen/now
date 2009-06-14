
--
-- raw storage for crawler
--
create table dump (
 account_id 	int, 
 url 		varchar(256),
 level          int, 
 retrieved	timestamp,
 html		LONGTEXT,
 foreign key(account_id) references account(id)
);


--
-- storage 
--
create table document (
 id int NOT NULL primary key auto_increment,
 account_id      int,
 url 		varchar(256),
 retrieved 	timestamp, 
 level          int, 
 title 		TEXT,
 content 	LONGTEXT,
 md5		varchar(60), 
 FULLTEXT(content), 
 foreign key(account_id)  references account(id)
) engine=MyISAM;
