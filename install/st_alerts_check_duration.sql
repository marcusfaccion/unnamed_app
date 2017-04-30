CREATE OR REPLACE FUNCTION public.st_alerts_check_duration(_id integer DEFAULT 0)
  RETURNS integer AS
$BODY$

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

END; $BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION public.st_alerts_check_duration(integer)
  OWNER TO postgres;


