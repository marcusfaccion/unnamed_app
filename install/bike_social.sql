--
-- PostgreSQL database dump
--

-- Dumped from database version 9.5.2
-- Dumped by pg_dump version 9.5.1

-- Started on 2017-05-25 16:03:50

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;

SET search_path = public, pg_catalog;

--
-- TOC entry 4292 (class 0 OID 45255)
-- Dependencies: 312
-- Data for Name: alert_comments; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO alert_comments VALUES (1, 24, 1, 'teste de comentario!!', '2017-05-02 01:08:04');
INSERT INTO alert_comments VALUES (2, 24, 1, 'teste de comentario!!', '2017-05-02 01:09:17');
INSERT INTO alert_comments VALUES (13, 33, 1, 'Boa informação.', '2017-05-02 14:40:10');
INSERT INTO alert_comments VALUES (14, 30, 1, 'Que perigo, obrigado vou evitar passar por ai.', '2017-05-02 14:47:54');
INSERT INTO alert_comments VALUES (15, 30, 1, ':)', '2017-05-02 14:49:31');
INSERT INTO alert_comments VALUES (16, 30, 1, ':(', '2017-05-02 14:54:52');
INSERT INTO alert_comments VALUES (17, 30, 2, 'Vlw marcus!', '2017-05-02 14:59:34');
INSERT INTO alert_comments VALUES (18, 22, 2, 'Essa obra náo acaba nunca', '2017-05-02 17:16:12');
INSERT INTO alert_comments VALUES (19, 24, 2, 'Essa ciclovia está cada vez mais perigosa hein. Poxa vida!', '2017-05-02 17:28:24');
INSERT INTO alert_comments VALUES (20, 35, 1, 'ooops', '2017-05-02 18:59:50');
INSERT INTO alert_comments VALUES (21, 35, 2, 'obrigado!', '2017-05-02 19:00:40');
INSERT INTO alert_comments VALUES (26, 37, 1, 'Cuidado pista com muito buraco', '2017-05-12 11:31:39');
INSERT INTO alert_comments VALUES (27, 23, 1, 'Teste', '2017-05-12 15:47:47');
INSERT INTO alert_comments VALUES (28, 23, 1, 'opss', '2017-05-12 15:48:00');
INSERT INTO alert_comments VALUES (29, 23, 1, 'bobs', '2017-05-12 15:48:34');
INSERT INTO alert_comments VALUES (30, 24, 1, 'Bom', '2017-05-12 16:59:43');
INSERT INTO alert_comments VALUES (31, 24, 1, 'Teste', '2017-05-14 01:29:13');
INSERT INTO alert_comments VALUES (32, 73, 1, 'teste', '2017-05-25 00:49:14');


--
-- TOC entry 4377 (class 0 OID 0)
-- Dependencies: 311
-- Name: alert_comments_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('alert_comments_id_seq', 32, true);


--
-- TOC entry 4259 (class 0 OID 22546)
-- Dependencies: 274
-- Data for Name: alert_types; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO alert_types VALUES (3, 'Roubos e Furtos', NULL);
INSERT INTO alert_types VALUES (2, 'Perigo na Via', NULL);
INSERT INTO alert_types VALUES (4, 'Interdições', NULL);
INSERT INTO alert_types VALUES (1, 'Alerta Genérico', NULL);


--
-- TOC entry 4262 (class 0 OID 28356)
-- Dependencies: 277
-- Data for Name: alert_types_spatial_types; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO alert_types_spatial_types VALUES (4, 1);
INSERT INTO alert_types_spatial_types VALUES (3, 1);
INSERT INTO alert_types_spatial_types VALUES (2, 1);


--
-- TOC entry 4257 (class 0 OID 22519)
-- Dependencies: 272
-- Data for Name: alerts; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO alerts VALUES (36, 'Muitos Pivetes 2220', '00 Muitos pivetes estão andando pela estrada!!!!!!', 3, 1, '2017-05-03 20:01:54', NULL, NULL, '14:36:52', '0101000020110F0000FBFFFF7FC7CF45C095909184F4FC36C0', 0, NULL, NULL);
INSERT INTO alerts VALUES (38, 'Acidentess', 'Acidente em frente ao santa Mônica dois ciclistas', 2, 1, '2017-05-06 16:28:01', NULL, NULL, '18:17:25', '0101000020110F0000030000C62ED745C04C2589FFC1EC36C0', 0, NULL, NULL);
INSERT INTO alerts VALUES (73, 'Teste', 'Teste', 3, 1, '2017-05-23 23:30:38', NULL, NULL, NULL, '0101000020110F0000FCFFFF5589CE45C0D67E1B8A7FE936C0', 0, NULL, '');
INSERT INTO alerts VALUES (76, 'teste', 'teste', 4, 1, '2017-05-23 23:32:16', NULL, NULL, NULL, '0101000020110F00000200001CB3CF45C047D60189FCE936C0', 0, NULL, '');
INSERT INTO alerts VALUES (33, 'Teste', 'Teste test testet teste', 1, 2, '2017-04-30 07:53:44', NULL, NULL, NULL, '0101000020110F000001000080C8BF45C01518CE7738FF36C0', 1, NULL, NULL);
INSERT INTO alerts VALUES (27, 'Teste Enable', 'Teste1', 1, 1, '2017-04-19 23:51:24', NULL, NULL, NULL, '0101000020110F0000FE7F0BB785C745C09B8180BFBC0137C0', 0, '2017-04-20 15:58:53', NULL);
INSERT INTO alerts VALUES (26, 'Teste de outro 2', 'Teste mais e mais e de novo 4', 1, 1, '2017-04-19 23:34:08', NULL, NULL, '10:45:49', '0101000020110F000002000080C9AF45C064F765AEF5EB36C0', 0, '2017-05-08 21:10:33', NULL);
INSERT INTO alerts VALUES (21, 'Obras BRT', 'teste', 4, 1, '2017-04-15 00:33:14', NULL, NULL, NULL, '0101000020110F0000FCFFFF6F0CB945C060449B3700DD36C0', 0, '2017-04-15 02:25:00', NULL);
INSERT INTO alerts VALUES (22, 'Obras no asfalto', 'Obras prefeitura do Rio de Janeiro.', 4, 1, '2017-04-20 23:21:52', 1, 1, '10:47:45', '0101000020110F0000FBFFFF7F53DA45C0DE76AA1403ED36C0', 0, '2017-05-08 20:40:14', NULL);
INSERT INTO alerts VALUES (41, 'Obras', 'Teste Teste Teste Teste Teste Teste Teste Teste Teste Teste Teste Teste Teste Teste Teste Teste ', 4, 4, '2017-05-16 10:00:00', NULL, NULL, NULL, '0101000020110F00000100000056B245C0132B224C5DEC36C0', 1, NULL, NULL);
INSERT INTO alerts VALUES (74, 'Teste 7', 'Teste', 2, 1, '2017-05-23 23:31:13', NULL, NULL, NULL, '0101000020110F0000FBFFFFA8CCCE45C0B079A58097E936C0', 0, NULL, '');
INSERT INTO alerts VALUES (75, 'Teste Teste 7', 'Teste', 1, 1, '2017-05-23 23:31:38', NULL, NULL, NULL, '0101000020110F00000000003261CF45C0B69D21DEE5E936C0', 0, NULL, '');
INSERT INTO alerts VALUES (89, 'addadsdas', 'adsdsadsa', 4, 1, '2017-05-25 02:21:06', NULL, NULL, NULL, '0101000020110F00000400008918D045C0F7EABD3527EA36C0', 0, NULL, '');
INSERT INTO alerts VALUES (23, 'Teste teste Enable', 'Teste de novo ?', 4, 2, '2017-04-19 23:30:05', NULL, NULL, NULL, '0101000020110F0000FEFFFF7F9ACF45C0D247EE7A42EA36C0', 1, NULL, NULL);
INSERT INTO alerts VALUES (77, 'Teste', 'Teste', 3, 1, '2017-05-25 00:41:41', NULL, NULL, NULL, '0101000020110F0000010000088BCF45C0741ABD07F7E936C0', 0, NULL, '');
INSERT INTO alerts VALUES (81, 'teste', 'teste', 2, 1, '2017-05-25 01:51:31', NULL, NULL, NULL, '0101000020110F0000030000A7CDCF45C08FD517510FEA36C0', 0, NULL, '');
INSERT INTO alerts VALUES (82, 'teste', 'teste
', 2, 1, '2017-05-25 01:52:13', NULL, NULL, NULL, '0101000020110F0000080000C0D2CF45C0C7FEA9D913EA36C0', 0, NULL, '');
INSERT INTO alerts VALUES (85, 'sdfsf', 'sdfdsf', 1, 1, '2017-05-25 02:18:39', NULL, NULL, NULL, '0101000020110F0000010000F255CF45C05600E6D816F536C0', 0, NULL, '');
INSERT INTO alerts VALUES (7, 'Saneamento da orla', 'Rua interditada', 4, 1, '2016-10-28 23:47:09', NULL, NULL, NULL, '0101000020110F0000010000C09CD845C0F9F9FF6294FB36C0', 0, '2017-04-20 15:58:53', NULL);
INSERT INTO alerts VALUES (25, '', 'Perigo animal morto', 2, 1, '2017-04-19 23:32:19', NULL, NULL, NULL, '0101000020110F000004000000C3BD45C0CAC271511CE136C0', 0, '2017-04-20 15:58:53', NULL);
INSERT INTO alerts VALUES (40, '', 'Acidente !', 3, 4, '2017-05-16 10:27:04', NULL, NULL, NULL, '0101000020110F000000000000C3BD45C010A3C960B6FC36C0', 1, NULL, NULL);
INSERT INTO alerts VALUES (86, 'sdsdf', 'sfsd', 3, 1, '2017-05-25 02:19:18', NULL, NULL, '04:01:52', '0101000020110F00000200006DE1C845C011BB2A2B74E736C0', 0, NULL, '');
INSERT INTO alerts VALUES (6, 'Incêndio na morro de Paciências', 'Bombeiros no local, rua interditada000s', 4, 1, '2016-10-28 23:44:41', NULL, NULL, '15:19:07', '0101000020110F00000400000073D145C0BA2741A543E736C0', 0, NULL, NULL);
INSERT INTO alerts VALUES (87, 'addsad', 'asdasads', 2, 1, '2017-05-25 02:20:02', NULL, NULL, NULL, '0101000020110F0000FCFFFF2093CC45C09E7DC6C0CBE836C0', 0, NULL, '');
INSERT INTO alerts VALUES (35, 'Teste', 'Teste mais e mais 2017', 1, 1, '2017-04-30 07:59:13', NULL, NULL, '14:36:25', '0101000020110F0000FCFFFF3FCCB045C02BCB5B315FF736C0', 0, NULL, NULL);
INSERT INTO alerts VALUES (88, 'saddadas', 'adsddsadsa', 1, 1, '2017-05-25 02:20:39', NULL, NULL, NULL, '0101000020110F00000800008913C645C05957C20C8EE536C0', 0, NULL, '');
INSERT INTO alerts VALUES (30, 'Uruguaiana está perigosa!', 'Ladrões estão roubando bicicletas nos bicicletários do metrô.', 3, 3, '2017-04-30 07:41:01', 1, NULL, NULL, '0101000020110F000002000080199C45C0F401B1B811EB36C0', 1, NULL, NULL);
INSERT INTO alerts VALUES (24, 'Teste de roubo', 'Ooopps
', 3, 2, '2017-04-19 23:31:39', NULL, NULL, NULL, '0101000020110F0000FFFFFF3FEFC945C0FFD28F68B1E936C0', 1, NULL, NULL);
INSERT INTO alerts VALUES (59, 'teste', 'Teste', 4, 1, '2017-05-21 22:11:01', NULL, NULL, NULL, '0101000020110F0000070000B8C3C845C0B6C22369BF0A37C0', 0, NULL, '');
INSERT INTO alerts VALUES (72, 'Perigo, obras', 'Prefeitura resolveu faze operação tapa buraco.', 4, 1, '2017-05-21 23:25:20', NULL, NULL, '23:43:11', '0101000020110F000000000000DCC245C0A041F719AAEA36C0', 0, NULL, '');
INSERT INTO alerts VALUES (37, 'Acidente', 'Acidente de dois ciclistas em frente ao santa mônica7', 1, 1, '2017-05-06 16:27:06', NULL, 1, '20:45:36', '0101000020110F0000FDFFFF6B2ED745C096208FE9C4EC36C0', 0, NULL, '');
INSERT INTO alerts VALUES (78, 'Teste', 'teste', 2, 1, '2017-05-25 00:42:11', NULL, NULL, NULL, '0101000020110F00000800002280CF45C0874EC68DF0E936C0', 0, NULL, '');
INSERT INTO alerts VALUES (79, 'Teste', 'teste', 3, 1, '2017-05-25 00:42:45', NULL, NULL, NULL, '0101000020110F0000FDFFFF47C3CF45C01D4A516E0BEA36C0', 0, NULL, '');
INSERT INTO alerts VALUES (80, 'Obras BRT', 'gygyut', 4, 1, '2017-05-25 00:48:13', NULL, NULL, NULL, '0101000020110F00000100007D70CF45C0F445036EE9E936C0', 0, NULL, '');
INSERT INTO alerts VALUES (83, 'teste', 'teste', 2, 1, '2017-05-25 02:16:54', NULL, NULL, NULL, '0101000020110F0000FFFFFF1BE0CF45C00BEF937514EA36C0', 0, NULL, '');
INSERT INTO alerts VALUES (84, 'sadsad', 'asdsad', 1, 1, '2017-05-25 02:17:23', NULL, NULL, NULL, '0101000020110F0000050000193DD045C01E30531438EA36C0', 0, NULL, '');
INSERT INTO alerts VALUES (90, 'Arrastão', 'Bandidos armados na estrada!', 3, 1, '2017-05-25 13:29:52', NULL, NULL, NULL, '0101000020110F0000FBFFFF9092D545C0650E0FF48AEA36C0', 0, NULL, '');
INSERT INTO alerts VALUES (91, 'Buraco!', 'Buraco no acostamento cuidado!', 2, 1, '2017-05-25 13:44:22', NULL, NULL, NULL, '0101000020110F0000FFFFFFFC32C945C02502212637ED36C0', 0, NULL, '');
INSERT INTO alerts VALUES (92, 'teste', 'teste', 1, 1, '2017-05-25 13:47:33', NULL, NULL, NULL, '0101000020110F0000FFFFFF89AECF45C0EEDFE9481EF536C0', 0, NULL, '');
INSERT INTO alerts VALUES (93, 'Teste', 'teste', 2, 1, '2017-05-25 13:49:47', NULL, NULL, NULL, '0101000020110F0000FEFFFFC9B9CF45C050315439EAF536C0', 0, NULL, '');
INSERT INTO alerts VALUES (31, 'Via perigosa15', 'Via com muito alagamento! 9', 2, 1, '2017-04-30 07:51:15', NULL, 1, '18:17:49', '0101000020110F0000FCFFFFFF10CA45C06075383DC8F436C0', 0, NULL, NULL);
INSERT INTO alerts VALUES (94, 'Alfalto quebrado', 'Ciclovia muito precária!!', 2, 1, '2017-05-25 14:24:38', NULL, NULL, NULL, '0101000020110F00000000004D5DC745C0B8F52894C6E636C0', 1, NULL, 'Rua Campo Grande, Campo Grande, Rio de Janeiro/Rio de Janeiro - Brasil');
INSERT INTO alerts VALUES (95, 'Operação SMTRJ', 'Rua interditada', 4, 1, '2017-05-25 14:28:21', NULL, NULL, NULL, '0101000020110F0000010000E0E1BE45C0CCD5EF13CB0537C0', 1, '2017-05-25 18:55:50', 'Avenida Teotônio Vilela, Recreio dos Bandeirantes, Rio de Janeiro/Rio de Janeiro - Brasil');
INSERT INTO alerts VALUES (34, 'dsdsdsd 2017', 'came on baby chance!ss', 4, 1, '2017-04-30 07:54:06', 1, NULL, '00:12:39', '0101000020110F0000070000007ABB45C0248C6E9A76FF36C0', 0, '2017-05-22 01:10:31', '');
INSERT INTO alerts VALUES (32, 'Asfalto quebrada', 'Muitos buracos divido asfalto quebrado ', 2, 2, '2017-04-30 07:52:55', NULL, NULL, '09:14:16', '0101000020110F0000040000C057C345C01503BAC5360237C0', 1, NULL, '');
INSERT INTO alerts VALUES (8, 'Acidênte no posto de combustível', 'Carro bateu em uma das bombas de combustível do posto da Av. João XXIII', 4, 2, '2016-10-28 23:50:19', NULL, NULL, '09:29:55', '0101000020110F0000FCFFFFFF20D845C08CB9C8C849E936C0', 0, '2017-05-22 10:35:46', '');


--
-- TOC entry 4378 (class 0 OID 0)
-- Dependencies: 271
-- Name: alerts_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('alerts_id_seq', 95, true);


--
-- TOC entry 4379 (class 0 OID 0)
-- Dependencies: 276
-- Name: alerts_types_geometries_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('alerts_types_geometries_id_seq', 7, true);


--
-- TOC entry 4380 (class 0 OID 0)
-- Dependencies: 273
-- Name: alerts_types_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('alerts_types_id_seq', 3, true);


--
-- TOC entry 4294 (class 0 OID 45281)
-- Dependencies: 314
-- Data for Name: bike_keeper_comments; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO bike_keeper_comments VALUES (1, 12, 1, 'Estou testando!', '2017-05-12 11:11:15');
INSERT INTO bike_keeper_comments VALUES (2, 12, 2, 'Obrigado Marcus', '2017-05-12 11:12:09');
INSERT INTO bike_keeper_comments VALUES (3, 12, 2, ':)', '2017-05-13 13:12:37');
INSERT INTO bike_keeper_comments VALUES (4, 12, 3, 'Teste', '2017-05-15 10:00:00');
INSERT INTO bike_keeper_comments VALUES (6, 12, 1, '<h1>Teste </h1>', '2017-05-12 11:25:33');
INSERT INTO bike_keeper_comments VALUES (5, 12, 1, '<img src=txt.png>Teste 2 </img>', '2017-05-12 11:25:10');
INSERT INTO bike_keeper_comments VALUES (7, 8, 1, 'Muito bom esse bicicletário', '2017-05-12 12:51:04');
INSERT INTO bike_keeper_comments VALUES (8, 3, 1, 'Okkek', '2017-05-12 15:14:11');
INSERT INTO bike_keeper_comments VALUES (9, 3, 1, 'teste', '2017-05-12 15:15:03');
INSERT INTO bike_keeper_comments VALUES (10, 6, 1, 'teste', '2017-05-12 15:25:34');
INSERT INTO bike_keeper_comments VALUES (11, 6, 1, 'teste 2', '2017-05-12 15:25:50');
INSERT INTO bike_keeper_comments VALUES (12, 6, 1, 'teste 3', '2017-05-12 15:29:59');
INSERT INTO bike_keeper_comments VALUES (13, 7, 1, 'Teste', '2017-05-12 15:30:52');
INSERT INTO bike_keeper_comments VALUES (17, 13, 1, 'Teste', '2017-05-12 15:41:39');
INSERT INTO bike_keeper_comments VALUES (18, 3, 1, 'chochomery', '2017-05-12 15:50:16');
INSERT INTO bike_keeper_comments VALUES (19, 13, 1, 'opa', '2017-05-12 17:31:28');
INSERT INTO bike_keeper_comments VALUES (20, 6, 1, 'Teste', '2017-05-14 01:29:04');
INSERT INTO bike_keeper_comments VALUES (21, 19, 2, 'Ok', '2017-05-14 01:29:25');


--
-- TOC entry 4381 (class 0 OID 0)
-- Dependencies: 313
-- Name: bike_keeper_comments_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('bike_keeper_comments_id_seq', 21, true);


--
-- TOC entry 4266 (class 0 OID 28435)
-- Dependencies: 281
-- Data for Name: bike_keepers; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO bike_keepers VALUES (20, 'Teste Jardim da saud bike', 1, NULL, 30, NULL, 1, 'Teste', 1, 1, '2017-05-10 13:56:45', 1, '0101000020110F0000060000409DD045C06DDBA745C2F236C0', '63ae6a07abf3e7030ce4a43d681746f57d79e4501b9654407efada41edc4d10120', 0, NULL, 'Até as 22hs', NULL, NULL, NULL, 1);
INSERT INTO bike_keepers VALUES (2, 'Bike Park', 1, 1, 15, 15, 1, 'Administração privada, próximo ao centro de Santa Cruz.', 0, 0, '2017-01-27 20:43:02', 0, '0101000020110F000002000004E1D745C06B44DE3313EA36C0', 'fcbe800376b367d2ee2aff49f589e68105223f1ef74675c4527ecbbd86f833232', 3, '2017-05-22 04:28:10', 'Segunda a Sexta 8h-22h', '', '', '', 1);
INSERT INTO bike_keepers VALUES (14, 'Teste', NULL, NULL, 1, NULL, 1, 'tygyu', 1, 1, '2017-05-09 12:13:36', 1, '0101000020110F0000FBFFFF7FC7CF45C0B4F771FB6DF536C0', '01e0ecf778c4cc97ab1c626a954ca9e2ce2ce29a1ef9de68dd0c7faebc7962d514', NULL, '2017-05-21 17:55:17', '16h', NULL, NULL, NULL, 1);
INSERT INTO bike_keepers VALUES (22, 'Bicicletário Gomes', 1, NULL, 5, NULL, 1, 'Teste de biciletário', 1, 1, '2017-05-15 21:48:45', 0, '0101000020110F000003000040BAC245C0F2834B60C8E336C0', 'e58912834f6450d62be7a1b59f202c2542e6a465e5f89ea56d1c4cc24af0f34a22', 0, NULL, 'Seg-Sex 09 as 22h', NULL, NULL, NULL, 1);
INSERT INTO bike_keepers VALUES (17, 'Teste', NULL, NULL, 120, NULL, 1, 'Marambaia', 0, 0, '2017-05-09 12:21:24', 0, '0101000020110F00000200008035F845C051C8CB80A91137C0', 'f4da9f0fb3cab7656917532e4b7507fe17c0157d0c90b5f6521d0b5b3997e61d17', 0, '2017-05-21 17:51:56', '31h', '', '', NULL, 1);
INSERT INTO bike_keepers VALUES (21, 'Teste', NULL, NULL, 4, 4, 1, 'Teste dois', 0, 0, '2017-05-10 14:00:30', 0, '0101000020110F000002000098CBD145C0EE4968488CF336C0', 'b098cceb6eb93af9a666550b60d9ea66bd0aba757f37812a8961b975cfd606a621', 3, '2017-05-22 06:24:34', 'Teste', '21 998555555', 'teste@golp.net', '', 1);
INSERT INTO bike_keepers VALUES (3, 'Biciletário Comunitário', 1, NULL, 200, NULL, 1, 'Teste', 1, 0, '2017-05-03 19:58:19', 0, '0101000020110F00000200008055BA45C05E6317C745E136C0', '7b1403a21d566ab260697b6be6071b8a6584055c1e3c5f35531c038faccbea5f3', 0, NULL, 'sds', NULL, NULL, NULL, 1);
INSERT INTO bike_keepers VALUES (30, 'Teste ssss', NULL, NULL, 150, NULL, 1, 'teste ssss', 1, 1, '2017-05-24 16:34:44', 0, '0101000020110F000004000060DBCC45C09A78C7F815E936C0', '92ef4d128f5428735be5bd332510bb5cfe51c05e490cfefe5afb47b3803cda8f29', NULL, '2017-05-25 04:00:30', '24h', NULL, NULL, '', 1);
INSERT INTO bike_keepers VALUES (4, 'Teste', NULL, 1, 150, NULL, 1, 'Teste', 0, 0, '2017-05-09 11:38:20', 0, '0101000020110F00000200008049C645C084ECE2D281E736C0', 'c6b73e816c0d379fdc8d48cfc960d4d678ecc90a1e3bd9ae7cd00f95e7ec841d4', 0, NULL, 'dssf', NULL, NULL, NULL, 1);
INSERT INTO bike_keepers VALUES (28, 'Teste cg', NULL, NULL, 56, NULL, 1, 'teste', 0, 1, '2017-05-21 20:19:13', 0, '0101000020110F0000FDFFFF5F49C845C0CF256B2EA6F336C0', 'ed030e4c1f2caecb5a980160ae6e08d64cc7ae9207cd0884ebad9221acbc3f2a28', 0, NULL, 'Seg a Sex 06h-22h', NULL, NULL, '', 1);
INSERT INTO bike_keepers VALUES (19, 'EB bike', NULL, NULL, 200, 3, 1, 'Teste', 1, 0, '2017-05-10 13:47:50', 0, '0101000020110F0000000000D06FD745C08E471FD1EDE836C0', 'ffeef7dfc257127d87214c15e3afb6ec110928b4059d6d4dbe285c9eaf95ee6919', 1, '2017-05-22 06:23:39', '24h', NULL, NULL, NULL, 0);
INSERT INTO bike_keepers VALUES (9, 'teste', NULL, 1, 40, NULL, 1, 'teste', 1, 0, '2017-05-09 12:07:11', 0, '0101000020110F00000500008004B145C097996E1C1BF536C0', 'd64d764cbbb0d412e80d0a16521b97918bb4ba7f01f6ece82f718d7bc06906719', 0, NULL, 'gd', NULL, NULL, NULL, 0);
INSERT INTO bike_keepers VALUES (31, 'Teste', NULL, NULL, 5, NULL, 1, 'teste', 1, 1, '2017-05-24 18:05:30', 1, '0101000020110F0000010000BE9BCC45C08BCEDC8ED4E836C0', 'b4005924e7d42e743cd8bb802e4eca50f1b2a9239fe0ed47496c12ea30ff74fe31', NULL, '2017-05-25 04:44:27', '454545', NULL, NULL, '', 1);
INSERT INTO bike_keepers VALUES (25, 'Bike CG', NULL, NULL, 16, 16, 2, 'Teste', 0, 0, '2017-05-21 20:14:47', 0, '0101000020110F0000010000C008C745C0028A9ABD70EF36C0', 'c8cfc93e3d74e487d8edb6b967a9a8ba4071be02f0fb2265f62b14369579e26925', 3, '2017-05-22 09:28:16', 'Seg a Sex 06h-22h', '21 30458956', 'Teste@ets.com', '', 1);
INSERT INTO bike_keepers VALUES (1, 'Bicicletário Supervia', NULL, NULL, 250, 1, 1, 'Administrado pela concessionária de trens Supervia. Clientes que seguirem viagem de trêm não pagam taxa.', 0, 1, '2017-01-27 18:59:05', 0, '0101000020110F00000300006481D745C038B9F43D3FEA36C0', '798130f65d286205a6325ef533858fbc6ce81ad0afd8d26b4c7716388bbcc3bb1', 1, '2017-05-21 14:44:54', 'dsds', NULL, NULL, NULL, 1);
INSERT INTO bike_keepers VALUES (18, 'Bike Vila', NULL, NULL, 150, NULL, 1, 'Teste', 1, 0, '2017-05-10 13:40:18', 0, '0101000020110F0000080000B421D845C00C43CA6629E936C0', '2bd8e87de8bd2e7ea1ba3acd3a45a052eeb04c66657134651d1538ce0186779c18', 0, '2017-05-21 17:52:34', 'seg-sab 08-22h', '', '', NULL, 0);
INSERT INTO bike_keepers VALUES (10, 'tetste', NULL, NULL, 5, NULL, 1, 'tytyu', 1, 1, '2017-05-09 12:08:34', 0, '0101000020110F0000FDFFFFBF2DC045C01B26A4F3B7F936C0', 'fcc81c0395a450dcb1a256a8c7837ddacc5c5bfcdccab781aa6381f47a1c6ebb10', 1, NULL, 'g', NULL, NULL, NULL, 1);
INSERT INTO bike_keepers VALUES (11, 'dsds', NULL, NULL, 1, NULL, 1, 'dsds', 1, 0, '2017-05-09 12:10:41', 0, '0101000020110F0000FDFFFFBF2DC045C01B26A4F3B7F936C0', '6a6749f90ef25cbf7fdd7cdf986cbb91ad3b860ad548833576bdf94de65774ce11', 0, NULL, 'dg', NULL, NULL, NULL, 1);
INSERT INTO bike_keepers VALUES (12, 'tete', NULL, NULL, 5, NULL, 1, 'teste', 1, 1, '2017-05-09 12:11:45', 0, '0101000020110F0000FDFFFFBF2DC045C01B26A4F3B7F936C0', 'da07b04761b09443b9c392574fc4eac0925d0a3afccb8283f921dc0ee178ddd012', 1, NULL, 'dg', NULL, NULL, NULL, 1);
INSERT INTO bike_keepers VALUES (13, 'sdsd', NULL, NULL, 1, NULL, 1, 'dsd', 1, 1, '2017-05-09 12:12:49', 0, '0101000020110F0000FAFFFFBFBED445C0D0E37F7028F636C0', '9688bc68aa8dc2d9b51acf427d9c0c9936d920577b52861c4956db368eb22bfb13', 1, NULL, 'dg', NULL, NULL, NULL, 1);
INSERT INTO bike_keepers VALUES (16, 'teste', NULL, 1, 5, NULL, 1, 'teste', 1, 1, '2017-05-09 12:17:14', 0, '0101000020110F0000FAFFFFBF32CA45C0803467F56AFB36C0', 'fbfa7649d9b8ef423337faf13d3b15d6c5ff23121476e2d6b51fb7052849f6cf16', 5, NULL, 'dg', NULL, NULL, NULL, 1);
INSERT INTO bike_keepers VALUES (15, 'hghg', NULL, NULL, 8, NULL, 1, 'hgjhg', 0, 0, '2017-05-09 12:14:55', 1, '0101000020110F00000600004005D245C0BFB4645BDEF136C0', '01bc5f8d88b3ddc66e7196bdd672bf69d6cf54cadc43eda05b3b178c78b568b015', 0, '2017-05-21 17:46:16', '24h', '', '', NULL, 0);
INSERT INTO bike_keepers VALUES (24, 'Bike Thaty', NULL, NULL, 15, NULL, 2, '', 1, 1, '2017-05-21 15:55:51', 0, '0101000020110F00000300007062CC45C0C09352C5BBE936C0', '3bed12d7fede28f9e5750ff8b2c2efd94db73dad71195a12f12ecde9df370b9124', NULL, '2017-05-21 17:43:44', 'Segunda a Sexta 8h-22h', NULL, NULL, NULL, 1);
INSERT INTO bike_keepers VALUES (7, 'Teste0000', NULL, 1, 30, NULL, 1, 'dsdsds', 1, 0, '2017-05-09 12:01:17', 0, '0101000020110F0000020000A440D845C0529D5C0A7CF836C0', 'dae30693d68e08df98e50f87b00328cd99e27caa86b873f067ad31bf0213453e7', 0, NULL, 'gd', NULL, NULL, NULL, 0);
INSERT INTO bike_keepers VALUES (8, 'sdsd', 2, NULL, 45, NULL, 1, 'ghgjh', 1, 0, '2017-05-09 12:04:53', 0, '0101000020110F0000040000C0E3CD45C0D0E37F7028F636C0', '8b6be9b5487ea6de330c87b96f7fa1f7730e6e82c2f6496fa3258381b24739098', 0, '2017-05-21 15:00:00', 'gd', NULL, NULL, NULL, 1);
INSERT INTO bike_keepers VALUES (5, 'Teste 200', NULL, NULL, 25, 10, 1, 'Mais um teste', 0, 1, '2017-05-09 11:44:41', 0, '0101000020110F0000FBFFFF7FBBAE45C064F765AEF5EB36C0', '0e03792ad29adc35de79be9fd53141cc96c36c8376883894eacc8b7cd4d0fec05', 1.29999995, NULL, 'gdf', NULL, NULL, NULL, 1);
INSERT INTO bike_keepers VALUES (6, 'Teste +', 1, NULL, 50, NULL, 1, 'Teste :)', 0, 1, '2017-05-09 11:47:23', 0, '0101000020110F0000FBFFFFDF23D745C03E7B78D8D1F936C0', '63dcb488f0e1ea0fa7e02d4a26270005d43f73c8fafb7141cf685bc3b392dcb16', NULL, '2017-05-25 03:59:55', 'gd', NULL, NULL, '', 1);
INSERT INTO bike_keepers VALUES (27, 'Bike Freguesia', NULL, NULL, 400, 71, 4, 'Teste', 0, 0, '2017-05-21 20:16:45', 1, '0101000020110F0000FCFFFF3F74BD45C0A3B7633C31F236C0', '671b452af42960c521138a6d34303667f44a81f1f73ca4cf75f695bebcaa14fc26', 5, '2017-05-22 09:12:14', 'Seg a Sex 06h-22h', '21 3049-0095', 'freguesia-bike@gmail.com', '', 1);
INSERT INTO bike_keepers VALUES (32, 'ssds', NULL, NULL, 1, NULL, 1, 'sdsd', 1, 1, '2017-05-25 02:25:19', 0, '0101000020110F0000FEFFFFC242D545C09AFFB1D6E2EB36C0', 'eae2a071a1a22872e957f3ea3696dd2216d34a6a78702e1601098c7e4cf727ae32', NULL, '2017-05-25 03:59:32', 'sdsd', NULL, NULL, '', 1);
INSERT INTO bike_keepers VALUES (33, 'Bicicletário fonte nova', NULL, NULL, 30, NULL, 4, '', 0, 0, '2017-05-25 08:47:19', 1, '0101000020110F000000000008D2C445C04EE089A435E536C0', '79c69381a00bb283bde8f7e2740f507fc7b84d8792bed50143e16aefc7d2abd533', 4, NULL, 'Segunda a Sexta 08h-22h', '21 3049-0000', 's-bike@gmail.com', '', 1);
INSERT INTO bike_keepers VALUES (23, 'teste', NULL, NULL, 10, 3, 1, 'Teste kjkkjkjjkkjk', 1, 0, '2017-05-20 18:53:19', 1, '0101000020110F0000020000809DCC45C0E47D95CE65E836C0', 'fab322ba15e25daf457023e35a779f75acfb65261b64636069b3a3d18ef262e723', 3.5, '2017-05-25 15:36:18', 'seg 24h sex 23h', '(021) 3021-5564', 'marcusfaccion@bol.com.br', '', 0);
INSERT INTO bike_keepers VALUES (36, 'teste', NULL, NULL, 120, NULL, 1, 'teste', 1, 1, '2017-05-25 14:57:25', 1, '0101000020110F000001000040B9D245C07DD106962AF536C0', 'f72d6f0a7605004bcaca9a633bea13500a05e460e424a6431109ecf2e10ce89d36', 0, NULL, 'ee', NULL, NULL, '', 1);
INSERT INTO bike_keepers VALUES (35, 'Bike Fontes', NULL, NULL, 50, NULL, 1, 'Localizado na rua Silva Fontes Teste de imagem', 0, 0, '2017-05-25 08:58:40', 1, '0101000020110F00000300007006BF45C03AD86165FCE136C0', '84c92b7911b1515a2b73e7c72c6d8c98a1ccd1a0c82022933256c645f0a8083135', 0, '2017-05-25 15:20:34', 'seg-sex 8-22h', '(21) 33169-55__', '', '', 1);
INSERT INTO bike_keepers VALUES (38, 'Teste de bicicletário', NULL, NULL, 15, NULL, 1, 'Teste2222', 0, 0, '2017-05-25 15:43:21', 1, '0101000020110F0000040000600BCA45C06B9FC2EA8BFD36C0', '46e1d5f366e3a23c44ae85211edc57c4e3083644eeb308c1c4cedd1c861eed5438', 2.9000001, '2017-05-25 18:30:56', '24h', '(021) 3386-7455', 'ccion@botat.com.br', '', 1);
INSERT INTO bike_keepers VALUES (37, 'Teste', NULL, NULL, 22, NULL, 1, 'teste', 1, 1, '2017-05-25 15:39:55', 1, '0101000020110F00000500008078D345C0A095145839F336C0', '0c9efb7cb0e123e8dbf66339f0b27762d9e81130af9d03195fb739bb7083408437', NULL, '2017-05-25 18:34:39', '4545', NULL, NULL, '', 1);
INSERT INTO bike_keepers VALUES (39, 'Bicicletário comunitário', NULL, NULL, 100, NULL, 1, 'Teste2103', 1, 1, '2017-05-25 16:05:15', 0, '0101000020110F0000FDFFFFCD37B745C0033861ADA70137C0', '64d37806f5f6dc8b1cc16aa8d7b90bc1bf8ca168d3124ebfd0083ef0e678e3a039', NULL, '2017-05-25 18:34:20', '8h-22h', NULL, NULL, 'Rua Sílvio da Rocha Pollis, s/n, Barra da Tijuca, Rio de Janeiro/Rio de Janeiro - Brasil', 1);
INSERT INTO bike_keepers VALUES (40, 'Bike Song 2017', NULL, NULL, 200, NULL, 1, 'Preço bom e confiável.', 0, 1, '2017-05-25 18:39:33', 1, '0101000020110F0000FCFFFF63DBD745C05F6A3DBB15EA36C0', 'b94af9ca8048b3c918755f316d2ddee266f064446c0cd108d65596f5eeebe96340', NULL, '2017-05-25 18:47:16', 'Segunda a Sexta 06h-22h', NULL, NULL, 'Rua do Prado, s/n, Santa Cruz, Rio de Janeiro/Rio de Janeiro - Brasil', 1);


--
-- TOC entry 4382 (class 0 OID 0)
-- Dependencies: 280
-- Name: bike_keepers_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('bike_keepers_id_seq', 40, true);


--
-- TOC entry 4273 (class 0 OID 28515)
-- Dependencies: 288
-- Data for Name: bike_keepers_multimedias; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO bike_keepers_multimedias VALUES (35, 79);
INSERT INTO bike_keepers_multimedias VALUES (36, 80);
INSERT INTO bike_keepers_multimedias VALUES (37, 81);
INSERT INTO bike_keepers_multimedias VALUES (38, 82);
INSERT INTO bike_keepers_multimedias VALUES (39, 83);
INSERT INTO bike_keepers_multimedias VALUES (40, 84);


--
-- TOC entry 4275 (class 0 OID 28598)
-- Dependencies: 290
-- Data for Name: events; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 4383 (class 0 OID 0)
-- Dependencies: 289
-- Name: events_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('events_id_seq', 1, false);


--
-- TOC entry 4272 (class 0 OID 28479)
-- Dependencies: 287
-- Data for Name: galleries; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO galleries VALUES (0, 'Default Gallery', 0, '2015-11-03 13:41:00');


--
-- TOC entry 4384 (class 0 OID 0)
-- Dependencies: 286
-- Name: galleries_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('galleries_id_seq', 1, false);


--
-- TOC entry 4270 (class 0 OID 28459)
-- Dependencies: 285
-- Data for Name: multimedia_types; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO multimedia_types VALUES (1, 'imagem', 'Fotografias ou  imagens', 'image/jpeg;image/pjpeg;image/jpeg;image/png');
INSERT INTO multimedia_types VALUES (2, 'vídeo', 'Vídeo de média duração (avi, mp4, webm)', 'video/avi;video/msvideo;video/x-msvideo;video/webm;video/ogg');
INSERT INTO multimedia_types VALUES (0, 'default', 'Genérico', 'application/octet-stream;');


--
-- TOC entry 4385 (class 0 OID 0)
-- Dependencies: 284
-- Name: multimedia_types_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('multimedia_types_id_seq', 2, true);


--
-- TOC entry 4268 (class 0 OID 28451)
-- Dependencies: 283
-- Data for Name: multimedias; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO multimedias VALUES (1, 1, NULL, '2017-01-10 11:38:21', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/fcbe800376b367d2ee2aff49f589e68105223f1ef74675c4527ecbbd86f833232/images/bicicletario_sc1.png', 0);
INSERT INTO multimedias VALUES (2, 1, NULL, '2017-01-10 11:39:08', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/fcbe800376b367d2ee2aff49f589e68105223f1ef74675c4527ecbbd86f833232/images/bicicletario_sc2.png', 0);
INSERT INTO multimedias VALUES (3, 1, NULL, '2017-01-26 18:04:30', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/7b1403a21d566ab260697b6be6071b8a6584055c1e3c5f35531c038faccbea5f3/images/bicicletario_sc1.png', 0);
INSERT INTO multimedias VALUES (4, 1, NULL, '2017-01-26 18:04:31', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/7b1403a21d566ab260697b6be6071b8a6584055c1e3c5f35531c038faccbea5f3/images/bicicletario_sc2.png', 0);
INSERT INTO multimedias VALUES (5, 1, NULL, '2017-01-26 18:10:02', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/7b1403a21d566ab260697b6be6071b8a6584055c1e3c5f35531c038faccbea5f3/images/bicicletario_sc1.png', 0);
INSERT INTO multimedias VALUES (6, 1, NULL, '2017-01-26 18:10:02', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/7b1403a21d566ab260697b6be6071b8a6584055c1e3c5f35531c038faccbea5f3/images/bicicletario_sc2.png', 0);
INSERT INTO multimedias VALUES (7, 1, NULL, '2017-01-26 18:11:10', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/798130f65d286205a6325ef533858fbc6ce81ad0afd8d26b4c7716388bbcc3bb1/images/bicicletario_sc1.png', 0);
INSERT INTO multimedias VALUES (8, 1, NULL, '2017-01-26 18:11:10', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/798130f65d286205a6325ef533858fbc6ce81ad0afd8d26b4c7716388bbcc3bb1/images/bicicletario_sc2.png', 0);
INSERT INTO multimedias VALUES (9, 1, NULL, '2017-01-26 18:45:32', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/63dcb488f0e1ea0fa7e02d4a26270005d43f73c8fafb7141cf685bc3b392dcb16/images/bicicletario_sc1.png', 0);
INSERT INTO multimedias VALUES (10, 1, NULL, '2017-01-26 18:45:32', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/63dcb488f0e1ea0fa7e02d4a26270005d43f73c8fafb7141cf685bc3b392dcb16/images/bicicletario_sc2.png', 0);
INSERT INTO multimedias VALUES (11, 1, NULL, '2017-01-26 18:48:23', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/dae30693d68e08df98e50f87b00328cd99e27caa86b873f067ad31bf0213453e7/images/bicicletario_sc1.png', 0);
INSERT INTO multimedias VALUES (12, 1, NULL, '2017-01-26 18:48:24', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/dae30693d68e08df98e50f87b00328cd99e27caa86b873f067ad31bf0213453e7/images/bicicletario_sc2.png', 0);
INSERT INTO multimedias VALUES (13, 1, NULL, '2017-01-26 19:05:48', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/798130f65d286205a6325ef533858fbc6ce81ad0afd8d26b4c7716388bbcc3bb1/images/bicicletario_sc1.png', 0);
INSERT INTO multimedias VALUES (14, 1, NULL, '2017-01-26 19:05:48', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/798130f65d286205a6325ef533858fbc6ce81ad0afd8d26b4c7716388bbcc3bb1/images/bicicletario_sc2.png', 0);
INSERT INTO multimedias VALUES (15, 1, NULL, '2017-01-26 19:11:04', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/7b1403a21d566ab260697b6be6071b8a6584055c1e3c5f35531c038faccbea5f3/images/bicicletario_sc1.png', 0);
INSERT INTO multimedias VALUES (16, 1, NULL, '2017-01-26 19:11:04', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/7b1403a21d566ab260697b6be6071b8a6584055c1e3c5f35531c038faccbea5f3/images/bicicletario_sc2.png', 0);
INSERT INTO multimedias VALUES (17, 1, NULL, '2017-01-26 19:56:05', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/c6b73e816c0d379fdc8d48cfc960d4d678ecc90a1e3bd9ae7cd00f95e7ec841d4/images/bicicletario_sc2.png', 0);
INSERT INTO multimedias VALUES (18, 1, NULL, '2017-01-26 19:59:14', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/0e03792ad29adc35de79be9fd53141cc96c36c8376883894eacc8b7cd4d0fec05/images/bicicletario_sc1.png', 0);
INSERT INTO multimedias VALUES (19, 1, NULL, '2017-01-26 20:04:48', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/63dcb488f0e1ea0fa7e02d4a26270005d43f73c8fafb7141cf685bc3b392dcb16/images/bicicletario_sc1.png', 0);
INSERT INTO multimedias VALUES (20, 1, NULL, '2017-01-26 20:07:43', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/dae30693d68e08df98e50f87b00328cd99e27caa86b873f067ad31bf0213453e7/images/bicicletario_sc2.png', 0);
INSERT INTO multimedias VALUES (21, 1, NULL, '2017-01-26 20:09:43', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/8b6be9b5487ea6de330c87b96f7fa1f7730e6e82c2f6496fa3258381b24739098/images/bicicletario_sc1.png', 0);
INSERT INTO multimedias VALUES (22, 1, NULL, '2017-01-26 20:27:33', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/d64d764cbbb0d412e80d0a16521b97918bb4ba7f01f6ece82f718d7bc06906719/images/bicicletario_sc1.png', 0);
INSERT INTO multimedias VALUES (23, 1, NULL, '2017-01-26 20:37:55', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/fcc81c0395a450dcb1a256a8c7837ddacc5c5bfcdccab781aa6381f47a1c6ebb10/images/bicicletario_sc2.png', 0);
INSERT INTO multimedias VALUES (24, 1, NULL, '2017-01-26 20:50:39', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/798130f65d286205a6325ef533858fbc6ce81ad0afd8d26b4c7716388bbcc3bb1/images/bicicletario_sc1.png', 0);
INSERT INTO multimedias VALUES (25, 1, NULL, '2017-01-26 20:53:53', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/7b1403a21d566ab260697b6be6071b8a6584055c1e3c5f35531c038faccbea5f3/images/bicicletario_sc2.png', 0);
INSERT INTO multimedias VALUES (26, 1, NULL, '2017-01-26 21:00:23', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/c6b73e816c0d379fdc8d48cfc960d4d678ecc90a1e3bd9ae7cd00f95e7ec841d4/images/bicicletario_sc2.png', 0);
INSERT INTO multimedias VALUES (27, 1, NULL, '2017-01-26 21:02:59', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/0e03792ad29adc35de79be9fd53141cc96c36c8376883894eacc8b7cd4d0fec05/images/bicicletario_sc2.png', 0);
INSERT INTO multimedias VALUES (28, 1, NULL, '2017-01-26 21:09:37', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/63dcb488f0e1ea0fa7e02d4a26270005d43f73c8fafb7141cf685bc3b392dcb16/images/bicicletario_sc2.png', 0);
INSERT INTO multimedias VALUES (29, 1, NULL, '2017-01-26 21:24:45', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/dae30693d68e08df98e50f87b00328cd99e27caa86b873f067ad31bf0213453e7/images/Maki_Icons_By_Mapbox.png', 0);
INSERT INTO multimedias VALUES (30, 1, NULL, '2017-01-26 21:25:54', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/8b6be9b5487ea6de330c87b96f7fa1f7730e6e82c2f6496fa3258381b24739098/images/Maki_Icons_By_Mapbox.png', 0);
INSERT INTO multimedias VALUES (31, 1, NULL, '2017-01-26 22:04:42', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/d64d764cbbb0d412e80d0a16521b97918bb4ba7f01f6ece82f718d7bc06906719/images/bicycle_512.png', 0);
INSERT INTO multimedias VALUES (32, 1, NULL, '2017-01-26 22:06:36', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/fcc81c0395a450dcb1a256a8c7837ddacc5c5bfcdccab781aa6381f47a1c6ebb10/images/alert_construction_40.png', 0);
INSERT INTO multimedias VALUES (33, 1, NULL, '2017-01-26 22:07:29', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/6a6749f90ef25cbf7fdd7cdf986cbb91ad3b860ad548833576bdf94de65774ce11/images/alert_construction_bk_80.png', 0);
INSERT INTO multimedias VALUES (34, 1, NULL, '2017-01-26 22:10:31', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/798130f65d286205a6325ef533858fbc6ce81ad0afd8d26b4c7716388bbcc3bb1/images/bicicletario_sc1.png', 0);
INSERT INTO multimedias VALUES (35, 1, NULL, '2017-01-26 22:10:31', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/798130f65d286205a6325ef533858fbc6ce81ad0afd8d26b4c7716388bbcc3bb1/images/bicicletario_sc2.png', 0);
INSERT INTO multimedias VALUES (36, 1, NULL, '2017-01-26 22:12:38', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/fcbe800376b367d2ee2aff49f589e68105223f1ef74675c4527ecbbd86f833232/images/bicicletario_sc1.png', 0);
INSERT INTO multimedias VALUES (37, 1, NULL, '2017-01-27 18:59:05', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/798130f65d286205a6325ef533858fbc6ce81ad0afd8d26b4c7716388bbcc3bb1/images/bicicletario_sc1.png', 0);
INSERT INTO multimedias VALUES (38, 1, NULL, '2017-01-27 20:43:02', 'C:\Users\User\Dropbox\www\app/web/bike-keepers/fcbe800376b367d2ee2aff49f589e68105223f1ef74675c4527ecbbd86f833232/images/bike_park_sc.png', 0);
INSERT INTO multimedias VALUES (39, 1, NULL, '2017-05-03 19:58:20', 'C:\Users\User\Dropbox\www\app/web/bs-contents/bike-keepers/7b1403a21d566ab260697b6be6071b8a6584055c1e3c5f35531c038faccbea5f3/images/13221035_1190969917582113_5869141520709282375_n.jpg', 0);
INSERT INTO multimedias VALUES (40, 1, NULL, '2017-05-09 11:38:21', 'C:\Users\User\Dropbox\www\app/web/bs-contents/bike-keepers/c6b73e816c0d379fdc8d48cfc960d4d678ecc90a1e3bd9ae7cd00f95e7ec841d4/images/php-logo.png', 0);
INSERT INTO multimedias VALUES (41, 1, NULL, '2017-05-09 11:44:41', 'C:\Users\User\Dropbox\www\app/web/bs-contents/bike-keepers/0e03792ad29adc35de79be9fd53141cc96c36c8376883894eacc8b7cd4d0fec05/images/leafletJS-logo.png', 0);
INSERT INTO multimedias VALUES (42, 1, NULL, '2017-05-09 11:47:23', 'C:\Users\User\Dropbox\www\app/web/bs-contents/bike-keepers/63dcb488f0e1ea0fa7e02d4a26270005d43f73c8fafb7141cf685bc3b392dcb16/images/how_gps_works.png', 0);
INSERT INTO multimedias VALUES (43, 1, NULL, '2017-05-09 12:01:17', 'C:\Users\User\Dropbox\www\app/web/bs-contents/bike-keepers/dae30693d68e08df98e50f87b00328cd99e27caa86b873f067ad31bf0213453e7/images/imagem_pedestre_ciclista_km_percorridos.png', 0);
INSERT INTO multimedias VALUES (44, 1, NULL, '2017-05-09 12:04:53', 'C:\Users\User\Dropbox\www\app/web/bs-contents/bike-keepers/8b6be9b5487ea6de330c87b96f7fa1f7730e6e82c2f6496fa3258381b24739098/images/postgis-logo.png', 0);
INSERT INTO multimedias VALUES (45, 1, NULL, '2017-05-09 12:07:11', 'C:\Users\User\Dropbox\www\app/web/bs-contents/bike-keepers/d64d764cbbb0d412e80d0a16521b97918bb4ba7f01f6ece82f718d7bc06906719/images/telas1.png', 0);
INSERT INTO multimedias VALUES (46, 1, NULL, '2017-05-09 12:08:34', 'C:\Users\User\Dropbox\www\app/web/bs-contents/bike-keepers/fcc81c0395a450dcb1a256a8c7837ddacc5c5bfcdccab781aa6381f47a1c6ebb10/images/how_gps_works.png', 0);
INSERT INTO multimedias VALUES (47, 1, NULL, '2017-05-09 12:10:42', 'C:\Users\User\Dropbox\www\app/web/bs-contents/bike-keepers/6a6749f90ef25cbf7fdd7cdf986cbb91ad3b860ad548833576bdf94de65774ce11/images/arquitetura_de_um_SIG.png', 0);
INSERT INTO multimedias VALUES (48, 1, NULL, '2017-05-09 12:11:45', 'C:\Users\User\Dropbox\www\app/web/bs-contents/bike-keepers/da07b04761b09443b9c392574fc4eac0925d0a3afccb8283f921dc0ee178ddd012/images/duolingo_facebook.png', 0);
INSERT INTO multimedias VALUES (49, 1, NULL, '2017-05-09 12:12:49', 'C:\Users\User\Dropbox\www\app/web/bs-contents/bike-keepers/9688bc68aa8dc2d9b51acf427d9c0c9936d920577b52861c4956db368eb22bfb13/images/telas9.png', 0);
INSERT INTO multimedias VALUES (50, 1, NULL, '2017-05-09 12:13:36', 'C:\Users\User\Dropbox\www\app/web/bs-contents/bike-keepers/01e0ecf778c4cc97ab1c626a954ca9e2ce2ce29a1ef9de68dd0c7faebc7962d514/images/telas4.png', 0);
INSERT INTO multimedias VALUES (51, 1, NULL, '2017-05-09 12:14:55', 'C:\Users\User\Dropbox\www\app/web/bs-contents/bike-keepers/01bc5f8d88b3ddc66e7196bdd672bf69d6cf54cadc43eda05b3b178c78b568b015/images/arquitetura_geoapi.jpg', 0);
INSERT INTO multimedias VALUES (52, 1, NULL, '2017-05-09 12:17:15', 'C:\Users\User\Dropbox\www\app/web/bs-contents/bike-keepers/fbfa7649d9b8ef423337faf13d3b15d6c5ff23121476e2d6b51fb7052849f6cf16/images/redes_sociais1.png', 0);
INSERT INTO multimedias VALUES (53, 1, NULL, '2017-05-09 12:21:24', 'C:\Users\User\Dropbox\www\app/web/bs-contents/bike-keepers/f4da9f0fb3cab7656917532e4b7507fe17c0157d0c90b5f6521d0b5b3997e61d17/images/php-logo.png', 0);
INSERT INTO multimedias VALUES (54, 1, NULL, '2017-05-10 13:40:18', 'C:\Users\User\Dropbox\www\app/web/bs-contents/bike-keepers/2bd8e87de8bd2e7ea1ba3acd3a45a052eeb04c66657134651d1538ce0186779c18/images/IMG_0075.jpg', 0);
INSERT INTO multimedias VALUES (55, 1, NULL, '2017-05-10 13:47:51', 'C:\Users\User\Dropbox\www\app/web/bs-contents/bike-keepers/ffeef7dfc257127d87214c15e3afb6ec110928b4059d6d4dbe285c9eaf95ee6919/images/IMG_0015.jpg', 0);
INSERT INTO multimedias VALUES (56, 1, NULL, '2017-05-10 13:56:46', 'C:\Users\User\Dropbox\www\app/web/bs-contents/bike-keepers/63ae6a07abf3e7030ce4a43d681746f57d79e4501b9654407efada41edc4d10120/images/IMG_0013.jpg', 0);
INSERT INTO multimedias VALUES (57, 1, NULL, '2017-05-10 14:00:30', 'C:\Users\User\Dropbox\www\app/web/bs-contents/bike-keepers/b098cceb6eb93af9a666550b60d9ea66bd0aba757f37812a8961b975cfd606a621/images/IMG_0001.jpg', 0);
INSERT INTO multimedias VALUES (58, 1, NULL, '2017-05-15 21:48:45', 'C:\Users\User\Dropbox\www\app/web/bs-contents/bike-keepers/e58912834f6450d62be7a1b59f202c2542e6a465e5f89ea56d1c4cc24af0f34a22/images/MeControlXXLUserTile.jpg', 0);
INSERT INTO multimedias VALUES (59, 1, NULL, '2017-05-20 18:53:19', 'C:\Users\User\Dropbox\www\app/web/bs-contents/bike-keepers/fab322ba15e25daf457023e35a779f75acfb65261b64636069b3a3d18ef262e723/images/gps_trilateracao.png', 0);
INSERT INTO multimedias VALUES (60, 1, NULL, '2017-05-21 15:55:51', 'C:\Users\User\Dropbox\www\app/web/bs-contents/bike-keepers/3bed12d7fede28f9e5750ff8b2c2efd94db73dad71195a12f12ecde9df370b9124/images/agps_opration.jpg', 0);
INSERT INTO multimedias VALUES (61, 1, NULL, '2017-05-21 20:14:47', 'C:\Users\User\Dropbox\www\app/web/bs-contents/bike-keepers/c8cfc93e3d74e487d8edb6b967a9a8ba4071be02f0fb2265f62b14369579e26925/images/how_gps_works.png', 0);
INSERT INTO multimedias VALUES (62, 1, NULL, '2017-05-21 20:15:11', 'C:\Users\User\Dropbox\www\app/web/bs-contents/bike-keepers/671b452af42960c521138a6d34303667f44a81f1f73ca4cf75f695bebcaa14fc26/images/how_gps_works.png', 0);
INSERT INTO multimedias VALUES (63, 1, NULL, '2017-05-21 20:16:45', 'C:\Users\User\Dropbox\www\app/web/bs-contents/bike-keepers/671b452af42960c521138a6d34303667f44a81f1f73ca4cf75f695bebcaa14fc26/images/13221035_1190969917582113_5869141520709282375_n.jpg', 0);
INSERT INTO multimedias VALUES (64, 1, NULL, '2017-05-21 20:19:37', 'C:\Users\User\Dropbox\www\app/web/bs-contents/bike-keepers/ed030e4c1f2caecb5a980160ae6e08d64cc7ae9207cd0884ebad9221acbc3f2a28/images/leafletJS-logo.png', 0);
INSERT INTO multimedias VALUES (65, 1, NULL, '2017-05-21 20:21:22', 'C:\Users\User\Dropbox\www\app/web/bs-contents/bike-keepers/92ef4d128f5428735be5bd332510bb5cfe51c05e490cfefe5afb47b3803cda8f29/images/leafletJS-logo.png', 0);
INSERT INTO multimedias VALUES (66, 1, NULL, '2017-05-24 16:34:44', 'C:\Users\User\Dropbox\www\app/web/bs-contents/bike-keepers/92ef4d128f5428735be5bd332510bb5cfe51c05e490cfefe5afb47b3803cda8f29/images/348126.jpg', 0);
INSERT INTO multimedias VALUES (67, 1, NULL, '2017-05-24 18:05:30', 'C:\Users\User\Dropbox\www\app/web/bs-contents/bike-keepers/b4005924e7d42e743cd8bb802e4eca50f1b2a9239fe0ed47496c12ea30ff74fe31/images/MeControlXXLUserTile.jpg', 0);
INSERT INTO multimedias VALUES (68, 1, NULL, '2017-05-25 02:25:20', 'C:\Users\User\Dropbox\www\app/web/bs-contents/bike-keepers/eae2a071a1a22872e957f3ea3696dd2216d34a6a78702e1601098c7e4cf727ae32/images/12809609_741716492597295_5854081116663842055_n.jpg', 0);
INSERT INTO multimedias VALUES (69, 1, NULL, '2017-05-25 08:47:19', '348126.jpg', 0);
INSERT INTO multimedias VALUES (70, 1, NULL, '2017-05-25 08:47:19', 'MeControlXXLUserTile.jpg', 0);
INSERT INTO multimedias VALUES (71, 1, NULL, '2017-05-25 08:54:31', '348126.jpg', 0);
INSERT INTO multimedias VALUES (72, 1, NULL, '2017-05-25 08:54:31', '12809609_741716492597295_5854081116663842055_n.jpg', 0);
INSERT INTO multimedias VALUES (73, 1, NULL, '2017-05-25 08:54:31', 'MeControlXXLUserTile.jpg', 0);
INSERT INTO multimedias VALUES (74, 1, NULL, '2017-05-25 08:58:40', '348126.jpg', 0);
INSERT INTO multimedias VALUES (75, 1, NULL, '2017-05-25 08:58:40', '12809609_741716492597295_5854081116663842055_n.jpg', 0);
INSERT INTO multimedias VALUES (76, 1, NULL, '2017-05-25 08:58:40', 'MeControlXXLUserTile.jpg', 0);
INSERT INTO multimedias VALUES (77, 1, NULL, '2017-05-25 11:52:52', '12809609_741716492597295_5854081116663842055_n.jpg', 0);
INSERT INTO multimedias VALUES (78, 1, NULL, '2017-05-25 11:54:01', '12809609_741716492597295_5854081116663842055_n.jpg', 0);
INSERT INTO multimedias VALUES (79, 1, NULL, '2017-05-25 11:54:49', '12809609_741716492597295_5854081116663842055_n.jpg', 0);
INSERT INTO multimedias VALUES (80, 1, NULL, '2017-05-25 14:57:25', '12809609_741716492597295_5854081116663842055_n.jpg', 0);
INSERT INTO multimedias VALUES (81, 1, NULL, '2017-05-25 15:39:55', 'MeControlXXLUserTile.jpg', 0);
INSERT INTO multimedias VALUES (82, 1, NULL, '2017-05-25 15:43:21', 'solar_storm_1920_1080.jpg', 0);
INSERT INTO multimedias VALUES (83, 1, NULL, '2017-05-25 16:05:15', 'black_hole_120_1080.jpg', 0);
INSERT INTO multimedias VALUES (84, 1, NULL, '2017-05-25 18:39:33', 'wormhole2_1920_1080.jpg', 0);


--
-- TOC entry 4386 (class 0 OID 0)
-- Dependencies: 282
-- Name: multimedias_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('multimedias_id_seq', 84, true);


--
-- TOC entry 3967 (class 0 OID 20651)
-- Dependencies: 266
-- Data for Name: pointcloud_formats; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 4277 (class 0 OID 28618)
-- Dependencies: 292
-- Data for Name: routes; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 4387 (class 0 OID 0)
-- Dependencies: 291
-- Name: routes_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('routes_id_seq', 1, false);


--
-- TOC entry 3971 (class 0 OID 18883)
-- Dependencies: 193
-- Data for Name: spatial_ref_sys; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 4260 (class 0 OID 28334)
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
-- TOC entry 3970 (class 0 OID 20621)
-- Dependencies: 263
-- Data for Name: us_gaz; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 3968 (class 0 OID 20607)
-- Dependencies: 261
-- Data for Name: us_lex; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 3969 (class 0 OID 20635)
-- Dependencies: 265
-- Data for Name: us_rules; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 4290 (class 0 OID 45172)
-- Dependencies: 310
-- Data for Name: user_alert_nonexistence; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO user_alert_nonexistence VALUES (2, 40, '2017-05-16 10:27:31');
INSERT INTO user_alert_nonexistence VALUES (1, 41, '2017-05-25 04:59:44');


--
-- TOC entry 4289 (class 0 OID 45158)
-- Dependencies: 309
-- Data for Name: user_alert_rates; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO user_alert_rates VALUES (1, 22, '2017-04-30 01:50:51', 'dislikes', NULL);
INSERT INTO user_alert_rates VALUES (1, 30, '2017-04-30 08:32:37', 'likes', NULL);
INSERT INTO user_alert_rates VALUES (2, 22, '2017-05-02 17:16:17', 'likes', NULL);
INSERT INTO user_alert_rates VALUES (1, 37, '2017-05-12 10:46:45', 'dislikes', NULL);
INSERT INTO user_alert_rates VALUES (1, 34, '2017-05-12 15:08:57', 'likes', NULL);
INSERT INTO user_alert_rates VALUES (1, 31, '2017-05-12 16:59:20', 'dislikes', NULL);


--
-- TOC entry 4296 (class 0 OID 45317)
-- Dependencies: 316
-- Data for Name: user_bike_keeper_nonexistence; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO user_bike_keeper_nonexistence VALUES (4, 22, '2017-05-22 06:19:03');
INSERT INTO user_bike_keeper_nonexistence VALUES (2, 22, '2017-05-22 06:19:17');
INSERT INTO user_bike_keeper_nonexistence VALUES (2, 8, '2017-05-22 06:19:24');
INSERT INTO user_bike_keeper_nonexistence VALUES (2, 9, '2017-05-22 06:22:53');
INSERT INTO user_bike_keeper_nonexistence VALUES (2, 6, '2017-05-25 04:04:29');


--
-- TOC entry 4295 (class 0 OID 45302)
-- Dependencies: 315
-- Data for Name: user_bike_keeper_rates; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO user_bike_keeper_rates VALUES (2, 8, '2017-05-14 00:30:17', 'likes', NULL);
INSERT INTO user_bike_keeper_rates VALUES (2, 9, '2017-05-14 01:27:02', 'dislikes', NULL);
INSERT INTO user_bike_keeper_rates VALUES (2, 7, '2017-05-20 21:26:05', 'dislikes', NULL);
INSERT INTO user_bike_keeper_rates VALUES (2, 22, '2017-05-20 21:26:57', 'likes', NULL);
INSERT INTO user_bike_keeper_rates VALUES (4, 2, '2017-05-21 17:56:14', 'likes', NULL);
INSERT INTO user_bike_keeper_rates VALUES (2, 2, '2017-05-21 17:56:39', 'dislikes', NULL);
INSERT INTO user_bike_keeper_rates VALUES (1, 6, '2017-05-12 14:38:35', 'likes', NULL);
INSERT INTO user_bike_keeper_rates VALUES (1, 3, '2017-05-12 15:50:00', 'likes', NULL);
INSERT INTO user_bike_keeper_rates VALUES (1, 8, '2017-05-12 17:05:50', 'likes', NULL);
INSERT INTO user_bike_keeper_rates VALUES (1, 4, '2017-05-12 17:11:33', 'dislikes', NULL);
INSERT INTO user_bike_keeper_rates VALUES (1, 20, '2017-05-12 17:31:05', 'likes', NULL);


--
-- TOC entry 4284 (class 0 OID 45063)
-- Dependencies: 301
-- Data for Name: user_feedings; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 4388 (class 0 OID 0)
-- Dependencies: 300
-- Name: user_feedings_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('user_feedings_id_seq', 1, false);


--
-- TOC entry 4280 (class 0 OID 36857)
-- Dependencies: 295
-- Data for Name: user_friendship_requests; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 4389 (class 0 OID 0)
-- Dependencies: 294
-- Name: user_friendship_requests_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('user_friendship_requests_id_seq', 48, true);


--
-- TOC entry 4278 (class 0 OID 36850)
-- Dependencies: 293
-- Data for Name: user_friendships; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO user_friendships VALUES (4, 2, '2017-04-11 06:35:55');
INSERT INTO user_friendships VALUES (1, 2, '2017-04-11 07:25:03');
INSERT INTO user_friendships VALUES (1, 4, '2017-05-25 05:12:15');


--
-- TOC entry 4390 (class 0 OID 0)
-- Dependencies: 208
-- Name: user_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('user_id_seq', 4, true);


--
-- TOC entry 4298 (class 0 OID 45351)
-- Dependencies: 318
-- Data for Name: user_navigation_routes; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 4391 (class 0 OID 0)
-- Dependencies: 317
-- Name: user_navigation_routes_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('user_navigation_routes_id_seq', 316, true);


--
-- TOC entry 4288 (class 0 OID 45093)
-- Dependencies: 305
-- Data for Name: user_sharing_types; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 4392 (class 0 OID 0)
-- Dependencies: 304
-- Name: user_sharing_types_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('user_sharing_types_id_seq', 1, false);


--
-- TOC entry 4282 (class 0 OID 45054)
-- Dependencies: 299
-- Data for Name: user_sharings; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 4393 (class 0 OID 0)
-- Dependencies: 298
-- Name: user_sharings_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('user_sharings_id_seq', 1, false);


--
-- TOC entry 4394 (class 0 OID 0)
-- Dependencies: 278
-- Name: user_tracking_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('user_tracking_id_seq', 1, false);


--
-- TOC entry 4264 (class 0 OID 28394)
-- Dependencies: 279
-- Data for Name: user_trackings; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 4254 (class 0 OID 20012)
-- Dependencies: 207
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO users VALUES (1, 'Marcus Vinicius', 'Faccion', 'Marcus', 'marcusfaccion', 'marcusfaccion@bol.com.br', '23064140', '2016-04-13 11:00:00', '2017-05-25 00:55:38', 'hEhA7gNNCs8NylQ28bjtVpJyb1hqFx1P', NULL, 'a99aa5e1912ac0ee7d0b2f8ce3e272ee', 0, NULL, NULL, 'Marcus Vinicius Faccion');
INSERT INTO users VALUES (2, 'Thatiane', 'Copque', 'Thaty', 'thatiane', 'thatiane_copque@hotmail.com', '123', '2016-11-20 04:38:59.73246', '2017-05-25 03:47:42', 'fHAyDZexEGjxh4RvCbHkd4fIP6OQPJWE', NULL, '9e459de99798f751a83cab6667d83491', 0, 'Primeiro nome do esposo?', 'marcus', 'Thatiane Copque');
INSERT INTO users VALUES (4, 'João Marcus', 'da Silva Gomes', 'João', 'joaocarlos', 'joaocarlos_1544812120@bol.com.br', '123456', '2017-04-09 22:38:53', '2017-05-25 04:58:13', '03mQZnINC6GUt4Bro2j27UzHC0196IOl', NULL, '34bbc48a4dd10814f2a31e82ecd9f214', 0, 'teste', 'teste', 'João Marcus da Silva Gomes');
INSERT INTO users VALUES (0, 'Bike', 'Social', 'BikeSocial', 'bikesocial', 'marcusfaccion@bol.com.br', 'bikesocial', '2016-11-03 13:44:18.824322', '2017-04-11 04:28:56', NULL, NULL, '856399845ab74597eda9777f091b277a', 0, NULL, NULL, 'Bike Social');
INSERT INTO users VALUES (3, 'Default', 'Default', 'Default', 'default', 'default@hotmail.com', '123456', '2016-11-20 05:07:14.755831', NULL, 'MFKZdmbnLVXRN3JJqCQVs5BRk-2IsoQE', NULL, '41378f6ece1dbf69d8eebe741a33c11d', 0, 'Primeiro nome do seu criador?', 'marcus', 'Default Default');


--
-- TOC entry 4286 (class 0 OID 45074)
-- Dependencies: 303
-- Data for Name: view_levels; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 4395 (class 0 OID 0)
-- Dependencies: 302
-- Name: view_levels_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('view_levels_id_seq', 1, false);


SET search_path = tiger, pg_catalog;

--
-- TOC entry 3972 (class 0 OID 20171)
-- Dependencies: 210
-- Data for Name: geocode_settings; Type: TABLE DATA; Schema: tiger; Owner: postgres
--



--
-- TOC entry 3973 (class 0 OID 20526)
-- Dependencies: 254
-- Data for Name: pagc_gaz; Type: TABLE DATA; Schema: tiger; Owner: postgres
--



--
-- TOC entry 3974 (class 0 OID 20538)
-- Dependencies: 256
-- Data for Name: pagc_lex; Type: TABLE DATA; Schema: tiger; Owner: postgres
--



--
-- TOC entry 3975 (class 0 OID 20550)
-- Dependencies: 258
-- Data for Name: pagc_rules; Type: TABLE DATA; Schema: tiger; Owner: postgres
--



-- Completed on 2017-05-25 16:03:53

--
-- PostgreSQL database dump complete
--

