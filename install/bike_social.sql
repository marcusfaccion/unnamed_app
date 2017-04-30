--
-- PostgreSQL database dump
--

-- Dumped from database version 9.5.2
-- Dumped by pg_dump version 9.5.1

-- Started on 2017-04-30 02:47:00

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;

--
-- TOC entry 4236 (class 1262 OID 18592)
-- Dependencies: 4235
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
-- TOC entry 4239 (class 0 OID 0)
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
-- TOC entry 4240 (class 0 OID 0)
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
-- TOC entry 4241 (class 0 OID 0)
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
-- TOC entry 4242 (class 0 OID 0)
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
-- TOC entry 4243 (class 0 OID 0)
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
-- TOC entry 4244 (class 0 OID 0)
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
-- TOC entry 4245 (class 0 OID 0)
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
-- TOC entry 4246 (class 0 OID 0)
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
-- TOC entry 4247 (class 0 OID 0)
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
-- TOC entry 4248 (class 0 OID 0)
-- Dependencies: 8
-- Name: EXTENSION postgis_sfcgal; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION postgis_sfcgal IS 'PostGIS SFCGAL functions';


SET search_path = public, pg_catalog;

--
-- TOC entry 2386 (class 1247 OID 45164)
-- Name: ratings; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE ratings AS ENUM (
    'likes',
    'dislikes'
);


ALTER TYPE ratings OWNER TO postgres;

--
-- TOC entry 1678 (class 1255 OID 45157)
-- Name: st_alerts_check_duration(integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION st_alerts_check_duration(_id integer DEFAULT 0) RETURNS integer
    LANGUAGE plpgsql
    AS $$

DECLARE 
disabled_num integer DEFAULT 0;
alert_record record;
alerts_cursor CURSOR FOR SELECT id, duration_date, 'enable'  FROM alerts WHERE enable=1;

BEGIN

IF _id=0 THEN
	-- Abre o cursor
	OPEN alerts_cursor;

	LOOP
		-- seleciona registro dentro da variável
		FETCH alerts_cursor INTO alert_record;
		-- sai quando não acha um registro pelo menos
		EXIT WHEN NOT FOUND;

		IF alert_record.duration_date < now() THEN
			UPDATE alerts 
			SET enable = 0 
			WHERE CURRENT OF alerts_cursor;
			disabled_num := disabled_num + 1;
		END IF;
		
	END LOOP;
	-- Fecha o cursor
	CLOSE alerts_cursor;
ELSE
	FOR alert_record IN (SELECT id, duration_date, 'enable'  FROM alerts WHERE id=_id) LOOP
		IF alert_record.duration_date < now() THEN
			UPDATE alerts SET enable = 0 WHERE id=alert_record.id;
			disabled_num := disabled_num + 1;
		END IF;
	END LOOP;
END IF;

RETURN disabled_num;

END; $$;


ALTER FUNCTION public.st_alerts_check_duration(_id integer) OWNER TO postgres;

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
    dislikes integer,
    updated_date time without time zone,
    geom geometry,
    enable smallint DEFAULT 1,
    duration_date timestamp without time zone
);


ALTER TABLE alerts OWNER TO postgres;

--
-- TOC entry 4249 (class 0 OID 0)
-- Dependencies: 272
-- Name: COLUMN alerts.duration_date; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN alerts.duration_date IS 'Data de encerramento do alerta';


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
-- TOC entry 4250 (class 0 OID 0)
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
-- TOC entry 4251 (class 0 OID 0)
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
-- TOC entry 4252 (class 0 OID 0)
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
    dislikes integer,
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
    cost real DEFAULT 0,
    updated_date timestamp without time zone
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
-- TOC entry 4253 (class 0 OID 0)
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
-- TOC entry 4255 (class 0 OID 0)
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
-- TOC entry 4256 (class 0 OID 0)
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
-- TOC entry 4257 (class 0 OID 0)
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
    description text,
    mime_types character varying(255) DEFAULT ''::character varying
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
-- TOC entry 4258 (class 0 OID 0)
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
    type_id integer DEFAULT 0,
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
-- TOC entry 4259 (class 0 OID 0)
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
-- TOC entry 4260 (class 0 OID 0)
-- Dependencies: 291
-- Name: routes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE routes_id_seq OWNED BY routes.id;


--
-- TOC entry 293 (class 1259 OID 36850)
-- Name: user_friendships; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE user_friendships (
    user_id integer NOT NULL,
    friend_user_id integer NOT NULL,
    created_date timestamp without time zone
);


ALTER TABLE user_friendships OWNER TO postgres;

--
-- TOC entry 4261 (class 0 OID 0)
-- Dependencies: 293
-- Name: TABLE user_friendships; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE user_friendships IS 'Tabela de ligação para relacionamente de cardinalidade NxN - Amizades';


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
    answer text,
    full_name character varying(100)
);


ALTER TABLE users OWNER TO postgres;

--
-- TOC entry 4262 (class 0 OID 0)
-- Dependencies: 207
-- Name: TABLE users; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE users IS 'Tabela de usuários da aplicação';


--
-- TOC entry 4263 (class 0 OID 0)
-- Dependencies: 207
-- Name: COLUMN users.email; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN users.email IS 'conta de email do usuário';


--
-- TOC entry 4264 (class 0 OID 0)
-- Dependencies: 207
-- Name: COLUMN users.password; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN users.password IS 'senha do usuário, usada na autenticação';


--
-- TOC entry 4265 (class 0 OID 0)
-- Dependencies: 207
-- Name: COLUMN users.auth_key; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN users.auth_key IS 'Chave de validação da identidade';


--
-- TOC entry 4266 (class 0 OID 0)
-- Dependencies: 207
-- Name: COLUMN users.access_token; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN users.access_token IS 'Token de acesso, aconselhado para ser utilizado com API RESTful';


--
-- TOC entry 307 (class 1259 OID 45146)
-- Name: user_0_friends_id; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW user_0_friends_id AS
 SELECT b.friend_user_id AS id
   FROM (users a
     JOIN user_friendships b ON ((a.id = b.user_id)))
  WHERE (a.id = 0)
UNION
 SELECT b.user_id AS id
   FROM (users a
     JOIN user_friendships b ON ((a.id = b.friend_user_id)))
  WHERE (a.id = 0);


ALTER TABLE user_0_friends_id OWNER TO postgres;

--
-- TOC entry 296 (class 1259 OID 45042)
-- Name: user_1_friends_id; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW user_1_friends_id AS
 SELECT b.friend_user_id AS id
   FROM (users a
     JOIN user_friendships b ON ((a.id = b.user_id)))
  WHERE (a.id = 1)
UNION
 SELECT b.user_id AS id
   FROM (users a
     JOIN user_friendships b ON ((a.id = b.friend_user_id)))
  WHERE (a.id = 1);


ALTER TABLE user_1_friends_id OWNER TO postgres;

--
-- TOC entry 297 (class 1259 OID 45047)
-- Name: user_2_friends_id; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW user_2_friends_id AS
 SELECT b.friend_user_id AS id
   FROM (users a
     JOIN user_friendships b ON ((a.id = b.user_id)))
  WHERE (a.id = 2)
UNION
 SELECT b.user_id AS id
   FROM (users a
     JOIN user_friendships b ON ((a.id = b.friend_user_id)))
  WHERE (a.id = 2);


ALTER TABLE user_2_friends_id OWNER TO postgres;

--
-- TOC entry 308 (class 1259 OID 45151)
-- Name: user_3_friends_id; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW user_3_friends_id AS
 SELECT b.friend_user_id AS id
   FROM (users a
     JOIN user_friendships b ON ((a.id = b.user_id)))
  WHERE (a.id = 3)
UNION
 SELECT b.user_id AS id
   FROM (users a
     JOIN user_friendships b ON ((a.id = b.friend_user_id)))
  WHERE (a.id = 3);


ALTER TABLE user_3_friends_id OWNER TO postgres;

--
-- TOC entry 306 (class 1259 OID 45141)
-- Name: user_4_friends_id; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW user_4_friends_id AS
 SELECT b.friend_user_id AS id
   FROM (users a
     JOIN user_friendships b ON ((a.id = b.user_id)))
  WHERE (a.id = 2)
UNION
 SELECT b.user_id AS id
   FROM (users a
     JOIN user_friendships b ON ((a.id = b.friend_user_id)))
  WHERE (a.id = 2);


ALTER TABLE user_4_friends_id OWNER TO postgres;

--
-- TOC entry 310 (class 1259 OID 45172)
-- Name: user_alert_existence; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE user_alert_existence (
    user_id integer NOT NULL,
    alert_id integer NOT NULL,
    created_date timestamp without time zone
);


ALTER TABLE user_alert_existence OWNER TO postgres;

--
-- TOC entry 4267 (class 0 OID 0)
-- Dependencies: 310
-- Name: TABLE user_alert_existence; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE user_alert_existence IS 'Tabela para cadastro de alertas que não existem mais segundo o reporte dos usuários';


--
-- TOC entry 309 (class 1259 OID 45158)
-- Name: user_alert_rates; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE user_alert_rates (
    user_id integer NOT NULL,
    alert_id integer NOT NULL,
    created_date timestamp without time zone,
    rating ratings,
    updated_date timestamp without time zone
);


ALTER TABLE user_alert_rates OWNER TO postgres;

--
-- TOC entry 301 (class 1259 OID 45063)
-- Name: user_feedings; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE user_feedings (
    id integer NOT NULL,
    user_id integer DEFAULT 0,
    user_sharing_id integer,
    text text,
    likes integer,
    created_date timestamp without time zone,
    updated_date timestamp without time zone,
    view_level_id integer,
    dislikes integer
);


ALTER TABLE user_feedings OWNER TO postgres;

--
-- TOC entry 4268 (class 0 OID 0)
-- Dependencies: 301
-- Name: TABLE user_feedings; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE user_feedings IS 'Notícias/Conteúdo temporal do usuário';


--
-- TOC entry 4269 (class 0 OID 0)
-- Dependencies: 301
-- Name: COLUMN user_feedings.user_sharing_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN user_feedings.user_sharing_id IS 'id do conteúdo compartilhado relacionando a este feed (não mandatório quando feed é textual)';


--
-- TOC entry 300 (class 1259 OID 45061)
-- Name: user_feedings_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE user_feedings_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE user_feedings_id_seq OWNER TO postgres;

--
-- TOC entry 4270 (class 0 OID 0)
-- Dependencies: 300
-- Name: user_feedings_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE user_feedings_id_seq OWNED BY user_feedings.id;


--
-- TOC entry 295 (class 1259 OID 36857)
-- Name: user_friendship_requests; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE user_friendship_requests (
    id integer NOT NULL,
    user_id integer DEFAULT 0,
    requested_user_id integer DEFAULT 0,
    created_date timestamp without time zone,
    enable smallint DEFAULT 1
);


ALTER TABLE user_friendship_requests OWNER TO postgres;

--
-- TOC entry 4271 (class 0 OID 0)
-- Dependencies: 295
-- Name: COLUMN user_friendship_requests.user_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN user_friendship_requests.user_id IS 'id do usuário que solicita';


--
-- TOC entry 4272 (class 0 OID 0)
-- Dependencies: 295
-- Name: COLUMN user_friendship_requests.requested_user_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN user_friendship_requests.requested_user_id IS 'id do usuário de quem se solicita a amizada';


--
-- TOC entry 294 (class 1259 OID 36855)
-- Name: user_friendship_requests_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE user_friendship_requests_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE user_friendship_requests_id_seq OWNER TO postgres;

--
-- TOC entry 4273 (class 0 OID 0)
-- Dependencies: 294
-- Name: user_friendship_requests_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE user_friendship_requests_id_seq OWNED BY user_friendship_requests.id;


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
-- TOC entry 4274 (class 0 OID 0)
-- Dependencies: 208
-- Name: user_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE user_id_seq OWNED BY users.id;


--
-- TOC entry 305 (class 1259 OID 45093)
-- Name: user_sharing_types; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE user_sharing_types (
    id integer NOT NULL,
    name character varying(100)
);


ALTER TABLE user_sharing_types OWNER TO postgres;

--
-- TOC entry 4275 (class 0 OID 0)
-- Dependencies: 305
-- Name: TABLE user_sharing_types; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE user_sharing_types IS 'Tipos de conteúdos compartilháveis pelo usuário (alertas, rotas,bicicletários,fotos...)';


--
-- TOC entry 304 (class 1259 OID 45091)
-- Name: user_sharing_types_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE user_sharing_types_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE user_sharing_types_id_seq OWNER TO postgres;

--
-- TOC entry 4276 (class 0 OID 0)
-- Dependencies: 304
-- Name: user_sharing_types_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE user_sharing_types_id_seq OWNED BY user_sharing_types.id;


--
-- TOC entry 299 (class 1259 OID 45054)
-- Name: user_sharings; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE user_sharings (
    id integer NOT NULL,
    user_id integer DEFAULT 0,
    user_feeding_id integer,
    view_level_id integer,
    content_id integer,
    created_date timestamp without time zone,
    updated_date timestamp without time zone,
    sharing_type_id integer
);


ALTER TABLE user_sharings OWNER TO postgres;

--
-- TOC entry 4277 (class 0 OID 0)
-- Dependencies: 299
-- Name: TABLE user_sharings; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE user_sharings IS 'Compartilhamentos do usuário';


--
-- TOC entry 4278 (class 0 OID 0)
-- Dependencies: 299
-- Name: COLUMN user_sharings.user_feeding_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN user_sharings.user_feeding_id IS 'id do feed de usuário relacionado com esse compartilhamento (não mandatório)';


--
-- TOC entry 4279 (class 0 OID 0)
-- Dependencies: 299
-- Name: COLUMN user_sharings.view_level_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN user_sharings.view_level_id IS 'id do nivel de visão para implementar segurança';


--
-- TOC entry 4280 (class 0 OID 0)
-- Dependencies: 299
-- Name: COLUMN user_sharings.content_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN user_sharings.content_id IS 'id do conteúdo(alerta, bicicletário, rota...) sendo compartilhado';


--
-- TOC entry 4281 (class 0 OID 0)
-- Dependencies: 299
-- Name: COLUMN user_sharings.sharing_type_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN user_sharings.sharing_type_id IS 'Tipo de conteúdo que o usuário pode compartilhar (alerta, bicicletário, foto...)';


--
-- TOC entry 298 (class 1259 OID 45052)
-- Name: user_sharings_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE user_sharings_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE user_sharings_id_seq OWNER TO postgres;

--
-- TOC entry 4282 (class 0 OID 0)
-- Dependencies: 298
-- Name: user_sharings_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE user_sharings_id_seq OWNED BY user_sharings.id;


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
-- TOC entry 4283 (class 0 OID 0)
-- Dependencies: 278
-- Name: user_tracking_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE user_tracking_id_seq OWNED BY user_trackings.id;


--
-- TOC entry 303 (class 1259 OID 45074)
-- Name: view_levels; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE view_levels (
    id integer NOT NULL,
    name character varying(50)
);


ALTER TABLE view_levels OWNER TO postgres;

--
-- TOC entry 4284 (class 0 OID 0)
-- Dependencies: 303
-- Name: TABLE view_levels; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE view_levels IS 'Níveis de segurança para acesso controlado ao conteúdo de usuário';


--
-- TOC entry 302 (class 1259 OID 45072)
-- Name: view_levels_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE view_levels_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE view_levels_id_seq OWNER TO postgres;

--
-- TOC entry 4285 (class 0 OID 0)
-- Dependencies: 302
-- Name: view_levels_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE view_levels_id_seq OWNED BY view_levels.id;


--
-- TOC entry 3952 (class 2604 OID 22549)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY alert_types ALTER COLUMN id SET DEFAULT nextval('alerts_types_id_seq'::regclass);


--
-- TOC entry 3950 (class 2604 OID 22522)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY alerts ALTER COLUMN id SET DEFAULT nextval('alerts_id_seq'::regclass);


--
-- TOC entry 3955 (class 2604 OID 28438)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY bike_keepers ALTER COLUMN id SET DEFAULT nextval('bike_keepers_id_seq'::regclass);


--
-- TOC entry 3969 (class 2604 OID 28601)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY events ALTER COLUMN id SET DEFAULT nextval('events_id_seq'::regclass);


--
-- TOC entry 3966 (class 2604 OID 28482)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY galleries ALTER COLUMN id SET DEFAULT nextval('galleries_id_seq'::regclass);


--
-- TOC entry 3964 (class 2604 OID 28462)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY multimedia_types ALTER COLUMN id SET DEFAULT nextval('multimedia_types_id_seq'::regclass);


--
-- TOC entry 3961 (class 2604 OID 28454)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY multimedias ALTER COLUMN id SET DEFAULT nextval('multimedias_id_seq'::regclass);


--
-- TOC entry 3973 (class 2604 OID 28621)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY routes ALTER COLUMN id SET DEFAULT nextval('routes_id_seq'::regclass);


--
-- TOC entry 3953 (class 2604 OID 28339)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY spatial_types ALTER COLUMN id SET DEFAULT nextval('alerts_types_geometries_id_seq'::regclass);


--
-- TOC entry 3981 (class 2604 OID 45066)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY user_feedings ALTER COLUMN id SET DEFAULT nextval('user_feedings_id_seq'::regclass);


--
-- TOC entry 3975 (class 2604 OID 36860)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY user_friendship_requests ALTER COLUMN id SET DEFAULT nextval('user_friendship_requests_id_seq'::regclass);


--
-- TOC entry 3984 (class 2604 OID 45096)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY user_sharing_types ALTER COLUMN id SET DEFAULT nextval('user_sharing_types_id_seq'::regclass);


--
-- TOC entry 3979 (class 2604 OID 45057)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY user_sharings ALTER COLUMN id SET DEFAULT nextval('user_sharings_id_seq'::regclass);


--
-- TOC entry 3954 (class 2604 OID 28397)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY user_trackings ALTER COLUMN id SET DEFAULT nextval('user_tracking_id_seq'::regclass);


--
-- TOC entry 3948 (class 2604 OID 20018)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY users ALTER COLUMN id SET DEFAULT nextval('user_id_seq'::regclass);


--
-- TOC entry 3983 (class 2604 OID 45077)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY view_levels ALTER COLUMN id SET DEFAULT nextval('view_levels_id_seq'::regclass);


--
-- TOC entry 4199 (class 0 OID 22546)
-- Dependencies: 274
-- Data for Name: alert_types; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY alert_types (id, description, parent_type_id) FROM stdin;
3	Roubos e Furtos	\N
2	Perigo na Via	\N
4	Interdições	\N
1	Alerta Genérico	\N
\.


--
-- TOC entry 4202 (class 0 OID 28356)
-- Dependencies: 277
-- Data for Name: alert_types_spatial_types; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY alert_types_spatial_types (alert_types_id, spatial_types_id) FROM stdin;
4	1
3	1
2	1
\.


--
-- TOC entry 4197 (class 0 OID 22519)
-- Dependencies: 272
-- Data for Name: alerts; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY alerts (id, title, description, type_id, user_id, created_date, likes, dislikes, updated_date, geom, enable, duration_date) FROM stdin;
24	Teste de roubo	Ooopps\r\n	3	2	2017-04-19 23:31:39	1	\N	\N	0101000020110F0000FFFFFF3FEFC945C0FFD28F68B1E936C0	1	\N
22	\N	Obras prefeitura.	4	1	2017-04-20 23:21:52	\N	1	\N	0101000020110F0000FBFFFF7F53DA45C0DE76AA1403ED36C0	1	\N
8	Acidênte no posto de combustível	Carro bateu em uma das bombas de combustível do posto da Av. João XXIII	4	1	2016-10-28 23:50:19	\N	\N	\N	0101000020110F0000FFFFFFFF20D845C098B9C8C849E936C0	1	\N
23	Teste teste Enable	Teste de novo ?	4	1	2017-04-19 23:30:05	\N	\N	\N	0101000020110F0000000000809ACF45C0D247EE7A42EA36C0	1	\N
26	Teste de outro	Teste	1	1	2017-04-19 23:34:08	\N	\N	\N	0101000020110F000001000080C9AF45C057F765AEF5EB36C0	1	\N
21	Obras BRT	teste	4	1	2017-04-15 00:33:14	\N	\N	\N	0101000020110F0000000000700CB945C063449B3700DD36C0	0	2017-04-15 02:25:00
27	Teste Enable	Teste1	1	1	2017-04-19 23:51:24	\N	\N	\N	0101000020110F000000800BB785C745C0A08180BFBC0137C0	0	2017-04-20 15:58:53
25		Perigo animal morto	2	1	2017-04-19 23:32:19	\N	\N	\N	0101000020110F000000000000C3BD45C0CBC271511CE136C0	0	2017-04-20 15:58:53
6	Incêndio na morro de Paciências	Bombeiros no local, rua interditada	4	1	2016-10-28 23:44:41	\N	\N	\N	0101000020110F00000000000073D145C0B42741A543E736C0	0	2017-04-20 15:58:53
7	Saneamento da orla	Rua interditada	4	1	2016-10-28 23:47:09	\N	\N	\N	0101000020110F0000000000C09CD845C0F4F9FF6294FB36C0	0	2017-04-20 15:58:53
\.


--
-- TOC entry 4286 (class 0 OID 0)
-- Dependencies: 271
-- Name: alerts_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('alerts_id_seq', 29, true);


--
-- TOC entry 4287 (class 0 OID 0)
-- Dependencies: 276
-- Name: alerts_types_geometries_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('alerts_types_geometries_id_seq', 7, true);


--
-- TOC entry 4288 (class 0 OID 0)
-- Dependencies: 273
-- Name: alerts_types_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('alerts_types_id_seq', 3, true);


--
-- TOC entry 4206 (class 0 OID 28435)
-- Dependencies: 281
-- Data for Name: bike_keepers; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY bike_keepers (id, title, likes, dislikes, capacity, used_capacity, user_id, description, outdoor, public, created_date, enable, geom, public_dir_name, cost, updated_date) FROM stdin;
1	Bicicletário Supervia	\N	\N	250	\N	1	Administrado pela concessionária de trens Supervia. Clientes que seguirem viagem de trêm não pagam taxa.	0	1	2017-01-27 18:59:05	1	0101000020110F00000000006481D745C035B9F43D3FEA36C0	798130f65d286205a6325ef533858fbc6ce81ad0afd8d26b4c7716388bbcc3bb1	1	\N
2	Bike Park	\N	\N	30	\N	1	Administração privada, próximo ao centro de Santa Cruz.	0	1	2017-01-27 20:43:02	1	0101000020110F000000000004E1D745C06A44DE3313EA36C0	fcbe800376b367d2ee2aff49f589e68105223f1ef74675c4527ecbbd86f833232	3	\N
\.


--
-- TOC entry 4289 (class 0 OID 0)
-- Dependencies: 280
-- Name: bike_keepers_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('bike_keepers_id_seq', 2, true);


--
-- TOC entry 4213 (class 0 OID 28515)
-- Dependencies: 288
-- Data for Name: bike_keepers_multimedias; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY bike_keepers_multimedias (bike_keepers_id, multimedias_id) FROM stdin;
\.


--
-- TOC entry 4215 (class 0 OID 28598)
-- Dependencies: 290
-- Data for Name: events; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY events (id, title, start_date, end_date, likes, unlikes, description, visible, enable, user_id) FROM stdin;
\.


--
-- TOC entry 4290 (class 0 OID 0)
-- Dependencies: 289
-- Name: events_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('events_id_seq', 1, false);


--
-- TOC entry 4212 (class 0 OID 28479)
-- Dependencies: 287
-- Data for Name: galleries; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY galleries (id, title, user_id, created_date) FROM stdin;
0	Default Gallery	0	2015-11-03 13:41:00
\.


--
-- TOC entry 4291 (class 0 OID 0)
-- Dependencies: 286
-- Name: galleries_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('galleries_id_seq', 1, false);


--
-- TOC entry 4210 (class 0 OID 28459)
-- Dependencies: 285
-- Data for Name: multimedia_types; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY multimedia_types (id, title, description, mime_types) FROM stdin;
1	imagem	Fotografias ou  imagens	image/jpeg;image/pjpeg;image/jpeg;image/png
2	vídeo	Vídeo de média duração (avi, mp4, webm)	video/avi;video/msvideo;video/x-msvideo;video/webm;video/ogg
0	default	Genérico	application/octet-stream;
\.


--
-- TOC entry 4292 (class 0 OID 0)
-- Dependencies: 284
-- Name: multimedia_types_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('multimedia_types_id_seq', 2, true);


--
-- TOC entry 4208 (class 0 OID 28451)
-- Dependencies: 283
-- Data for Name: multimedias; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY multimedias (id, type_id, title, created_date, src, gallery_id) FROM stdin;
1	1	\N	2017-01-10 11:38:21	C:\\Users\\User\\Dropbox\\www\\app/web/bike-keepers/fcbe800376b367d2ee2aff49f589e68105223f1ef74675c4527ecbbd86f833232/images/bicicletario_sc1.png	0
2	1	\N	2017-01-10 11:39:08	C:\\Users\\User\\Dropbox\\www\\app/web/bike-keepers/fcbe800376b367d2ee2aff49f589e68105223f1ef74675c4527ecbbd86f833232/images/bicicletario_sc2.png	0
3	1	\N	2017-01-26 18:04:30	C:\\Users\\User\\Dropbox\\www\\app/web/bike-keepers/7b1403a21d566ab260697b6be6071b8a6584055c1e3c5f35531c038faccbea5f3/images/bicicletario_sc1.png	0
4	1	\N	2017-01-26 18:04:31	C:\\Users\\User\\Dropbox\\www\\app/web/bike-keepers/7b1403a21d566ab260697b6be6071b8a6584055c1e3c5f35531c038faccbea5f3/images/bicicletario_sc2.png	0
5	1	\N	2017-01-26 18:10:02	C:\\Users\\User\\Dropbox\\www\\app/web/bike-keepers/7b1403a21d566ab260697b6be6071b8a6584055c1e3c5f35531c038faccbea5f3/images/bicicletario_sc1.png	0
6	1	\N	2017-01-26 18:10:02	C:\\Users\\User\\Dropbox\\www\\app/web/bike-keepers/7b1403a21d566ab260697b6be6071b8a6584055c1e3c5f35531c038faccbea5f3/images/bicicletario_sc2.png	0
7	1	\N	2017-01-26 18:11:10	C:\\Users\\User\\Dropbox\\www\\app/web/bike-keepers/798130f65d286205a6325ef533858fbc6ce81ad0afd8d26b4c7716388bbcc3bb1/images/bicicletario_sc1.png	0
8	1	\N	2017-01-26 18:11:10	C:\\Users\\User\\Dropbox\\www\\app/web/bike-keepers/798130f65d286205a6325ef533858fbc6ce81ad0afd8d26b4c7716388bbcc3bb1/images/bicicletario_sc2.png	0
9	1	\N	2017-01-26 18:45:32	C:\\Users\\User\\Dropbox\\www\\app/web/bike-keepers/63dcb488f0e1ea0fa7e02d4a26270005d43f73c8fafb7141cf685bc3b392dcb16/images/bicicletario_sc1.png	0
10	1	\N	2017-01-26 18:45:32	C:\\Users\\User\\Dropbox\\www\\app/web/bike-keepers/63dcb488f0e1ea0fa7e02d4a26270005d43f73c8fafb7141cf685bc3b392dcb16/images/bicicletario_sc2.png	0
11	1	\N	2017-01-26 18:48:23	C:\\Users\\User\\Dropbox\\www\\app/web/bike-keepers/dae30693d68e08df98e50f87b00328cd99e27caa86b873f067ad31bf0213453e7/images/bicicletario_sc1.png	0
12	1	\N	2017-01-26 18:48:24	C:\\Users\\User\\Dropbox\\www\\app/web/bike-keepers/dae30693d68e08df98e50f87b00328cd99e27caa86b873f067ad31bf0213453e7/images/bicicletario_sc2.png	0
13	1	\N	2017-01-26 19:05:48	C:\\Users\\User\\Dropbox\\www\\app/web/bike-keepers/798130f65d286205a6325ef533858fbc6ce81ad0afd8d26b4c7716388bbcc3bb1/images/bicicletario_sc1.png	0
14	1	\N	2017-01-26 19:05:48	C:\\Users\\User\\Dropbox\\www\\app/web/bike-keepers/798130f65d286205a6325ef533858fbc6ce81ad0afd8d26b4c7716388bbcc3bb1/images/bicicletario_sc2.png	0
15	1	\N	2017-01-26 19:11:04	C:\\Users\\User\\Dropbox\\www\\app/web/bike-keepers/7b1403a21d566ab260697b6be6071b8a6584055c1e3c5f35531c038faccbea5f3/images/bicicletario_sc1.png	0
16	1	\N	2017-01-26 19:11:04	C:\\Users\\User\\Dropbox\\www\\app/web/bike-keepers/7b1403a21d566ab260697b6be6071b8a6584055c1e3c5f35531c038faccbea5f3/images/bicicletario_sc2.png	0
17	1	\N	2017-01-26 19:56:05	C:\\Users\\User\\Dropbox\\www\\app/web/bike-keepers/c6b73e816c0d379fdc8d48cfc960d4d678ecc90a1e3bd9ae7cd00f95e7ec841d4/images/bicicletario_sc2.png	0
18	1	\N	2017-01-26 19:59:14	C:\\Users\\User\\Dropbox\\www\\app/web/bike-keepers/0e03792ad29adc35de79be9fd53141cc96c36c8376883894eacc8b7cd4d0fec05/images/bicicletario_sc1.png	0
19	1	\N	2017-01-26 20:04:48	C:\\Users\\User\\Dropbox\\www\\app/web/bike-keepers/63dcb488f0e1ea0fa7e02d4a26270005d43f73c8fafb7141cf685bc3b392dcb16/images/bicicletario_sc1.png	0
20	1	\N	2017-01-26 20:07:43	C:\\Users\\User\\Dropbox\\www\\app/web/bike-keepers/dae30693d68e08df98e50f87b00328cd99e27caa86b873f067ad31bf0213453e7/images/bicicletario_sc2.png	0
21	1	\N	2017-01-26 20:09:43	C:\\Users\\User\\Dropbox\\www\\app/web/bike-keepers/8b6be9b5487ea6de330c87b96f7fa1f7730e6e82c2f6496fa3258381b24739098/images/bicicletario_sc1.png	0
22	1	\N	2017-01-26 20:27:33	C:\\Users\\User\\Dropbox\\www\\app/web/bike-keepers/d64d764cbbb0d412e80d0a16521b97918bb4ba7f01f6ece82f718d7bc06906719/images/bicicletario_sc1.png	0
23	1	\N	2017-01-26 20:37:55	C:\\Users\\User\\Dropbox\\www\\app/web/bike-keepers/fcc81c0395a450dcb1a256a8c7837ddacc5c5bfcdccab781aa6381f47a1c6ebb10/images/bicicletario_sc2.png	0
24	1	\N	2017-01-26 20:50:39	C:\\Users\\User\\Dropbox\\www\\app/web/bike-keepers/798130f65d286205a6325ef533858fbc6ce81ad0afd8d26b4c7716388bbcc3bb1/images/bicicletario_sc1.png	0
25	1	\N	2017-01-26 20:53:53	C:\\Users\\User\\Dropbox\\www\\app/web/bike-keepers/7b1403a21d566ab260697b6be6071b8a6584055c1e3c5f35531c038faccbea5f3/images/bicicletario_sc2.png	0
26	1	\N	2017-01-26 21:00:23	C:\\Users\\User\\Dropbox\\www\\app/web/bike-keepers/c6b73e816c0d379fdc8d48cfc960d4d678ecc90a1e3bd9ae7cd00f95e7ec841d4/images/bicicletario_sc2.png	0
27	1	\N	2017-01-26 21:02:59	C:\\Users\\User\\Dropbox\\www\\app/web/bike-keepers/0e03792ad29adc35de79be9fd53141cc96c36c8376883894eacc8b7cd4d0fec05/images/bicicletario_sc2.png	0
28	1	\N	2017-01-26 21:09:37	C:\\Users\\User\\Dropbox\\www\\app/web/bike-keepers/63dcb488f0e1ea0fa7e02d4a26270005d43f73c8fafb7141cf685bc3b392dcb16/images/bicicletario_sc2.png	0
29	1	\N	2017-01-26 21:24:45	C:\\Users\\User\\Dropbox\\www\\app/web/bike-keepers/dae30693d68e08df98e50f87b00328cd99e27caa86b873f067ad31bf0213453e7/images/Maki_Icons_By_Mapbox.png	0
30	1	\N	2017-01-26 21:25:54	C:\\Users\\User\\Dropbox\\www\\app/web/bike-keepers/8b6be9b5487ea6de330c87b96f7fa1f7730e6e82c2f6496fa3258381b24739098/images/Maki_Icons_By_Mapbox.png	0
31	1	\N	2017-01-26 22:04:42	C:\\Users\\User\\Dropbox\\www\\app/web/bike-keepers/d64d764cbbb0d412e80d0a16521b97918bb4ba7f01f6ece82f718d7bc06906719/images/bicycle_512.png	0
32	1	\N	2017-01-26 22:06:36	C:\\Users\\User\\Dropbox\\www\\app/web/bike-keepers/fcc81c0395a450dcb1a256a8c7837ddacc5c5bfcdccab781aa6381f47a1c6ebb10/images/alert_construction_40.png	0
33	1	\N	2017-01-26 22:07:29	C:\\Users\\User\\Dropbox\\www\\app/web/bike-keepers/6a6749f90ef25cbf7fdd7cdf986cbb91ad3b860ad548833576bdf94de65774ce11/images/alert_construction_bk_80.png	0
34	1	\N	2017-01-26 22:10:31	C:\\Users\\User\\Dropbox\\www\\app/web/bike-keepers/798130f65d286205a6325ef533858fbc6ce81ad0afd8d26b4c7716388bbcc3bb1/images/bicicletario_sc1.png	0
35	1	\N	2017-01-26 22:10:31	C:\\Users\\User\\Dropbox\\www\\app/web/bike-keepers/798130f65d286205a6325ef533858fbc6ce81ad0afd8d26b4c7716388bbcc3bb1/images/bicicletario_sc2.png	0
36	1	\N	2017-01-26 22:12:38	C:\\Users\\User\\Dropbox\\www\\app/web/bike-keepers/fcbe800376b367d2ee2aff49f589e68105223f1ef74675c4527ecbbd86f833232/images/bicicletario_sc1.png	0
37	1	\N	2017-01-27 18:59:05	C:\\Users\\User\\Dropbox\\www\\app/web/bike-keepers/798130f65d286205a6325ef533858fbc6ce81ad0afd8d26b4c7716388bbcc3bb1/images/bicicletario_sc1.png	0
38	1	\N	2017-01-27 20:43:02	C:\\Users\\User\\Dropbox\\www\\app/web/bike-keepers/fcbe800376b367d2ee2aff49f589e68105223f1ef74675c4527ecbbd86f833232/images/bike_park_sc.png	0
\.


--
-- TOC entry 4293 (class 0 OID 0)
-- Dependencies: 282
-- Name: multimedias_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('multimedias_id_seq', 38, true);


--
-- TOC entry 3938 (class 0 OID 20651)
-- Dependencies: 266
-- Data for Name: pointcloud_formats; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY pointcloud_formats  FROM stdin;
\.


--
-- TOC entry 4217 (class 0 OID 28618)
-- Dependencies: 292
-- Data for Name: routes; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY routes (id, geom, user_id, description, created_date) FROM stdin;
\.


--
-- TOC entry 4294 (class 0 OID 0)
-- Dependencies: 291
-- Name: routes_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('routes_id_seq', 1, false);


--
-- TOC entry 3942 (class 0 OID 18883)
-- Dependencies: 193
-- Data for Name: spatial_ref_sys; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY spatial_ref_sys  FROM stdin;
\.


--
-- TOC entry 4200 (class 0 OID 28334)
-- Dependencies: 275
-- Data for Name: spatial_types; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY spatial_types (id, description) FROM stdin;
1	Point
2	LineString
3	Polygon
4	MultiPoint
5	MultiLineString
6	MultiPolygon
7	GeometryCollection
\.


--
-- TOC entry 3941 (class 0 OID 20621)
-- Dependencies: 263
-- Data for Name: us_gaz; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY us_gaz  FROM stdin;
\.


--
-- TOC entry 3939 (class 0 OID 20607)
-- Dependencies: 261
-- Data for Name: us_lex; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY us_lex  FROM stdin;
\.


--
-- TOC entry 3940 (class 0 OID 20635)
-- Dependencies: 265
-- Data for Name: us_rules; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY us_rules  FROM stdin;
\.


--
-- TOC entry 4230 (class 0 OID 45172)
-- Dependencies: 310
-- Data for Name: user_alert_existence; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY user_alert_existence (user_id, alert_id, created_date) FROM stdin;
\.


--
-- TOC entry 4229 (class 0 OID 45158)
-- Dependencies: 309
-- Data for Name: user_alert_rates; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY user_alert_rates (user_id, alert_id, created_date, rating, updated_date) FROM stdin;
1	24	2017-04-29 00:55:03	likes	\N
1	22	2017-04-30 01:50:51	dislikes	\N
\.


--
-- TOC entry 4224 (class 0 OID 45063)
-- Dependencies: 301
-- Data for Name: user_feedings; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY user_feedings (id, user_id, user_sharing_id, text, likes, created_date, updated_date, view_level_id, dislikes) FROM stdin;
\.


--
-- TOC entry 4295 (class 0 OID 0)
-- Dependencies: 300
-- Name: user_feedings_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('user_feedings_id_seq', 1, false);


--
-- TOC entry 4220 (class 0 OID 36857)
-- Dependencies: 295
-- Data for Name: user_friendship_requests; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY user_friendship_requests (id, user_id, requested_user_id, created_date, enable) FROM stdin;
\.


--
-- TOC entry 4296 (class 0 OID 0)
-- Dependencies: 294
-- Name: user_friendship_requests_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('user_friendship_requests_id_seq', 42, true);


--
-- TOC entry 4218 (class 0 OID 36850)
-- Dependencies: 293
-- Data for Name: user_friendships; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY user_friendships (user_id, friend_user_id, created_date) FROM stdin;
4	2	2017-04-11 06:35:55
1	2	2017-04-11 07:25:03
\.


--
-- TOC entry 4297 (class 0 OID 0)
-- Dependencies: 208
-- Name: user_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('user_id_seq', 4, true);


--
-- TOC entry 4228 (class 0 OID 45093)
-- Dependencies: 305
-- Data for Name: user_sharing_types; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY user_sharing_types (id, name) FROM stdin;
\.


--
-- TOC entry 4298 (class 0 OID 0)
-- Dependencies: 304
-- Name: user_sharing_types_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('user_sharing_types_id_seq', 1, false);


--
-- TOC entry 4222 (class 0 OID 45054)
-- Dependencies: 299
-- Data for Name: user_sharings; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY user_sharings (id, user_id, user_feeding_id, view_level_id, content_id, created_date, updated_date, sharing_type_id) FROM stdin;
\.


--
-- TOC entry 4299 (class 0 OID 0)
-- Dependencies: 298
-- Name: user_sharings_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('user_sharings_id_seq', 1, false);


--
-- TOC entry 4300 (class 0 OID 0)
-- Dependencies: 278
-- Name: user_tracking_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('user_tracking_id_seq', 1, false);


--
-- TOC entry 4204 (class 0 OID 28394)
-- Dependencies: 279
-- Data for Name: user_trackings; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY user_trackings (id, user_id, register_date, geom) FROM stdin;
\.


--
-- TOC entry 4194 (class 0 OID 20012)
-- Dependencies: 207
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY users (id, first_name, last_name, how_to_be_called, username, email, password, signup_date, last_access_date, auth_key, access_token, home_dir_name, online, question, answer, full_name) FROM stdin;
4	João Marcus	da Silva Gomes	João	joaocarlos	joaocarlos_1544812120@bol.com.br	123456	2017-04-09 22:38:53	2017-04-11 04:17:49	03mQZnINC6GUt4Bro2j27UzHC0196IOl	\N	34bbc48a4dd10814f2a31e82ecd9f214	0	teste	teste	João Marcus da Silva Gomes
0	Bike	Social	BikeSocial	bikesocial	marcusfaccion@bol.com.br	bikesocial	2016-11-03 13:44:18.824322	2017-04-11 04:28:56	\N	\N	856399845ab74597eda9777f091b277a	0	\N	\N	Bike Social
1	Marcus Vinicius	Faccion	Marcus	marcusfaccion	marcusfaccion@bol.com.br	23064140	2016-04-13 11:00:00	2017-04-20 17:40:38	hEhA7gNNCs8NylQ28bjtVpJyb1hqFx1P	\N	a99aa5e1912ac0ee7d0b2f8ce3e272ee	0	\N	\N	Marcus Vinicius Faccion
2	Thatiane	Copque	Thaty	thatiane	thatiane_copque@hotmail.com	123	2016-11-20 04:38:59.73246	2017-04-21 00:46:05	fHAyDZexEGjxh4RvCbHkd4fIP6OQPJWE	\N	9e459de99798f751a83cab6667d83491	0	Primeiro nome do esposo?	marcus	Thatiane Copque
3	Default	Default	Default	default	default@hotmail.com	123456	2016-11-20 05:07:14.755831	\N	MFKZdmbnLVXRN3JJqCQVs5BRk-2IsoQE	\N	41378f6ece1dbf69d8eebe741a33c11d	0	Primeiro nome do seu criador?	marcus	Default Default
\.


--
-- TOC entry 4226 (class 0 OID 45074)
-- Dependencies: 303
-- Data for Name: view_levels; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY view_levels (id, name) FROM stdin;
\.


--
-- TOC entry 4301 (class 0 OID 0)
-- Dependencies: 302
-- Name: view_levels_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('view_levels_id_seq', 1, false);


SET search_path = tiger, pg_catalog;

--
-- TOC entry 3943 (class 0 OID 20171)
-- Dependencies: 210
-- Data for Name: geocode_settings; Type: TABLE DATA; Schema: tiger; Owner: postgres
--

COPY geocode_settings  FROM stdin;
\.


--
-- TOC entry 3944 (class 0 OID 20526)
-- Dependencies: 254
-- Data for Name: pagc_gaz; Type: TABLE DATA; Schema: tiger; Owner: postgres
--

COPY pagc_gaz  FROM stdin;
\.


--
-- TOC entry 3945 (class 0 OID 20538)
-- Dependencies: 256
-- Data for Name: pagc_lex; Type: TABLE DATA; Schema: tiger; Owner: postgres
--

COPY pagc_lex  FROM stdin;
\.


--
-- TOC entry 3946 (class 0 OID 20550)
-- Dependencies: 258
-- Data for Name: pagc_rules; Type: TABLE DATA; Schema: tiger; Owner: postgres
--

COPY pagc_rules  FROM stdin;
\.


SET search_path = public, pg_catalog;

--
-- TOC entry 3993 (class 2606 OID 28348)
-- Name: alert_types_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY alert_types
    ADD CONSTRAINT alert_types_pk PRIMARY KEY (id);


--
-- TOC entry 3998 (class 2606 OID 28372)
-- Name: alert_types_spatial_types_ManyMany_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY alert_types_spatial_types
    ADD CONSTRAINT "alert_types_spatial_types_ManyMany_pk" PRIMARY KEY (alert_types_id, spatial_types_id);


--
-- TOC entry 3996 (class 2606 OID 28355)
-- Name: alert_types_spatial_types_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY spatial_types
    ADD CONSTRAINT alert_types_spatial_types_pk PRIMARY KEY (id);


--
-- TOC entry 3989 (class 2606 OID 22524)
-- Name: alerts_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY alerts
    ADD CONSTRAINT alerts_pk PRIMARY KEY (id);


--
-- TOC entry 4012 (class 2606 OID 28519)
-- Name: bike_keepers_multimedias_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY bike_keepers_multimedias
    ADD CONSTRAINT bike_keepers_multimedias_pk PRIMARY KEY (bike_keepers_id, multimedias_id);


--
-- TOC entry 4004 (class 2606 OID 28440)
-- Name: bike_keepers_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY bike_keepers
    ADD CONSTRAINT bike_keepers_pk PRIMARY KEY (id);


--
-- TOC entry 4014 (class 2606 OID 28609)
-- Name: events_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY events
    ADD CONSTRAINT events_pk PRIMARY KEY (id);


--
-- TOC entry 4010 (class 2606 OID 28485)
-- Name: galleries_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY galleries
    ADD CONSTRAINT galleries_pk PRIMARY KEY (id);


--
-- TOC entry 4008 (class 2606 OID 28467)
-- Name: multimedia_types_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY multimedia_types
    ADD CONSTRAINT multimedia_types_pk PRIMARY KEY (id);


--
-- TOC entry 4006 (class 2606 OID 28456)
-- Name: multimedias_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY multimedias
    ADD CONSTRAINT multimedias_pk PRIMARY KEY (id);


--
-- TOC entry 3986 (class 2606 OID 20020)
-- Name: pk_user; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY users
    ADD CONSTRAINT pk_user PRIMARY KEY (id);


--
-- TOC entry 4017 (class 2606 OID 28627)
-- Name: routes_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY routes
    ADD CONSTRAINT routes_pk PRIMARY KEY (id);


--
-- TOC entry 4039 (class 2606 OID 45176)
-- Name: user_alert_existence_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY user_alert_existence
    ADD CONSTRAINT user_alert_existence_pk PRIMARY KEY (user_id, alert_id);


--
-- TOC entry 4037 (class 2606 OID 45162)
-- Name: user_alert_rates_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY user_alert_rates
    ADD CONSTRAINT user_alert_rates_pk PRIMARY KEY (user_id, alert_id);


--
-- TOC entry 4031 (class 2606 OID 45071)
-- Name: user_feedings_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY user_feedings
    ADD CONSTRAINT user_feedings_pk PRIMARY KEY (id);


--
-- TOC entry 4021 (class 2606 OID 36862)
-- Name: user_friendship_requests_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY user_friendship_requests
    ADD CONSTRAINT user_friendship_requests_pkey PRIMARY KEY (id);


--
-- TOC entry 4019 (class 2606 OID 36854)
-- Name: user_friendships_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY user_friendships
    ADD CONSTRAINT user_friendships_pk PRIMARY KEY (user_id, friend_user_id);


--
-- TOC entry 4035 (class 2606 OID 45098)
-- Name: user_sharing_types_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY user_sharing_types
    ADD CONSTRAINT user_sharing_types_pk PRIMARY KEY (id);


--
-- TOC entry 4026 (class 2606 OID 45059)
-- Name: user_sharings_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY user_sharings
    ADD CONSTRAINT user_sharings_pk PRIMARY KEY (id);


--
-- TOC entry 4001 (class 2606 OID 28399)
-- Name: user_trackings_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY user_trackings
    ADD CONSTRAINT user_trackings_pk PRIMARY KEY (id);


--
-- TOC entry 4033 (class 2606 OID 45079)
-- Name: view_levels_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY view_levels
    ADD CONSTRAINT view_levels_pk PRIMARY KEY (id);


--
-- TOC entry 3987 (class 1259 OID 28373)
-- Name: alerts_geom_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX alerts_geom_idx ON alerts USING gist (geom);


--
-- TOC entry 4002 (class 1259 OID 28647)
-- Name: bike_keepers_geom_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX bike_keepers_geom_idx ON bike_keepers USING gist (geom);


--
-- TOC entry 3994 (class 1259 OID 28391)
-- Name: fki_alert_types_belongsTo_alert_types_fk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX "fki_alert_types_belongsTo_alert_types_fk" ON alert_types USING btree (parent_type_id);


--
-- TOC entry 3990 (class 1259 OID 28385)
-- Name: fki_alerts_belongsTo_alert_types_fk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX "fki_alerts_belongsTo_alert_types_fk" ON alerts USING btree (type_id);


--
-- TOC entry 3991 (class 1259 OID 28379)
-- Name: fki_alerts_belongsTo_users_fk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX "fki_alerts_belongsTo_users_fk" ON alerts USING btree (user_id);


--
-- TOC entry 4027 (class 1259 OID 45134)
-- Name: fki_user_feedings_belongsTo_user_sharings_fk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX "fki_user_feedings_belongsTo_user_sharings_fk" ON user_feedings USING btree (user_sharing_id);


--
-- TOC entry 4028 (class 1259 OID 45127)
-- Name: fki_user_feedings_belongsTo_users_fk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX "fki_user_feedings_belongsTo_users_fk" ON user_feedings USING btree (user_id);


--
-- TOC entry 4029 (class 1259 OID 45140)
-- Name: fki_user_feedings_belongsTo_view_levels_fk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX "fki_user_feedings_belongsTo_view_levels_fk" ON user_feedings USING btree (view_level_id);


--
-- TOC entry 4022 (class 1259 OID 45121)
-- Name: fki_user_sharings_belongsTo_user_sharing_types_pk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX "fki_user_sharings_belongsTo_user_sharing_types_pk" ON user_sharings USING btree (sharing_type_id);


--
-- TOC entry 4023 (class 1259 OID 45090)
-- Name: fki_user_sharings_belongsTo_users_fk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX "fki_user_sharings_belongsTo_users_fk" ON user_sharings USING btree (user_id);


--
-- TOC entry 4024 (class 1259 OID 45110)
-- Name: fki_user_sharings_belongsTo_view_levels_fk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX "fki_user_sharings_belongsTo_view_levels_fk" ON user_sharings USING btree (view_level_id);


--
-- TOC entry 4015 (class 1259 OID 28633)
-- Name: routes_geom_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX routes_geom_idx ON routes USING gist (geom);


--
-- TOC entry 3999 (class 1259 OID 28527)
-- Name: user_tackings_geom_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX user_tackings_geom_idx ON user_trackings USING gist (geom);


--
-- TOC entry 4042 (class 2606 OID 28386)
-- Name: alert_types_belongsTo_alert_types_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY alert_types
    ADD CONSTRAINT "alert_types_belongsTo_alert_types_fk" FOREIGN KEY (parent_type_id) REFERENCES alert_types(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 4040 (class 2606 OID 28380)
-- Name: alerts_belongsTo_alert_types_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY alerts
    ADD CONSTRAINT "alerts_belongsTo_alert_types_fk" FOREIGN KEY (type_id) REFERENCES alert_types(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 4041 (class 2606 OID 28374)
-- Name: alerts_belongsTo_users_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY alerts
    ADD CONSTRAINT "alerts_belongsTo_users_fk" FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 4048 (class 2606 OID 45238)
-- Name: bike_keepers_belongsTo_bike_keepers_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY bike_keepers_multimedias
    ADD CONSTRAINT "bike_keepers_belongsTo_bike_keepers_fk" FOREIGN KEY (bike_keepers_id) REFERENCES bike_keepers(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 4049 (class 2606 OID 45233)
-- Name: bike_keepers_belongsTo_multimedias_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY bike_keepers_multimedias
    ADD CONSTRAINT "bike_keepers_belongsTo_multimedias_fk" FOREIGN KEY (multimedias_id) REFERENCES multimedias(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 4044 (class 2606 OID 28497)
-- Name: bike_keepers_belongsTo_users_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY bike_keepers
    ADD CONSTRAINT "bike_keepers_belongsTo_users_fk" FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE SET DEFAULT;


--
-- TOC entry 4050 (class 2606 OID 28610)
-- Name: events_belongsTo_users_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY events
    ADD CONSTRAINT "events_belongsTo_users_fk" FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE SET DEFAULT;


--
-- TOC entry 4047 (class 2606 OID 28491)
-- Name: galleries_belongsTo_users_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY galleries
    ADD CONSTRAINT "galleries_belongsTo_users_fk" FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE SET DEFAULT;


--
-- TOC entry 4046 (class 2606 OID 28510)
-- Name: multimedias_belongsTo_galleries_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY multimedias
    ADD CONSTRAINT "multimedias_belongsTo_galleries_fk" FOREIGN KEY (gallery_id) REFERENCES galleries(id) ON UPDATE CASCADE ON DELETE SET DEFAULT;


--
-- TOC entry 4045 (class 2606 OID 45228)
-- Name: multimedias_belongsTo_multimedia_types_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY multimedias
    ADD CONSTRAINT "multimedias_belongsTo_multimedia_types_fk" FOREIGN KEY (type_id) REFERENCES multimedia_types(id) ON UPDATE CASCADE ON DELETE SET DEFAULT;


--
-- TOC entry 4051 (class 2606 OID 28628)
-- Name: routes_belongsTo_users_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY routes
    ADD CONSTRAINT "routes_belongsTo_users_fk" FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE SET DEFAULT;


--
-- TOC entry 4065 (class 2606 OID 45212)
-- Name: user_alert_existence_belongsTo_alerts_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY user_alert_existence
    ADD CONSTRAINT "user_alert_existence_belongsTo_alerts_fk" FOREIGN KEY (alert_id) REFERENCES alerts(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 4066 (class 2606 OID 45207)
-- Name: user_alert_existence_belongsTo_users_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY user_alert_existence
    ADD CONSTRAINT "user_alert_existence_belongsTo_users_fk" FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 4063 (class 2606 OID 45192)
-- Name: user_alert_rates_belongsTo_alerts_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY user_alert_rates
    ADD CONSTRAINT "user_alert_rates_belongsTo_alerts_fk" FOREIGN KEY (alert_id) REFERENCES alerts(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 4064 (class 2606 OID 45187)
-- Name: user_alert_rates_belongsTo_users_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY user_alert_rates
    ADD CONSTRAINT "user_alert_rates_belongsTo_users_fk" FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 4061 (class 2606 OID 45129)
-- Name: user_feedings_belongsTo_user_sharings_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY user_feedings
    ADD CONSTRAINT "user_feedings_belongsTo_user_sharings_fk" FOREIGN KEY (user_sharing_id) REFERENCES user_sharings(id) ON UPDATE CASCADE ON DELETE SET NULL NOT VALID;


--
-- TOC entry 4062 (class 2606 OID 45122)
-- Name: user_feedings_belongsTo_users_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY user_feedings
    ADD CONSTRAINT "user_feedings_belongsTo_users_fk" FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE SET DEFAULT;


--
-- TOC entry 4060 (class 2606 OID 45135)
-- Name: user_feedings_belongsTo_view_levels_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY user_feedings
    ADD CONSTRAINT "user_feedings_belongsTo_view_levels_fk" FOREIGN KEY (view_level_id) REFERENCES view_levels(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 4054 (class 2606 OID 45248)
-- Name: user_friendship_requests_belongsTo_users2_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY user_friendship_requests
    ADD CONSTRAINT "user_friendship_requests_belongsTo_users2_fk" FOREIGN KEY (requested_user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 4055 (class 2606 OID 45243)
-- Name: user_friendship_requests_belongsTo_users_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY user_friendship_requests
    ADD CONSTRAINT "user_friendship_requests_belongsTo_users_fk" FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 4052 (class 2606 OID 45222)
-- Name: user_friendships_belongsTo_users2_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY user_friendships
    ADD CONSTRAINT "user_friendships_belongsTo_users2_fk" FOREIGN KEY (friend_user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 4053 (class 2606 OID 45217)
-- Name: user_friendships_belongsTo_users_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY user_friendships
    ADD CONSTRAINT "user_friendships_belongsTo_users_fk" FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 4059 (class 2606 OID 45080)
-- Name: user_sharings_belongsTo_user_feedings_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY user_sharings
    ADD CONSTRAINT "user_sharings_belongsTo_user_feedings_fk" FOREIGN KEY (user_feeding_id) REFERENCES user_feedings(id) ON UPDATE CASCADE ON DELETE SET NULL NOT VALID;


--
-- TOC entry 4056 (class 2606 OID 45116)
-- Name: user_sharings_belongsTo_user_sharing_types_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY user_sharings
    ADD CONSTRAINT "user_sharings_belongsTo_user_sharing_types_fk" FOREIGN KEY (sharing_type_id) REFERENCES user_sharing_types(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 4058 (class 2606 OID 45085)
-- Name: user_sharings_belongsTo_users_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY user_sharings
    ADD CONSTRAINT "user_sharings_belongsTo_users_fk" FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE SET DEFAULT;


--
-- TOC entry 4057 (class 2606 OID 45111)
-- Name: user_sharings_belongsTo_view_levels_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY user_sharings
    ADD CONSTRAINT "user_sharings_belongsTo_view_levels_fk" FOREIGN KEY (view_level_id) REFERENCES view_levels(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 4043 (class 2606 OID 28405)
-- Name: user_trackings_belongsTo_users_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY user_trackings
    ADD CONSTRAINT "user_trackings_belongsTo_users_fk" FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 4238 (class 0 OID 0)
-- Dependencies: 15
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- TOC entry 4254 (class 0 OID 0)
-- Dependencies: 280
-- Name: bike_keepers_id_seq; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON SEQUENCE bike_keepers_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE bike_keepers_id_seq FROM postgres;
GRANT ALL ON SEQUENCE bike_keepers_id_seq TO postgres;


-- Completed on 2017-04-30 02:47:02

--
-- PostgreSQL database dump complete
--

