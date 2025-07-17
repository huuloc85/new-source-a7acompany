
drop procedure if exists insert_wc_trash_to_schedule_details;
CREATE PROCEDURE insert_wc_trash_to_schedule_details()
BEGIN
    DECLARE i INT DEFAULT 1;

    WHILE i <= 5
        DO
            SET @sql = CONCAT('
                INSERT INTO schedule_details (employee_id, schedule_id, date, is_wc_trash, created_at, updated_at)
                select employee_id,
                       celender_id,
                       DATE_ADD(c.date, INTERVAL ((7 - DAYOFWEEK(c.date) + 1) % 7 + ',i -1,' * 7)-1 DAY),
                       if(day',i,' IS NOT NULL AND day',i,' != \'\', 1, 0),
                       NOW(),
                       NOW()
                from celender_detail_wc_clean_men wc_men
                         left join celenders c on wc_men.celender_id = c.id
                where YEAR(c.date) =
                      YEAR(DATE_ADD(c.date, INTERVAL ((7 - DAYOFWEEK(c.date) + 1) % 7 + ',i-1,' * 7)-1 DAY))
                  AND MONTH(c.date) =
                      MONTH(DATE_ADD(c.date, INTERVAL ((7 - DAYOFWEEK(c.date) + 1) % 7 + ',i-1,' * 7)-1 DAY))
                ON DUPLICATE KEY UPDATE is_wc_clean_men = VALUES(is_wc_clean_men),
                                        updated_at      = NOW();
            '
                       );

            PREPARE stmt FROM @sql;
            EXECUTE stmt;
            DEALLOCATE PREPARE stmt;

            SET i = i + 1;
        END WHILE;
END;
