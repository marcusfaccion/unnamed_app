DROP FUNCTION public.st_disable_alerts(integer);

CREATE OR REPLACE FUNCTION public.st_disable_alerts(_id integer DEFAULT 0)
  RETURNS integer AS
$BODY$

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

END; $BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION public.st_disable_alerts(integer)
  OWNER TO postgres;