
-- DROP DATABASE apit;

CREATE DATABASE apit
  WITH OWNER = postgres
       ENCODING = 'UTF8'
       TABLESPACE = pg_default
       LC_COLLATE = 'en_US.UTF-8'
       LC_CTYPE = 'en_US.UTF-8'
       CONNECTION LIMIT = -1;


-- Schema: apit

-- DROP SCHEMA apit;

CREATE SCHEMA apit
  AUTHORIZATION postgres;


-- Table: apit.users

-- DROP TABLE apit.users;

CREATE TABLE apit.users
(
  id bigserial NOT NULL,
  login character varying NOT NULL,
  name character varying NOT NULL,
  password character varying NOT NULL,
  CONSTRAINT users_pkey PRIMARY KEY (id),
  CONSTRAINT users_login_key UNIQUE (login)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE apit.users
  OWNER TO postgres;


-- Table: apit.movies

-- DROP TABLE apit.movies;

CREATE TABLE apit.movies
(
  id bigserial NOT NULL,
  title character varying NOT NULL,
  description character varying NOT NULL,
  date timestamp without time zone,
  directorname character varying,
  duration integer,
  writername character varying,
  stars integer,
  actors character varying,
  CONSTRAINT movies_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE apit.movies
  OWNER TO postgres;
