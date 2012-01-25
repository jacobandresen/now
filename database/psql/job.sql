create sequence job_seq START 1;
create sequence job_seq START 1;

create table job_type (
  job_type_id        integer PRIMARY KEY DEFAULT nextval('job_type_seq'),
  name               varchar(60) NOT NULL UNIQUE
);

create table job (
  job_id             integer  PRIMARY KEY DEFAULT nextval('job_seq'),
  name               varchar(60) NOT NULL UNIQUE,
  collection_id      integer NOT NULL,
  last_modified      datetime,
  last_run           datetime,
  start_time         datetime,
  stop_time          datetime,
  FOREIGN KEY        (collection_id) references collection(collection_id)
);
