--
-- PostgreSQL database dump
--

-- Dumped from database version 9.5.2
-- Dumped by pg_dump version 9.5.1

-- Started on 2017-01-05 10:42:00

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;

--
-- TOC entry 4099 (class 1262 OID 18592)
-- Name: app; Type: DATABASE; Schema: -; Owner: postgres
--

CREATE DATABASE app WITH TEMPLATE = template0 ENCODING = 'UTF8' LC_COLLATE = 'Portuguese_Brazil.1252' LC_CTYPE = 'Portuguese_Brazil.1252';


ALTER DATABASE app OWNER TO postgres;

\connect app

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;

--
-- TOC entry 4100 (class 1262 OID 18592)
-- Dependencies: 4099
-- Name: app; Type: COMMENT; Schema: -; Owner: postgres
--

COMMENT ON DATABASE app IS 'Banco de dados da aplicação prática da Monografia UVA.

Aplicação tipo webmapping com rede social e compartilhamento de funcionalidades para ciclistas. ';


--
-- TOC entry 17 (class 2615 OID 20164)
-- Name: tiger; Type: SCHEMA; Schema: -; Owner: postgres
--

CREATE SCHEMA tiger;


ALTER SCHEMA tiger OWNER TO postgres;

--
-- TOC entry 7 (class 3079 OID 20153)
-- Name: fuzzystrmatch; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS fuzzystrmatch WITH SCHEMA public;


--
-- TOC entry 4103 (class 0 OID 0)
-- Dependencies: 7
-- Name: EXTENSION fuzzystrmatch; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION fuzzystrmatch IS 'determine similarities and distance between strings';


--
-- TOC entry 9 (class 3079 OID 18593)
-- Name: postgis; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS postgis WITH SCHEMA public;


--
-- TOC entry 4104 (class 0 OID 0)
-- Dependencies: 9
-- Name: EXTENSION postgis; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION postgis IS 'PostGIS geometry, geography, and raster spatial types and functions';


--
-- TOC entry 10 (class 3079 OID 20165)
-- Name: postgis_tiger_geocoder; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS postgis_tiger_geocoder WITH SCHEMA tiger;


--
-- TOC entry 4105 (class 0 OID 0)
-- Dependencies: 10
-- Name: EXTENSION postgis_tiger_geocoder; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION postgis_tiger_geocoder IS 'PostGIS tiger geocoder and reverse geocoder';


--
-- TOC entry 1 (class 3079 OID 12355)
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- TOC entry 4106 (class 0 OID 0)
-- Dependencies: 1
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


--
-- TOC entry 6 (class 3079 OID 20593)
-- Name: address_standardizer; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS address_standardizer WITH SCHEMA public;


--
-- TOC entry 4107 (class 0 OID 0)
-- Dependencies: 6
-- Name: EXTENSION address_standardizer; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION address_standardizer IS 'Used to parse an address into constituent elements. Generally used to support geocoding address normalization step.';


--
-- TOC entry 5 (class 3079 OID 20604)
-- Name: address_standardizer_data_us; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS address_standardizer_data_us WITH SCHEMA public;


--
-- TOC entry 4108 (class 0 OID 0)
-- Dependencies: 5
-- Name: EXTENSION address_standardizer_data_us; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION address_standardizer_data_us IS 'Address Standardizer US dataset example';


--
-- TOC entry 2 (class 3079 OID 20739)
-- Name: pgrouting; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS pgrouting WITH SCHEMA public;


--
-- TOC entry 4109 (class 0 OID 0)
-- Dependencies: 2
-- Name: EXTENSION pgrouting; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION pgrouting IS 'pgRouting Extension';


--
-- TOC entry 4 (class 3079 OID 20649)
-- Name: pointcloud; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS pointcloud WITH SCHEMA public;


--
-- TOC entry 4110 (class 0 OID 0)
-- Dependencies: 4
-- Name: EXTENSION pointcloud; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION pointcloud IS 'data type for lidar point clouds';


--
-- TOC entry 3 (class 3079 OID 20731)
-- Name: pointcloud_postgis; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS pointcloud_postgis WITH SCHEMA public;


--
-- TOC entry 4111 (class 0 OID 0)
-- Dependencies: 3
-- Name: EXTENSION pointcloud_postgis; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION pointcloud_postgis IS 'integration for pointcloud LIDAR data and PostGIS geometry data';


--
-- TOC entry 8 (class 3079 OID 20135)
-- Name: postgis_sfcgal; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS postgis_sfcgal WITH SCHEMA public;


--
-- TOC entry 4112 (class 0 OID 0)
-- Dependencies: 8
-- Name: EXTENSION postgis_sfcgal; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION postgis_sfcgal IS 'PostGIS SFCGAL functions';


SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- TOC entry 274 (class 1259 OID 22546)
-- Name: alert_types; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE alert_types (
    id integer NOT NULL,
    description character varying(50),
    parent_type_id integer
);


ALTER TABLE alert_types OWNER TO postgres;

--
-- TOC entry 277 (class 1259 OID 28356)
-- Name: alert_types_spatial_types; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE alert_types_spatial_types (
    alert_types_id integer NOT NULL,
    spatial_types_id integer NOT NULL
);


ALTER TABLE alert_types_spatial_types OWNER TO postgres;

--
-- TOC entry 272 (class 1259 OID 22519)
-- Name: alerts; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE alerts (
    id integer NOT NULL,
    title character varying(40),
    description text,
    type_id integer,
    user_id integer,
    created_date timestamp without time zone,
    likes integer,
    unlikes integer,
    updated_date time without time zone,
    geom geometry,
    enable smallint DEFAULT 1
);


ALTER TABLE alerts OWNER TO postgres;

--
-- TOC entry 271 (class 1259 OID 22517)
-- Name: alerts_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE alerts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE alerts_id_seq OWNER TO postgres;

--
-- TOC entry 4113 (class 0 OID 0)
-- Dependencies: 271
-- Name: alerts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE alerts_id_seq OWNED BY alerts.id;


--
-- TOC entry 275 (class 1259 OID 28334)
-- Name: spatial_types; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE spatial_types (
    id integer NOT NULL,
    description character varying(32)
);


ALTER TABLE spatial_types OWNER TO postgres;

--
-- TOC entry 276 (class 1259 OID 28337)
-- Name: alerts_types_geometries_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE alerts_types_geometries_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE alerts_types_geometries_id_seq OWNER TO postgres;

--
-- TOC entry 4114 (class 0 OID 0)
-- Dependencies: 276
-- Name: alerts_types_geometries_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE alerts_types_geometries_id_seq OWNED BY spatial_types.id;


--
-- TOC entry 273 (class 1259 OID 22544)
-- Name: alerts_types_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE alerts_types_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE alerts_types_id_seq OWNER TO postgres;

--
-- TOC entry 4115 (class 0 OID 0)
-- Dependencies: 273
-- Name: alerts_types_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE alerts_types_id_seq OWNED BY alert_types.id;


--
-- TOC entry 281 (class 1259 OID 28435)
-- Name: bike_keepers; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE bike_keepers (
    id integer NOT NULL,
    title character varying(40),
    likes integer,
    unlikes integer,
    capacity integer,
    used_capacity integer,
    user_id integer DEFAULT 0,
    description text,
    outdoor smallint DEFAULT 1,
    public smallint DEFAULT 1,
    created_date timestamp without time zone,
    enable smallint DEFAULT 1,
    geom geometry,
    public_dir_name character varying(512),
    cost real DEFAULT 0
);


ALTER TABLE bike_keepers OWNER TO postgres;

--
-- TOC entry 280 (class 1259 OID 28433)
-- Name: bike_keepers_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE bike_keepers_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE bike_keepers_id_seq OWNER TO postgres;

--
-- TOC entry 4116 (class 0 OID 0)
-- Dependencies: 280
-- Name: bike_keepers_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE bike_keepers_id_seq OWNED BY bike_keepers.id;


--
-- TOC entry 288 (class 1259 OID 28515)
-- Name: bike_keepers_multimedias; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE bike_keepers_multimedias (
    bike_keepers_id integer NOT NULL,
    multimedias_id integer NOT NULL
);


ALTER TABLE bike_keepers_multimedias OWNER TO postgres;

--
-- TOC entry 4117 (class 0 OID 0)
-- Dependencies: 288
-- Name: COLUMN bike_keepers_multimedias.multimedias_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN bike_keepers_multimedias.multimedias_id IS '
';


--
-- TOC entry 290 (class 1259 OID 28598)
-- Name: events; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE events (
    id integer NOT NULL,
    title character varying(100),
    start_date timestamp without time zone,
    end_date timestamp without time zone,
    likes integer DEFAULT 0,
    unlikes integer DEFAULT 0,
    description text,
    visible character varying(2048),
    enable smallint DEFAULT 0,
    user_id integer DEFAULT 0
);


ALTER TABLE events OWNER TO postgres;

--
-- TOC entry 289 (class 1259 OID 28596)
-- Name: events_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE events_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE events_id_seq OWNER TO postgres;

--
-- TOC entry 4118 (class 0 OID 0)
-- Dependencies: 289
-- Name: events_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE events_id_seq OWNED BY events.id;


--
-- TOC entry 287 (class 1259 OID 28479)
-- Name: galleries; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE galleries (
    id integer NOT NULL,
    title character varying(40),
    user_id integer DEFAULT 0,
    created_date timestamp without time zone
);


ALTER TABLE galleries OWNER TO postgres;

--
-- TOC entry 286 (class 1259 OID 28477)
-- Name: galleries_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE galleries_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE galleries_id_seq OWNER TO postgres;

--
-- TOC entry 4119 (class 0 OID 0)
-- Dependencies: 286
-- Name: galleries_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE galleries_id_seq OWNED BY galleries.id;


--
-- TOC entry 285 (class 1259 OID 28459)
-- Name: multimedia_types; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE multimedia_types (
    id integer NOT NULL,
    title character varying(40),
    description text
);


ALTER TABLE multimedia_types OWNER TO postgres;

--
-- TOC entry 284 (class 1259 OID 28457)
-- Name: multimedia_types_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE multimedia_types_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE multimedia_types_id_seq OWNER TO postgres;

--
-- TOC entry 4120 (class 0 OID 0)
-- Dependencies: 284
-- Name: multimedia_types_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE multimedia_types_id_seq OWNED BY multimedia_types.id;


--
-- TOC entry 283 (class 1259 OID 28451)
-- Name: multimedias; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE multimedias (
    id integer NOT NULL,
    type_id integer,
    title character varying(40),
    created_date timestamp without time zone,
    src character varying(512),
    gallery_id integer DEFAULT 0
);


ALTER TABLE multimedias OWNER TO postgres;

--
-- TOC entry 282 (class 1259 OID 28449)
-- Name: multimedias_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE multimedias_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE multimedias_id_seq OWNER TO postgres;

--
-- TOC entry 4121 (class 0 OID 0)
-- Dependencies: 282
-- Name: multimedias_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE multimedias_id_seq OWNED BY multimedias.id;


--
-- TOC entry 292 (class 1259 OID 28618)
-- Name: routes; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE routes (
    id integer NOT NULL,
    geom geometry,
    user_id integer DEFAULT 0,
    description text,
    created_date timestamp without time zone
);


ALTER TABLE routes OWNER TO postgres;

--
-- TOC entry 291 (class 1259 OID 28616)
-- Name: routes_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE routes_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE routes_id_seq OWNER TO postgres;

--
-- TOC entry 4122 (class 0 OID 0)
-- Dependencies: 291
-- Name: routes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE routes_id_seq OWNED BY routes.id;


--
-- TOC entry 207 (class 1259 OID 20012)
-- Name: users; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE users (
    id integer NOT NULL,
    first_name character varying(50),
    last_name character varying(50),
    how_to_be_called character varying(30),
    username character varying(30),
    email character varying(100),
    password character varying(16),
    signup_date timestamp without time zone DEFAULT now(),
    last_access_date timestamp without time zone,
    auth_key character varying(32),
    access_token character varying(32),
    home_dir_name character varying(512),
    online integer DEFAULT 0,
    question text,
    answer text
);


ALTER TABLE users OWNER TO postgres;

--
-- TOC entry 4123 (class 0 OID 0)
-- Dependencies: 207
-- Name: TABLE users; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE users IS 'Tabela de usuários da aplicação';


--
-- TOC entry 4124 (class 0 OID 0)
-- Dependencies: 207
-- Name: COLUMN users.email; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN users.email IS 'conta de email do usuário';


--
-- TOC entry 4125 (class 0 OID 0)
-- Dependencies: 207
-- Name: COLUMN users.password; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN users.password IS 'senha do usuário, usada na autenticação';


--
-- TOC entry 4126 (class 0 OID 0)
-- Dependencies: 207
-- Name: COLUMN users.auth_key; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN users.auth_key IS 'Chave de validação da identidade';


--
-- TOC entry 4127 (class 0 OID 0)
-- Dependencies: 207
-- Name: COLUMN users.access_token; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN users.access_token IS 'Token de acesso, aconselhado para ser utilizado com API RESTful';


--
-- TOC entry 208 (class 1259 OID 20016)
-- Name: user_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE user_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE user_id_seq OWNER TO postgres;

--
-- TOC entry 4128 (class 0 OID 0)
-- Dependencies: 208
-- Name: user_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE user_id_seq OWNED BY users.id;


--
-- TOC entry 279 (class 1259 OID 28394)
-- Name: user_trackings; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE user_trackings (
    id integer NOT NULL,
    user_id integer,
    register_date timestamp without time zone,
    geom geometry
);


ALTER TABLE user_trackings OWNER TO postgres;

--
-- TOC entry 278 (class 1259 OID 28392)
-- Name: user_tracking_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE user_tracking_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE user_tracking_id_seq OWNER TO postgres;

--
-- TOC entry 4129 (class 0 OID 0)
-- Dependencies: 278
-- Name: user_tracking_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE user_tracking_id_seq OWNED BY user_trackings.id;


--
-- TOC entry 3885 (class 2604 OID 22549)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY alert_types ALTER COLUMN id SET DEFAULT nextval('alerts_types_id_seq'::regclass);


--
-- TOC entry 3883 (class 2604 OID 22522)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY alerts ALTER COLUMN id SET DEFAULT nextval('alerts_id_seq'::regclass);


--
-- TOC entry 3888 (class 2604 OID 28438)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY bike_keepers ALTER COLUMN id SET DEFAULT nextval('bike_keepers_id_seq'::regclass);


--
-- TOC entry 3900 (class 2604 OID 28601)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY events ALTER COLUMN id SET DEFAULT nextval('events_id_seq'::regclass);


--
-- TOC entry 3897 (class 2604 OID 28482)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY galleries ALTER COLUMN id SET DEFAULT nextval('galleries_id_seq'::regclass);


--
-- TOC entry 3896 (class 2604 OID 28462)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY multimedia_types ALTER COLUMN id SET DEFAULT nextval('multimedia_types_id_seq'::regclass);


--
-- TOC entry 3894 (class 2604 OID 28454)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY multimedias ALTER COLUMN id SET DEFAULT nextval('multimedias_id_seq'::regclass);


--
-- TOC entry 3904 (class 2604 OID 28621)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY routes ALTER COLUMN id SET DEFAULT nextval('routes_id_seq'::regclass);


--
-- TOC entry 3886 (class 2604 OID 28339)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY spatial_types ALTER COLUMN id SET DEFAULT nextval('alerts_types_geometries_id_seq'::regclass);


--
-- TOC entry 3887 (class 2604 OID 28397)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY user_trackings ALTER COLUMN id SET DEFAULT nextval('user_tracking_id_seq'::regclass);


--
-- TOC entry 3881 (class 2604 OID 20018)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY users ALTER COLUMN id SET DEFAULT nextval('user_id_seq'::regclass);


--
-- TOC entry 4076 (class 0 OID 22546)
-- Dependencies: 274
-- Data for Name: alert_types; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO alert_types VALUES (3, 'Roubos e Furtos', NULL);
INSERT INTO alert_types VALUES (1, 'Outro', NULL);
INSERT INTO alert_types VALUES (2, 'Perigo na Via', NULL);
INSERT INTO alert_types VALUES (4, 'Interdições', NULL);


--
-- TOC entry 4079 (class 0 OID 28356)
-- Dependencies: 277
-- Data for Name: alert_types_spatial_types; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO alert_types_spatial_types VALUES (4, 1);
INSERT INTO alert_types_spatial_types VALUES (3, 1);
INSERT INTO alert_types_spatial_types VALUES (2, 1);


--
-- TOC entry 4074 (class 0 OID 22519)
-- Dependencies: 272
-- Data for Name: alerts; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO alerts VALUES (5, 'Teste', 'Teste 2', 4, 1, '2016-10-28 23:40:14', NULL, NULL, NULL, '0101000020110F0000000000006EC745C02B11B1D59DE636C0', 1);
INSERT INTO alerts VALUES (6, 'Incêndio na morro de Paciências', 'Bombeiros no local, rua interditada', 4, 1, '2016-10-28 23:44:41', NULL, NULL, NULL, '0101000020110F00000000000073D145C0B42741A543E736C0', 1);
INSERT INTO alerts VALUES (7, 'Saneamento da orla', 'Rua interditada', 4, 1, '2016-10-28 23:47:09', NULL, NULL, NULL, '0101000020110F0000000000C09CD845C0F4F9FF6294FB36C0', 1);
INSERT INTO alerts VALUES (8, 'Acidênte no posto de combustível', 'Carro bateu em uma das bombas de combustível do posto da Av. João XXIII', 4, 1, '2016-10-28 23:50:19', NULL, NULL, NULL, '0101000020110F0000FFFFFFFF20D845C098B9C8C849E936C0', 1);
INSERT INTO alerts VALUES (9, 'Teste', 'Teste insert', 4, 1, '2016-10-29 00:01:03', NULL, NULL, NULL, '0101000020110F00000000008069B545C02195E120DEE036C0', 1);


--
-- TOC entry 4130 (class 0 OID 0)
-- Dependencies: 271
-- Name: alerts_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('alerts_id_seq', 20, true);


--
-- TOC entry 4131 (class 0 OID 0)
-- Dependencies: 276
-- Name: alerts_types_geometries_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('alerts_types_geometries_id_seq', 7, true);


--
-- TOC entry 4132 (class 0 OID 0)
-- Dependencies: 273
-- Name: alerts_types_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('alerts_types_id_seq', 3, true);


--
-- TOC entry 4083 (class 0 OID 28435)
-- Dependencies: 281
-- Data for Name: bike_keepers; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 4133 (class 0 OID 0)
-- Dependencies: 280
-- Name: bike_keepers_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('bike_keepers_id_seq', 1, false);


--
-- TOC entry 4090 (class 0 OID 28515)
-- Dependencies: 288
-- Data for Name: bike_keepers_multimedias; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 4092 (class 0 OID 28598)
-- Dependencies: 290
-- Data for Name: events; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 4134 (class 0 OID 0)
-- Dependencies: 289
-- Name: events_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('events_id_seq', 1, false);


--
-- TOC entry 4089 (class 0 OID 28479)
-- Dependencies: 287
-- Data for Name: galleries; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO galleries VALUES (0, 'Default Gallery', 0, '2015-11-03 13:41:00');


--
-- TOC entry 4135 (class 0 OID 0)
-- Dependencies: 286
-- Name: galleries_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('galleries_id_seq', 1, false);


--
-- TOC entry 4087 (class 0 OID 28459)
-- Dependencies: 285
-- Data for Name: multimedia_types; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO multimedia_types VALUES (1, 'foto', 'Fotografias ou  imagens');
INSERT INTO multimedia_types VALUES (2, 'vídeo', 'Vídeo de média duração (avi, mp4, webm)');


--
-- TOC entry 4136 (class 0 OID 0)
-- Dependencies: 284
-- Name: multimedia_types_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('multimedia_types_id_seq', 2, true);


--
-- TOC entry 4085 (class 0 OID 28451)
-- Dependencies: 283
-- Data for Name: multimedias; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 4137 (class 0 OID 0)
-- Dependencies: 282
-- Name: multimedias_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('multimedias_id_seq', 1, false);


--
-- TOC entry 3871 (class 0 OID 20651)
-- Dependencies: 266
-- Data for Name: pointcloud_formats; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 4094 (class 0 OID 28618)
-- Dependencies: 292
-- Data for Name: routes; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 4138 (class 0 OID 0)
-- Dependencies: 291
-- Name: routes_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('routes_id_seq', 1, false);


--
-- TOC entry 3875 (class 0 OID 18883)
-- Dependencies: 193
-- Data for Name: spatial_ref_sys; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 4077 (class 0 OID 28334)
-- Dependencies: 275
-- Data for Name: spatial_types; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO spatial_types VALUES (1, 'Point');
INSERT INTO spatial_types VALUES (2, 'LineString');
INSERT INTO spatial_types VALUES (3, 'Polygon');
INSERT INTO spatial_types VALUES (4, 'MultiPoint');
INSERT INTO spatial_types VALUES (5, 'MultiLineString');
INSERT INTO spatial_types VALUES (6, 'MultiPolygon');
INSERT INTO spatial_types VALUES (7, 'GeometryCollection');


--
-- TOC entry 3874 (class 0 OID 20621)
-- Dependencies: 263
-- Data for Name: us_gaz; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 3872 (class 0 OID 20607)
-- Dependencies: 261
-- Data for Name: us_lex; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 3873 (class 0 OID 20635)
-- Dependencies: 265
-- Data for Name: us_rules; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 4139 (class 0 OID 0)
-- Dependencies: 208
-- Name: user_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('user_id_seq', 3, true);


--
-- TOC entry 4140 (class 0 OID 0)
-- Dependencies: 278
-- Name: user_tracking_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('user_tracking_id_seq', 1, false);


--
-- TOC entry 4081 (class 0 OID 28394)
-- Dependencies: 279
-- Data for Name: user_trackings; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 4071 (class 0 OID 20012)
-- Dependencies: 207
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO users VALUES (1, 'Marcus', 'Faccion', 'Marcus', 'marcusfaccion', 'marcusfaccion@bol.com.br', '23064140', '2016-04-13 11:00:00', NULL, 'hEhA7gNNCs8NylQ28bjtVpJyb1hqFx1P', NULL, 'a99aa5e1912ac0ee7d0b2f8ce3e272ee', 0, NULL, NULL);
INSERT INTO users VALUES (0, 'Bike', 'Social', 'BikeSocial', 'bikesocial', 'marcusfaccion@bol.com.br', 'bikesocial', '2016-11-03 13:44:18.824322', NULL, NULL, NULL, '856399845ab74597eda9777f091b277a', 0, NULL, NULL);
INSERT INTO users VALUES (2, 'Thatiane', 'Copque', 'Thaty', 'thatiane', 'thatiane_copque@hotmail.com', '123', '2016-11-20 04:38:59.73246', NULL, 'fHAyDZexEGjxh4RvCbHkd4fIP6OQPJWE', NULL, '9e459de99798f751a83cab6667d83491', 0, 'Primeiro nome do esposo?', 'marcus');
INSERT INTO users VALUES (3, 'Default', 'Default', 'Default', 'default', 'default@hotmail.com', '123456', '2016-11-20 05:07:14.755831', NULL, 'MFKZdmbnLVXRN3JJqCQVs5BRk-2IsoQE', NULL, '41378f6ece1dbf69d8eebe741a33c11d', 0, 'Primeiro nome do seu criador?', 'marcus');


SET search_path = tiger, pg_catalog;

--
-- TOC entry 3876 (class 0 OID 20171)
-- Dependencies: 210
-- Data for Name: geocode_settings; Type: TABLE DATA; Schema: tiger; Owner: postgres
--



--
-- TOC entry 3877 (class 0 OID 20526)
-- Dependencies: 254
-- Data for Name: pagc_gaz; Type: TABLE DATA; Schema: tiger; Owner: postgres
--



--
-- TOC entry 3878 (class 0 OID 20538)
-- Dependencies: 256
-- Data for Name: pagc_lex; Type: TABLE DATA; Schema: tiger; Owner: postgres
--



--
-- TOC entry 3879 (class 0 OID 20550)
-- Dependencies: 258
-- Data for Name: pagc_rules; Type: TABLE DATA; Schema: tiger; Owner: postgres
--



SET search_path = public, pg_catalog;

--
-- TOC entry 3914 (class 2606 OID 28348)
-- Name: alert_types_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY alert_types
    ADD CONSTRAINT alert_types_pk PRIMARY KEY (id);


--
-- TOC entry 3919 (class 2606 OID 28372)
-- Name: alert_types_spatial_types_ManyMany_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY alert_types_spatial_types
    ADD CONSTRAINT "alert_types_spatial_types_ManyMany_pk" PRIMARY KEY (alert_types_id, spatial_types_id);


--
-- TOC entry 3917 (class 2606 OID 28355)
-- Name: alert_types_spatial_types_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY spatial_types
    ADD CONSTRAINT alert_types_spatial_types_pk PRIMARY KEY (id);


--
-- TOC entry 3910 (class 2606 OID 22524)
-- Name: alerts_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY alerts
    ADD CONSTRAINT alerts_pk PRIMARY KEY (id);


--
-- TOC entry 3933 (class 2606 OID 28519)
-- Name: bike_keepers_multimedias_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY bike_keepers_multimedias
    ADD CONSTRAINT bike_keepers_multimedias_pk PRIMARY KEY (bike_keepers_id, multimedias_id);


--
-- TOC entry 3925 (class 2606 OID 28440)
-- Name: bike_keepers_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY bike_keepers
    ADD CONSTRAINT bike_keepers_pk PRIMARY KEY (id);


--
-- TOC entry 3935 (class 2606 OID 28609)
-- Name: events_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY events
    ADD CONSTRAINT events_pk PRIMARY KEY (id);


--
-- TOC entry 3931 (class 2606 OID 28485)
-- Name: galleries_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY galleries
    ADD CONSTRAINT galleries_pk PRIMARY KEY (id);


--
-- TOC entry 3929 (class 2606 OID 28467)
-- Name: multimedia_types_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY multimedia_types
    ADD CONSTRAINT multimedia_types_pk PRIMARY KEY (id);


--
-- TOC entry 3927 (class 2606 OID 28456)
-- Name: multimedias_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY multimedias
    ADD CONSTRAINT multimedias_pk PRIMARY KEY (id);


--
-- TOC entry 3907 (class 2606 OID 20020)
-- Name: pk_user; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY users
    ADD CONSTRAINT pk_user PRIMARY KEY (id);


--
-- TOC entry 3938 (class 2606 OID 28627)
-- Name: routes_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY routes
    ADD CONSTRAINT routes_pk PRIMARY KEY (id);


--
-- TOC entry 3922 (class 2606 OID 28399)
-- Name: user_trackings_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY user_trackings
    ADD CONSTRAINT user_trackings_pk PRIMARY KEY (id);


--
-- TOC entry 3908 (class 1259 OID 28373)
-- Name: alerts_geom_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX alerts_geom_idx ON alerts USING gist (geom);


--
-- TOC entry 3923 (class 1259 OID 28647)
-- Name: bike_keepers_geom_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX bike_keepers_geom_idx ON bike_keepers USING gist (geom);


--
-- TOC entry 3915 (class 1259 OID 28391)
-- Name: fki_alert_types_belongsTo_alert_types_fk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX "fki_alert_types_belongsTo_alert_types_fk" ON alert_types USING btree (parent_type_id);


--
-- TOC entry 3911 (class 1259 OID 28385)
-- Name: fki_alerts_belongsTo_alert_types_fk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX "fki_alerts_belongsTo_alert_types_fk" ON alerts USING btree (type_id);


--
-- TOC entry 3912 (class 1259 OID 28379)
-- Name: fki_alerts_belongsTo_users_fk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX "fki_alerts_belongsTo_users_fk" ON alerts USING btree (user_id);


--
-- TOC entry 3936 (class 1259 OID 28633)
-- Name: routes_geom_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX routes_geom_idx ON routes USING gist (geom);


--
-- TOC entry 3920 (class 1259 OID 28527)
-- Name: user_tackings_geom_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX user_tackings_geom_idx ON user_trackings USING gist (geom);


--
-- TOC entry 3941 (class 2606 OID 28386)
-- Name: alert_types_belongsTo_alert_types_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY alert_types
    ADD CONSTRAINT "alert_types_belongsTo_alert_types_fk" FOREIGN KEY (parent_type_id) REFERENCES alert_types(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 3940 (class 2606 OID 28380)
-- Name: alerts_belongsTo_alert_types_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY alerts
    ADD CONSTRAINT "alerts_belongsTo_alert_types_fk" FOREIGN KEY (type_id) REFERENCES alert_types(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 3939 (class 2606 OID 28374)
-- Name: alerts_belongsTo_users_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY alerts
    ADD CONSTRAINT "alerts_belongsTo_users_fk" FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 3943 (class 2606 OID 28497)
-- Name: bike_keepers_belongsTo_users_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY bike_keepers
    ADD CONSTRAINT "bike_keepers_belongsTo_users_fk" FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE SET DEFAULT;


--
-- TOC entry 3947 (class 2606 OID 28610)
-- Name: events_belongsTo_users_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY events
    ADD CONSTRAINT "events_belongsTo_users_fk" FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE SET DEFAULT;


--
-- TOC entry 3946 (class 2606 OID 28491)
-- Name: galleries_belongsTo_users_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY galleries
    ADD CONSTRAINT "galleries_belongsTo_users_fk" FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE SET DEFAULT;


--
-- TOC entry 3945 (class 2606 OID 28510)
-- Name: multimedias_belongsTo_galleries_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY multimedias
    ADD CONSTRAINT "multimedias_belongsTo_galleries_fk" FOREIGN KEY (gallery_id) REFERENCES galleries(id) ON UPDATE CASCADE ON DELETE SET DEFAULT;


--
-- TOC entry 3944 (class 2606 OID 28472)
-- Name: multimedias_belongsTo_multimedia_types_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY multimedias
    ADD CONSTRAINT "multimedias_belongsTo_multimedia_types_fk" FOREIGN KEY (type_id) REFERENCES multimedia_types(id);


--
-- TOC entry 3948 (class 2606 OID 28628)
-- Name: routes_belongsTo_users_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY routes
    ADD CONSTRAINT "routes_belongsTo_users_fk" FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE SET DEFAULT;


--
-- TOC entry 3942 (class 2606 OID 28405)
-- Name: user_trackings_belongsTo_users_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY user_trackings
    ADD CONSTRAINT "user_trackings_belongsTo_users_fk" FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 4102 (class 0 OID 0)
-- Dependencies: 15
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


-- Completed on 2017-01-05 10:42:01

--
-- PostgreSQL database dump complete
--

