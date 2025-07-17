
drop procedure if exists insert_hnhc_to_schedule_details;
CREATE PROCEDURE insert_hnhc_to_schedule_details()
BEGIN
    DECLARE i INT DEFAULT 1;

    WHILE i <= 31
        DO
            SET @sql = CONCAT('
           INSERT INTO schedule_details (employee_id, schedule_id, date, hnhc, created_at, updated_at)
            select detail.employee_id,
                   detail.celender_id,
                   DATE_ADD(c.date, interval ',i-1,' day),
                   if(UPPER(TRIM(detail.day',i,')) IN (\'N\', \'D\', \'LN\', \'TC\', \'X\'), UPPER(TRIM(detail.day',i,')), NULL),
                   NOW(),
                   NOW()
            from celender_detail_hnhc detail
                     left join celenders c on detail.celender_id = c.id
            where YEAR(c.date) = YEAR(DATE_ADD(c.date, interval ',i-1,' day))
              AND MONTH(c.date) = MONTH(DATE_ADD(c.date, interval ',i-1,' day))
            ON DUPLICATE KEY
                UPDATE hnhc       = VALUES(hnhc),
                       updated_at = NOW();
            '
                       );

            PREPARE stmt FROM @sql;
            EXECUTE stmt;
            DEALLOCATE PREPARE stmt;

            SET i = i + 1;
        END WHILE;
END;
