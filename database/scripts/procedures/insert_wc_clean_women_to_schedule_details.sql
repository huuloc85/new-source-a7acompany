
drop procedure if exists insert_wc_clean_women_to_schedule_details;
CREATE PROCEDURE insert_wc_clean_women_to_schedule_details()
BEGIN
    DECLARE i INT DEFAULT 1;

    WHILE i <= 31
        DO
            SET @sql = CONCAT('
          INSERT INTO schedule_details (employee_id, schedule_id, date, is_wc_clean_women, created_at, updated_at)
            select detail.employee_id,
                   detail.celender_id,
                   DATE_ADD(c.date, interval ', i - 1, ' day),
                   if(detail.day', i, ' IS NOT NULL AND detail.day', i, ' != \'\', 1, 0),
                   NOW(),
                   NOW()
            from celender_detail_wc_clean_women detail
            left join celenders c on detail.celender_id = c.id
            where YEAR(c.date) = YEAR(DATE_ADD(c.date, interval ', i - 1, ' day))
            AND MONTH(c.date) = MONTH(DATE_ADD(c.date, interval ', i - 1, ' day))
            ON DUPLICATE KEY UPDATE is_wc_clean_women = VALUES(is_wc_clean_women),
                            updated_at      = NOW();
            '
                       );

            PREPARE stmt FROM @sql;
            EXECUTE stmt;
            DEALLOCATE PREPARE stmt;

            SET i = i + 1;
        END WHILE;
END;