--
-- PostgreSQL database dump
--

-- Dumped from database version 9.5.2
-- Dumped by pg_dump version 9.5.1

-- Started on 2017-06-11 06:44:22

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;

--
-- TOC entry 18 (class 2615 OID 2200)
-- Name: public; Type: SCHEMA; Schema: -; Owner: -
--

CREATE SCHEMA public;


--
-- TOC entry 4309 (class 0 OID 0)
-- Dependencies: 18
-- Name: SCHEMA public; Type: COMMENT; Schema: -; Owner: -
--

COMMENT ON SCHEMA public IS 'standard public schema';


SET search_path = public, pg_catalog;

--
-- TOC entry 2365 (class 1247 OID 45164)
-- Name: ratings; Type: TYPE; Schema: public; Owner: -
--

CREATE TYPE ratings AS ENUM (
    'likes',
    'dislikes'
);


--
-- TOC entry 1692 (class 1255 OID 45157)
-- Name: st_alerts_check_duration(integer); Type: FUNCTION; Schema: public; Owner: -
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


--
-- TOC entry 1693 (class 1255 OID 45487)
-- Name: st_disable_alerts(integer); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION st_disable_alerts(_id integer DEFAULT 0) RETURNS integer
    LANGUAGE plpgsql
    AS $$

DECLARE 
	disabled_num integer DEFAULT 0;
	nea_number integer DEFAULT 0;
	ctrl BOOLEAN := FALSE;
	alert_record record;
	alerts_cursor CURSOR FOR SELECT id, created_date, duration_date, likes, dislikes, type_id, 'enable'  FROM alerts WHERE enable=1;

BEGIN

IF _id=0 THEN
	-- Abre o cursor
	OPEN alerts_cursor;

	LOOP
		-- seleciona registro dentro da variável
		FETCH alerts_cursor INTO alert_record;
		-- sai quando não acha um registro pelo menos
		EXIT WHEN NOT FOUND;

		-- Critérios para desativação:
		
		-- Se o alerta possuir mais de 2 reportes de inexistência
		SELECT INTO nea_number count(*) FROM user_alert_nonexistence WHERE alert_id=alert_record.id;
		IF(nea_number>=2) THEN
			ctrl := TRUE;
			DELETE FROM user_alert_nonexistence
			WHERE alert_id=alert_record.id;
		END IF;

		-- Se numero de má avaliação for 1 vez e meia maior que o de boa avaliação
		IF((alert_record.dislikes::real/alert_record.likes::real)>=1.5) THEN
			ctrl :=  TRUE;
		END IF;

--		Se o alerta for do tipo roubos e furtos se tiver mais de 2 dias
		IF(alert_record.type_id=3 AND ((now() - interval '48 hours') > alert_record.created_date))THEN
			ctrl :=  TRUE;
		END IF;

		-- Se o alerta não for do tipo roubos e furtos e não tiver duration_date definido e se tiver mais de 1 dia
		IF(alert_record.type_id<>3 AND ((now() - interval '24 hours') > alert_record.created_date) AND alert_record.duration_date is null)THEN
			ctrl :=  TRUE;
		END IF;
		
		IF ctrl = TRUE THEN
			UPDATE alerts 
			SET enable = 0 
			WHERE CURRENT OF alerts_cursor;
			disabled_num := disabled_num + 1;
		END IF;
		ctrl := FALSE;
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


SET default_tablespace = '';

SET default_with_oids = false;

--
-- TOC entry 312 (class 1259 OID 45255)
-- Name: alert_comments; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE alert_comments (
    id integer NOT NULL,
    alert_id integer DEFAULT 0,
    user_id integer DEFAULT 0,
    text text,
    created_date timestamp without time zone
);


--
-- TOC entry 4311 (class 0 OID 0)
-- Dependencies: 312
-- Name: TABLE alert_comments; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON TABLE alert_comments IS 'Tabela de comentários sobre os alertas';


--
-- TOC entry 4312 (class 0 OID 0)
-- Dependencies: 312
-- Name: COLUMN alert_comments.alert_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN alert_comments.alert_id IS 'id do alerta a que se refere o comanetário';


--
-- TOC entry 4313 (class 0 OID 0)
-- Dependencies: 312
-- Name: COLUMN alert_comments.user_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN alert_comments.user_id IS 'id do usuário que comentou';


--
-- TOC entry 4314 (class 0 OID 0)
-- Dependencies: 312
-- Name: COLUMN alert_comments.text; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN alert_comments.text IS 'Texto do comentário';


--
-- TOC entry 311 (class 1259 OID 45253)
-- Name: alert_comments_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE alert_comments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 4315 (class 0 OID 0)
-- Dependencies: 311
-- Name: alert_comments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE alert_comments_id_seq OWNED BY alert_comments.id;


--
-- TOC entry 274 (class 1259 OID 22546)
-- Name: alert_types; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE alert_types (
    id integer NOT NULL,
    description character varying(50),
    parent_type_id integer
);


--
-- TOC entry 277 (class 1259 OID 28356)
-- Name: alert_types_spatial_types; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE alert_types_spatial_types (
    alert_types_id integer NOT NULL,
    spatial_types_id integer NOT NULL
);


--
-- TOC entry 272 (class 1259 OID 22519)
-- Name: alerts; Type: TABLE; Schema: public; Owner: -
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
    duration_date timestamp without time zone,
    address text
);


--
-- TOC entry 4316 (class 0 OID 0)
-- Dependencies: 272
-- Name: COLUMN alerts.duration_date; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN alerts.duration_date IS 'Data de encerramento do alerta';


--
-- TOC entry 4317 (class 0 OID 0)
-- Dependencies: 272
-- Name: COLUMN alerts.address; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN alerts.address IS 'Endereço em forma textual';


--
-- TOC entry 271 (class 1259 OID 22517)
-- Name: alerts_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE alerts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 4318 (class 0 OID 0)
-- Dependencies: 271
-- Name: alerts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE alerts_id_seq OWNED BY alerts.id;


--
-- TOC entry 275 (class 1259 OID 28334)
-- Name: spatial_types; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE spatial_types (
    id integer NOT NULL,
    description character varying(32)
);


--
-- TOC entry 276 (class 1259 OID 28337)
-- Name: alerts_types_geometries_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE alerts_types_geometries_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 4319 (class 0 OID 0)
-- Dependencies: 276
-- Name: alerts_types_geometries_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE alerts_types_geometries_id_seq OWNED BY spatial_types.id;


--
-- TOC entry 273 (class 1259 OID 22544)
-- Name: alerts_types_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE alerts_types_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 4320 (class 0 OID 0)
-- Dependencies: 273
-- Name: alerts_types_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE alerts_types_id_seq OWNED BY alert_types.id;


--
-- TOC entry 313 (class 1259 OID 45279)
-- Name: bike_keeper_comments_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE bike_keeper_comments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 314 (class 1259 OID 45281)
-- Name: bike_keeper_comments; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE bike_keeper_comments (
    id integer DEFAULT nextval('bike_keeper_comments_id_seq'::regclass) NOT NULL,
    bike_keeper_id integer DEFAULT 0,
    user_id integer DEFAULT 0,
    text text,
    created_date timestamp without time zone
);


--
-- TOC entry 4321 (class 0 OID 0)
-- Dependencies: 314
-- Name: TABLE bike_keeper_comments; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON TABLE bike_keeper_comments IS 'Tabela de comentários sobre os bicicletários';


--
-- TOC entry 4322 (class 0 OID 0)
-- Dependencies: 314
-- Name: COLUMN bike_keeper_comments.bike_keeper_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN bike_keeper_comments.bike_keeper_id IS 'id do bicicletário a que se refere o comanetário';


--
-- TOC entry 4323 (class 0 OID 0)
-- Dependencies: 314
-- Name: COLUMN bike_keeper_comments.user_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN bike_keeper_comments.user_id IS 'id do usuário que comentou';


--
-- TOC entry 4324 (class 0 OID 0)
-- Dependencies: 314
-- Name: COLUMN bike_keeper_comments.text; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN bike_keeper_comments.text IS 'Texto do comentário';


--
-- TOC entry 281 (class 1259 OID 28435)
-- Name: bike_keepers; Type: TABLE; Schema: public; Owner: -
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
    updated_date timestamp without time zone,
    business_hours text,
    tel character varying(20),
    email character varying(100),
    address text,
    is_open smallint DEFAULT 1
);


--
-- TOC entry 4325 (class 0 OID 0)
-- Dependencies: 281
-- Name: COLUMN bike_keepers.business_hours; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN bike_keepers.business_hours IS 'Descrição do horário de funcionamento';


--
-- TOC entry 4326 (class 0 OID 0)
-- Dependencies: 281
-- Name: COLUMN bike_keepers.tel; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN bike_keepers.tel IS 'Telefone de contato';


--
-- TOC entry 4327 (class 0 OID 0)
-- Dependencies: 281
-- Name: COLUMN bike_keepers.email; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN bike_keepers.email IS 'Email de contato';


--
-- TOC entry 4328 (class 0 OID 0)
-- Dependencies: 281
-- Name: COLUMN bike_keepers.address; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN bike_keepers.address IS 'Endereço em forma textual';


--
-- TOC entry 280 (class 1259 OID 28433)
-- Name: bike_keepers_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE bike_keepers_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 4329 (class 0 OID 0)
-- Dependencies: 280
-- Name: bike_keepers_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE bike_keepers_id_seq OWNED BY bike_keepers.id;


--
-- TOC entry 288 (class 1259 OID 28515)
-- Name: bike_keepers_multimedias; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE bike_keepers_multimedias (
    bike_keepers_id integer NOT NULL,
    multimedias_id integer NOT NULL
);


--
-- TOC entry 4331 (class 0 OID 0)
-- Dependencies: 288
-- Name: COLUMN bike_keepers_multimedias.multimedias_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN bike_keepers_multimedias.multimedias_id IS '
';


--
-- TOC entry 290 (class 1259 OID 28598)
-- Name: events; Type: TABLE; Schema: public; Owner: -
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


--
-- TOC entry 289 (class 1259 OID 28596)
-- Name: events_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE events_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 4332 (class 0 OID 0)
-- Dependencies: 289
-- Name: events_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE events_id_seq OWNED BY events.id;


--
-- TOC entry 287 (class 1259 OID 28479)
-- Name: galleries; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE galleries (
    id integer NOT NULL,
    title character varying(40),
    user_id integer DEFAULT 0,
    created_date timestamp without time zone
);


--
-- TOC entry 286 (class 1259 OID 28477)
-- Name: galleries_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE galleries_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 4333 (class 0 OID 0)
-- Dependencies: 286
-- Name: galleries_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE galleries_id_seq OWNED BY galleries.id;


--
-- TOC entry 285 (class 1259 OID 28459)
-- Name: multimedia_types; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE multimedia_types (
    id integer NOT NULL,
    title character varying(40),
    description text,
    mime_types character varying(255) DEFAULT ''::character varying
);


--
-- TOC entry 284 (class 1259 OID 28457)
-- Name: multimedia_types_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE multimedia_types_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 4334 (class 0 OID 0)
-- Dependencies: 284
-- Name: multimedia_types_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE multimedia_types_id_seq OWNED BY multimedia_types.id;


--
-- TOC entry 283 (class 1259 OID 28451)
-- Name: multimedias; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE multimedias (
    id integer NOT NULL,
    type_id integer DEFAULT 0,
    title character varying(40),
    created_date timestamp without time zone,
    src character varying(512),
    gallery_id integer DEFAULT 0
);


--
-- TOC entry 282 (class 1259 OID 28449)
-- Name: multimedias_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE multimedias_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 4335 (class 0 OID 0)
-- Dependencies: 282
-- Name: multimedias_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE multimedias_id_seq OWNED BY multimedias.id;


--
-- TOC entry 321 (class 1259 OID 45514)
-- Name: online_users; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE online_users (
    user_id integer NOT NULL,
    updated_date timestamp without time zone NOT NULL
);


--
-- TOC entry 4336 (class 0 OID 0)
-- Dependencies: 321
-- Name: TABLE online_users; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON TABLE online_users IS 'Tabela para identificação dos usuários online, de 1 em 1min é feito uma atualização de cada linha dessa tabela.';


--
-- TOC entry 4337 (class 0 OID 0)
-- Dependencies: 321
-- Name: COLUMN online_users.user_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN online_users.user_id IS 'Id do usuário';


--
-- TOC entry 292 (class 1259 OID 28618)
-- Name: routes; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE routes (
    id integer NOT NULL,
    geom geometry,
    user_id integer DEFAULT 0,
    description text,
    created_date timestamp without time zone
);


--
-- TOC entry 291 (class 1259 OID 28616)
-- Name: routes_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE routes_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 4338 (class 0 OID 0)
-- Dependencies: 291
-- Name: routes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE routes_id_seq OWNED BY routes.id;


--
-- TOC entry 293 (class 1259 OID 36850)
-- Name: user_friendships; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE user_friendships (
    user_id integer NOT NULL,
    friend_user_id integer NOT NULL,
    created_date timestamp without time zone
);


--
-- TOC entry 4339 (class 0 OID 0)
-- Dependencies: 293
-- Name: TABLE user_friendships; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON TABLE user_friendships IS 'Tabela de ligação para relacionamente de cardinalidade NxN - Amizades';


--
-- TOC entry 207 (class 1259 OID 20012)
-- Name: users; Type: TABLE; Schema: public; Owner: -
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
    question text,
    answer text,
    full_name character varying(100),
    pharse text
);


--
-- TOC entry 4340 (class 0 OID 0)
-- Dependencies: 207
-- Name: TABLE users; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON TABLE users IS 'Tabela de usuários da aplicação';


--
-- TOC entry 4341 (class 0 OID 0)
-- Dependencies: 207
-- Name: COLUMN users.email; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN users.email IS 'conta de email do usuário';


--
-- TOC entry 4342 (class 0 OID 0)
-- Dependencies: 207
-- Name: COLUMN users.password; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN users.password IS 'senha do usuário, usada na autenticação';


--
-- TOC entry 4343 (class 0 OID 0)
-- Dependencies: 207
-- Name: COLUMN users.auth_key; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN users.auth_key IS 'Chave de validação da identidade';


--
-- TOC entry 4344 (class 0 OID 0)
-- Dependencies: 207
-- Name: COLUMN users.access_token; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN users.access_token IS 'Token de acesso, aconselhado para ser utilizado com API RESTful';


--
-- TOC entry 4345 (class 0 OID 0)
-- Dependencies: 207
-- Name: COLUMN users.pharse; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN users.pharse IS 'Frase do usuário';


--
-- TOC entry 307 (class 1259 OID 45146)
-- Name: user_0_friends_id; Type: VIEW; Schema: public; Owner: -
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


--
-- TOC entry 296 (class 1259 OID 45042)
-- Name: user_1_friends_id; Type: VIEW; Schema: public; Owner: -
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


--
-- TOC entry 297 (class 1259 OID 45047)
-- Name: user_2_friends_id; Type: VIEW; Schema: public; Owner: -
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


--
-- TOC entry 308 (class 1259 OID 45151)
-- Name: user_3_friends_id; Type: VIEW; Schema: public; Owner: -
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


--
-- TOC entry 306 (class 1259 OID 45141)
-- Name: user_4_friends_id; Type: VIEW; Schema: public; Owner: -
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


--
-- TOC entry 324 (class 1259 OID 45553)
-- Name: user_5_friends_id; Type: VIEW; Schema: public; Owner: -
--

CREATE VIEW user_5_friends_id AS
 SELECT b.friend_user_id AS id
   FROM (users a
     JOIN user_friendships b ON ((a.id = b.user_id)))
  WHERE (a.id = 2)
UNION
 SELECT b.user_id AS id
   FROM (users a
     JOIN user_friendships b ON ((a.id = b.friend_user_id)))
  WHERE (a.id = 2);


--
-- TOC entry 310 (class 1259 OID 45172)
-- Name: user_alert_nonexistence; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE user_alert_nonexistence (
    user_id integer NOT NULL,
    alert_id integer NOT NULL,
    created_date timestamp without time zone
);


--
-- TOC entry 4347 (class 0 OID 0)
-- Dependencies: 310
-- Name: TABLE user_alert_nonexistence; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON TABLE user_alert_nonexistence IS 'Tabela para cadastro de alertas que não existem mais segundo o reporte dos usuários';


--
-- TOC entry 309 (class 1259 OID 45158)
-- Name: user_alert_rates; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE user_alert_rates (
    user_id integer NOT NULL,
    alert_id integer NOT NULL,
    created_date timestamp without time zone,
    rating ratings,
    updated_date timestamp without time zone
);


--
-- TOC entry 316 (class 1259 OID 45317)
-- Name: user_bike_keeper_nonexistence; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE user_bike_keeper_nonexistence (
    user_id integer NOT NULL,
    bike_keeper_id integer NOT NULL,
    created_date timestamp without time zone
);


--
-- TOC entry 4348 (class 0 OID 0)
-- Dependencies: 316
-- Name: TABLE user_bike_keeper_nonexistence; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON TABLE user_bike_keeper_nonexistence IS 'Tabela para cadastro de bicicletários que não existem mais segundo o reporte dos usuários';


--
-- TOC entry 315 (class 1259 OID 45302)
-- Name: user_bike_keeper_rates; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE user_bike_keeper_rates (
    user_id integer NOT NULL,
    bike_keeper_id integer NOT NULL,
    created_date timestamp without time zone,
    rating ratings,
    updated_date timestamp without time zone
);


--
-- TOC entry 323 (class 1259 OID 45528)
-- Name: user_conversation_alerts; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE user_conversation_alerts (
    id integer NOT NULL,
    user_id integer,
    user_id2 integer,
    created_date timestamp without time zone
);


--
-- TOC entry 4349 (class 0 OID 0)
-- Dependencies: 323
-- Name: TABLE user_conversation_alerts; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON TABLE user_conversation_alerts IS 'Tabela de avisos de novas mensagens';


--
-- TOC entry 322 (class 1259 OID 45526)
-- Name: user_conversation_alerts_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE user_conversation_alerts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 4350 (class 0 OID 0)
-- Dependencies: 322
-- Name: user_conversation_alerts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE user_conversation_alerts_id_seq OWNED BY user_conversation_alerts.id;


--
-- TOC entry 319 (class 1259 OID 45488)
-- Name: user_conversations; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE user_conversations (
    user_id integer,
    user_id2 integer,
    created_date timestamp without time zone,
    text text,
    id integer NOT NULL,
    user_saw smallint DEFAULT 0,
    user2_saw smallint DEFAULT 0
);


--
-- TOC entry 4351 (class 0 OID 0)
-- Dependencies: 319
-- Name: TABLE user_conversations; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON TABLE user_conversations IS 'Tabela de mensagens usuário-usuário';


--
-- TOC entry 320 (class 1259 OID 45494)
-- Name: user_conversation_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE user_conversation_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 4352 (class 0 OID 0)
-- Dependencies: 320
-- Name: user_conversation_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE user_conversation_id_seq OWNED BY user_conversations.id;


--
-- TOC entry 301 (class 1259 OID 45063)
-- Name: user_feedings; Type: TABLE; Schema: public; Owner: -
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


--
-- TOC entry 4353 (class 0 OID 0)
-- Dependencies: 301
-- Name: TABLE user_feedings; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON TABLE user_feedings IS 'Notícias/Conteúdo temporal do usuário';


--
-- TOC entry 4354 (class 0 OID 0)
-- Dependencies: 301
-- Name: COLUMN user_feedings.user_sharing_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN user_feedings.user_sharing_id IS 'id do conteúdo compartilhado relacionando a este feed (não mandatório quando feed é textual)';


--
-- TOC entry 300 (class 1259 OID 45061)
-- Name: user_feedings_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE user_feedings_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 4355 (class 0 OID 0)
-- Dependencies: 300
-- Name: user_feedings_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE user_feedings_id_seq OWNED BY user_feedings.id;


--
-- TOC entry 295 (class 1259 OID 36857)
-- Name: user_friendship_requests; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE user_friendship_requests (
    id integer NOT NULL,
    user_id integer DEFAULT 0,
    requested_user_id integer DEFAULT 0,
    created_date timestamp without time zone,
    enable smallint DEFAULT 1
);


--
-- TOC entry 4356 (class 0 OID 0)
-- Dependencies: 295
-- Name: COLUMN user_friendship_requests.user_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN user_friendship_requests.user_id IS 'id do usuário que solicita';


--
-- TOC entry 4357 (class 0 OID 0)
-- Dependencies: 295
-- Name: COLUMN user_friendship_requests.requested_user_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN user_friendship_requests.requested_user_id IS 'id do usuário de quem se solicita a amizada';


--
-- TOC entry 294 (class 1259 OID 36855)
-- Name: user_friendship_requests_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE user_friendship_requests_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 4358 (class 0 OID 0)
-- Dependencies: 294
-- Name: user_friendship_requests_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE user_friendship_requests_id_seq OWNED BY user_friendship_requests.id;


--
-- TOC entry 208 (class 1259 OID 20016)
-- Name: user_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE user_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 4359 (class 0 OID 0)
-- Dependencies: 208
-- Name: user_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE user_id_seq OWNED BY users.id;


--
-- TOC entry 318 (class 1259 OID 45351)
-- Name: user_navigation_routes; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE user_navigation_routes (
    id integer NOT NULL,
    origin_geom geometry,
    destination_geom geometry,
    line_string_geom geometry,
    user_id integer DEFAULT 0,
    duration integer,
    distance real,
    origin_address text,
    destination_address text
);


--
-- TOC entry 4360 (class 0 OID 0)
-- Dependencies: 318
-- Name: TABLE user_navigation_routes; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON TABLE user_navigation_routes IS 'Tabela de rotas que o usuário faz no modo navegação tipo origem-destino';


--
-- TOC entry 4361 (class 0 OID 0)
-- Dependencies: 318
-- Name: COLUMN user_navigation_routes.origin_geom; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN user_navigation_routes.origin_geom IS 'Para guardar o ponto de origem';


--
-- TOC entry 4362 (class 0 OID 0)
-- Dependencies: 318
-- Name: COLUMN user_navigation_routes.destination_geom; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN user_navigation_routes.destination_geom IS 'Para guardar o ponto de destino';


--
-- TOC entry 4363 (class 0 OID 0)
-- Dependencies: 318
-- Name: COLUMN user_navigation_routes.line_string_geom; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN user_navigation_routes.line_string_geom IS 'Para guardar a geometria da rota.';


--
-- TOC entry 4364 (class 0 OID 0)
-- Dependencies: 318
-- Name: COLUMN user_navigation_routes.user_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN user_navigation_routes.user_id IS 'id do usuário que fez a rota';


--
-- TOC entry 4365 (class 0 OID 0)
-- Dependencies: 318
-- Name: COLUMN user_navigation_routes.duration; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN user_navigation_routes.duration IS 'Duração em segundos';


--
-- TOC entry 4366 (class 0 OID 0)
-- Dependencies: 318
-- Name: COLUMN user_navigation_routes.distance; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN user_navigation_routes.distance IS 'Distância em metros';


--
-- TOC entry 4367 (class 0 OID 0)
-- Dependencies: 318
-- Name: COLUMN user_navigation_routes.origin_address; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN user_navigation_routes.origin_address IS 'Endereço de origem textual';


--
-- TOC entry 4368 (class 0 OID 0)
-- Dependencies: 318
-- Name: COLUMN user_navigation_routes.destination_address; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN user_navigation_routes.destination_address IS 'Endereço de destino textual';


--
-- TOC entry 317 (class 1259 OID 45349)
-- Name: user_navigation_routes_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE user_navigation_routes_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 4369 (class 0 OID 0)
-- Dependencies: 317
-- Name: user_navigation_routes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE user_navigation_routes_id_seq OWNED BY user_navigation_routes.id;


--
-- TOC entry 305 (class 1259 OID 45093)
-- Name: user_sharing_types; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE user_sharing_types (
    id integer NOT NULL,
    name character varying(100)
);


--
-- TOC entry 4370 (class 0 OID 0)
-- Dependencies: 305
-- Name: TABLE user_sharing_types; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON TABLE user_sharing_types IS 'Tipos de conteúdos compartilháveis pelo usuário (alertas, rotas,bicicletários,fotos...)';


--
-- TOC entry 304 (class 1259 OID 45091)
-- Name: user_sharing_types_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE user_sharing_types_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 4371 (class 0 OID 0)
-- Dependencies: 304
-- Name: user_sharing_types_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE user_sharing_types_id_seq OWNED BY user_sharing_types.id;


--
-- TOC entry 299 (class 1259 OID 45054)
-- Name: user_sharings; Type: TABLE; Schema: public; Owner: -
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


--
-- TOC entry 4372 (class 0 OID 0)
-- Dependencies: 299
-- Name: TABLE user_sharings; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON TABLE user_sharings IS 'Compartilhamentos do usuário';


--
-- TOC entry 4373 (class 0 OID 0)
-- Dependencies: 299
-- Name: COLUMN user_sharings.user_feeding_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN user_sharings.user_feeding_id IS 'id do feed de usuário relacionado com esse compartilhamento (não mandatório)';


--
-- TOC entry 4374 (class 0 OID 0)
-- Dependencies: 299
-- Name: COLUMN user_sharings.view_level_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN user_sharings.view_level_id IS 'id do nivel de visão para implementar segurança';


--
-- TOC entry 4375 (class 0 OID 0)
-- Dependencies: 299
-- Name: COLUMN user_sharings.content_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN user_sharings.content_id IS 'id do conteúdo(alerta, bicicletário, rota...) sendo compartilhado';


--
-- TOC entry 4376 (class 0 OID 0)
-- Dependencies: 299
-- Name: COLUMN user_sharings.sharing_type_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN user_sharings.sharing_type_id IS 'Tipo de conteúdo que o usuário pode compartilhar (alerta, bicicletário, foto...)';


--
-- TOC entry 298 (class 1259 OID 45052)
-- Name: user_sharings_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE user_sharings_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 4377 (class 0 OID 0)
-- Dependencies: 298
-- Name: user_sharings_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE user_sharings_id_seq OWNED BY user_sharings.id;


--
-- TOC entry 279 (class 1259 OID 28394)
-- Name: user_trackings; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE user_trackings (
    id integer NOT NULL,
    user_id integer,
    register_date timestamp without time zone,
    geom geometry
);


--
-- TOC entry 278 (class 1259 OID 28392)
-- Name: user_tracking_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE user_tracking_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 4378 (class 0 OID 0)
-- Dependencies: 278
-- Name: user_tracking_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE user_tracking_id_seq OWNED BY user_trackings.id;


--
-- TOC entry 303 (class 1259 OID 45074)
-- Name: view_levels; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE view_levels (
    id integer NOT NULL,
    name character varying(50)
);


--
-- TOC entry 4379 (class 0 OID 0)
-- Dependencies: 303
-- Name: TABLE view_levels; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON TABLE view_levels IS 'Níveis de segurança para acesso controlado ao conteúdo de usuário';


--
-- TOC entry 302 (class 1259 OID 45072)
-- Name: view_levels_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE view_levels_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 4380 (class 0 OID 0)
-- Dependencies: 302
-- Name: view_levels_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE view_levels_id_seq OWNED BY view_levels.id;


--
-- TOC entry 4002 (class 2604 OID 45258)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY alert_comments ALTER COLUMN id SET DEFAULT nextval('alert_comments_id_seq'::regclass);


--
-- TOC entry 3968 (class 2604 OID 22549)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY alert_types ALTER COLUMN id SET DEFAULT nextval('alerts_types_id_seq'::regclass);


--
-- TOC entry 3966 (class 2604 OID 22522)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY alerts ALTER COLUMN id SET DEFAULT nextval('alerts_id_seq'::regclass);


--
-- TOC entry 3971 (class 2604 OID 28438)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY bike_keepers ALTER COLUMN id SET DEFAULT nextval('bike_keepers_id_seq'::regclass);


--
-- TOC entry 3986 (class 2604 OID 28601)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY events ALTER COLUMN id SET DEFAULT nextval('events_id_seq'::regclass);


--
-- TOC entry 3983 (class 2604 OID 28482)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY galleries ALTER COLUMN id SET DEFAULT nextval('galleries_id_seq'::regclass);


--
-- TOC entry 3981 (class 2604 OID 28462)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY multimedia_types ALTER COLUMN id SET DEFAULT nextval('multimedia_types_id_seq'::regclass);


--
-- TOC entry 3978 (class 2604 OID 28454)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY multimedias ALTER COLUMN id SET DEFAULT nextval('multimedias_id_seq'::regclass);


--
-- TOC entry 3990 (class 2604 OID 28621)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY routes ALTER COLUMN id SET DEFAULT nextval('routes_id_seq'::regclass);


--
-- TOC entry 3969 (class 2604 OID 28339)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY spatial_types ALTER COLUMN id SET DEFAULT nextval('alerts_types_geometries_id_seq'::regclass);


--
-- TOC entry 4013 (class 2604 OID 45531)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY user_conversation_alerts ALTER COLUMN id SET DEFAULT nextval('user_conversation_alerts_id_seq'::regclass);


--
-- TOC entry 4010 (class 2604 OID 45496)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY user_conversations ALTER COLUMN id SET DEFAULT nextval('user_conversation_id_seq'::regclass);


--
-- TOC entry 3998 (class 2604 OID 45066)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY user_feedings ALTER COLUMN id SET DEFAULT nextval('user_feedings_id_seq'::regclass);


--
-- TOC entry 3992 (class 2604 OID 36860)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY user_friendship_requests ALTER COLUMN id SET DEFAULT nextval('user_friendship_requests_id_seq'::regclass);


--
-- TOC entry 4008 (class 2604 OID 45354)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY user_navigation_routes ALTER COLUMN id SET DEFAULT nextval('user_navigation_routes_id_seq'::regclass);


--
-- TOC entry 4001 (class 2604 OID 45096)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY user_sharing_types ALTER COLUMN id SET DEFAULT nextval('user_sharing_types_id_seq'::regclass);


--
-- TOC entry 3996 (class 2604 OID 45057)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY user_sharings ALTER COLUMN id SET DEFAULT nextval('user_sharings_id_seq'::regclass);


--
-- TOC entry 3970 (class 2604 OID 28397)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY user_trackings ALTER COLUMN id SET DEFAULT nextval('user_tracking_id_seq'::regclass);


--
-- TOC entry 3965 (class 2604 OID 20018)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY users ALTER COLUMN id SET DEFAULT nextval('user_id_seq'::regclass);


--
-- TOC entry 4000 (class 2604 OID 45077)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY view_levels ALTER COLUMN id SET DEFAULT nextval('view_levels_id_seq'::regclass);


--
-- TOC entry 4293 (class 0 OID 45255)
-- Dependencies: 312
-- Data for Name: alert_comments; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO alert_comments (id, alert_id, user_id, text, created_date) VALUES (1, 24, 1, 'teste de comentario!!', '2017-05-02 01:08:04');
INSERT INTO alert_comments (id, alert_id, user_id, text, created_date) VALUES (2, 24, 1, 'teste de comentario!!', '2017-05-02 01:09:17');
INSERT INTO alert_comments (id, alert_id, user_id, text, created_date) VALUES (13, 33, 1, 'Boa informação.', '2017-05-02 14:40:10');
INSERT INTO alert_comments (id, alert_id, user_id, text, created_date) VALUES (14, 30, 1, 'Que perigo, obrigado vou evitar passar por ai.', '2017-05-02 14:47:54');
INSERT INTO alert_comments (id, alert_id, user_id, text, created_date) VALUES (15, 30, 1, ':)', '2017-05-02 14:49:31');
INSERT INTO alert_comments (id, alert_id, user_id, text, created_date) VALUES (16, 30, 1, ':(', '2017-05-02 14:54:52');
INSERT INTO alert_comments (id, alert_id, user_id, text, created_date) VALUES (17, 30, 2, 'Vlw marcus!', '2017-05-02 14:59:34');
INSERT INTO alert_comments (id, alert_id, user_id, text, created_date) VALUES (18, 22, 2, 'Essa obra náo acaba nunca', '2017-05-02 17:16:12');
INSERT INTO alert_comments (id, alert_id, user_id, text, created_date) VALUES (19, 24, 2, 'Essa ciclovia está cada vez mais perigosa hein. Poxa vida!', '2017-05-02 17:28:24');
INSERT INTO alert_comments (id, alert_id, user_id, text, created_date) VALUES (20, 35, 1, 'ooops', '2017-05-02 18:59:50');
INSERT INTO alert_comments (id, alert_id, user_id, text, created_date) VALUES (21, 35, 2, 'obrigado!', '2017-05-02 19:00:40');
INSERT INTO alert_comments (id, alert_id, user_id, text, created_date) VALUES (26, 37, 1, 'Cuidado pista com muito buraco', '2017-05-12 11:31:39');
INSERT INTO alert_comments (id, alert_id, user_id, text, created_date) VALUES (27, 23, 1, 'Teste', '2017-05-12 15:47:47');
INSERT INTO alert_comments (id, alert_id, user_id, text, created_date) VALUES (28, 23, 1, 'opss', '2017-05-12 15:48:00');
INSERT INTO alert_comments (id, alert_id, user_id, text, created_date) VALUES (29, 23, 1, 'bobs', '2017-05-12 15:48:34');
INSERT INTO alert_comments (id, alert_id, user_id, text, created_date) VALUES (30, 24, 1, 'Bom', '2017-05-12 16:59:43');
INSERT INTO alert_comments (id, alert_id, user_id, text, created_date) VALUES (31, 24, 1, 'Teste', '2017-05-14 01:29:13');
INSERT INTO alert_comments (id, alert_id, user_id, text, created_date) VALUES (32, 73, 1, 'teste', '2017-05-25 00:49:14');
INSERT INTO alert_comments (id, alert_id, user_id, text, created_date) VALUES (34, 96, 1, 'Teste', '2017-05-29 17:40:48');
INSERT INTO alert_comments (id, alert_id, user_id, text, created_date) VALUES (35, 96, 1, 'Teste', '2017-05-29 17:40:54');
INSERT INTO alert_comments (id, alert_id, user_id, text, created_date) VALUES (33, 96, 1, 'Eita!!', '2017-05-28 17:40:41');
INSERT INTO alert_comments (id, alert_id, user_id, text, created_date) VALUES (36, 96, 1, 'teste 2', '2017-05-28 17:42:21');
INSERT INTO alert_comments (id, alert_id, user_id, text, created_date) VALUES (37, 96, 2, 'Olá', '2017-05-30 07:20:30');
INSERT INTO alert_comments (id, alert_id, user_id, text, created_date) VALUES (38, 96, 2, 'Olá', '2017-05-30 07:20:30');
INSERT INTO alert_comments (id, alert_id, user_id, text, created_date) VALUES (39, 96, 2, 'Teste de comment', '2017-05-30 07:22:14');
INSERT INTO alert_comments (id, alert_id, user_id, text, created_date) VALUES (40, 96, 1, 'opa', '2017-05-30 07:57:26');
INSERT INTO alert_comments (id, alert_id, user_id, text, created_date) VALUES (41, 96, 1, 'Muito obrigado', '2017-05-30 07:59:46');
INSERT INTO alert_comments (id, alert_id, user_id, text, created_date) VALUES (43, 104, 2, 'Esse fica perto da UVA né?', '2017-06-10 19:53:01');
INSERT INTO alert_comments (id, alert_id, user_id, text, created_date) VALUES (42, 104, 1, 'Mais um buraco!!', '2017-06-09 10:35:45');
INSERT INTO alert_comments (id, alert_id, user_id, text, created_date) VALUES (44, 107, 2, 'Horrível passar ai rsrs.', '2017-06-10 21:38:13');
INSERT INTO alert_comments (id, alert_id, user_id, text, created_date) VALUES (45, 107, 2, 'Ainda mais de bike', '2017-06-10 21:38:28');


--
-- TOC entry 4381 (class 0 OID 0)
-- Dependencies: 311
-- Name: alert_comments_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('alert_comments_id_seq', 45, true);


--
-- TOC entry 4260 (class 0 OID 22546)
-- Dependencies: 274
-- Data for Name: alert_types; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO alert_types (id, description, parent_type_id) VALUES (3, 'Roubos e Furtos', NULL);
INSERT INTO alert_types (id, description, parent_type_id) VALUES (2, 'Perigo na Via', NULL);
INSERT INTO alert_types (id, description, parent_type_id) VALUES (4, 'Interdições', NULL);
INSERT INTO alert_types (id, description, parent_type_id) VALUES (1, 'Alerta Genérico', NULL);


--
-- TOC entry 4263 (class 0 OID 28356)
-- Dependencies: 277
-- Data for Name: alert_types_spatial_types; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO alert_types_spatial_types (alert_types_id, spatial_types_id) VALUES (4, 1);
INSERT INTO alert_types_spatial_types (alert_types_id, spatial_types_id) VALUES (3, 1);
INSERT INTO alert_types_spatial_types (alert_types_id, spatial_types_id) VALUES (2, 1);


--
-- TOC entry 4258 (class 0 OID 22519)
-- Dependencies: 272
-- Data for Name: alerts; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO alerts (id, title, description, type_id, user_id, created_date, likes, dislikes, updated_date, geom, enable, duration_date, address) VALUES (36, 'Muitos Pivetes 2220', '00 Muitos pivetes estão andando pela estrada!!!!!!', 3, 1, '2017-05-03 20:01:54', NULL, NULL, '14:36:52', '0101000020110F0000FBFFFF7FC7CF45C095909184F4FC36C0', 0, NULL, NULL);
INSERT INTO alerts (id, title, description, type_id, user_id, created_date, likes, dislikes, updated_date, geom, enable, duration_date, address) VALUES (38, 'Acidentess', 'Acidente em frente ao santa Mônica dois ciclistas', 2, 1, '2017-05-06 16:28:01', NULL, NULL, '18:17:25', '0101000020110F0000030000C62ED745C04C2589FFC1EC36C0', 0, NULL, NULL);
INSERT INTO alerts (id, title, description, type_id, user_id, created_date, likes, dislikes, updated_date, geom, enable, duration_date, address) VALUES (73, 'Teste', 'Teste', 3, 1, '2017-05-23 23:30:38', NULL, NULL, NULL, '0101000020110F0000FCFFFF5589CE45C0D67E1B8A7FE936C0', 0, NULL, '');
INSERT INTO alerts (id, title, description, type_id, user_id, created_date, likes, dislikes, updated_date, geom, enable, duration_date, address) VALUES (76, 'teste', 'teste', 4, 1, '2017-05-23 23:32:16', NULL, NULL, NULL, '0101000020110F00000200001CB3CF45C047D60189FCE936C0', 0, NULL, '');
INSERT INTO alerts (id, title, description, type_id, user_id, created_date, likes, dislikes, updated_date, geom, enable, duration_date, address) VALUES (24, 'Teste de roubo', 'Ooopps
', 3, 2, '2017-04-19 23:31:39', NULL, NULL, NULL, '0101000020110F0000FFFFFF3FEFC945C0FFD28F68B1E936C0', 0, NULL, NULL);
INSERT INTO alerts (id, title, description, type_id, user_id, created_date, likes, dislikes, updated_date, geom, enable, duration_date, address) VALUES (30, 'Uruguaiana está perigosa!', 'Ladrões estão roubando bicicletas nos bicicletários do metrô.', 3, 3, '2017-04-30 07:41:01', 1, NULL, NULL, '0101000020110F000002000080199C45C0F401B1B811EB36C0', 0, NULL, NULL);
INSERT INTO alerts (id, title, description, type_id, user_id, created_date, likes, dislikes, updated_date, geom, enable, duration_date, address) VALUES (88, 'saddadas', 'adsddsadsa', 3, 1, '2017-05-26 02:20:39', NULL, NULL, NULL, '0101000020110F00000800008913C645C05957C20C8EE536C0', 0, NULL, '');
INSERT INTO alerts (id, title, description, type_id, user_id, created_date, likes, dislikes, updated_date, geom, enable, duration_date, address) VALUES (7, 'Saneamento da orla', 'Rua interditada', 4, 1, '2016-10-28 23:47:09', NULL, NULL, NULL, '0101000020110F0000010000C09CD845C0F9F9FF6294FB36C0', 0, '2017-04-20 15:58:53', NULL);
INSERT INTO alerts (id, title, description, type_id, user_id, created_date, likes, dislikes, updated_date, geom, enable, duration_date, address) VALUES (81, 'teste', 'teste', 2, 1, '2017-05-25 01:51:31', 9, 14, NULL, '0101000020110F0000030000A7CDCF45C08FD517510FEA36C0', 0, NULL, '');
INSERT INTO alerts (id, title, description, type_id, user_id, created_date, likes, dislikes, updated_date, geom, enable, duration_date, address) VALUES (74, 'Teste 7', 'Teste', 2, 1, '2017-05-23 23:31:13', NULL, NULL, NULL, '0101000020110F0000FBFFFFA8CCCE45C0B079A58097E936C0', 0, NULL, '');
INSERT INTO alerts (id, title, description, type_id, user_id, created_date, likes, dislikes, updated_date, geom, enable, duration_date, address) VALUES (75, 'Teste Teste 7', 'Teste', 1, 1, '2017-05-23 23:31:38', NULL, NULL, NULL, '0101000020110F00000000003261CF45C0B69D21DEE5E936C0', 0, NULL, '');
INSERT INTO alerts (id, title, description, type_id, user_id, created_date, likes, dislikes, updated_date, geom, enable, duration_date, address) VALUES (77, 'Teste', 'Teste', 3, 1, '2017-05-25 00:41:41', NULL, NULL, NULL, '0101000020110F0000010000088BCF45C0741ABD07F7E936C0', 0, NULL, '');
INSERT INTO alerts (id, title, description, type_id, user_id, created_date, likes, dislikes, updated_date, geom, enable, duration_date, address) VALUES (95, 'Operação SMTRJ', 'Rua interditada', 4, 1, '2017-05-25 14:28:21', 3, 5, NULL, '0101000020110F0000010000E0E1BE45C0CCD5EF13CB0537C0', 0, '2017-05-25 18:55:50', 'Avenida Teotônio Vilela, Recreio dos Bandeirantes, Rio de Janeiro/Rio de Janeiro - Brasil');
INSERT INTO alerts (id, title, description, type_id, user_id, created_date, likes, dislikes, updated_date, geom, enable, duration_date, address) VALUES (25, '', 'Perigo animal morto', 2, 1, '2017-04-19 23:32:19', NULL, NULL, NULL, '0101000020110F000004000000C3BD45C0CAC271511CE136C0', 0, '2017-04-20 15:58:53', NULL);
INSERT INTO alerts (id, title, description, type_id, user_id, created_date, likes, dislikes, updated_date, geom, enable, duration_date, address) VALUES (6, 'Incêndio na morro de Paciências', 'Bombeiros no local, rua interditada000s', 4, 1, '2016-10-28 23:44:41', NULL, NULL, '15:19:07', '0101000020110F00000400000073D145C0BA2741A543E736C0', 0, NULL, NULL);
INSERT INTO alerts (id, title, description, type_id, user_id, created_date, likes, dislikes, updated_date, geom, enable, duration_date, address) VALUES (35, 'Teste', 'Teste mais e mais 2017', 1, 1, '2017-04-30 07:59:13', NULL, NULL, '14:36:25', '0101000020110F0000FCFFFF3FCCB045C02BCB5B315FF736C0', 0, NULL, NULL);
INSERT INTO alerts (id, title, description, type_id, user_id, created_date, likes, dislikes, updated_date, geom, enable, duration_date, address) VALUES (90, 'Arrastão', 'Bandidos armados na estrada!', 3, 1, '2017-05-26 13:29:52', NULL, NULL, NULL, '0101000020110F0000FBFFFF9092D545C0650E0FF48AEA36C0', 0, NULL, '');
INSERT INTO alerts (id, title, description, type_id, user_id, created_date, likes, dislikes, updated_date, geom, enable, duration_date, address) VALUES (59, 'teste', 'Teste', 4, 1, '2017-05-21 22:11:01', NULL, NULL, NULL, '0101000020110F0000070000B8C3C845C0B6C22369BF0A37C0', 0, NULL, '');
INSERT INTO alerts (id, title, description, type_id, user_id, created_date, likes, dislikes, updated_date, geom, enable, duration_date, address) VALUES (72, 'Perigo, obras', 'Prefeitura resolveu faze operação tapa buraco.', 4, 1, '2017-05-21 23:25:20', NULL, NULL, '23:43:11', '0101000020110F000000000000DCC245C0A041F719AAEA36C0', 0, NULL, '');
INSERT INTO alerts (id, title, description, type_id, user_id, created_date, likes, dislikes, updated_date, geom, enable, duration_date, address) VALUES (37, 'Acidente', 'Acidente de dois ciclistas em frente ao santa mônica7', 1, 1, '2017-05-06 16:27:06', NULL, 1, '20:45:36', '0101000020110F0000FDFFFF6B2ED745C096208FE9C4EC36C0', 0, NULL, '');
INSERT INTO alerts (id, title, description, type_id, user_id, created_date, likes, dislikes, updated_date, geom, enable, duration_date, address) VALUES (78, 'Teste', 'teste', 2, 1, '2017-05-25 00:42:11', NULL, NULL, NULL, '0101000020110F00000800002280CF45C0874EC68DF0E936C0', 0, NULL, '');
INSERT INTO alerts (id, title, description, type_id, user_id, created_date, likes, dislikes, updated_date, geom, enable, duration_date, address) VALUES (91, 'Buraco!', 'Buraco no acostamento cuidado!', 2, 1, '2017-05-25 13:44:22', NULL, NULL, NULL, '0101000020110F0000FFFFFFFC32C945C02502212637ED36C0', 0, NULL, '');
INSERT INTO alerts (id, title, description, type_id, user_id, created_date, likes, dislikes, updated_date, geom, enable, duration_date, address) VALUES (92, 'teste', 'teste', 1, 1, '2017-05-25 13:47:33', NULL, NULL, NULL, '0101000020110F0000FFFFFF89AECF45C0EEDFE9481EF536C0', 0, NULL, '');
INSERT INTO alerts (id, title, description, type_id, user_id, created_date, likes, dislikes, updated_date, geom, enable, duration_date, address) VALUES (93, 'Teste', 'teste', 2, 1, '2017-05-25 13:49:47', NULL, NULL, NULL, '0101000020110F0000FEFFFFC9B9CF45C050315439EAF536C0', 0, NULL, '');
INSERT INTO alerts (id, title, description, type_id, user_id, created_date, likes, dislikes, updated_date, geom, enable, duration_date, address) VALUES (31, 'Via perigosa15', 'Via com muito alagamento! 9', 2, 1, '2017-04-30 07:51:15', NULL, 1, '18:17:49', '0101000020110F0000FCFFFFFF10CA45C06075383DC8F436C0', 0, NULL, NULL);
INSERT INTO alerts (id, title, description, type_id, user_id, created_date, likes, dislikes, updated_date, geom, enable, duration_date, address) VALUES (41, 'Obras', 'Teste Teste Teste Teste Teste Teste Teste Teste Teste Teste Teste Teste Teste Teste Teste Teste ', 4, 4, '2017-05-26 19:53:44', NULL, NULL, NULL, '0101000020110F00000100000056B245C0132B224C5DEC36C0', 0, NULL, NULL);
INSERT INTO alerts (id, title, description, type_id, user_id, created_date, likes, dislikes, updated_date, geom, enable, duration_date, address) VALUES (87, 'addsad', 'asdasads', 2, 1, '2017-05-25 02:20:02', NULL, NULL, NULL, '0101000020110F0000FCFFFF2093CC45C09E7DC6C0CBE836C0', 0, '2017-05-29 02:20:39', '');
INSERT INTO alerts (id, title, description, type_id, user_id, created_date, likes, dislikes, updated_date, geom, enable, duration_date, address) VALUES (85, 'sdfsf', 'sdfdsf', 1, 1, '2017-05-25 02:18:39', NULL, NULL, NULL, '0101000020110F0000010000F255CF45C05600E6D816F536C0', 0, '2017-05-29 02:20:39', '');
INSERT INTO alerts (id, title, description, type_id, user_id, created_date, likes, dislikes, updated_date, geom, enable, duration_date, address) VALUES (22, 'Obras no asfalto', 'Obras prefeitura do Rio de Janeiro.', 4, 1, '2017-04-20 23:21:52', 1, 1, '10:47:45', '0101000020110F0000FBFFFF7F53DA45C0DE76AA1403ED36C0', 0, '2017-05-08 20:40:14', NULL);
INSERT INTO alerts (id, title, description, type_id, user_id, created_date, likes, dislikes, updated_date, geom, enable, duration_date, address) VALUES (8, 'Acidênte no posto de combustível', 'Carro bateu em uma das bombas de combustível do posto da Av. João XXIII', 4, 2, '2016-10-28 23:50:19', NULL, NULL, '09:29:55', '0101000020110F0000FCFFFFFF20D845C08CB9C8C849E936C0', 0, '2017-05-22 10:35:46', '');
INSERT INTO alerts (id, title, description, type_id, user_id, created_date, likes, dislikes, updated_date, geom, enable, duration_date, address) VALUES (21, 'Obras BRT', 'teste', 4, 1, '2017-04-15 00:33:14', NULL, NULL, NULL, '0101000020110F0000FCFFFF6F0CB945C060449B3700DD36C0', 0, '2017-04-15 02:25:00', NULL);
INSERT INTO alerts (id, title, description, type_id, user_id, created_date, likes, dislikes, updated_date, geom, enable, duration_date, address) VALUES (33, 'Teste', 'Teste test testet teste', 1, 2, '2017-05-26 19:53:44', NULL, NULL, NULL, '0101000020110F000001000080C8BF45C01518CE7738FF36C0', 0, NULL, NULL);
INSERT INTO alerts (id, title, description, type_id, user_id, created_date, likes, dislikes, updated_date, geom, enable, duration_date, address) VALUES (26, 'Teste de outro 2', 'Teste mais e mais e de novo 4', 1, 1, '2017-04-19 23:34:08', NULL, NULL, '10:45:49', '0101000020110F000002000080C9AF45C064F765AEF5EB36C0', 0, '2017-05-08 21:10:33', NULL);
INSERT INTO alerts (id, title, description, type_id, user_id, created_date, likes, dislikes, updated_date, geom, enable, duration_date, address) VALUES (34, 'dsdsdsd 2017', 'came on baby chance!ss', 4, 1, '2017-04-30 07:54:06', 1, NULL, '00:12:39', '0101000020110F0000070000007ABB45C0248C6E9A76FF36C0', 0, '2017-05-22 01:10:31', '');
INSERT INTO alerts (id, title, description, type_id, user_id, created_date, likes, dislikes, updated_date, geom, enable, duration_date, address) VALUES (23, 'Teste teste Enable', 'Teste de novo ?', 4, 2, '2017-05-26 19:53:44', NULL, NULL, NULL, '0101000020110F0000FEFFFF7F9ACF45C0D247EE7A42EA36C0', 0, NULL, NULL);
INSERT INTO alerts (id, title, description, type_id, user_id, created_date, likes, dislikes, updated_date, geom, enable, duration_date, address) VALUES (27, 'Teste Enable', 'Teste1', 1, 1, '2017-04-19 23:51:24', NULL, NULL, NULL, '0101000020110F0000FE7F0BB785C745C09B8180BFBC0137C0', 0, '2017-04-20 15:58:53', NULL);
INSERT INTO alerts (id, title, description, type_id, user_id, created_date, likes, dislikes, updated_date, geom, enable, duration_date, address) VALUES (32, 'Asfalto quebrada', 'Muitos buracos divido asfalto quebrado ', 2, 2, '2017-05-26 19:58:44', NULL, NULL, '09:14:16', '0101000020110F0000040000C057C345C01503BAC5360237C0', 0, NULL, '');
INSERT INTO alerts (id, title, description, type_id, user_id, created_date, likes, dislikes, updated_date, geom, enable, duration_date, address) VALUES (89, 'addadsdas', 'adsdsadsa', 4, 1, '2017-05-25 02:21:06', NULL, NULL, NULL, '0101000020110F00000400008918D045C0F7EABD3527EA36C0', 0, NULL, '');
INSERT INTO alerts (id, title, description, type_id, user_id, created_date, likes, dislikes, updated_date, geom, enable, duration_date, address) VALUES (86, 'sdsdf', 'sfsd', 3, 1, '2017-05-24 02:19:18', NULL, NULL, '04:01:52', '0101000020110F00000200006DE1C845C011BB2A2B74E736C0', 0, NULL, '');
INSERT INTO alerts (id, title, description, type_id, user_id, created_date, likes, dislikes, updated_date, geom, enable, duration_date, address) VALUES (83, 'teste', 'teste', 2, 1, '2017-05-25 02:16:54', NULL, NULL, NULL, '0101000020110F0000FFFFFF1BE0CF45C00BEF937514EA36C0', 0, NULL, '');
INSERT INTO alerts (id, title, description, type_id, user_id, created_date, likes, dislikes, updated_date, geom, enable, duration_date, address) VALUES (94, 'Alfalto quebrado', 'Ciclovia muito precária!!', 2, 1, '2017-05-26 19:24:38', NULL, NULL, NULL, '0101000020110F00000000004D5DC745C0B8F52894C6E636C0', 0, NULL, 'Rua Campo Grande, Campo Grande, Rio de Janeiro/Rio de Janeiro - Brasil');
INSERT INTO alerts (id, title, description, type_id, user_id, created_date, likes, dislikes, updated_date, geom, enable, duration_date, address) VALUES (82, 'teste', 'teste
', 2, 1, '2017-05-25 01:52:13', NULL, NULL, NULL, '0101000020110F0000080000C0D2CF45C0C7FEA9D913EA36C0', 0, NULL, '');
INSERT INTO alerts (id, title, description, type_id, user_id, created_date, likes, dislikes, updated_date, geom, enable, duration_date, address) VALUES (84, 'sadsad', 'asdsad', 1, 1, '2017-05-25 02:17:23', NULL, NULL, NULL, '0101000020110F0000050000193DD045C01E30531438EA36C0', 0, '2017-05-29 02:20:39', '');
INSERT INTO alerts (id, title, description, type_id, user_id, created_date, likes, dislikes, updated_date, geom, enable, duration_date, address) VALUES (79, 'Teste', 'teste', 3, 1, '2017-05-20 00:42:45', NULL, NULL, NULL, '0101000020110F0000FDFFFF47C3CF45C01D4A516E0BEA36C0', 0, NULL, '');
INSERT INTO alerts (id, title, description, type_id, user_id, created_date, likes, dislikes, updated_date, geom, enable, duration_date, address) VALUES (96, 'Buraco na rua', 'Teste', 4, 1, '2017-05-29 17:40:23', NULL, NULL, NULL, '0101000020110F00000000008057C745C018A9CA5D06E936C0', 0, NULL, 'Rua Cumaí, s/n, Campo Grande, Rio de Janeiro/Rio de Janeiro - Brasil');
INSERT INTO alerts (id, title, description, type_id, user_id, created_date, likes, dislikes, updated_date, geom, enable, duration_date, address) VALUES (97, 'Cuidado dobrado!', 'Ladrões estão roubando na saída da ciclovia para a estrada', 3, 1, '2017-06-01 05:06:54', NULL, NULL, NULL, '0101000020110F000000000000A0D145C074DA7F8F12ED36C0', 0, NULL, 'Rua Pedra do Sino, s/n, Paciência, Rio de Janeiro/Rio de Janeiro - Brasil');
INSERT INTO alerts (id, title, description, type_id, user_id, created_date, likes, dislikes, updated_date, geom, enable, duration_date, address) VALUES (99, 'Buraco', 'Ciclovia com buraco grande, cuidado!', 2, 1, '2017-06-06 11:13:43', NULL, NULL, NULL, '0101000020110F00000100002B41D845C0B49CE58550E936C0', 0, NULL, 'Rua Tenente Roland Ritimeister, s/n, Santa Cruz, Rio de Janeiro/Rio de Janeiro - Brasil');
INSERT INTO alerts (id, title, description, type_id, user_id, created_date, likes, dislikes, updated_date, geom, enable, duration_date, address) VALUES (80, 'Obras BRT', 'gygyut', 4, 1, '2017-05-25 00:48:13', 3, 4, NULL, '0101000020110F00000100007D70CF45C0F445036EE9E936C0', 0, NULL, '');
INSERT INTO alerts (id, title, description, type_id, user_id, created_date, likes, dislikes, updated_date, geom, enable, duration_date, address) VALUES (102, 'Trânsito intenso', 'Muitos carros e pedestres em frente a UVA', 2, 1, '2017-06-08 00:14:22', NULL, NULL, NULL, '0101000020110F0000010000BF619C45C0C931E63F87E936C0', 0, NULL, 'Rua Ibituruna, s/n, Maracanã, Rio de Janeiro/Rio de Janeiro - Brasil');
INSERT INTO alerts (id, title, description, type_id, user_id, created_date, likes, dislikes, updated_date, geom, enable, duration_date, address) VALUES (100, 'Obras no asfalto', 'Rua parcialmente interditada devido a obra no asfalto', 4, 1, '2017-06-06 11:20:17', NULL, NULL, NULL, '0101000020110F0000000000CC17D945C06A214B317CEB36C0', 0, '2017-06-09 13:15:55', 'Rua do Império, s/n, Santa Cruz, Rio de Janeiro/Rio de Janeiro - Brasil');
INSERT INTO alerts (id, title, description, type_id, user_id, created_date, likes, dislikes, updated_date, geom, enable, duration_date, address) VALUES (98, 'Obras CEDAE', 'Obras de esgoto feita pela CEDAE', 4, 1, '2017-06-01 05:09:08', 1, NULL, NULL, '0101000020110F0000070000007ED545C0E19C4EA9A9EF36C0', 0, '2017-06-10 05:05:39', 'Estrada de Sepetiba, s/n, Santa Cruz, Rio de Janeiro/Rio de Janeiro - Brasil');
INSERT INTO alerts (id, title, description, type_id, user_id, created_date, likes, dislikes, updated_date, geom, enable, duration_date, address) VALUES (101, 'Animais', 'Animais silvestres atravessando a pista', 2, 1, '2017-06-07 21:01:51', NULL, NULL, NULL, '0101000020110F00000100003EF3C745C01B83944CFDE836C0', 0, NULL, 'Rua Avaré, s/n, Campo Grande, Rio de Janeiro/Rio de Janeiro - Brasil');
INSERT INTO alerts (id, title, description, type_id, user_id, created_date, likes, dislikes, updated_date, geom, enable, duration_date, address) VALUES (103, 'atenção', 'Buraco na ciclovia', 2, 1, '2017-06-10 13:32:55', NULL, NULL, NULL, '0101000020110F000005000038DA9B45C0BAA6751CF2E936C0', 0, NULL, 'Rua Mariz e Barros, s/n, Maracanã, Rio de Janeiro/Rio de Janeiro - Brasil');
INSERT INTO alerts (id, title, description, type_id, user_id, created_date, likes, dislikes, updated_date, geom, enable, duration_date, address) VALUES (104, 'Atenção', ' Buraco na via pessoal!!', 2, 1, '2017-06-10 14:46:21', NULL, NULL, NULL, '0101000020110F0000010000DBDC9B45C082A54A2DD9E936C0', 1, NULL, 'Rua Mariz e Barros, s/n, Maracanã, Rio de Janeiro/Rio de Janeiro - Brasil');
INSERT INTO alerts (id, title, description, type_id, user_id, created_date, likes, dislikes, updated_date, geom, enable, duration_date, address) VALUES (105, 'Acidente de carro', 'Acidente grave, fecharam a rua
', 4, 1, '2017-06-10 20:20:33', NULL, NULL, NULL, '0101000020110F0000010000C5699D45C08E24554625EA36C0', 1, '2017-06-12 23:00:30', 'Rua Visconde de Itamarati, s/n, Maracanã, Rio de Janeiro/Rio de Janeiro - Brasil');
INSERT INTO alerts (id, title, description, type_id, user_id, created_date, likes, dislikes, updated_date, geom, enable, duration_date, address) VALUES (106, 'Trânsito intenso', 'Muitos veículos, ônibus, carros e pedestres', 1, 1, '2017-06-10 20:44:04', NULL, NULL, NULL, '0101000020110F0000010000B0169C45C0CF67D07630E936C0', 1, '2017-06-12 22:40:20', 'Rua Senador Furtado, s/n, Maracanã, Rio de Janeiro/Rio de Janeiro - Brasil');
INSERT INTO alerts (id, title, description, type_id, user_id, created_date, likes, dislikes, updated_date, geom, enable, duration_date, address) VALUES (107, 'Díficil de passar', 'Quasa não dá pra passar aqui a tarde. Muitos alunos da UVA!!!', 1, 1, '2017-06-10 20:48:10', NULL, NULL, NULL, '0101000020110F0000000000486F9C45C0BE3EB6803AE936C0', 1, '2017-06-13 03:00:21', 'Rua General Canabarro, s/n, Maracanã, Rio de Janeiro/Rio de Janeiro - Brasil');
INSERT INTO alerts (id, title, description, type_id, user_id, created_date, likes, dislikes, updated_date, geom, enable, duration_date, address) VALUES (108, 'Bueiro aberto', 'Cuidado há um boeiro aberto.', 2, 1, '2017-06-10 21:32:55', NULL, NULL, NULL, '0101000020110F000000000050769C45C07744F61A6AE936C0', 1, '2017-06-13 03:00:21', 'Rua Moraes e Silva, s/n, Maracanã, Rio de Janeiro/Rio de Janeiro - Brasil');
INSERT INTO alerts (id, title, description, type_id, user_id, created_date, likes, dislikes, updated_date, geom, enable, duration_date, address) VALUES (109, 'Ciclovia danificada', 'Bloco de concreto elevado, risco de queda!', 2, 1, '2017-06-12 10:56:14', NULL, NULL, NULL, '0101000020110F00000100006C3CD845C07680FDAC56E936C0', 1, NULL, 'Avenida João XXIII, s/n, Santa Cruz, Rio de Janeiro/Rio de Janeiro - Brasil');


--
-- TOC entry 4382 (class 0 OID 0)
-- Dependencies: 271
-- Name: alerts_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('alerts_id_seq', 109, true);


--
-- TOC entry 4383 (class 0 OID 0)
-- Dependencies: 276
-- Name: alerts_types_geometries_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('alerts_types_geometries_id_seq', 7, true);


--
-- TOC entry 4384 (class 0 OID 0)
-- Dependencies: 273
-- Name: alerts_types_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('alerts_types_id_seq', 3, true);


--
-- TOC entry 4295 (class 0 OID 45281)
-- Dependencies: 314
-- Data for Name: bike_keeper_comments; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO bike_keeper_comments (id, bike_keeper_id, user_id, text, created_date) VALUES (1, 12, 1, 'Estou testando!', '2017-05-12 11:11:15');
INSERT INTO bike_keeper_comments (id, bike_keeper_id, user_id, text, created_date) VALUES (2, 12, 2, 'Obrigado Marcus', '2017-05-12 11:12:09');
INSERT INTO bike_keeper_comments (id, bike_keeper_id, user_id, text, created_date) VALUES (3, 12, 2, ':)', '2017-05-13 13:12:37');
INSERT INTO bike_keeper_comments (id, bike_keeper_id, user_id, text, created_date) VALUES (4, 12, 3, 'Teste', '2017-05-15 10:00:00');
INSERT INTO bike_keeper_comments (id, bike_keeper_id, user_id, text, created_date) VALUES (6, 12, 1, '<h1>Teste </h1>', '2017-05-12 11:25:33');
INSERT INTO bike_keeper_comments (id, bike_keeper_id, user_id, text, created_date) VALUES (5, 12, 1, '<img src=txt.png>Teste 2 </img>', '2017-05-12 11:25:10');
INSERT INTO bike_keeper_comments (id, bike_keeper_id, user_id, text, created_date) VALUES (7, 8, 1, 'Muito bom esse bicicletário', '2017-05-12 12:51:04');
INSERT INTO bike_keeper_comments (id, bike_keeper_id, user_id, text, created_date) VALUES (8, 3, 1, 'Okkek', '2017-05-12 15:14:11');
INSERT INTO bike_keeper_comments (id, bike_keeper_id, user_id, text, created_date) VALUES (9, 3, 1, 'teste', '2017-05-12 15:15:03');
INSERT INTO bike_keeper_comments (id, bike_keeper_id, user_id, text, created_date) VALUES (10, 6, 1, 'teste', '2017-05-12 15:25:34');
INSERT INTO bike_keeper_comments (id, bike_keeper_id, user_id, text, created_date) VALUES (11, 6, 1, 'teste 2', '2017-05-12 15:25:50');
INSERT INTO bike_keeper_comments (id, bike_keeper_id, user_id, text, created_date) VALUES (12, 6, 1, 'teste 3', '2017-05-12 15:29:59');
INSERT INTO bike_keeper_comments (id, bike_keeper_id, user_id, text, created_date) VALUES (13, 7, 1, 'Teste', '2017-05-12 15:30:52');
INSERT INTO bike_keeper_comments (id, bike_keeper_id, user_id, text, created_date) VALUES (17, 13, 1, 'Teste', '2017-05-12 15:41:39');
INSERT INTO bike_keeper_comments (id, bike_keeper_id, user_id, text, created_date) VALUES (18, 3, 1, 'chochomery', '2017-05-12 15:50:16');
INSERT INTO bike_keeper_comments (id, bike_keeper_id, user_id, text, created_date) VALUES (19, 13, 1, 'opa', '2017-05-12 17:31:28');
INSERT INTO bike_keeper_comments (id, bike_keeper_id, user_id, text, created_date) VALUES (20, 6, 1, 'Teste', '2017-05-14 01:29:04');
INSERT INTO bike_keeper_comments (id, bike_keeper_id, user_id, text, created_date) VALUES (21, 19, 2, 'Ok', '2017-05-14 01:29:25');
INSERT INTO bike_keeper_comments (id, bike_keeper_id, user_id, text, created_date) VALUES (23, 35, 1, 'Outro teste', '2017-05-29 17:48:52');
INSERT INTO bike_keeper_comments (id, bike_keeper_id, user_id, text, created_date) VALUES (22, 35, 1, 'Teste', '2017-05-28 10:48:44');
INSERT INTO bike_keeper_comments (id, bike_keeper_id, user_id, text, created_date) VALUES (24, 14, 2, 'Teste de software', '2017-05-30 07:24:53');
INSERT INTO bike_keeper_comments (id, bike_keeper_id, user_id, text, created_date) VALUES (25, 35, 1, 'Sério?', '2017-05-30 08:00:06');
INSERT INTO bike_keeper_comments (id, bike_keeper_id, user_id, text, created_date) VALUES (27, 41, 2, 'Não, só pela manhã por causa da escola', '2017-06-10 19:44:13');
INSERT INTO bike_keeper_comments (id, bike_keeper_id, user_id, text, created_date) VALUES (26, 41, 1, 'Só vive cheio!', '2017-06-08 16:41:53');
INSERT INTO bike_keeper_comments (id, bike_keeper_id, user_id, text, created_date) VALUES (28, 41, 1, 'Ok obrigado', '2017-06-10 19:45:43');


--
-- TOC entry 4385 (class 0 OID 0)
-- Dependencies: 313
-- Name: bike_keeper_comments_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('bike_keeper_comments_id_seq', 28, true);


--
-- TOC entry 4267 (class 0 OID 28435)
-- Dependencies: 281
-- Data for Name: bike_keepers; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO bike_keepers (id, title, likes, dislikes, capacity, used_capacity, user_id, description, outdoor, public, created_date, enable, geom, public_dir_name, cost, updated_date, business_hours, tel, email, address, is_open) VALUES (2, 'Bike Park', 1, 1, 15, 15, 1, 'Administração privada, próximo ao centro de Santa Cruz.', 0, 0, '2017-01-27 20:43:02', 0, '0101000020110F000002000004E1D745C06B44DE3313EA36C0', 'fcbe800376b367d2ee2aff49f589e68105223f1ef74675c4527ecbbd86f833232', 3, '2017-05-22 04:28:10', 'Segunda a Sexta 8h-22h', '', '', '', 1);
INSERT INTO bike_keepers (id, title, likes, dislikes, capacity, used_capacity, user_id, description, outdoor, public, created_date, enable, geom, public_dir_name, cost, updated_date, business_hours, tel, email, address, is_open) VALUES (17, 'Teste', NULL, NULL, 120, NULL, 1, 'Marambaia', 0, 0, '2017-05-09 12:21:24', 0, '0101000020110F00000200008035F845C051C8CB80A91137C0', 'f4da9f0fb3cab7656917532e4b7507fe17c0157d0c90b5f6521d0b5b3997e61d17', 0, '2017-05-21 17:51:56', '31h', '', '', NULL, 1);
INSERT INTO bike_keepers (id, title, likes, dislikes, capacity, used_capacity, user_id, description, outdoor, public, created_date, enable, geom, public_dir_name, cost, updated_date, business_hours, tel, email, address, is_open) VALUES (21, 'Teste', NULL, NULL, 4, 4, 1, 'Teste dois', 0, 0, '2017-05-10 14:00:30', 0, '0101000020110F000002000098CBD145C0EE4968488CF336C0', 'b098cceb6eb93af9a666550b60d9ea66bd0aba757f37812a8961b975cfd606a621', 3, '2017-05-22 06:24:34', 'Teste', '21 998555555', 'teste@golp.net', '', 1);
INSERT INTO bike_keepers (id, title, likes, dislikes, capacity, used_capacity, user_id, description, outdoor, public, created_date, enable, geom, public_dir_name, cost, updated_date, business_hours, tel, email, address, is_open) VALUES (3, 'Biciletário Comunitário', 1, NULL, 200, NULL, 1, 'Teste', 1, 0, '2017-05-03 19:58:19', 0, '0101000020110F00000200008055BA45C05E6317C745E136C0', '7b1403a21d566ab260697b6be6071b8a6584055c1e3c5f35531c038faccbea5f3', 0, NULL, 'sds', NULL, NULL, NULL, 1);
INSERT INTO bike_keepers (id, title, likes, dislikes, capacity, used_capacity, user_id, description, outdoor, public, created_date, enable, geom, public_dir_name, cost, updated_date, business_hours, tel, email, address, is_open) VALUES (30, 'Teste ssss', NULL, NULL, 150, NULL, 1, 'teste ssss', 1, 1, '2017-05-24 16:34:44', 0, '0101000020110F000004000060DBCC45C09A78C7F815E936C0', '92ef4d128f5428735be5bd332510bb5cfe51c05e490cfefe5afb47b3803cda8f29', NULL, '2017-05-25 04:00:30', '24h', NULL, NULL, '', 1);
INSERT INTO bike_keepers (id, title, likes, dislikes, capacity, used_capacity, user_id, description, outdoor, public, created_date, enable, geom, public_dir_name, cost, updated_date, business_hours, tel, email, address, is_open) VALUES (4, 'Teste', NULL, 1, 150, NULL, 1, 'Teste', 0, 0, '2017-05-09 11:38:20', 0, '0101000020110F00000200008049C645C084ECE2D281E736C0', 'c6b73e816c0d379fdc8d48cfc960d4d678ecc90a1e3bd9ae7cd00f95e7ec841d4', 0, NULL, 'dssf', NULL, NULL, NULL, 1);
INSERT INTO bike_keepers (id, title, likes, dislikes, capacity, used_capacity, user_id, description, outdoor, public, created_date, enable, geom, public_dir_name, cost, updated_date, business_hours, tel, email, address, is_open) VALUES (28, 'Teste cg', NULL, NULL, 56, NULL, 1, 'teste', 0, 1, '2017-05-21 20:19:13', 0, '0101000020110F0000FDFFFF5F49C845C0CF256B2EA6F336C0', 'ed030e4c1f2caecb5a980160ae6e08d64cc7ae9207cd0884ebad9221acbc3f2a28', 0, NULL, 'Seg a Sex 06h-22h', NULL, NULL, '', 1);
INSERT INTO bike_keepers (id, title, likes, dislikes, capacity, used_capacity, user_id, description, outdoor, public, created_date, enable, geom, public_dir_name, cost, updated_date, business_hours, tel, email, address, is_open) VALUES (19, 'EB bike', NULL, NULL, 200, 3, 1, 'Teste', 1, 0, '2017-05-10 13:47:50', 0, '0101000020110F0000000000D06FD745C08E471FD1EDE836C0', 'ffeef7dfc257127d87214c15e3afb6ec110928b4059d6d4dbe285c9eaf95ee6919', 1, '2017-05-22 06:23:39', '24h', NULL, NULL, NULL, 0);
INSERT INTO bike_keepers (id, title, likes, dislikes, capacity, used_capacity, user_id, description, outdoor, public, created_date, enable, geom, public_dir_name, cost, updated_date, business_hours, tel, email, address, is_open) VALUES (9, 'teste', NULL, 1, 40, NULL, 1, 'teste', 1, 0, '2017-05-09 12:07:11', 0, '0101000020110F00000500008004B145C097996E1C1BF536C0', 'd64d764cbbb0d412e80d0a16521b97918bb4ba7f01f6ece82f718d7bc06906719', 0, NULL, 'gd', NULL, NULL, NULL, 0);
INSERT INTO bike_keepers (id, title, likes, dislikes, capacity, used_capacity, user_id, description, outdoor, public, created_date, enable, geom, public_dir_name, cost, updated_date, business_hours, tel, email, address, is_open) VALUES (25, 'Bike CG', NULL, NULL, 16, 16, 2, 'Teste', 0, 0, '2017-05-21 20:14:47', 0, '0101000020110F0000010000C008C745C0028A9ABD70EF36C0', 'c8cfc93e3d74e487d8edb6b967a9a8ba4071be02f0fb2265f62b14369579e26925', 3, '2017-05-22 09:28:16', 'Seg a Sex 06h-22h', '21 30458956', 'Teste@ets.com', '', 1);
INSERT INTO bike_keepers (id, title, likes, dislikes, capacity, used_capacity, user_id, description, outdoor, public, created_date, enable, geom, public_dir_name, cost, updated_date, business_hours, tel, email, address, is_open) VALUES (1, 'Bicicletário Supervia', NULL, NULL, 250, 1, 1, 'Administrado pela concessionária de trens Supervia. Clientes que seguirem viagem de trêm não pagam taxa.', 0, 1, '2017-01-27 18:59:05', 0, '0101000020110F00000300006481D745C038B9F43D3FEA36C0', '798130f65d286205a6325ef533858fbc6ce81ad0afd8d26b4c7716388bbcc3bb1', 1, '2017-05-21 14:44:54', 'dsds', NULL, NULL, NULL, 1);
INSERT INTO bike_keepers (id, title, likes, dislikes, capacity, used_capacity, user_id, description, outdoor, public, created_date, enable, geom, public_dir_name, cost, updated_date, business_hours, tel, email, address, is_open) VALUES (18, 'Bike Vila', NULL, NULL, 150, NULL, 1, 'Teste', 1, 0, '2017-05-10 13:40:18', 0, '0101000020110F0000080000B421D845C00C43CA6629E936C0', '2bd8e87de8bd2e7ea1ba3acd3a45a052eeb04c66657134651d1538ce0186779c18', 0, '2017-05-21 17:52:34', 'seg-sab 08-22h', '', '', NULL, 0);
INSERT INTO bike_keepers (id, title, likes, dislikes, capacity, used_capacity, user_id, description, outdoor, public, created_date, enable, geom, public_dir_name, cost, updated_date, business_hours, tel, email, address, is_open) VALUES (10, 'tetste', NULL, NULL, 5, NULL, 1, 'tytyu', 1, 1, '2017-05-09 12:08:34', 0, '0101000020110F0000FDFFFFBF2DC045C01B26A4F3B7F936C0', 'fcc81c0395a450dcb1a256a8c7837ddacc5c5bfcdccab781aa6381f47a1c6ebb10', 1, NULL, 'g', NULL, NULL, NULL, 1);
INSERT INTO bike_keepers (id, title, likes, dislikes, capacity, used_capacity, user_id, description, outdoor, public, created_date, enable, geom, public_dir_name, cost, updated_date, business_hours, tel, email, address, is_open) VALUES (11, 'dsds', NULL, NULL, 1, NULL, 1, 'dsds', 1, 0, '2017-05-09 12:10:41', 0, '0101000020110F0000FDFFFFBF2DC045C01B26A4F3B7F936C0', '6a6749f90ef25cbf7fdd7cdf986cbb91ad3b860ad548833576bdf94de65774ce11', 0, NULL, 'dg', NULL, NULL, NULL, 1);
INSERT INTO bike_keepers (id, title, likes, dislikes, capacity, used_capacity, user_id, description, outdoor, public, created_date, enable, geom, public_dir_name, cost, updated_date, business_hours, tel, email, address, is_open) VALUES (12, 'tete', NULL, NULL, 5, NULL, 1, 'teste', 1, 1, '2017-05-09 12:11:45', 0, '0101000020110F0000FDFFFFBF2DC045C01B26A4F3B7F936C0', 'da07b04761b09443b9c392574fc4eac0925d0a3afccb8283f921dc0ee178ddd012', 1, NULL, 'dg', NULL, NULL, NULL, 1);
INSERT INTO bike_keepers (id, title, likes, dislikes, capacity, used_capacity, user_id, description, outdoor, public, created_date, enable, geom, public_dir_name, cost, updated_date, business_hours, tel, email, address, is_open) VALUES (13, 'sdsd', NULL, NULL, 1, NULL, 1, 'dsd', 1, 1, '2017-05-09 12:12:49', 0, '0101000020110F0000FAFFFFBFBED445C0D0E37F7028F636C0', '9688bc68aa8dc2d9b51acf427d9c0c9936d920577b52861c4956db368eb22bfb13', 1, NULL, 'dg', NULL, NULL, NULL, 1);
INSERT INTO bike_keepers (id, title, likes, dislikes, capacity, used_capacity, user_id, description, outdoor, public, created_date, enable, geom, public_dir_name, cost, updated_date, business_hours, tel, email, address, is_open) VALUES (16, 'teste', NULL, 1, 5, NULL, 1, 'teste', 1, 1, '2017-05-09 12:17:14', 0, '0101000020110F0000FAFFFFBF32CA45C0803467F56AFB36C0', 'fbfa7649d9b8ef423337faf13d3b15d6c5ff23121476e2d6b51fb7052849f6cf16', 5, NULL, 'dg', NULL, NULL, NULL, 1);
INSERT INTO bike_keepers (id, title, likes, dislikes, capacity, used_capacity, user_id, description, outdoor, public, created_date, enable, geom, public_dir_name, cost, updated_date, business_hours, tel, email, address, is_open) VALUES (36, 'teste', NULL, NULL, 120, NULL, 1, 'teste', 1, 1, '2017-05-25 14:57:25', 0, '0101000020110F000006000040B9D245C08AD106962AF536C0', 'f72d6f0a7605004bcaca9a633bea13500a05e460e424a6431109ecf2e10ce89d36', 0, NULL, 'ee', NULL, NULL, '', 1);
INSERT INTO bike_keepers (id, title, likes, dislikes, capacity, used_capacity, user_id, description, outdoor, public, created_date, enable, geom, public_dir_name, cost, updated_date, business_hours, tel, email, address, is_open) VALUES (24, 'Bike Thaty', NULL, NULL, 15, NULL, 2, '', 1, 1, '2017-05-21 15:55:51', 0, '0101000020110F00000300007062CC45C0C09352C5BBE936C0', '3bed12d7fede28f9e5750ff8b2c2efd94db73dad71195a12f12ecde9df370b9124', NULL, '2017-05-21 17:43:44', 'Segunda a Sexta 8h-22h', NULL, NULL, NULL, 1);
INSERT INTO bike_keepers (id, title, likes, dislikes, capacity, used_capacity, user_id, description, outdoor, public, created_date, enable, geom, public_dir_name, cost, updated_date, business_hours, tel, email, address, is_open) VALUES (7, 'Teste0000', NULL, 1, 30, NULL, 1, 'dsdsds', 1, 0, '2017-05-09 12:01:17', 0, '0101000020110F0000020000A440D845C0529D5C0A7CF836C0', 'dae30693d68e08df98e50f87b00328cd99e27caa86b873f067ad31bf0213453e7', 0, NULL, 'gd', NULL, NULL, NULL, 0);
INSERT INTO bike_keepers (id, title, likes, dislikes, capacity, used_capacity, user_id, description, outdoor, public, created_date, enable, geom, public_dir_name, cost, updated_date, business_hours, tel, email, address, is_open) VALUES (8, 'sdsd', 2, NULL, 45, NULL, 1, 'ghgjh', 1, 0, '2017-05-09 12:04:53', 0, '0101000020110F0000040000C0E3CD45C0D0E37F7028F636C0', '8b6be9b5487ea6de330c87b96f7fa1f7730e6e82c2f6496fa3258381b24739098', 0, '2017-05-21 15:00:00', 'gd', NULL, NULL, NULL, 1);
INSERT INTO bike_keepers (id, title, likes, dislikes, capacity, used_capacity, user_id, description, outdoor, public, created_date, enable, geom, public_dir_name, cost, updated_date, business_hours, tel, email, address, is_open) VALUES (5, 'Teste 200', NULL, NULL, 25, 10, 1, 'Mais um teste', 0, 1, '2017-05-09 11:44:41', 0, '0101000020110F0000FBFFFF7FBBAE45C064F765AEF5EB36C0', '0e03792ad29adc35de79be9fd53141cc96c36c8376883894eacc8b7cd4d0fec05', 1.29999995, NULL, 'gdf', NULL, NULL, NULL, 1);
INSERT INTO bike_keepers (id, title, likes, dislikes, capacity, used_capacity, user_id, description, outdoor, public, created_date, enable, geom, public_dir_name, cost, updated_date, business_hours, tel, email, address, is_open) VALUES (6, 'Teste +', 1, NULL, 50, NULL, 1, 'Teste :)', 0, 1, '2017-05-09 11:47:23', 0, '0101000020110F0000FBFFFFDF23D745C03E7B78D8D1F936C0', '63dcb488f0e1ea0fa7e02d4a26270005d43f73c8fafb7141cf685bc3b392dcb16', NULL, '2017-05-25 03:59:55', 'gd', NULL, NULL, '', 1);
INSERT INTO bike_keepers (id, title, likes, dislikes, capacity, used_capacity, user_id, description, outdoor, public, created_date, enable, geom, public_dir_name, cost, updated_date, business_hours, tel, email, address, is_open) VALUES (27, 'Bike Freguesia', NULL, NULL, 400, 71, 4, 'Teste', 0, 0, '2017-05-21 20:16:45', 1, '0101000020110F0000FCFFFF3F74BD45C0A3B7633C31F236C0', '671b452af42960c521138a6d34303667f44a81f1f73ca4cf75f695bebcaa14fc26', 5, '2017-05-22 09:12:14', 'Seg a Sex 06h-22h', '21 3049-0095', 'freguesia-bike@gmail.com', '', 1);
INSERT INTO bike_keepers (id, title, likes, dislikes, capacity, used_capacity, user_id, description, outdoor, public, created_date, enable, geom, public_dir_name, cost, updated_date, business_hours, tel, email, address, is_open) VALUES (32, 'ssds', NULL, NULL, 1, NULL, 1, 'sdsd', 1, 1, '2017-05-25 02:25:19', 0, '0101000020110F0000FEFFFFC242D545C09AFFB1D6E2EB36C0', 'eae2a071a1a22872e957f3ea3696dd2216d34a6a78702e1601098c7e4cf727ae32', NULL, '2017-05-25 03:59:32', 'sdsd', NULL, NULL, '', 1);
INSERT INTO bike_keepers (id, title, likes, dislikes, capacity, used_capacity, user_id, description, outdoor, public, created_date, enable, geom, public_dir_name, cost, updated_date, business_hours, tel, email, address, is_open) VALUES (37, 'Teste', 1, NULL, 22, NULL, 1, 'teste', 1, 1, '2017-05-25 15:39:55', 0, '0101000020110F00000500008078D345C0A095145839F336C0', '0c9efb7cb0e123e8dbf66339f0b27762d9e81130af9d03195fb739bb7083408437', NULL, '2017-05-25 18:34:39', '4545', NULL, NULL, '', 1);
INSERT INTO bike_keepers (id, title, likes, dislikes, capacity, used_capacity, user_id, description, outdoor, public, created_date, enable, geom, public_dir_name, cost, updated_date, business_hours, tel, email, address, is_open) VALUES (38, 'Teste de bicicletário', NULL, NULL, 15, NULL, 1, 'Teste2222 7', 0, 0, '2017-05-25 15:43:21', 0, '0101000020110F0000040000600BCA45C06B9FC2EA8BFD36C0', '46e1d5f366e3a23c44ae85211edc57c4e3083644eeb308c1c4cedd1c861eed5438', 2.9000001, '2017-06-10 18:43:51', '24h', '(021) 3386-7455', 'ccion@botat.com.br', '', 1);
INSERT INTO bike_keepers (id, title, likes, dislikes, capacity, used_capacity, user_id, description, outdoor, public, created_date, enable, geom, public_dir_name, cost, updated_date, business_hours, tel, email, address, is_open) VALUES (22, 'Bicicletário Gomes', 1, NULL, 5, NULL, 1, 'Teste de biciletário', 1, 1, '2017-05-15 21:48:45', 0, '0101000020110F000003000040BAC245C0F2834B60C8E336C0', 'e58912834f6450d62be7a1b59f202c2542e6a465e5f89ea56d1c4cc24af0f34a22', 0, NULL, 'Seg-Sex 09 as 22h', NULL, NULL, NULL, 1);
INSERT INTO bike_keepers (id, title, likes, dislikes, capacity, used_capacity, user_id, description, outdoor, public, created_date, enable, geom, public_dir_name, cost, updated_date, business_hours, tel, email, address, is_open) VALUES (33, 'Bicicletário fonte nova', 1, NULL, 30, NULL, 4, '', 0, 0, '2017-05-25 08:47:19', 1, '0101000020110F000005000008D2C445C05AE089A435E536C0', '79c69381a00bb283bde8f7e2740f507fc7b84d8792bed50143e16aefc7d2abd533', 4, NULL, 'Segunda a Sexta 08h-22h', '21 3049-0000', 's-bike@gmail.com', '', 1);
INSERT INTO bike_keepers (id, title, likes, dislikes, capacity, used_capacity, user_id, description, outdoor, public, created_date, enable, geom, public_dir_name, cost, updated_date, business_hours, tel, email, address, is_open) VALUES (23, 'teste', NULL, NULL, 10, 3, 1, 'Teste kjkkjkjjkkjk', 1, 0, '2017-05-20 18:53:19', 0, '0101000020110F0000020000809DCC45C0E47D95CE65E836C0', 'fab322ba15e25daf457023e35a779f75acfb65261b64636069b3a3d18ef262e723', 3.5, '2017-05-25 15:36:18', 'seg 24h sex 23h', '(021) 3021-5564', 'marcusfaccion@bol.com.br', '', 0);
INSERT INTO bike_keepers (id, title, likes, dislikes, capacity, used_capacity, user_id, description, outdoor, public, created_date, enable, geom, public_dir_name, cost, updated_date, business_hours, tel, email, address, is_open) VALUES (20, 'Teste Jardim da saud bike', 1, NULL, 30, NULL, 1, 'Teste', 1, 1, '2017-05-10 13:56:45', 0, '0101000020110F0000060000409DD045C06DDBA745C2F236C0', '63ae6a07abf3e7030ce4a43d681746f57d79e4501b9654407efada41edc4d10120', 0, NULL, 'Até as 22hs', NULL, NULL, NULL, 1);
INSERT INTO bike_keepers (id, title, likes, dislikes, capacity, used_capacity, user_id, description, outdoor, public, created_date, enable, geom, public_dir_name, cost, updated_date, business_hours, tel, email, address, is_open) VALUES (14, 'Teste', NULL, NULL, 1, NULL, 1, 'tygyu', 1, 1, '2017-05-09 12:13:36', 0, '0101000020110F0000FBFFFF7FC7CF45C0B4F771FB6DF536C0', '01e0ecf778c4cc97ab1c626a954ca9e2ce2ce29a1ef9de68dd0c7faebc7962d514', NULL, '2017-05-21 17:55:17', '16h', NULL, NULL, NULL, 1);
INSERT INTO bike_keepers (id, title, likes, dislikes, capacity, used_capacity, user_id, description, outdoor, public, created_date, enable, geom, public_dir_name, cost, updated_date, business_hours, tel, email, address, is_open) VALUES (31, 'Teste', NULL, NULL, 5, NULL, 1, 'teste', 1, 1, '2017-05-24 18:05:30', 0, '0101000020110F0000010000BE9BCC45C08BCEDC8ED4E836C0', 'b4005924e7d42e743cd8bb802e4eca50f1b2a9239fe0ed47496c12ea30ff74fe31', NULL, '2017-05-25 04:44:27', '454545', NULL, NULL, '', 1);
INSERT INTO bike_keepers (id, title, likes, dislikes, capacity, used_capacity, user_id, description, outdoor, public, created_date, enable, geom, public_dir_name, cost, updated_date, business_hours, tel, email, address, is_open) VALUES (15, 'hghg', NULL, NULL, 8, NULL, 1, 'hgjhg', 0, 0, '2017-05-09 12:14:55', 0, '0101000020110F00000600004005D245C0BFB4645BDEF136C0', '01bc5f8d88b3ddc66e7196bdd672bf69d6cf54cadc43eda05b3b178c78b568b015', 0, '2017-05-21 17:46:16', '24h', '', '', NULL, 0);
INSERT INTO bike_keepers (id, title, likes, dislikes, capacity, used_capacity, user_id, description, outdoor, public, created_date, enable, geom, public_dir_name, cost, updated_date, business_hours, tel, email, address, is_open) VALUES (35, 'Bike Fontes', NULL, NULL, 50, NULL, 1, 'Localizado na rua Silva Fontes Teste de imagem', 0, 0, '2017-05-25 08:58:40', 0, '0101000020110F00000300007006BF45C03AD86165FCE136C0', '84c92b7911b1515a2b73e7c72c6d8c98a1ccd1a0c82022933256c645f0a8083135', 0, '2017-05-25 15:20:34', 'seg-sex 8-22h', '(21) 33169-55__', '', '', 1);
INSERT INTO bike_keepers (id, title, likes, dislikes, capacity, used_capacity, user_id, description, outdoor, public, created_date, enable, geom, public_dir_name, cost, updated_date, business_hours, tel, email, address, is_open) VALUES (42, 'Bicicletário UVA', NULL, NULL, 15, NULL, 1, 'Acesso pela rua Senador furtado', 1, 1, '2017-06-10 21:01:03', 0, '0101000020110F000007000026469C45C0EA13C98F5AE936C0', '3cba53bdb9eeea5347de07e757b490c653e4ff41002c97bc48688b133df1183e42', NULL, '2017-06-10 21:02:01', 'Seg-Sab 07-22h', NULL, NULL, NULL, 1);
INSERT INTO bike_keepers (id, title, likes, dislikes, capacity, used_capacity, user_id, description, outdoor, public, created_date, enable, geom, public_dir_name, cost, updated_date, business_hours, tel, email, address, is_open) VALUES (44, 'CEFET-RJ', NULL, NULL, 80, NULL, 1, 'Localizado dentro do colégio', 1, 1, '2017-06-10 21:19:26', 0, '0101000020110F0000010000B4B79C45C0CD31E63F87E936C0', 'c84a89f54bb566dcc5f597eb12d783df03a01d3d7b8f6ccba35ca00abba376ff44', NULL, '2017-06-10 21:24:51', 'seg-sab 07h30 as 22h', NULL, NULL, 'aaaaa', 1);
INSERT INTO bike_keepers (id, title, likes, dislikes, capacity, used_capacity, user_id, description, outdoor, public, created_date, enable, geom, public_dir_name, cost, updated_date, business_hours, tel, email, address, is_open) VALUES (39, 'Bicicletário comunitário', NULL, NULL, 100, NULL, 1, 'Teste2103', 1, 1, '2017-05-25 16:05:15', 0, '0101000020110F0000FDFFFFCD37B745C0033861ADA70137C0', '64d37806f5f6dc8b1cc16aa8d7b90bc1bf8ca168d3124ebfd0083ef0e678e3a039', NULL, '2017-05-25 18:34:20', '8h-22h', NULL, NULL, 'Rua Sílvio da Rocha Pollis, s/n, Barra da Tijuca, Rio de Janeiro/Rio de Janeiro - Brasil', 1);
INSERT INTO bike_keepers (id, title, likes, dislikes, capacity, used_capacity, user_id, description, outdoor, public, created_date, enable, geom, public_dir_name, cost, updated_date, business_hours, tel, email, address, is_open) VALUES (45, 'CEFET-RJ', NULL, NULL, 49, NULL, 1, 'Funciona dentro do campus', 1, 1, '2017-06-10 21:27:22', 1, '0101000020110F000001000087B79C45C09705E47C8AE936C0', 'bf644e2c84edfaa4fcbf927e686de96a25a6de8b952a96233ccc4f0192c3be7245', 0, NULL, 'Segunda a Sábado 07h-22h', NULL, NULL, 'Rua General Canabarro, s/n, Maracanã, Rio de Janeiro/Rio de Janeiro - Brasil', 1);
INSERT INTO bike_keepers (id, title, likes, dislikes, capacity, used_capacity, user_id, description, outdoor, public, created_date, enable, geom, public_dir_name, cost, updated_date, business_hours, tel, email, address, is_open) VALUES (41, 'Bicicletário municipal', NULL, NULL, 100, NULL, 2, 'Bicicletário da Escola', 1, 1, '2017-06-06 11:35:44', 1, '0101000020110F000000000022FCCB45C0C77896411AE436C0', 'd4efe4ae9401434aa7fb946b66db02db86a2286406fc41102a56d16409f21fd041', 0, NULL, '24hs', NULL, NULL, 'Rua Moreno Brandão, s/n, Inhoaíba, Rio de Janeiro/Rio de Janeiro - Brasil', 1);
INSERT INTO bike_keepers (id, title, likes, dislikes, capacity, used_capacity, user_id, description, outdoor, public, created_date, enable, geom, public_dir_name, cost, updated_date, business_hours, tel, email, address, is_open) VALUES (40, 'Bike Park', 1, NULL, 200, 90, 1, 'Preço bom e confiável.', 0, 0, '2017-05-25 18:39:33', 1, '0101000020110F0000FCFFFF63DBD745C05F6A3DBB15EA36C0', 'b94af9ca8048b3c918755f316d2ddee266f064446c0cd108d65596f5eeebe96340', 2.5, '2017-06-10 22:02:58', 'Segunda a Sexta 06h-22h', '', '', 'Rua do Império 02, Santa Cruz, Rio de Janeiro/Rio de Janeiro - Brasil', 1);
INSERT INTO bike_keepers (id, title, likes, dislikes, capacity, used_capacity, user_id, description, outdoor, public, created_date, enable, geom, public_dir_name, cost, updated_date, business_hours, tel, email, address, is_open) VALUES (43, 'Supervia Realengo', NULL, NULL, 250, NULL, 1, '', 0, 1, '2017-06-10 21:09:00', 1, '0101000020110F0000FDFFFF72EDB645C0F233C899E6DF36C0', 'ebd36777c646580afc55c25f40b53e4f866bd608b15ffe70fdaaeb8b4cf2916043', NULL, '2017-06-10 22:04:12', '06h-22h', NULL, NULL, 'Rua Doutor Lessa, s/n, Realengo, Rio de Janeiro/Rio de Janeiro - Brasil', 1);


--
-- TOC entry 4386 (class 0 OID 0)
-- Dependencies: 280
-- Name: bike_keepers_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('bike_keepers_id_seq', 45, true);


--
-- TOC entry 4274 (class 0 OID 28515)
-- Dependencies: 288
-- Data for Name: bike_keepers_multimedias; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO bike_keepers_multimedias (bike_keepers_id, multimedias_id) VALUES (35, 79);
INSERT INTO bike_keepers_multimedias (bike_keepers_id, multimedias_id) VALUES (36, 80);
INSERT INTO bike_keepers_multimedias (bike_keepers_id, multimedias_id) VALUES (37, 81);
INSERT INTO bike_keepers_multimedias (bike_keepers_id, multimedias_id) VALUES (38, 82);
INSERT INTO bike_keepers_multimedias (bike_keepers_id, multimedias_id) VALUES (39, 83);
INSERT INTO bike_keepers_multimedias (bike_keepers_id, multimedias_id) VALUES (41, 85);
INSERT INTO bike_keepers_multimedias (bike_keepers_id, multimedias_id) VALUES (42, 87);
INSERT INTO bike_keepers_multimedias (bike_keepers_id, multimedias_id) VALUES (43, 88);
INSERT INTO bike_keepers_multimedias (bike_keepers_id, multimedias_id) VALUES (44, 89);
INSERT INTO bike_keepers_multimedias (bike_keepers_id, multimedias_id) VALUES (44, 90);
INSERT INTO bike_keepers_multimedias (bike_keepers_id, multimedias_id) VALUES (45, 91);
INSERT INTO bike_keepers_multimedias (bike_keepers_id, multimedias_id) VALUES (45, 92);
INSERT INTO bike_keepers_multimedias (bike_keepers_id, multimedias_id) VALUES (40, 93);


--
-- TOC entry 4276 (class 0 OID 28598)
-- Dependencies: 290
-- Data for Name: events; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 4387 (class 0 OID 0)
-- Dependencies: 289
-- Name: events_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('events_id_seq', 1, false);


--
-- TOC entry 4273 (class 0 OID 28479)
-- Dependencies: 287
-- Data for Name: galleries; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO galleries (id, title, user_id, created_date) VALUES (0, 'Default Gallery', 0, '2015-11-03 13:41:00');


--
-- TOC entry 4388 (class 0 OID 0)
-- Dependencies: 286
-- Name: galleries_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('galleries_id_seq', 1, false);


--
-- TOC entry 4271 (class 0 OID 28459)
-- Dependencies: 285
-- Data for Name: multimedia_types; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO multimedia_types (id, title, description, mime_types) VALUES (1, 'imagem', 'Fotografias ou  imagens', 'image/jpeg;image/pjpeg;image/jpeg;image/png');
INSERT INTO multimedia_types (id, title, description, mime_types) VALUES (2, 'vídeo', 'Vídeo de média duração (avi, mp4, webm)', 'video/avi;video/msvideo;video/x-msvideo;video/webm;video/ogg');
INSERT INTO multimedia_types (id, title, description, mime_types) VALUES (0, 'default', 'Genérico', 'application/octet-stream;');


--
-- TOC entry 4389 (class 0 OID 0)
-- Dependencies: 284
-- Name: multimedia_types_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('multimedia_types_id_seq', 2, true);


--
-- TOC entry 4269 (class 0 OID 28451)
-- Dependencies: 283
-- Data for Name: multimedias; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (1, 1, NULL, '2017-01-10 11:38:21', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/fcbe800376b367d2ee2aff49f589e68105223f1ef74675c4527ecbbd86f833232/images/bicicletario_sc1.png', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (2, 1, NULL, '2017-01-10 11:39:08', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/fcbe800376b367d2ee2aff49f589e68105223f1ef74675c4527ecbbd86f833232/images/bicicletario_sc2.png', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (3, 1, NULL, '2017-01-26 18:04:30', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/7b1403a21d566ab260697b6be6071b8a6584055c1e3c5f35531c038faccbea5f3/images/bicicletario_sc1.png', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (4, 1, NULL, '2017-01-26 18:04:31', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/7b1403a21d566ab260697b6be6071b8a6584055c1e3c5f35531c038faccbea5f3/images/bicicletario_sc2.png', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (5, 1, NULL, '2017-01-26 18:10:02', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/7b1403a21d566ab260697b6be6071b8a6584055c1e3c5f35531c038faccbea5f3/images/bicicletario_sc1.png', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (6, 1, NULL, '2017-01-26 18:10:02', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/7b1403a21d566ab260697b6be6071b8a6584055c1e3c5f35531c038faccbea5f3/images/bicicletario_sc2.png', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (7, 1, NULL, '2017-01-26 18:11:10', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/798130f65d286205a6325ef533858fbc6ce81ad0afd8d26b4c7716388bbcc3bb1/images/bicicletario_sc1.png', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (8, 1, NULL, '2017-01-26 18:11:10', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/798130f65d286205a6325ef533858fbc6ce81ad0afd8d26b4c7716388bbcc3bb1/images/bicicletario_sc2.png', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (9, 1, NULL, '2017-01-26 18:45:32', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/63dcb488f0e1ea0fa7e02d4a26270005d43f73c8fafb7141cf685bc3b392dcb16/images/bicicletario_sc1.png', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (10, 1, NULL, '2017-01-26 18:45:32', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/63dcb488f0e1ea0fa7e02d4a26270005d43f73c8fafb7141cf685bc3b392dcb16/images/bicicletario_sc2.png', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (11, 1, NULL, '2017-01-26 18:48:23', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/dae30693d68e08df98e50f87b00328cd99e27caa86b873f067ad31bf0213453e7/images/bicicletario_sc1.png', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (12, 1, NULL, '2017-01-26 18:48:24', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/dae30693d68e08df98e50f87b00328cd99e27caa86b873f067ad31bf0213453e7/images/bicicletario_sc2.png', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (13, 1, NULL, '2017-01-26 19:05:48', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/798130f65d286205a6325ef533858fbc6ce81ad0afd8d26b4c7716388bbcc3bb1/images/bicicletario_sc1.png', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (14, 1, NULL, '2017-01-26 19:05:48', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/798130f65d286205a6325ef533858fbc6ce81ad0afd8d26b4c7716388bbcc3bb1/images/bicicletario_sc2.png', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (15, 1, NULL, '2017-01-26 19:11:04', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/7b1403a21d566ab260697b6be6071b8a6584055c1e3c5f35531c038faccbea5f3/images/bicicletario_sc1.png', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (16, 1, NULL, '2017-01-26 19:11:04', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/7b1403a21d566ab260697b6be6071b8a6584055c1e3c5f35531c038faccbea5f3/images/bicicletario_sc2.png', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (17, 1, NULL, '2017-01-26 19:56:05', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/c6b73e816c0d379fdc8d48cfc960d4d678ecc90a1e3bd9ae7cd00f95e7ec841d4/images/bicicletario_sc2.png', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (18, 1, NULL, '2017-01-26 19:59:14', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/0e03792ad29adc35de79be9fd53141cc96c36c8376883894eacc8b7cd4d0fec05/images/bicicletario_sc1.png', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (19, 1, NULL, '2017-01-26 20:04:48', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/63dcb488f0e1ea0fa7e02d4a26270005d43f73c8fafb7141cf685bc3b392dcb16/images/bicicletario_sc1.png', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (20, 1, NULL, '2017-01-26 20:07:43', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/dae30693d68e08df98e50f87b00328cd99e27caa86b873f067ad31bf0213453e7/images/bicicletario_sc2.png', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (21, 1, NULL, '2017-01-26 20:09:43', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/8b6be9b5487ea6de330c87b96f7fa1f7730e6e82c2f6496fa3258381b24739098/images/bicicletario_sc1.png', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (22, 1, NULL, '2017-01-26 20:27:33', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/d64d764cbbb0d412e80d0a16521b97918bb4ba7f01f6ece82f718d7bc06906719/images/bicicletario_sc1.png', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (23, 1, NULL, '2017-01-26 20:37:55', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/fcc81c0395a450dcb1a256a8c7837ddacc5c5bfcdccab781aa6381f47a1c6ebb10/images/bicicletario_sc2.png', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (24, 1, NULL, '2017-01-26 20:50:39', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/798130f65d286205a6325ef533858fbc6ce81ad0afd8d26b4c7716388bbcc3bb1/images/bicicletario_sc1.png', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (25, 1, NULL, '2017-01-26 20:53:53', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/7b1403a21d566ab260697b6be6071b8a6584055c1e3c5f35531c038faccbea5f3/images/bicicletario_sc2.png', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (26, 1, NULL, '2017-01-26 21:00:23', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/c6b73e816c0d379fdc8d48cfc960d4d678ecc90a1e3bd9ae7cd00f95e7ec841d4/images/bicicletario_sc2.png', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (27, 1, NULL, '2017-01-26 21:02:59', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/0e03792ad29adc35de79be9fd53141cc96c36c8376883894eacc8b7cd4d0fec05/images/bicicletario_sc2.png', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (28, 1, NULL, '2017-01-26 21:09:37', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/63dcb488f0e1ea0fa7e02d4a26270005d43f73c8fafb7141cf685bc3b392dcb16/images/bicicletario_sc2.png', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (29, 1, NULL, '2017-01-26 21:24:45', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/dae30693d68e08df98e50f87b00328cd99e27caa86b873f067ad31bf0213453e7/images/Maki_Icons_By_Mapbox.png', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (30, 1, NULL, '2017-01-26 21:25:54', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/8b6be9b5487ea6de330c87b96f7fa1f7730e6e82c2f6496fa3258381b24739098/images/Maki_Icons_By_Mapbox.png', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (31, 1, NULL, '2017-01-26 22:04:42', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/d64d764cbbb0d412e80d0a16521b97918bb4ba7f01f6ece82f718d7bc06906719/images/bicycle_512.png', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (32, 1, NULL, '2017-01-26 22:06:36', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/fcc81c0395a450dcb1a256a8c7837ddacc5c5bfcdccab781aa6381f47a1c6ebb10/images/alert_construction_40.png', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (33, 1, NULL, '2017-01-26 22:07:29', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/6a6749f90ef25cbf7fdd7cdf986cbb91ad3b860ad548833576bdf94de65774ce11/images/alert_construction_bk_80.png', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (34, 1, NULL, '2017-01-26 22:10:31', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/798130f65d286205a6325ef533858fbc6ce81ad0afd8d26b4c7716388bbcc3bb1/images/bicicletario_sc1.png', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (35, 1, NULL, '2017-01-26 22:10:31', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/798130f65d286205a6325ef533858fbc6ce81ad0afd8d26b4c7716388bbcc3bb1/images/bicicletario_sc2.png', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (36, 1, NULL, '2017-01-26 22:12:38', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/fcbe800376b367d2ee2aff49f589e68105223f1ef74675c4527ecbbd86f833232/images/bicicletario_sc1.png', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (37, 1, NULL, '2017-01-27 18:59:05', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/798130f65d286205a6325ef533858fbc6ce81ad0afd8d26b4c7716388bbcc3bb1/images/bicicletario_sc1.png', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (38, 1, NULL, '2017-01-27 20:43:02', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/fcbe800376b367d2ee2aff49f589e68105223f1ef74675c4527ecbbd86f833232/images/bike_park_sc.png', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (39, 1, NULL, '2017-05-03 19:58:20', 'C:\Users\User\Dropbox\www\app/web/bs-contents/bike-keepers/7b1403a21d566ab260697b6be6071b8a6584055c1e3c5f35531c038faccbea5f3/images/13221035_1190969917582113_5869141520709282375_n.jpg', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (40, 1, NULL, '2017-05-09 11:38:21', 'C:\Users\User\Dropbox\www\app/web/bs-contents/bike-keepers/c6b73e816c0d379fdc8d48cfc960d4d678ecc90a1e3bd9ae7cd00f95e7ec841d4/images/php-logo.png', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (41, 1, NULL, '2017-05-09 11:44:41', 'C:\Users\User\Dropbox\www\app/web/bs-contents/bike-keepers/0e03792ad29adc35de79be9fd53141cc96c36c8376883894eacc8b7cd4d0fec05/images/leafletJS-logo.png', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (42, 1, NULL, '2017-05-09 11:47:23', 'C:\Users\User\Dropbox\www\app/web/bs-contents/bike-keepers/63dcb488f0e1ea0fa7e02d4a26270005d43f73c8fafb7141cf685bc3b392dcb16/images/how_gps_works.png', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (43, 1, NULL, '2017-05-09 12:01:17', 'C:\Users\User\Dropbox\www\app/web/bs-contents/bike-keepers/dae30693d68e08df98e50f87b00328cd99e27caa86b873f067ad31bf0213453e7/images/imagem_pedestre_ciclista_km_percorridos.png', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (44, 1, NULL, '2017-05-09 12:04:53', 'C:\Users\User\Dropbox\www\app/web/bs-contents/bike-keepers/8b6be9b5487ea6de330c87b96f7fa1f7730e6e82c2f6496fa3258381b24739098/images/postgis-logo.png', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (45, 1, NULL, '2017-05-09 12:07:11', 'C:\Users\User\Dropbox\www\app/web/bs-contents/bike-keepers/d64d764cbbb0d412e80d0a16521b97918bb4ba7f01f6ece82f718d7bc06906719/images/telas1.png', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (46, 1, NULL, '2017-05-09 12:08:34', 'C:\Users\User\Dropbox\www\app/web/bs-contents/bike-keepers/fcc81c0395a450dcb1a256a8c7837ddacc5c5bfcdccab781aa6381f47a1c6ebb10/images/how_gps_works.png', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (47, 1, NULL, '2017-05-09 12:10:42', 'C:\Users\User\Dropbox\www\app/web/bs-contents/bike-keepers/6a6749f90ef25cbf7fdd7cdf986cbb91ad3b860ad548833576bdf94de65774ce11/images/arquitetura_de_um_SIG.png', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (48, 1, NULL, '2017-05-09 12:11:45', 'C:\Users\User\Dropbox\www\app/web/bs-contents/bike-keepers/da07b04761b09443b9c392574fc4eac0925d0a3afccb8283f921dc0ee178ddd012/images/duolingo_facebook.png', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (49, 1, NULL, '2017-05-09 12:12:49', 'C:\Users\User\Dropbox\www\app/web/bs-contents/bike-keepers/9688bc68aa8dc2d9b51acf427d9c0c9936d920577b52861c4956db368eb22bfb13/images/telas9.png', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (50, 1, NULL, '2017-05-09 12:13:36', 'C:\Users\User\Dropbox\www\app/web/bs-contents/bike-keepers/01e0ecf778c4cc97ab1c626a954ca9e2ce2ce29a1ef9de68dd0c7faebc7962d514/images/telas4.png', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (51, 1, NULL, '2017-05-09 12:14:55', 'C:\Users\User\Dropbox\www\app/web/bs-contents/bike-keepers/01bc5f8d88b3ddc66e7196bdd672bf69d6cf54cadc43eda05b3b178c78b568b015/images/arquitetura_geoapi.jpg', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (52, 1, NULL, '2017-05-09 12:17:15', 'C:\Users\User\Dropbox\www\app/web/bs-contents/bike-keepers/fbfa7649d9b8ef423337faf13d3b15d6c5ff23121476e2d6b51fb7052849f6cf16/images/redes_sociais1.png', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (53, 1, NULL, '2017-05-09 12:21:24', 'C:\Users\User\Dropbox\www\app/web/bs-contents/bike-keepers/f4da9f0fb3cab7656917532e4b7507fe17c0157d0c90b5f6521d0b5b3997e61d17/images/php-logo.png', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (54, 1, NULL, '2017-05-10 13:40:18', 'C:\Users\User\Dropbox\www\app/web/bs-contents/bike-keepers/2bd8e87de8bd2e7ea1ba3acd3a45a052eeb04c66657134651d1538ce0186779c18/images/IMG_0075.jpg', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (55, 1, NULL, '2017-05-10 13:47:51', 'C:\Users\User\Dropbox\www\app/web/bs-contents/bike-keepers/ffeef7dfc257127d87214c15e3afb6ec110928b4059d6d4dbe285c9eaf95ee6919/images/IMG_0015.jpg', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (56, 1, NULL, '2017-05-10 13:56:46', 'C:\Users\User\Dropbox\www\app/web/bs-contents/bike-keepers/63ae6a07abf3e7030ce4a43d681746f57d79e4501b9654407efada41edc4d10120/images/IMG_0013.jpg', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (57, 1, NULL, '2017-05-10 14:00:30', 'C:\Users\User\Dropbox\www\app/web/bs-contents/bike-keepers/b098cceb6eb93af9a666550b60d9ea66bd0aba757f37812a8961b975cfd606a621/images/IMG_0001.jpg', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (58, 1, NULL, '2017-05-15 21:48:45', 'C:\Users\User\Dropbox\www\app/web/bs-contents/bike-keepers/e58912834f6450d62be7a1b59f202c2542e6a465e5f89ea56d1c4cc24af0f34a22/images/MeControlXXLUserTile.jpg', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (59, 1, NULL, '2017-05-20 18:53:19', 'C:\Users\User\Dropbox\www\app/web/bs-contents/bike-keepers/fab322ba15e25daf457023e35a779f75acfb65261b64636069b3a3d18ef262e723/images/gps_trilateracao.png', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (60, 1, NULL, '2017-05-21 15:55:51', 'C:\Users\User\Dropbox\www\app/web/bs-contents/bike-keepers/3bed12d7fede28f9e5750ff8b2c2efd94db73dad71195a12f12ecde9df370b9124/images/agps_opration.jpg', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (61, 1, NULL, '2017-05-21 20:14:47', 'C:\Users\User\Dropbox\www\app/web/bs-contents/bike-keepers/c8cfc93e3d74e487d8edb6b967a9a8ba4071be02f0fb2265f62b14369579e26925/images/how_gps_works.png', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (62, 1, NULL, '2017-05-21 20:15:11', 'C:\Users\User\Dropbox\www\app/web/bs-contents/bike-keepers/671b452af42960c521138a6d34303667f44a81f1f73ca4cf75f695bebcaa14fc26/images/how_gps_works.png', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (63, 1, NULL, '2017-05-21 20:16:45', 'C:\Users\User\Dropbox\www\app/web/bs-contents/bike-keepers/671b452af42960c521138a6d34303667f44a81f1f73ca4cf75f695bebcaa14fc26/images/13221035_1190969917582113_5869141520709282375_n.jpg', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (64, 1, NULL, '2017-05-21 20:19:37', 'C:\Users\User\Dropbox\www\app/web/bs-contents/bike-keepers/ed030e4c1f2caecb5a980160ae6e08d64cc7ae9207cd0884ebad9221acbc3f2a28/images/leafletJS-logo.png', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (65, 1, NULL, '2017-05-21 20:21:22', 'C:\Users\User\Dropbox\www\app/web/bs-contents/bike-keepers/92ef4d128f5428735be5bd332510bb5cfe51c05e490cfefe5afb47b3803cda8f29/images/leafletJS-logo.png', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (66, 1, NULL, '2017-05-24 16:34:44', 'C:\Users\User\Dropbox\www\app/web/bs-contents/bike-keepers/92ef4d128f5428735be5bd332510bb5cfe51c05e490cfefe5afb47b3803cda8f29/images/348126.jpg', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (67, 1, NULL, '2017-05-24 18:05:30', 'C:\Users\User\Dropbox\www\app/web/bs-contents/bike-keepers/b4005924e7d42e743cd8bb802e4eca50f1b2a9239fe0ed47496c12ea30ff74fe31/images/MeControlXXLUserTile.jpg', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (68, 1, NULL, '2017-05-25 02:25:20', 'C:\Users\User\Dropbox\www\app/web/bs-contents/bike-keepers/eae2a071a1a22872e957f3ea3696dd2216d34a6a78702e1601098c7e4cf727ae32/images/12809609_741716492597295_5854081116663842055_n.jpg', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (69, 1, NULL, '2017-05-25 08:47:19', '348126.jpg', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (70, 1, NULL, '2017-05-25 08:47:19', 'MeControlXXLUserTile.jpg', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (71, 1, NULL, '2017-05-25 08:54:31', '348126.jpg', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (72, 1, NULL, '2017-05-25 08:54:31', '12809609_741716492597295_5854081116663842055_n.jpg', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (73, 1, NULL, '2017-05-25 08:54:31', 'MeControlXXLUserTile.jpg', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (74, 1, NULL, '2017-05-25 08:58:40', '348126.jpg', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (75, 1, NULL, '2017-05-25 08:58:40', '12809609_741716492597295_5854081116663842055_n.jpg', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (76, 1, NULL, '2017-05-25 08:58:40', 'MeControlXXLUserTile.jpg', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (77, 1, NULL, '2017-05-25 11:52:52', '12809609_741716492597295_5854081116663842055_n.jpg', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (78, 1, NULL, '2017-05-25 11:54:01', '12809609_741716492597295_5854081116663842055_n.jpg', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (79, 1, NULL, '2017-05-25 11:54:49', '12809609_741716492597295_5854081116663842055_n.jpg', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (80, 1, NULL, '2017-05-25 14:57:25', '12809609_741716492597295_5854081116663842055_n.jpg', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (81, 1, NULL, '2017-05-25 15:39:55', 'MeControlXXLUserTile.jpg', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (82, 1, NULL, '2017-05-25 15:43:21', 'solar_storm_1920_1080.jpg', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (83, 1, NULL, '2017-05-25 16:05:15', 'black_hole_120_1080.jpg', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (84, 1, NULL, '2017-05-25 18:39:33', 'wormhole2_1920_1080.jpg', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (85, 1, NULL, '2017-06-06 11:35:44', 'bicicletario_lages_rj.jpg', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (86, 1, NULL, '2017-06-10 21:01:03', 'slq_joins_cheat_chart.jpeg', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (87, 1, NULL, '2017-06-10 21:02:01', 'photoUVA.jpg', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (88, 1, NULL, '2017-06-10 21:09:00', 'realengo.jpg', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (89, 1, NULL, '2017-06-10 21:19:26', 'cefet1.jpg', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (90, 1, NULL, '2017-06-10 21:19:26', 'cefet2.jpg', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (91, 1, NULL, '2017-06-10 21:27:22', 'cefet1.jpg', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (92, 1, NULL, '2017-06-10 21:27:22', 'cefet2.jpg', 0);
INSERT INTO multimedias (id, type_id, title, created_date, src, gallery_id) VALUES (93, 1, NULL, '2017-06-10 22:02:58', 'bike_park_sc.png', 0);


--
-- TOC entry 4390 (class 0 OID 0)
-- Dependencies: 282
-- Name: multimedias_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('multimedias_id_seq', 93, true);


--
-- TOC entry 4302 (class 0 OID 45514)
-- Dependencies: 321
-- Data for Name: online_users; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO online_users (user_id, updated_date) VALUES (2, '2017-06-11 07:47:40');
INSERT INTO online_users (user_id, updated_date) VALUES (1, '2017-06-11 07:47:53');
INSERT INTO online_users (user_id, updated_date) VALUES (4, '2017-05-31 18:55:30');
INSERT INTO online_users (user_id, updated_date) VALUES (5, '2017-06-01 04:49:22');


--
-- TOC entry 3959 (class 0 OID 20651)
-- Dependencies: 266
-- Data for Name: pointcloud_formats; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 4278 (class 0 OID 28618)
-- Dependencies: 292
-- Data for Name: routes; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 4391 (class 0 OID 0)
-- Dependencies: 291
-- Name: routes_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('routes_id_seq', 1, false);


--
-- TOC entry 3963 (class 0 OID 18883)
-- Dependencies: 193
-- Data for Name: spatial_ref_sys; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 4261 (class 0 OID 28334)
-- Dependencies: 275
-- Data for Name: spatial_types; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO spatial_types (id, description) VALUES (1, 'Point');
INSERT INTO spatial_types (id, description) VALUES (2, 'LineString');
INSERT INTO spatial_types (id, description) VALUES (3, 'Polygon');
INSERT INTO spatial_types (id, description) VALUES (4, 'MultiPoint');
INSERT INTO spatial_types (id, description) VALUES (5, 'MultiLineString');
INSERT INTO spatial_types (id, description) VALUES (6, 'MultiPolygon');
INSERT INTO spatial_types (id, description) VALUES (7, 'GeometryCollection');


--
-- TOC entry 3962 (class 0 OID 20621)
-- Dependencies: 263
-- Data for Name: us_gaz; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 3960 (class 0 OID 20607)
-- Dependencies: 261
-- Data for Name: us_lex; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 3961 (class 0 OID 20635)
-- Dependencies: 265
-- Data for Name: us_rules; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 4291 (class 0 OID 45172)
-- Dependencies: 310
-- Data for Name: user_alert_nonexistence; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO user_alert_nonexistence (user_id, alert_id, created_date) VALUES (1, 85, '2017-05-27 00:00:00');
INSERT INTO user_alert_nonexistence (user_id, alert_id, created_date) VALUES (2, 83, '2017-05-27 00:00:00');
INSERT INTO user_alert_nonexistence (user_id, alert_id, created_date) VALUES (2, 97, '2017-06-01 20:08:21');
INSERT INTO user_alert_nonexistence (user_id, alert_id, created_date) VALUES (2, 95, '2017-05-27 18:13:44');


--
-- TOC entry 4290 (class 0 OID 45158)
-- Dependencies: 309
-- Data for Name: user_alert_rates; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO user_alert_rates (user_id, alert_id, created_date, rating, updated_date) VALUES (1, 22, '2017-04-30 01:50:51', 'dislikes', NULL);
INSERT INTO user_alert_rates (user_id, alert_id, created_date, rating, updated_date) VALUES (1, 30, '2017-04-30 08:32:37', 'likes', NULL);
INSERT INTO user_alert_rates (user_id, alert_id, created_date, rating, updated_date) VALUES (2, 22, '2017-05-02 17:16:17', 'likes', NULL);
INSERT INTO user_alert_rates (user_id, alert_id, created_date, rating, updated_date) VALUES (1, 37, '2017-05-12 10:46:45', 'dislikes', NULL);
INSERT INTO user_alert_rates (user_id, alert_id, created_date, rating, updated_date) VALUES (1, 34, '2017-05-12 15:08:57', 'likes', NULL);
INSERT INTO user_alert_rates (user_id, alert_id, created_date, rating, updated_date) VALUES (1, 31, '2017-05-12 16:59:20', 'dislikes', NULL);
INSERT INTO user_alert_rates (user_id, alert_id, created_date, rating, updated_date) VALUES (1, 98, '2017-06-01 19:27:12', 'likes', NULL);


--
-- TOC entry 4297 (class 0 OID 45317)
-- Dependencies: 316
-- Data for Name: user_bike_keeper_nonexistence; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO user_bike_keeper_nonexistence (user_id, bike_keeper_id, created_date) VALUES (2, 8, '2017-05-22 06:19:24');
INSERT INTO user_bike_keeper_nonexistence (user_id, bike_keeper_id, created_date) VALUES (2, 9, '2017-05-22 06:22:53');
INSERT INTO user_bike_keeper_nonexistence (user_id, bike_keeper_id, created_date) VALUES (2, 6, '2017-05-25 04:04:29');
INSERT INTO user_bike_keeper_nonexistence (user_id, bike_keeper_id, created_date) VALUES (1, 33, '2017-06-07 22:04:54');


--
-- TOC entry 4296 (class 0 OID 45302)
-- Dependencies: 315
-- Data for Name: user_bike_keeper_rates; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO user_bike_keeper_rates (user_id, bike_keeper_id, created_date, rating, updated_date) VALUES (2, 8, '2017-05-14 00:30:17', 'likes', NULL);
INSERT INTO user_bike_keeper_rates (user_id, bike_keeper_id, created_date, rating, updated_date) VALUES (2, 9, '2017-05-14 01:27:02', 'dislikes', NULL);
INSERT INTO user_bike_keeper_rates (user_id, bike_keeper_id, created_date, rating, updated_date) VALUES (2, 7, '2017-05-20 21:26:05', 'dislikes', NULL);
INSERT INTO user_bike_keeper_rates (user_id, bike_keeper_id, created_date, rating, updated_date) VALUES (2, 22, '2017-05-20 21:26:57', 'likes', NULL);
INSERT INTO user_bike_keeper_rates (user_id, bike_keeper_id, created_date, rating, updated_date) VALUES (4, 2, '2017-05-21 17:56:14', 'likes', NULL);
INSERT INTO user_bike_keeper_rates (user_id, bike_keeper_id, created_date, rating, updated_date) VALUES (2, 2, '2017-05-21 17:56:39', 'dislikes', NULL);
INSERT INTO user_bike_keeper_rates (user_id, bike_keeper_id, created_date, rating, updated_date) VALUES (1, 37, '2017-06-01 19:16:36', 'likes', NULL);
INSERT INTO user_bike_keeper_rates (user_id, bike_keeper_id, created_date, rating, updated_date) VALUES (1, 33, '2017-06-07 22:04:51', 'likes', NULL);
INSERT INTO user_bike_keeper_rates (user_id, bike_keeper_id, created_date, rating, updated_date) VALUES (1, 40, '2017-06-10 13:52:57', 'likes', NULL);
INSERT INTO user_bike_keeper_rates (user_id, bike_keeper_id, created_date, rating, updated_date) VALUES (1, 6, '2017-05-12 14:38:35', 'likes', NULL);
INSERT INTO user_bike_keeper_rates (user_id, bike_keeper_id, created_date, rating, updated_date) VALUES (1, 3, '2017-05-12 15:50:00', 'likes', NULL);
INSERT INTO user_bike_keeper_rates (user_id, bike_keeper_id, created_date, rating, updated_date) VALUES (1, 8, '2017-05-12 17:05:50', 'likes', NULL);
INSERT INTO user_bike_keeper_rates (user_id, bike_keeper_id, created_date, rating, updated_date) VALUES (1, 4, '2017-05-12 17:11:33', 'dislikes', NULL);
INSERT INTO user_bike_keeper_rates (user_id, bike_keeper_id, created_date, rating, updated_date) VALUES (1, 20, '2017-05-12 17:31:05', 'likes', NULL);


--
-- TOC entry 4304 (class 0 OID 45528)
-- Dependencies: 323
-- Data for Name: user_conversation_alerts; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO user_conversation_alerts (id, user_id, user_id2, created_date) VALUES (26, 4, 1, '2017-06-07 14:55:06');
INSERT INTO user_conversation_alerts (id, user_id, user_id2, created_date) VALUES (28, 4, 1, '2017-06-10 19:39:26');


--
-- TOC entry 4392 (class 0 OID 0)
-- Dependencies: 322
-- Name: user_conversation_alerts_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('user_conversation_alerts_id_seq', 30, true);


--
-- TOC entry 4393 (class 0 OID 0)
-- Dependencies: 320
-- Name: user_conversation_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('user_conversation_id_seq', 55, true);


--
-- TOC entry 4300 (class 0 OID 45488)
-- Dependencies: 319
-- Data for Name: user_conversations; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO user_conversations (user_id, user_id2, created_date, text, id, user_saw, user2_saw) VALUES (1, 4, '2017-05-29 16:31:02', 'Testando conseguiremos', 2, 0, 0);
INSERT INTO user_conversations (user_id, user_id2, created_date, text, id, user_saw, user2_saw) VALUES (1, 4, '2017-05-29 16:33:00', 'teste teste', 3, 0, 0);
INSERT INTO user_conversations (user_id, user_id2, created_date, text, id, user_saw, user2_saw) VALUES (4, 1, '2017-05-29 16:38:00', 'Outro teste , agora vai funcionar tudo hein', 4, 0, 0);
INSERT INTO user_conversations (user_id, user_id2, created_date, text, id, user_saw, user2_saw) VALUES (1, 4, '2017-05-29 16:45:00', 'Teste', 5, 0, 0);
INSERT INTO user_conversations (user_id, user_id2, created_date, text, id, user_saw, user2_saw) VALUES (1, 4, '2017-05-28 16:30:00', 'Teste de conversa', 1, 0, 0);
INSERT INTO user_conversations (user_id, user_id2, created_date, text, id, user_saw, user2_saw) VALUES (1, 4, '2017-05-29 19:09:33', 'Oi João tudo bem?', 6, 0, 0);
INSERT INTO user_conversations (user_id, user_id2, created_date, text, id, user_saw, user2_saw) VALUES (1, 4, '2017-05-29 19:13:43', 'Como estão as coisas?', 7, 0, 0);
INSERT INTO user_conversations (user_id, user_id2, created_date, text, id, user_saw, user2_saw) VALUES (4, 1, '2017-05-29 19:15:43', 'Tudo bem e ai?', 8, 0, 0);
INSERT INTO user_conversations (user_id, user_id2, created_date, text, id, user_saw, user2_saw) VALUES (1, 4, '2017-05-29 19:42:02', 'E ai rapaz????', 9, 0, 0);
INSERT INTO user_conversations (user_id, user_id2, created_date, text, id, user_saw, user2_saw) VALUES (4, 1, '2017-05-29 19:42:39', 'Eu estou bem e vc?', 10, 0, 0);
INSERT INTO user_conversations (user_id, user_id2, created_date, text, id, user_saw, user2_saw) VALUES (2, 1, '2017-05-29 19:47:34', 'Oi Marcus tudo bem?', 11, 0, 0);
INSERT INTO user_conversations (user_id, user_id2, created_date, text, id, user_saw, user2_saw) VALUES (1, 2, '2017-05-29 19:47:52', 'E vc?', 12, 0, 0);
INSERT INTO user_conversations (user_id, user_id2, created_date, text, id, user_saw, user2_saw) VALUES (1, 2, '2017-05-29 19:48:16', 'Seu bolo!', 13, 0, 0);
INSERT INTO user_conversations (user_id, user_id2, created_date, text, id, user_saw, user2_saw) VALUES (2, 1, '2017-05-29 19:48:16', 'Qual é a boa do bicicletário?!', 14, 0, 0);
INSERT INTO user_conversations (user_id, user_id2, created_date, text, id, user_saw, user2_saw) VALUES (2, 1, '2017-05-29 19:48:25', 'Eu tô boa', 15, 0, 0);
INSERT INTO user_conversations (user_id, user_id2, created_date, text, id, user_saw, user2_saw) VALUES (1, 2, '2017-05-29 19:48:38', 'Tu é terrível', 16, 0, 0);
INSERT INTO user_conversations (user_id, user_id2, created_date, text, id, user_saw, user2_saw) VALUES (2, 1, '2017-05-29 19:48:40', 'Vamos de bike pra CG', 17, 0, 0);
INSERT INTO user_conversations (user_id, user_id2, created_date, text, id, user_saw, user2_saw) VALUES (2, 1, '2017-05-29 19:48:48', '??', 18, 0, 0);
INSERT INTO user_conversations (user_id, user_id2, created_date, text, id, user_saw, user2_saw) VALUES (1, 2, '2017-05-29 19:48:53', 'kkkk', 19, 0, 0);
INSERT INTO user_conversations (user_id, user_id2, created_date, text, id, user_saw, user2_saw) VALUES (2, 1, '2017-05-29 19:49:12', 'Vamos logo 
Ainda tenho que passar no veterinário', 20, 0, 0);
INSERT INTO user_conversations (user_id, user_id2, created_date, text, id, user_saw, user2_saw) VALUES (4, 1, '2017-05-30 05:10:14', 'teste', 21, 0, 0);
INSERT INTO user_conversations (user_id, user_id2, created_date, text, id, user_saw, user2_saw) VALUES (4, 2, '2017-05-30 05:14:52', 'Oi Thaty', 22, 0, 0);
INSERT INTO user_conversations (user_id, user_id2, created_date, text, id, user_saw, user2_saw) VALUES (1, 4, '2017-05-30 05:42:00', 'Oi eu vou ai mais tarde', 23, 0, 0);
INSERT INTO user_conversations (user_id, user_id2, created_date, text, id, user_saw, user2_saw) VALUES (1, 4, '2017-05-30 05:42:42', 'Oi eu vou ai mais tarde', 24, 0, 0);
INSERT INTO user_conversations (user_id, user_id2, created_date, text, id, user_saw, user2_saw) VALUES (1, 4, '2017-05-30 05:43:27', 'Vou ai ok?', 25, 0, 0);
INSERT INTO user_conversations (user_id, user_id2, created_date, text, id, user_saw, user2_saw) VALUES (1, 4, '2017-05-30 05:47:27', 'Olá!', 26, 0, 0);
INSERT INTO user_conversations (user_id, user_id2, created_date, text, id, user_saw, user2_saw) VALUES (1, 4, '2017-05-30 05:47:43', 'Vai jogar hj?', 27, 0, 0);
INSERT INTO user_conversations (user_id, user_id2, created_date, text, id, user_saw, user2_saw) VALUES (4, 1, '2017-05-30 06:41:56', 'Tudo bem marcus e vc?', 28, 0, 0);
INSERT INTO user_conversations (user_id, user_id2, created_date, text, id, user_saw, user2_saw) VALUES (4, 1, '2017-05-30 06:42:08', 'Espero que esteja tudo bem ai..', 29, 0, 0);
INSERT INTO user_conversations (user_id, user_id2, created_date, text, id, user_saw, user2_saw) VALUES (4, 1, '2017-05-30 06:42:22', 'Eu vou tentar aparecer mais ok.', 30, 0, 0);
INSERT INTO user_conversations (user_id, user_id2, created_date, text, id, user_saw, user2_saw) VALUES (4, 1, '2017-05-30 06:43:06', 'Teste', 31, 0, 0);
INSERT INTO user_conversations (user_id, user_id2, created_date, text, id, user_saw, user2_saw) VALUES (4, 1, '2017-05-30 06:44:58', 'Tudo bem marcus.', 32, 0, 0);
INSERT INTO user_conversations (user_id, user_id2, created_date, text, id, user_saw, user2_saw) VALUES (4, 1, '2017-05-30 06:45:06', 'Segunda eu vou ai', 33, 0, 0);
INSERT INTO user_conversations (user_id, user_id2, created_date, text, id, user_saw, user2_saw) VALUES (2, 4, '2017-05-30 06:52:10', 'Fala João!', 34, 0, 0);
INSERT INTO user_conversations (user_id, user_id2, created_date, text, id, user_saw, user2_saw) VALUES (4, 2, '2017-05-30 06:56:48', 'Tudo bem thtay. Obrigado!', 35, 0, 0);
INSERT INTO user_conversations (user_id, user_id2, created_date, text, id, user_saw, user2_saw) VALUES (2, 4, '2017-05-30 06:59:55', 'João estou bem obrigado!
E você como está?', 36, 0, 0);
INSERT INTO user_conversations (user_id, user_id2, created_date, text, id, user_saw, user2_saw) VALUES (2, 4, '2017-05-30 07:01:36', 'Estou testando ok', 37, 0, 0);
INSERT INTO user_conversations (user_id, user_id2, created_date, text, id, user_saw, user2_saw) VALUES (2, 4, '2017-05-30 07:02:01', 'beleza', 38, 0, 0);
INSERT INTO user_conversations (user_id, user_id2, created_date, text, id, user_saw, user2_saw) VALUES (2, 4, '2017-05-30 07:02:09', 'depois eu vejo isso', 39, 0, 0);
INSERT INTO user_conversations (user_id, user_id2, created_date, text, id, user_saw, user2_saw) VALUES (4, 2, '2017-05-30 07:06:12', 'Oi Thaty', 40, 0, 0);
INSERT INTO user_conversations (user_id, user_id2, created_date, text, id, user_saw, user2_saw) VALUES (2, 4, '2017-05-30 07:06:45', 'Tudo bem com vc?', 41, 0, 0);
INSERT INTO user_conversations (user_id, user_id2, created_date, text, id, user_saw, user2_saw) VALUES (2, 4, '2017-05-30 07:07:25', 'oque aconteceu', 42, 0, 0);
INSERT INTO user_conversations (user_id, user_id2, created_date, text, id, user_saw, user2_saw) VALUES (2, 4, '2017-05-30 07:07:41', 'sei lá', 43, 0, 0);
INSERT INTO user_conversations (user_id, user_id2, created_date, text, id, user_saw, user2_saw) VALUES (2, 4, '2017-05-30 07:09:24', 'kkkk', 44, 0, 0);
INSERT INTO user_conversations (user_id, user_id2, created_date, text, id, user_saw, user2_saw) VALUES (2, 4, '2017-05-30 07:09:29', 'Até parece', 45, 0, 0);
INSERT INTO user_conversations (user_id, user_id2, created_date, text, id, user_saw, user2_saw) VALUES (1, 4, '2017-05-30 07:35:12', 'valeu', 46, 0, 0);
INSERT INTO user_conversations (user_id, user_id2, created_date, text, id, user_saw, user2_saw) VALUES (1, 2, '2017-05-30 08:00:56', 'Olá', 47, 0, 0);
INSERT INTO user_conversations (user_id, user_id2, created_date, text, id, user_saw, user2_saw) VALUES (1, 2, '2017-05-30 08:01:08', 'como vai vc?', 48, 0, 0);
INSERT INTO user_conversations (user_id, user_id2, created_date, text, id, user_saw, user2_saw) VALUES (1, 2, '2017-05-30 08:01:47', ':)', 49, 0, 0);
INSERT INTO user_conversations (user_id, user_id2, created_date, text, id, user_saw, user2_saw) VALUES (2, 1, '2017-05-30 08:02:44', 'totnery baby', 50, 0, 0);
INSERT INTO user_conversations (user_id, user_id2, created_date, text, id, user_saw, user2_saw) VALUES (1, 4, '2017-06-07 14:55:06', 'Olá ?', 51, 0, 0);
INSERT INTO user_conversations (user_id, user_id2, created_date, text, id, user_saw, user2_saw) VALUES (1, 2, '2017-06-10 19:23:31', 'Thaty depois me liga. Ok?', 52, 0, 0);
INSERT INTO user_conversations (user_id, user_id2, created_date, text, id, user_saw, user2_saw) VALUES (1, 4, '2017-06-10 19:39:26', 'Ei joão vamos pedalar?', 53, 0, 0);
INSERT INTO user_conversations (user_id, user_id2, created_date, text, id, user_saw, user2_saw) VALUES (1, 2, '2017-06-10 21:53:45', 'Vamos segunda para a Tijuca?', 54, 0, 0);
INSERT INTO user_conversations (user_id, user_id2, created_date, text, id, user_saw, user2_saw) VALUES (2, 1, '2017-06-10 21:54:10', 'Sim claro!!!', 55, 0, 0);


--
-- TOC entry 4285 (class 0 OID 45063)
-- Dependencies: 301
-- Data for Name: user_feedings; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO user_feedings (id, user_id, user_sharing_id, text, likes, created_date, updated_date, view_level_id, dislikes) VALUES (8, 1, 8, 'Teste real 2', NULL, '2017-06-05 12:12:45', NULL, NULL, NULL);
INSERT INTO user_feedings (id, user_id, user_sharing_id, text, likes, created_date, updated_date, view_level_id, dislikes) VALUES (9, 1, 9, 'Teste 3', NULL, '2017-06-05 12:29:07', NULL, NULL, NULL);
INSERT INTO user_feedings (id, user_id, user_sharing_id, text, likes, created_date, updated_date, view_level_id, dislikes) VALUES (12, 1, 12, NULL, NULL, '2017-06-06 11:13:43', NULL, NULL, NULL);
INSERT INTO user_feedings (id, user_id, user_sharing_id, text, likes, created_date, updated_date, view_level_id, dislikes) VALUES (13, 1, 13, NULL, NULL, '2017-06-06 11:20:18', NULL, NULL, NULL);
INSERT INTO user_feedings (id, user_id, user_sharing_id, text, likes, created_date, updated_date, view_level_id, dislikes) VALUES (14, 2, 14, NULL, NULL, '2017-06-06 11:35:45', NULL, NULL, NULL);
INSERT INTO user_feedings (id, user_id, user_sharing_id, text, likes, created_date, updated_date, view_level_id, dislikes) VALUES (15, 1, 15, NULL, NULL, '2017-06-07 21:01:51', NULL, NULL, NULL);
INSERT INTO user_feedings (id, user_id, user_sharing_id, text, likes, created_date, updated_date, view_level_id, dislikes) VALUES (16, 1, 16, NULL, NULL, '2017-06-07 23:00:00', NULL, NULL, NULL);
INSERT INTO user_feedings (id, user_id, user_sharing_id, text, likes, created_date, updated_date, view_level_id, dislikes) VALUES (17, 1, 17, 'Uma pedalada rápida, para testar o Bike Social', NULL, '2017-06-08 16:22:42', NULL, NULL, NULL);
INSERT INTO user_feedings (id, user_id, user_sharing_id, text, likes, created_date, updated_date, view_level_id, dislikes) VALUES (18, 1, 18, 'Essa pedala foi boa pessoal! Recomendo.', NULL, '2017-06-10 12:09:54', NULL, NULL, NULL);
INSERT INTO user_feedings (id, user_id, user_sharing_id, text, likes, created_date, updated_date, view_level_id, dislikes) VALUES (19, 1, 19, NULL, NULL, '2017-06-10 13:32:55', NULL, NULL, NULL);
INSERT INTO user_feedings (id, user_id, user_sharing_id, text, likes, created_date, updated_date, view_level_id, dislikes) VALUES (20, 1, 20, NULL, NULL, '2017-06-10 14:46:22', NULL, NULL, NULL);
INSERT INTO user_feedings (id, user_id, user_sharing_id, text, likes, created_date, updated_date, view_level_id, dislikes) VALUES (21, 1, 21, NULL, NULL, '2017-06-10 20:20:33', NULL, NULL, NULL);
INSERT INTO user_feedings (id, user_id, user_sharing_id, text, likes, created_date, updated_date, view_level_id, dislikes) VALUES (22, 1, 22, NULL, NULL, '2017-06-10 20:44:04', NULL, NULL, NULL);
INSERT INTO user_feedings (id, user_id, user_sharing_id, text, likes, created_date, updated_date, view_level_id, dislikes) VALUES (23, 1, 23, NULL, NULL, '2017-06-10 20:48:11', NULL, NULL, NULL);
INSERT INTO user_feedings (id, user_id, user_sharing_id, text, likes, created_date, updated_date, view_level_id, dislikes) VALUES (24, 1, 24, NULL, NULL, '2017-06-10 21:01:03', NULL, NULL, NULL);
INSERT INTO user_feedings (id, user_id, user_sharing_id, text, likes, created_date, updated_date, view_level_id, dislikes) VALUES (25, 1, 25, NULL, NULL, '2017-06-10 21:09:00', NULL, NULL, NULL);
INSERT INTO user_feedings (id, user_id, user_sharing_id, text, likes, created_date, updated_date, view_level_id, dislikes) VALUES (26, 1, 26, NULL, NULL, '2017-06-10 21:19:26', NULL, NULL, NULL);
INSERT INTO user_feedings (id, user_id, user_sharing_id, text, likes, created_date, updated_date, view_level_id, dislikes) VALUES (27, 1, 27, NULL, NULL, '2017-06-10 21:27:23', NULL, NULL, NULL);
INSERT INTO user_feedings (id, user_id, user_sharing_id, text, likes, created_date, updated_date, view_level_id, dislikes) VALUES (28, 1, 28, NULL, NULL, '2017-06-10 21:32:55', NULL, NULL, NULL);
INSERT INTO user_feedings (id, user_id, user_sharing_id, text, likes, created_date, updated_date, view_level_id, dislikes) VALUES (29, 1, 29, NULL, NULL, '2017-06-10 21:56:14', NULL, NULL, NULL);


--
-- TOC entry 4394 (class 0 OID 0)
-- Dependencies: 300
-- Name: user_feedings_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('user_feedings_id_seq', 29, true);


--
-- TOC entry 4281 (class 0 OID 36857)
-- Dependencies: 295
-- Data for Name: user_friendship_requests; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 4395 (class 0 OID 0)
-- Dependencies: 294
-- Name: user_friendship_requests_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('user_friendship_requests_id_seq', 48, true);


--
-- TOC entry 4279 (class 0 OID 36850)
-- Dependencies: 293
-- Data for Name: user_friendships; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO user_friendships (user_id, friend_user_id, created_date) VALUES (4, 2, '2017-04-11 06:35:55');
INSERT INTO user_friendships (user_id, friend_user_id, created_date) VALUES (1, 2, '2017-04-11 07:25:03');
INSERT INTO user_friendships (user_id, friend_user_id, created_date) VALUES (1, 4, '2017-05-25 05:12:15');


--
-- TOC entry 4396 (class 0 OID 0)
-- Dependencies: 208
-- Name: user_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('user_id_seq', 5, true);


--
-- TOC entry 4299 (class 0 OID 45351)
-- Dependencies: 318
-- Data for Name: user_navigation_routes; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO user_navigation_routes (id, origin_geom, destination_geom, line_string_geom, user_id, duration, distance, origin_address, destination_address) VALUES (559, '0101000020110F0000AE635C7171D845C0A14CA3C9C5E836C0', '0101000020110F0000CB48BDA772D845C0C23577F4BFE836C0', '0102000020110F000001000000CB48BDA772D845C0C23577F4BFE836C0', 1, 20, 0, 'Rua Tenente Antônio Batista Segundo', 'Rua Ulisses Guimarães');
INSERT INTO user_navigation_routes (id, origin_geom, destination_geom, line_string_geom, user_id, duration, distance, origin_address, destination_address) VALUES (548, '0101000020110F000061C614AC71D845C0B98B3045B9E836C0', '0101000020110F0000763579CA6AD845C0696FF085C9E836C0', '0102000020110F0000270000007BB6D4626CD845C0472ECDE7BBE836C0D43BC3716CD845C0A66418FBBBE836C09FB237846CD845C0DBF5353FBCE836C01C9F24B86CD845C08EDA928FBCE836C0A5D278B76CD845C034336DDEBCE836C04BBC6ED76CD845C070CDC243BDE836C0C28C63036DD845C081BEA1AEBDE836C0873FBB2A6DD845C03F5D9324BEE836C0981548586DD845C0103964A0BEE836C081ADF98E6DD845C0921F123ABFE836C0DA3631C96DD845C0AAC596D4BFE836C0CEB2401E6ED845C0326533C9C0E836C015FF98526ED845C0A9678344C1E836C0C7CCEC906ED845C0F6EA25BEC1E836C086BB27CD6ED845C020CA4033C2E836C026261C386FD845C02BE0F9A0C2E836C08B52DF856FD845C0D2E0F8D2C2E836C0F642BFBE6FD845C0D20AC2CBC2E836C0858EC29A6FD845C0B439CE6DC2E836C020BADA696FD845C05BE2F182C2E836C091A717426FD845C06DA1759EC2E836C0CD0780016FD845C013CC3DC9C2E836C0807566CC6ED845C0907758F2C2E836C0C70DBF9B6ED845C0D290AF0CC3E836C0273C78586ED845C0E4A3C519C3E836C021EE24016ED845C049115B72C3E836C0DA1796606DD845C02BD67802C4E836C0E60A6B3A6DD845C030615539C4E836C0E0C0600E6DD845C0C5C407F2C3E836C06FB488CD6CD845C001FBA6B8C3E836C0458524926CD845C0D2ECD354C3E836C0CFDEF85E6CD845C02BC65455C3E836C087FD3B2E6CD845C084CDE779C3E836C087774EED6BD845C078F11BB2C3E836C05EF00E956BD845C00D0585A4C3E836C0760082416BD845C019ED2BEEC3E836C0D5ED68F36AD845C00DC11660C4E836C011FA3EC16AD845C0892031EEC4E836C0763579CA6AD845C0696FF085C9E836C0', 1, 68, 42.3353233, 'Rua Tenente Antonio Batista Segundo', 'Rua Tenente Antonio Batista Segundo');
INSERT INTO user_navigation_routes (id, origin_geom, destination_geom, line_string_geom, user_id, duration, distance, origin_address, destination_address) VALUES (546, '0101000020110F0000E3A430EF71D845C0ACA92C0ABBE836C0', '0101000020110F00008192020B60D845C0DF6B088ECBE836C0', '0102000020110F000027000000802F9EA46DD845C0DB617E34BDE836C02D9CDEA46DD845C01CA59E47BDE836C00FD7C5C86DD845C0E649257EBDE836C0681F2BF86DD845C08D46DB84BDE836C0CE716E136ED845C03F371357BEE836C0DF5ABB276ED845C0CEFDD5E3BEE836C086295F0A6ED845C02D163380BFE836C06F4A9ABB6DD845C03F31DBE3BFE836C0B02D4D5B6DD845C0207CE6B4C0E836C02D62F1FE6CD845C07FC25575C1E836C0C81E259F6CD845C0EAE8D928C2E836C028DEF92C6CD845C0BAF2BCC8C2E836C0B7CDD8C06BD845C08A50325AC3E836C0D5958DD66AD845C06CA718ADC4E836C082284EA46AD845C0CBBB2C1EC5E836C0DBA5AA5C6AD845C0188F185EC5E836C05F74A2F269D845C0002B13C0C5E836C03B59A46869D845C00CE9F010C6E836C0A74C20DB68D845C0E8C60D22C6E836C018991D3568D845C08966F915C6E836C02A4CC6CE67D845C08F49B169C6E836C0E9ED537E67D845C0E225D5CAC6E836C00DCB1B3767D845C0D6519B59C7E836C066DDDCEB66D845C082BB3F20C8E836C0BA92D38D66D845C0287487E2C8E836C07218693566D845C02E31BF68C9E836C0900302DF65D845C0515B34AEC9E836C07F292C8665D845C02233BCFEC9E836C00E5ADD2465D845C06901B948CAE836C0EA83FAD064D845C0B0234884CAE836C03E0F287A64D845C021EF4DBACAE836C06D37A02964D845C02DD3ABD8CAE836C0DFDFC1CB63D845C08C0D4017CBE836C0CD2FB56B63D845C0CE7A2923CBE836C05056881E63D845C009E32339CBE836C00342CAD362D845C074F1F1E8CAE836C0B0FE539A62D845C0AA1A1063CAE836C0B66A956862D845C022ADCEBDC9E836C08192020B60D845C0DF6B088ECBE836C0', 1, 63, 57.2692184, 'Rua Tenente Antonio Batista Segundo', 'Rua Tenente Antonio Batista Segundo');
INSERT INTO user_navigation_routes (id, origin_geom, destination_geom, line_string_geom, user_id, duration, distance, origin_address, destination_address) VALUES (603, '0101000020110F00007A56D28A6FD845C0ACC612D6C6E836C0', '0101000020110F0000EBFD463B6ED845C0946A9F8EC7E836C0', '0102000020110F000001000000EBFD463B6ED845C0946A9F8EC7E836C0', 1, 11, 0, 'Rua Tenente Antônio Batista Segundo', 'Rua Tenente Antônio Batista Segundo');


--
-- TOC entry 4397 (class 0 OID 0)
-- Dependencies: 317
-- Name: user_navigation_routes_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('user_navigation_routes_id_seq', 626, true);


--
-- TOC entry 4289 (class 0 OID 45093)
-- Dependencies: 305
-- Data for Name: user_sharing_types; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO user_sharing_types (id, name) VALUES (2, 'bike keeper');
INSERT INTO user_sharing_types (id, name) VALUES (1, 'alert');
INSERT INTO user_sharing_types (id, name) VALUES (3, 'route');


--
-- TOC entry 4398 (class 0 OID 0)
-- Dependencies: 304
-- Name: user_sharing_types_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('user_sharing_types_id_seq', 3, true);


--
-- TOC entry 4283 (class 0 OID 45054)
-- Dependencies: 299
-- Data for Name: user_sharings; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO user_sharings (id, user_id, user_feeding_id, view_level_id, content_id, created_date, updated_date, sharing_type_id) VALUES (8, 1, 8, NULL, 546, '2017-06-05 12:12:45', NULL, 3);
INSERT INTO user_sharings (id, user_id, user_feeding_id, view_level_id, content_id, created_date, updated_date, sharing_type_id) VALUES (9, 1, 9, NULL, 548, '2017-06-05 12:29:07', NULL, 3);
INSERT INTO user_sharings (id, user_id, user_feeding_id, view_level_id, content_id, created_date, updated_date, sharing_type_id) VALUES (12, 1, 12, NULL, 99, '2017-06-06 11:13:43', NULL, 1);
INSERT INTO user_sharings (id, user_id, user_feeding_id, view_level_id, content_id, created_date, updated_date, sharing_type_id) VALUES (13, 1, 13, NULL, 100, '2017-06-06 11:20:18', NULL, 1);
INSERT INTO user_sharings (id, user_id, user_feeding_id, view_level_id, content_id, created_date, updated_date, sharing_type_id) VALUES (14, 2, 14, NULL, 41, '2017-06-06 11:35:45', NULL, 2);
INSERT INTO user_sharings (id, user_id, user_feeding_id, view_level_id, content_id, created_date, updated_date, sharing_type_id) VALUES (15, 1, 15, NULL, 101, '2017-06-07 21:01:51', NULL, 1);
INSERT INTO user_sharings (id, user_id, user_feeding_id, view_level_id, content_id, created_date, updated_date, sharing_type_id) VALUES (16, 1, NULL, NULL, 102, '2017-06-08 00:14:22', NULL, 1);
INSERT INTO user_sharings (id, user_id, user_feeding_id, view_level_id, content_id, created_date, updated_date, sharing_type_id) VALUES (17, 1, 17, NULL, 559, '2017-06-08 16:22:42', NULL, 3);
INSERT INTO user_sharings (id, user_id, user_feeding_id, view_level_id, content_id, created_date, updated_date, sharing_type_id) VALUES (18, 1, 18, NULL, 603, '2017-06-10 12:09:54', NULL, 3);
INSERT INTO user_sharings (id, user_id, user_feeding_id, view_level_id, content_id, created_date, updated_date, sharing_type_id) VALUES (19, 1, 19, NULL, 103, '2017-06-10 13:32:55', NULL, 1);
INSERT INTO user_sharings (id, user_id, user_feeding_id, view_level_id, content_id, created_date, updated_date, sharing_type_id) VALUES (20, 1, 20, NULL, 104, '2017-06-10 14:46:22', NULL, 1);
INSERT INTO user_sharings (id, user_id, user_feeding_id, view_level_id, content_id, created_date, updated_date, sharing_type_id) VALUES (21, 1, 21, NULL, 105, '2017-06-10 20:20:33', NULL, 1);
INSERT INTO user_sharings (id, user_id, user_feeding_id, view_level_id, content_id, created_date, updated_date, sharing_type_id) VALUES (22, 1, 22, NULL, 106, '2017-06-10 20:44:04', NULL, 1);
INSERT INTO user_sharings (id, user_id, user_feeding_id, view_level_id, content_id, created_date, updated_date, sharing_type_id) VALUES (23, 1, 23, NULL, 107, '2017-06-10 20:48:11', NULL, 1);
INSERT INTO user_sharings (id, user_id, user_feeding_id, view_level_id, content_id, created_date, updated_date, sharing_type_id) VALUES (24, 1, 24, NULL, 42, '2017-06-10 21:01:03', NULL, 2);
INSERT INTO user_sharings (id, user_id, user_feeding_id, view_level_id, content_id, created_date, updated_date, sharing_type_id) VALUES (25, 1, 25, NULL, 43, '2017-06-10 21:09:00', NULL, 2);
INSERT INTO user_sharings (id, user_id, user_feeding_id, view_level_id, content_id, created_date, updated_date, sharing_type_id) VALUES (26, 1, 26, NULL, 44, '2017-06-10 21:19:26', NULL, 2);
INSERT INTO user_sharings (id, user_id, user_feeding_id, view_level_id, content_id, created_date, updated_date, sharing_type_id) VALUES (27, 1, 27, NULL, 45, '2017-06-10 21:27:22', NULL, 2);
INSERT INTO user_sharings (id, user_id, user_feeding_id, view_level_id, content_id, created_date, updated_date, sharing_type_id) VALUES (28, 1, 28, NULL, 108, '2017-06-10 21:32:55', NULL, 1);
INSERT INTO user_sharings (id, user_id, user_feeding_id, view_level_id, content_id, created_date, updated_date, sharing_type_id) VALUES (29, 1, 29, NULL, 109, '2017-06-10 21:56:14', NULL, 1);


--
-- TOC entry 4399 (class 0 OID 0)
-- Dependencies: 298
-- Name: user_sharings_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('user_sharings_id_seq', 29, true);


--
-- TOC entry 4400 (class 0 OID 0)
-- Dependencies: 278
-- Name: user_tracking_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('user_tracking_id_seq', 1, false);


--
-- TOC entry 4265 (class 0 OID 28394)
-- Dependencies: 279
-- Data for Name: user_trackings; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 4255 (class 0 OID 20012)
-- Dependencies: 207
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO users (id, first_name, last_name, how_to_be_called, username, email, password, signup_date, last_access_date, auth_key, access_token, home_dir_name, question, answer, full_name, pharse) VALUES (3, 'Default', 'Default', 'Default', 'default', 'default@hotmail.com', '123456', '2016-11-20 05:07:14.755831', NULL, 'MFKZdmbnLVXRN3JJqCQVs5BRk-2IsoQE', NULL, '41378f6ece1dbf69d8eebe741a33c11d', 'Primeiro nome do seu criador?', 'marcus', 'Default Default', NULL);
INSERT INTO users (id, first_name, last_name, how_to_be_called, username, email, password, signup_date, last_access_date, auth_key, access_token, home_dir_name, question, answer, full_name, pharse) VALUES (1, 'Marcus Vinicius', 'Faccion', 'Marcus', 'marcusfaccion', 'marcusfaccion@bol.com.br', '123456', '2016-04-13 11:00:00', '2017-06-10 19:42:40', 'hEhA7gNNCs8NylQ28bjtVpJyb1hqFx1P', NULL, 'a99aa5e1912ac0ee7d0b2f8ce3e272ee', 'Primeiro nome da esposa?', 'thatiane', 'Marcus Vinicius Faccion', 'Pedalo para me manter em equilibrio!
');
INSERT INTO users (id, first_name, last_name, how_to_be_called, username, email, password, signup_date, last_access_date, auth_key, access_token, home_dir_name, question, answer, full_name, pharse) VALUES (2, 'Thatiane', 'Copque', 'Thaty', 'thatiane', 'thatiane_copque@hotmail.com', '123456', '2016-11-20 04:38:59.73246', '2017-06-10 19:43:26', 'fHAyDZexEGjxh4RvCbHkd4fIP6OQPJWE', NULL, '9e459de99798f751a83cab6667d83491', 'Primeiro nome do esposo?', 'marcus', 'Thatiane Copque', '');
INSERT INTO users (id, first_name, last_name, how_to_be_called, username, email, password, signup_date, last_access_date, auth_key, access_token, home_dir_name, question, answer, full_name, pharse) VALUES (0, 'Bike', 'Social', 'BikeSocial', 'bikesocial', 'marcusfaccion2@bol.com.br', 'bikesocial', '2016-11-03 13:44:18.824322', '2017-04-11 04:28:56', NULL, NULL, '856399845ab74597eda9777f091b277a', NULL, NULL, 'Bike Social', NULL);
INSERT INTO users (id, first_name, last_name, how_to_be_called, username, email, password, signup_date, last_access_date, auth_key, access_token, home_dir_name, question, answer, full_name, pharse) VALUES (4, 'João Marcus', 'da Silva Gomes', 'João', 'joaocarlos', 'joaocarlos_1544812120@bol.com.br', '123456', '2017-04-09 22:38:53', '2017-05-31 16:42:55', '03mQZnINC6GUt4Bro2j27UzHC0196IOl', NULL, '34bbc48a4dd10814f2a31e82ecd9f214', 'teste', 'teste', 'João Marcus da Silva Gomes', NULL);
INSERT INTO users (id, first_name, last_name, how_to_be_called, username, email, password, signup_date, last_access_date, auth_key, access_token, home_dir_name, question, answer, full_name, pharse) VALUES (5, 'Aline', 'Silva', 'Aline', 'aline', 'tt@ss.com.br', '123456789', '2017-06-01 04:49:21', NULL, 'WDnRrmr1KeWyFyd8Ll3kC8lElELoqEMl', NULL, '254a2abaef9dacb844691d577b1c5356', 'teste', 'teste', 'Aline Silva', NULL);


--
-- TOC entry 4287 (class 0 OID 45074)
-- Dependencies: 303
-- Data for Name: view_levels; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 4401 (class 0 OID 0)
-- Dependencies: 302
-- Name: view_levels_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('view_levels_id_seq', 1, false);


--
-- TOC entry 4070 (class 2606 OID 45265)
-- Name: alert_comments_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY alert_comments
    ADD CONSTRAINT alert_comments_pk PRIMARY KEY (id);


--
-- TOC entry 4022 (class 2606 OID 28348)
-- Name: alert_types_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY alert_types
    ADD CONSTRAINT alert_types_pk PRIMARY KEY (id);


--
-- TOC entry 4027 (class 2606 OID 28372)
-- Name: alert_types_spatial_types_ManyMany_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY alert_types_spatial_types
    ADD CONSTRAINT "alert_types_spatial_types_ManyMany_pk" PRIMARY KEY (alert_types_id, spatial_types_id);


--
-- TOC entry 4025 (class 2606 OID 28355)
-- Name: alert_types_spatial_types_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY spatial_types
    ADD CONSTRAINT alert_types_spatial_types_pk PRIMARY KEY (id);


--
-- TOC entry 4018 (class 2606 OID 22524)
-- Name: alerts_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY alerts
    ADD CONSTRAINT alerts_pk PRIMARY KEY (id);


--
-- TOC entry 4072 (class 2606 OID 45291)
-- Name: bike_keeper_comments_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY bike_keeper_comments
    ADD CONSTRAINT bike_keeper_comments_pk PRIMARY KEY (id);


--
-- TOC entry 4041 (class 2606 OID 28519)
-- Name: bike_keepers_multimedias_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY bike_keepers_multimedias
    ADD CONSTRAINT bike_keepers_multimedias_pk PRIMARY KEY (bike_keepers_id, multimedias_id);


--
-- TOC entry 4033 (class 2606 OID 28440)
-- Name: bike_keepers_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY bike_keepers
    ADD CONSTRAINT bike_keepers_pk PRIMARY KEY (id);


--
-- TOC entry 4043 (class 2606 OID 28609)
-- Name: events_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY events
    ADD CONSTRAINT events_pk PRIMARY KEY (id);


--
-- TOC entry 4039 (class 2606 OID 28485)
-- Name: galleries_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY galleries
    ADD CONSTRAINT galleries_pk PRIMARY KEY (id);


--
-- TOC entry 4037 (class 2606 OID 28467)
-- Name: multimedia_types_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY multimedia_types
    ADD CONSTRAINT multimedia_types_pk PRIMARY KEY (id);


--
-- TOC entry 4035 (class 2606 OID 28456)
-- Name: multimedias_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY multimedias
    ADD CONSTRAINT multimedias_pk PRIMARY KEY (id);


--
-- TOC entry 4085 (class 2606 OID 45520)
-- Name: online_users_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY online_users
    ADD CONSTRAINT online_users_pk PRIMARY KEY (user_id);


--
-- TOC entry 4015 (class 2606 OID 20020)
-- Name: pk_user; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY users
    ADD CONSTRAINT pk_user PRIMARY KEY (id);


--
-- TOC entry 4046 (class 2606 OID 28627)
-- Name: routes_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY routes
    ADD CONSTRAINT routes_pk PRIMARY KEY (id);


--
-- TOC entry 4068 (class 2606 OID 45176)
-- Name: user_alert_existence_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY user_alert_nonexistence
    ADD CONSTRAINT user_alert_existence_pk PRIMARY KEY (user_id, alert_id);


--
-- TOC entry 4066 (class 2606 OID 45162)
-- Name: user_alert_rates_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY user_alert_rates
    ADD CONSTRAINT user_alert_rates_pk PRIMARY KEY (user_id, alert_id);


--
-- TOC entry 4076 (class 2606 OID 45321)
-- Name: user_bike_keeper_existence_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY user_bike_keeper_nonexistence
    ADD CONSTRAINT user_bike_keeper_existence_pk PRIMARY KEY (user_id, bike_keeper_id);


--
-- TOC entry 4074 (class 2606 OID 45306)
-- Name: user_bike_keeper_rates_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY user_bike_keeper_rates
    ADD CONSTRAINT user_bike_keeper_rates_pk PRIMARY KEY (user_id, bike_keeper_id);


--
-- TOC entry 4087 (class 2606 OID 45533)
-- Name: user_conversation_alerts_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY user_conversation_alerts
    ADD CONSTRAINT user_conversation_alerts_pk PRIMARY KEY (id);


--
-- TOC entry 4083 (class 2606 OID 45504)
-- Name: user_conversations_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY user_conversations
    ADD CONSTRAINT user_conversations_pk PRIMARY KEY (id);


--
-- TOC entry 4060 (class 2606 OID 45071)
-- Name: user_feedings_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY user_feedings
    ADD CONSTRAINT user_feedings_pk PRIMARY KEY (id);


--
-- TOC entry 4050 (class 2606 OID 36862)
-- Name: user_friendship_requests_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY user_friendship_requests
    ADD CONSTRAINT user_friendship_requests_pkey PRIMARY KEY (id);


--
-- TOC entry 4048 (class 2606 OID 36854)
-- Name: user_friendships_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY user_friendships
    ADD CONSTRAINT user_friendships_pk PRIMARY KEY (user_id, friend_user_id);


--
-- TOC entry 4081 (class 2606 OID 45372)
-- Name: user_navigation_routes_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY user_navigation_routes
    ADD CONSTRAINT user_navigation_routes_pk PRIMARY KEY (id);


--
-- TOC entry 4064 (class 2606 OID 45098)
-- Name: user_sharing_types_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY user_sharing_types
    ADD CONSTRAINT user_sharing_types_pk PRIMARY KEY (id);


--
-- TOC entry 4055 (class 2606 OID 45059)
-- Name: user_sharings_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY user_sharings
    ADD CONSTRAINT user_sharings_pk PRIMARY KEY (id);


--
-- TOC entry 4030 (class 2606 OID 28399)
-- Name: user_trackings_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY user_trackings
    ADD CONSTRAINT user_trackings_pk PRIMARY KEY (id);


--
-- TOC entry 4062 (class 2606 OID 45079)
-- Name: view_levels_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY view_levels
    ADD CONSTRAINT view_levels_pk PRIMARY KEY (id);


--
-- TOC entry 4016 (class 1259 OID 28373)
-- Name: alerts_geom_idx; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX alerts_geom_idx ON alerts USING gist (geom);


--
-- TOC entry 4031 (class 1259 OID 28647)
-- Name: bike_keepers_geom_idx; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX bike_keepers_geom_idx ON bike_keepers USING gist (geom);


--
-- TOC entry 4023 (class 1259 OID 28391)
-- Name: fki_alert_types_belongsTo_alert_types_fk; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX "fki_alert_types_belongsTo_alert_types_fk" ON alert_types USING btree (parent_type_id);


--
-- TOC entry 4019 (class 1259 OID 28385)
-- Name: fki_alerts_belongsTo_alert_types_fk; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX "fki_alerts_belongsTo_alert_types_fk" ON alerts USING btree (type_id);


--
-- TOC entry 4020 (class 1259 OID 28379)
-- Name: fki_alerts_belongsTo_users_fk; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX "fki_alerts_belongsTo_users_fk" ON alerts USING btree (user_id);


--
-- TOC entry 4056 (class 1259 OID 45134)
-- Name: fki_user_feedings_belongsTo_user_sharings_fk; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX "fki_user_feedings_belongsTo_user_sharings_fk" ON user_feedings USING btree (user_sharing_id);


--
-- TOC entry 4057 (class 1259 OID 45127)
-- Name: fki_user_feedings_belongsTo_users_fk; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX "fki_user_feedings_belongsTo_users_fk" ON user_feedings USING btree (user_id);


--
-- TOC entry 4058 (class 1259 OID 45140)
-- Name: fki_user_feedings_belongsTo_view_levels_fk; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX "fki_user_feedings_belongsTo_view_levels_fk" ON user_feedings USING btree (view_level_id);


--
-- TOC entry 4051 (class 1259 OID 45121)
-- Name: fki_user_sharings_belongsTo_user_sharing_types_pk; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX "fki_user_sharings_belongsTo_user_sharing_types_pk" ON user_sharings USING btree (sharing_type_id);


--
-- TOC entry 4052 (class 1259 OID 45090)
-- Name: fki_user_sharings_belongsTo_users_fk; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX "fki_user_sharings_belongsTo_users_fk" ON user_sharings USING btree (user_id);


--
-- TOC entry 4053 (class 1259 OID 45110)
-- Name: fki_user_sharings_belongsTo_view_levels_fk; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX "fki_user_sharings_belongsTo_view_levels_fk" ON user_sharings USING btree (view_level_id);


--
-- TOC entry 4044 (class 1259 OID 28633)
-- Name: routes_geom_idx; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX routes_geom_idx ON routes USING gist (geom);


--
-- TOC entry 4077 (class 1259 OID 45359)
-- Name: user_navigation_routes_destination_idx; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX user_navigation_routes_destination_idx ON user_navigation_routes USING gist (destination_geom);


--
-- TOC entry 4078 (class 1259 OID 45360)
-- Name: user_navigation_routes_linestring_idx; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX user_navigation_routes_linestring_idx ON user_navigation_routes USING gist (line_string_geom);


--
-- TOC entry 4079 (class 1259 OID 45358)
-- Name: user_navigation_routes_origin_idx; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX user_navigation_routes_origin_idx ON user_navigation_routes USING gist (origin_geom);


--
-- TOC entry 4028 (class 1259 OID 28527)
-- Name: user_tackings_geom_idx; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX user_tackings_geom_idx ON user_trackings USING gist (geom);


--
-- TOC entry 4115 (class 2606 OID 45271)
-- Name: alert_comments_belongsTo_alerts_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY alert_comments
    ADD CONSTRAINT "alert_comments_belongsTo_alerts_fk" FOREIGN KEY (alert_id) REFERENCES alerts(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 4116 (class 2606 OID 45266)
-- Name: alert_comments_belongsTo_users_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY alert_comments
    ADD CONSTRAINT "alert_comments_belongsTo_users_fk" FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 4090 (class 2606 OID 28386)
-- Name: alert_types_belongsTo_alert_types_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY alert_types
    ADD CONSTRAINT "alert_types_belongsTo_alert_types_fk" FOREIGN KEY (parent_type_id) REFERENCES alert_types(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 4088 (class 2606 OID 28380)
-- Name: alerts_belongsTo_alert_types_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY alerts
    ADD CONSTRAINT "alerts_belongsTo_alert_types_fk" FOREIGN KEY (type_id) REFERENCES alert_types(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 4089 (class 2606 OID 28374)
-- Name: alerts_belongsTo_users_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY alerts
    ADD CONSTRAINT "alerts_belongsTo_users_fk" FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 4118 (class 2606 OID 45292)
-- Name: bike_keeper_comments_belongsTo_bike_keepers_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY bike_keeper_comments
    ADD CONSTRAINT "bike_keeper_comments_belongsTo_bike_keepers_fk" FOREIGN KEY (bike_keeper_id) REFERENCES bike_keepers(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 4117 (class 2606 OID 45297)
-- Name: bike_keeper_comments_belongsTo_users_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY bike_keeper_comments
    ADD CONSTRAINT "bike_keeper_comments_belongsTo_users_fk" FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 4096 (class 2606 OID 45238)
-- Name: bike_keepers_belongsTo_bike_keepers_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY bike_keepers_multimedias
    ADD CONSTRAINT "bike_keepers_belongsTo_bike_keepers_fk" FOREIGN KEY (bike_keepers_id) REFERENCES bike_keepers(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 4097 (class 2606 OID 45233)
-- Name: bike_keepers_belongsTo_multimedias_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY bike_keepers_multimedias
    ADD CONSTRAINT "bike_keepers_belongsTo_multimedias_fk" FOREIGN KEY (multimedias_id) REFERENCES multimedias(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 4092 (class 2606 OID 28497)
-- Name: bike_keepers_belongsTo_users_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY bike_keepers
    ADD CONSTRAINT "bike_keepers_belongsTo_users_fk" FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE SET DEFAULT;


--
-- TOC entry 4098 (class 2606 OID 28610)
-- Name: events_belongsTo_users_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY events
    ADD CONSTRAINT "events_belongsTo_users_fk" FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE SET DEFAULT;


--
-- TOC entry 4095 (class 2606 OID 28491)
-- Name: galleries_belongsTo_users_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY galleries
    ADD CONSTRAINT "galleries_belongsTo_users_fk" FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE SET DEFAULT;


--
-- TOC entry 4094 (class 2606 OID 28510)
-- Name: multimedias_belongsTo_galleries_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY multimedias
    ADD CONSTRAINT "multimedias_belongsTo_galleries_fk" FOREIGN KEY (gallery_id) REFERENCES galleries(id) ON UPDATE CASCADE ON DELETE SET DEFAULT;


--
-- TOC entry 4093 (class 2606 OID 45228)
-- Name: multimedias_belongsTo_multimedia_types_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY multimedias
    ADD CONSTRAINT "multimedias_belongsTo_multimedia_types_fk" FOREIGN KEY (type_id) REFERENCES multimedia_types(id) ON UPDATE CASCADE ON DELETE SET DEFAULT;


--
-- TOC entry 4124 (class 2606 OID 45521)
-- Name: online_users_belongsTo_users_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY online_users
    ADD CONSTRAINT "online_users_belongsTo_users_fk" FOREIGN KEY (user_id) REFERENCES users(id);


--
-- TOC entry 4099 (class 2606 OID 28628)
-- Name: routes_belongsTo_users_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY routes
    ADD CONSTRAINT "routes_belongsTo_users_fk" FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE SET DEFAULT;


--
-- TOC entry 4113 (class 2606 OID 45212)
-- Name: user_alert_existence_belongsTo_alerts_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY user_alert_nonexistence
    ADD CONSTRAINT "user_alert_existence_belongsTo_alerts_fk" FOREIGN KEY (alert_id) REFERENCES alerts(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 4114 (class 2606 OID 45207)
-- Name: user_alert_existence_belongsTo_users_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY user_alert_nonexistence
    ADD CONSTRAINT "user_alert_existence_belongsTo_users_fk" FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 4111 (class 2606 OID 45192)
-- Name: user_alert_rates_belongsTo_alerts_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY user_alert_rates
    ADD CONSTRAINT "user_alert_rates_belongsTo_alerts_fk" FOREIGN KEY (alert_id) REFERENCES alerts(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 4112 (class 2606 OID 45187)
-- Name: user_alert_rates_belongsTo_users_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY user_alert_rates
    ADD CONSTRAINT "user_alert_rates_belongsTo_users_fk" FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 4122 (class 2606 OID 45322)
-- Name: user_bike_keeper_existence_belongsTo_bike_keepers_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY user_bike_keeper_nonexistence
    ADD CONSTRAINT "user_bike_keeper_existence_belongsTo_bike_keepers_fk" FOREIGN KEY (bike_keeper_id) REFERENCES bike_keepers(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 4121 (class 2606 OID 45327)
-- Name: user_bike_keeper_existence_belongsTo_users_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY user_bike_keeper_nonexistence
    ADD CONSTRAINT "user_bike_keeper_existence_belongsTo_users_fk" FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 4120 (class 2606 OID 45307)
-- Name: user_bike_keeper_rates_belongsTo_bike_keepers_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY user_bike_keeper_rates
    ADD CONSTRAINT "user_bike_keeper_rates_belongsTo_bike_keepers_fk" FOREIGN KEY (bike_keeper_id) REFERENCES bike_keepers(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 4119 (class 2606 OID 45312)
-- Name: user_bike_keeper_rates_belongsTo_users_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY user_bike_keeper_rates
    ADD CONSTRAINT "user_bike_keeper_rates_belongsTo_users_fk" FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 4125 (class 2606 OID 45539)
-- Name: user_conversation_alerts_belongsTo_user2_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY user_conversation_alerts
    ADD CONSTRAINT "user_conversation_alerts_belongsTo_user2_fk" FOREIGN KEY (user_id2) REFERENCES users(id);


--
-- TOC entry 4126 (class 2606 OID 45534)
-- Name: user_conversation_alerts_belongsTo_user_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY user_conversation_alerts
    ADD CONSTRAINT "user_conversation_alerts_belongsTo_user_fk" FOREIGN KEY (user_id) REFERENCES users(id);


--
-- TOC entry 4109 (class 2606 OID 45129)
-- Name: user_feedings_belongsTo_user_sharings_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY user_feedings
    ADD CONSTRAINT "user_feedings_belongsTo_user_sharings_fk" FOREIGN KEY (user_sharing_id) REFERENCES user_sharings(id) ON UPDATE CASCADE ON DELETE SET NULL NOT VALID;


--
-- TOC entry 4110 (class 2606 OID 45122)
-- Name: user_feedings_belongsTo_users_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY user_feedings
    ADD CONSTRAINT "user_feedings_belongsTo_users_fk" FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE SET DEFAULT;


--
-- TOC entry 4108 (class 2606 OID 45135)
-- Name: user_feedings_belongsTo_view_levels_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY user_feedings
    ADD CONSTRAINT "user_feedings_belongsTo_view_levels_fk" FOREIGN KEY (view_level_id) REFERENCES view_levels(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 4102 (class 2606 OID 45248)
-- Name: user_friendship_requests_belongsTo_users2_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY user_friendship_requests
    ADD CONSTRAINT "user_friendship_requests_belongsTo_users2_fk" FOREIGN KEY (requested_user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 4103 (class 2606 OID 45243)
-- Name: user_friendship_requests_belongsTo_users_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY user_friendship_requests
    ADD CONSTRAINT "user_friendship_requests_belongsTo_users_fk" FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 4100 (class 2606 OID 45222)
-- Name: user_friendships_belongsTo_users2_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY user_friendships
    ADD CONSTRAINT "user_friendships_belongsTo_users2_fk" FOREIGN KEY (friend_user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 4101 (class 2606 OID 45217)
-- Name: user_friendships_belongsTo_users_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY user_friendships
    ADD CONSTRAINT "user_friendships_belongsTo_users_fk" FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 4123 (class 2606 OID 45373)
-- Name: user_navigation_routes_belongsTo_users_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY user_navigation_routes
    ADD CONSTRAINT "user_navigation_routes_belongsTo_users_fk" FOREIGN KEY (user_id) REFERENCES users(id);


--
-- TOC entry 4107 (class 2606 OID 45080)
-- Name: user_sharings_belongsTo_user_feedings_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY user_sharings
    ADD CONSTRAINT "user_sharings_belongsTo_user_feedings_fk" FOREIGN KEY (user_feeding_id) REFERENCES user_feedings(id) ON UPDATE CASCADE ON DELETE SET NULL NOT VALID;


--
-- TOC entry 4104 (class 2606 OID 45116)
-- Name: user_sharings_belongsTo_user_sharing_types_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY user_sharings
    ADD CONSTRAINT "user_sharings_belongsTo_user_sharing_types_fk" FOREIGN KEY (sharing_type_id) REFERENCES user_sharing_types(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 4106 (class 2606 OID 45085)
-- Name: user_sharings_belongsTo_users_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY user_sharings
    ADD CONSTRAINT "user_sharings_belongsTo_users_fk" FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE SET DEFAULT;


--
-- TOC entry 4105 (class 2606 OID 45111)
-- Name: user_sharings_belongsTo_view_levels_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY user_sharings
    ADD CONSTRAINT "user_sharings_belongsTo_view_levels_fk" FOREIGN KEY (view_level_id) REFERENCES view_levels(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 4091 (class 2606 OID 28405)
-- Name: user_trackings_belongsTo_users_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY user_trackings
    ADD CONSTRAINT "user_trackings_belongsTo_users_fk" FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 4310 (class 0 OID 0)
-- Dependencies: 18
-- Name: public; Type: ACL; Schema: -; Owner: -
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- TOC entry 4330 (class 0 OID 0)
-- Dependencies: 280
-- Name: bike_keepers_id_seq; Type: ACL; Schema: public; Owner: -
--

REVOKE ALL ON SEQUENCE bike_keepers_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE bike_keepers_id_seq FROM postgres;
GRANT ALL ON SEQUENCE bike_keepers_id_seq TO postgres;


--
-- TOC entry 4346 (class 0 OID 0)
-- Dependencies: 324
-- Name: user_5_friends_id; Type: ACL; Schema: public; Owner: -
--

REVOKE ALL ON TABLE user_5_friends_id FROM PUBLIC;
REVOKE ALL ON TABLE user_5_friends_id FROM postgres;
GRANT ALL ON TABLE user_5_friends_id TO postgres;
GRANT ALL ON TABLE user_5_friends_id TO PUBLIC;


--
-- TOC entry 3577 (class 826 OID 45550)
-- Name: DEFAULT PRIVILEGES FOR SEQUENCES; Type: DEFAULT ACL; Schema: public; Owner: -
--

ALTER DEFAULT PRIVILEGES FOR ROLE postgres IN SCHEMA public REVOKE ALL ON SEQUENCES  FROM PUBLIC;
ALTER DEFAULT PRIVILEGES FOR ROLE postgres IN SCHEMA public REVOKE ALL ON SEQUENCES  FROM postgres;
ALTER DEFAULT PRIVILEGES FOR ROLE postgres IN SCHEMA public GRANT ALL ON SEQUENCES  TO PUBLIC;


--
-- TOC entry 3579 (class 826 OID 45552)
-- Name: DEFAULT PRIVILEGES FOR TYPES; Type: DEFAULT ACL; Schema: public; Owner: -
--

ALTER DEFAULT PRIVILEGES FOR ROLE postgres IN SCHEMA public REVOKE ALL ON TYPES  FROM PUBLIC;
ALTER DEFAULT PRIVILEGES FOR ROLE postgres IN SCHEMA public REVOKE ALL ON TYPES  FROM postgres;
ALTER DEFAULT PRIVILEGES FOR ROLE postgres IN SCHEMA public GRANT ALL ON TYPES  TO PUBLIC;


--
-- TOC entry 3578 (class 826 OID 45551)
-- Name: DEFAULT PRIVILEGES FOR FUNCTIONS; Type: DEFAULT ACL; Schema: public; Owner: -
--

ALTER DEFAULT PRIVILEGES FOR ROLE postgres IN SCHEMA public REVOKE ALL ON FUNCTIONS  FROM PUBLIC;
ALTER DEFAULT PRIVILEGES FOR ROLE postgres IN SCHEMA public REVOKE ALL ON FUNCTIONS  FROM postgres;
ALTER DEFAULT PRIVILEGES FOR ROLE postgres IN SCHEMA public GRANT ALL ON FUNCTIONS  TO PUBLIC;


--
-- TOC entry 3576 (class 826 OID 45549)
-- Name: DEFAULT PRIVILEGES FOR TABLES; Type: DEFAULT ACL; Schema: public; Owner: -
--

ALTER DEFAULT PRIVILEGES FOR ROLE postgres IN SCHEMA public REVOKE ALL ON TABLES  FROM PUBLIC;
ALTER DEFAULT PRIVILEGES FOR ROLE postgres IN SCHEMA public REVOKE ALL ON TABLES  FROM postgres;
ALTER DEFAULT PRIVILEGES FOR ROLE postgres IN SCHEMA public GRANT ALL ON TABLES  TO PUBLIC;


-- Completed on 2017-06-11 06:44:23

--
-- PostgreSQL database dump complete
--

