create table collection (
    id                              serial,
    accountid                       integer,
    name                            varchar(512),
    documentlimit                   integer,
    levellimit                      integer,
    seendocuments                   integer,
    indexeddocuments                integer,
    starturl                        TEXT,
    lastupdated                     date,
    foreign key(accountid)          references account(id)
);

create table document (
    id                              serial,
    collectionid                    integer,
    url                             varchar(512),
    md5                             varchar(255),
    level                           integer,
    content_type                    varchar(255),
    retrieved                       timestamp,
    fulltext                        TEXT,
    FOREIGN KEY(collection_id)      references collection(collection_id)
);

create table field (
    id                              serial,
    name                            TEXT,
    content                         TEXT,
    document_id                     integer,
    FOREIGN KEY(document_id)        references document(id)
);
