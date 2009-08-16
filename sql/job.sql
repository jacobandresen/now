create table job (
    id int primary key auto_increment not null,
    account_id int, 
    jobtype varchar(12),
    jobstart  datetime,
    jobfinish datetime,
    pending boolean, 
    FOREIGN KEY(account_id) REFERENCES account(id)
);
