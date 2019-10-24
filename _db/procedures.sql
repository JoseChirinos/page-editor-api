-- ----------------INSERT NURSE
DROP PROCEDURE IF EXISTS insertNurse;
DELIMITER $$
CREATE PROCEDURE insertNurse(
	IN _rfid VARCHAR(11),
	IN _firstName VARCHAR(100),
	IN _lastName VARCHAR(100),
	IN _ci VARCHAR(20),
	IN _cellphone VARCHAR(50)
)
BEGIN

    DECLARE error INT DEFAULT 0;
    DECLARE msg TEXT DEFAULT '';
    DECLARE failed BOOLEAN DEFAULT false;
    DECLARE idRepeat INT;
    DECLARE idInsert INT;
	DECLARE CONTINUE HANDLER FOR SQLEXCEPTION
	BEGIN
		SET error=1;
		SELECT "HANDLER FOR SQLEXCEPTION" error,"Transacción no completada: insertNurse." msg,'true' failed;
	END;

    START TRANSACTION;

    -- CHECK REPEAT
    SET idRepeat = (SELECT id_nurse FROM nurse WHERE ci = _ci OR rfid = _rfid ORDER BY id_nurse DESC LIMIT 1);
    
    if isnull(idRepeat) THEN
        INSERT INTO nurse VALUES(
            null,
            _rfid,
            _firstName, 
            _lastName, 
            _ci,	
            _cellphone, 
            CURRENT_TIMESTAMP, 
            CURRENT_TIMESTAMP,
            1
        );
        SET msg = "Insertado con exito";
        SET idInsert = @@identity;
    ELSE
		SET msg = "Ya fue registrado";
        SET idInsert = idRepeat;
        SET failed = true;
    END IF;
    
    IF (error = 1) THEN
		ROLLBACK;
	ELSE
		SELECT idInsert, msg, failed;
		COMMIT;
	END IF;
END
$$

CALL insertNurse('6f87e8ff44', 'Lady', 'Ramos', '12395700','79413052');

-- ----------------UPDATED NURSE
DROP PROCEDURE IF EXISTS updateNurse;
DELIMITER $$
CREATE PROCEDURE updateNurse(
	IN _idNurse INT(11),
	IN _firstName VARCHAR(100),
	IN _lastName VARCHAR(100),
	IN _ci VARCHAR(20),
	IN _cellphone VARCHAR(50)
)
BEGIN

    DECLARE error INT DEFAULT 0;
    DECLARE msg TEXT DEFAULT '';
    DECLARE failed BOOLEAN DEFAULT false;
    DECLARE idRepeat INT;
    DECLARE idInsert INT;
	DECLARE CONTINUE HANDLER FOR SQLEXCEPTION
	BEGIN
		SET error=1;
		SELECT "HANDLER FOR SQLEXCEPTION" error,"Transacción no completada: insertNurse." msg,'true' failed;
	END;

    START TRANSACTION;

    -- CHECK REPEAT
    SET idRepeat = (SELECT id_nurse FROM nurse WHERE id_nurse = _idNurse ORDER BY id_nurse DESC LIMIT 1);
    
    if !isnull(idRepeat) THEN
        SET idRepeat = (SELECT id_nurse FROM nurse WHERE id_nurse != _idNurse AND ci = _ci ORDER BY id_nurse DESC LIMIT 1);
        if isnull(idRepeat) THEN
			UPDATE nurse SET
				first_name = _firstName, 
				last_name = _lastName, 
				ci = _ci,	
				cellphone = _cellphone,
				updated = CURRENT_TIMESTAMP
				WHERE id_nurse = _idNurse;
			SET msg = "Actualizado con exito";
			SET idInsert = _idNurse;
        ELSE
			SET msg = "El ci ya esta en uso";
			SET idInsert = idRepeat;
            SET failed = true;
        END IF;
    ELSE
		SET msg = "El usuario no esta registrado";
        SET idInsert = idRepeat;
        SET failed = true;
    END IF;
    
    IF (error = 1) THEN
		ROLLBACK;
	ELSE
		SELECT idInsert, msg, failed;
		COMMIT;
	END IF;
END
$$

CALL updateNurse(1, 'Lady Ximena', 'Ramos Lopez', '12395700','79413052');