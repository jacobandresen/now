DELIMITER ;

CREATE TABLE account (
  id                 int NOT NULL primary key auto_increment ,
  username           varchar(256) NOT NULL UNIQUE,
  password           varchar(256) NOT NULL,
  first_name         varchar(256),
  last_name          varchar(256)
);

CREATE TABLE token (
  id                int NOT NULL primary key auto_increment,
  value             varchar(60),
  account_id        int NOT NULL,
  FOREIGN KEY       (account_id)     references account(id)
);

CREATE PROCEDURE sp_get_token(username VARCHAR(60), password VARCHAR(60), OUT P token VARCHAR(255)) 
BEGIN
  DECLARE token varchar(255)
  SELECT t.value FROM account a, token t WHERE a.username  = @username anda.password = @password INTO token 
END;

CREATE PROCEDURE sp_login(token VARCHAR(255))
BEGIN
  SELECT account_id FROM token WHERE value = @token
END;
