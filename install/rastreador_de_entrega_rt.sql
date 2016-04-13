--
-- PostgreSQL database dump
--

-- Dumped from database version 9.5.2
-- Dumped by pg_dump version 9.5.1

-- Started on 2016-04-13 11:22:08

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;

--
-- TOC entry 3423 (class 1262 OID 18592)
-- Name: rastreador_de_entrega_rt; Type: DATABASE; Schema: -; Owner: postgres
--

CREATE DATABASE rastreador_de_entrega_rt WITH TEMPLATE = template0 ENCODING = 'UTF8' LC_COLLATE = 'Portuguese_Brazil.1252' LC_CTYPE = 'Portuguese_Brazil.1252';


ALTER DATABASE rastreador_de_entrega_rt OWNER TO postgres;

\connect rastreador_de_entrega_rt

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;

--
-- TOC entry 3424 (class 1262 OID 18592)
-- Dependencies: 3423
-- Name: rastreador_de_entrega_rt; Type: COMMENT; Schema: -; Owner: postgres
--

COMMENT ON DATABASE rastreador_de_entrega_rt IS 'Banco de dados para rastreador de encomenda em tempo real';


--
-- TOC entry 1 (class 3079 OID 12355)
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- TOC entry 3427 (class 0 OID 0)
-- Dependencies: 1
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


--
-- TOC entry 2 (class 3079 OID 18593)
-- Name: postgis; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS postgis WITH SCHEMA public;


--
-- TOC entry 3428 (class 0 OID 0)
-- Dependencies: 2
-- Name: EXTENSION postgis; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION postgis IS 'PostGIS geometry, geography, and raster spatial types and functions';


SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- TOC entry 197 (class 1259 OID 20012)
-- Name: user; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE "user" (
    id integer NOT NULL,
    primeiro_nome character varying(50),
    ultimo_nome character varying(50),
    como_ser_chamado character varying(25),
    login character varying(30),
    email character varying(100),
    senha character varying(16),
    data_cadastro timestamp without time zone DEFAULT now(),
    data_ultimo_acesso timestamp without time zone,
    a character varying(2),
    b character varying(2),
    c character varying(2),
    d character varying(2),
    e character varying(2),
    f character varying(2)
);


ALTER TABLE "user" OWNER TO postgres;

--
-- TOC entry 3429 (class 0 OID 0)
-- Dependencies: 197
-- Name: TABLE "user"; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE "user" IS 'Tabela de usuários da aplicação';


--
-- TOC entry 3430 (class 0 OID 0)
-- Dependencies: 197
-- Name: COLUMN "user".email; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN "user".email IS 'conta de email do usuário';


--
-- TOC entry 3431 (class 0 OID 0)
-- Dependencies: 197
-- Name: COLUMN "user".senha; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN "user".senha IS 'senha do usuário, usada na autenticação';


--
-- TOC entry 198 (class 1259 OID 20016)
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
-- TOC entry 3432 (class 0 OID 0)
-- Dependencies: 198
-- Name: user_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE user_id_seq OWNED BY "user".id;


--
-- TOC entry 3293 (class 2604 OID 20018)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY "user" ALTER COLUMN id SET DEFAULT nextval('user_id_seq'::regclass);


--
-- TOC entry 3291 (class 0 OID 18883)
-- Dependencies: 183
-- Data for Name: spatial_ref_sys; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY spatial_ref_sys  FROM stdin;
\.


--
-- TOC entry 3417 (class 0 OID 20012)
-- Dependencies: 197
-- Data for Name: user; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY "user" (id, primeiro_nome, ultimo_nome, como_ser_chamado, login, email, senha, data_cadastro, data_ultimo_acesso, a, b, c, d, e, f) FROM stdin;
1	Marcus	Faccion	\N	marcusfaccion	marcusfaccion@bol.com.br	23064140	2016-04-13 11:00:00	\N	\N	\N	\N	\N	\N	\N
\.


--
-- TOC entry 3433 (class 0 OID 0)
-- Dependencies: 198
-- Name: user_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('user_id_seq', 1, true);


--
-- TOC entry 3295 (class 2606 OID 20020)
-- Name: pk_user; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY "user"
    ADD CONSTRAINT pk_user PRIMARY KEY (id);


--
-- TOC entry 3426 (class 0 OID 0)
-- Dependencies: 7
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


-- Completed on 2016-04-13 11:22:09

--
-- PostgreSQL database dump complete
--

