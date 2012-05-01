create sequence collection_seq START 1;
create sequence collection_domain_seq START 1;
create sequence document_seq START 1;
create sequence node_seq START 1;
create sequence attribute_seq START 1;

-- a collection of documents
create table collection (
    collection_id                   integer PRIMARY KEY DEFAULT nextval('collection_seq'),
    account_id                      integer,
    name                            varchar(512),
    page_limit                      integer,
    level_limit                     integer,
    seen_documents                  integer,
    indexed_documents               integer,
    start_url                       varchar(512),
    last_updated                    date,
    foreign key(account_id)         references account(account_id) on delete cascade
);

-- which domains to allow for a collection
create table collection_domain (
    collection_domain_id 	        integer  PRIMARY KEY DEFAULT nextval('collection_domain_seq'),
    collection_id			        integer,
    domain			                varchar(255),
    foreign key(collection_id)      references collection(collection_id)
);

-- document for transport over HTTP
create table document (
    document_id                     integer  PRIMARY KEY DEFAULT nextval('document_seq'),
    collection_id                   integer,
    url                             varchar(512),
    md5                             varchar(255),   -- used to check if we allready crawled the doc
    level                           integer,
    content_type                    varchar(255),
    retrieved                       timestamp,
    content                         TEXT,           -- flat text version of content in nodes
    FOREIGN KEY(collection_id)      references collection(collection_id)
);

-- node in document
create table node (
    node_id                         integer PRIMARY KEY DEFAULT nextval('node_seq'),
    name                            varchar(255),  -- json key name or xml tag name
    content                         TEXT,          -- content in current node
    type                            varchar(255),   -- datatype
    document_id                     integer,
    FOREIGN KEY(document_id)        references document(document_id) on delete cascade
);
