
drop procedure if exists trg_insert_hnhc_to_schedule_details;
CREATE PROCEDURE trg_insert_hnhc_to_schedule_details(IN p_employee_id BIGINT, IN p_celender_id BIGINT)
BEGIN
    -- Use a single query with UNION to handle all days at once without dynamic SQL
    INSERT INTO schedule_details (employee_id, schedule_id, date, hnhc, created_at, updated_at)
    SELECT 
        detail.employee_id,
        detail.celender_id,
        DATE_ADD(c.date, INTERVAL (days.day_num - 1) DAY) as date,
        CASE days.day_num
            WHEN 1 THEN IF(UPPER(TRIM(detail.day1)) IN ('N', 'D', 'LN', 'TC', 'X'), UPPER(TRIM(detail.day1)), NULL)
            WHEN 2 THEN IF(UPPER(TRIM(detail.day2)) IN ('N', 'D', 'LN', 'TC', 'X'), UPPER(TRIM(detail.day2)), NULL)
            WHEN 3 THEN IF(UPPER(TRIM(detail.day3)) IN ('N', 'D', 'LN', 'TC', 'X'), UPPER(TRIM(detail.day3)), NULL)
            WHEN 4 THEN IF(UPPER(TRIM(detail.day4)) IN ('N', 'D', 'LN', 'TC', 'X'), UPPER(TRIM(detail.day4)), NULL)
            WHEN 5 THEN IF(UPPER(TRIM(detail.day5)) IN ('N', 'D', 'LN', 'TC', 'X'), UPPER(TRIM(detail.day5)), NULL)
            WHEN 6 THEN IF(UPPER(TRIM(detail.day6)) IN ('N', 'D', 'LN', 'TC', 'X'), UPPER(TRIM(detail.day6)), NULL)
            WHEN 7 THEN IF(UPPER(TRIM(detail.day7)) IN ('N', 'D', 'LN', 'TC', 'X'), UPPER(TRIM(detail.day7)), NULL)
            WHEN 8 THEN IF(UPPER(TRIM(detail.day8)) IN ('N', 'D', 'LN', 'TC', 'X'), UPPER(TRIM(detail.day8)), NULL)
            WHEN 9 THEN IF(UPPER(TRIM(detail.day9)) IN ('N', 'D', 'LN', 'TC', 'X'), UPPER(TRIM(detail.day9)), NULL)
            WHEN 10 THEN IF(UPPER(TRIM(detail.day10)) IN ('N', 'D', 'LN', 'TC', 'X'), UPPER(TRIM(detail.day10)), NULL)
            WHEN 11 THEN IF(UPPER(TRIM(detail.day11)) IN ('N', 'D', 'LN', 'TC', 'X'), UPPER(TRIM(detail.day11)), NULL)
            WHEN 12 THEN IF(UPPER(TRIM(detail.day12)) IN ('N', 'D', 'LN', 'TC', 'X'), UPPER(TRIM(detail.day12)), NULL)
            WHEN 13 THEN IF(UPPER(TRIM(detail.day13)) IN ('N', 'D', 'LN', 'TC', 'X'), UPPER(TRIM(detail.day13)), NULL)
            WHEN 14 THEN IF(UPPER(TRIM(detail.day14)) IN ('N', 'D', 'LN', 'TC', 'X'), UPPER(TRIM(detail.day14)), NULL)
            WHEN 15 THEN IF(UPPER(TRIM(detail.day15)) IN ('N', 'D', 'LN', 'TC', 'X'), UPPER(TRIM(detail.day15)), NULL)
            WHEN 16 THEN IF(UPPER(TRIM(detail.day16)) IN ('N', 'D', 'LN', 'TC', 'X'), UPPER(TRIM(detail.day16)), NULL)
            WHEN 17 THEN IF(UPPER(TRIM(detail.day17)) IN ('N', 'D', 'LN', 'TC', 'X'), UPPER(TRIM(detail.day17)), NULL)
            WHEN 18 THEN IF(UPPER(TRIM(detail.day18)) IN ('N', 'D', 'LN', 'TC', 'X'), UPPER(TRIM(detail.day18)), NULL)
            WHEN 19 THEN IF(UPPER(TRIM(detail.day19)) IN ('N', 'D', 'LN', 'TC', 'X'), UPPER(TRIM(detail.day19)), NULL)
            WHEN 20 THEN IF(UPPER(TRIM(detail.day20)) IN ('N', 'D', 'LN', 'TC', 'X'), UPPER(TRIM(detail.day20)), NULL)
            WHEN 21 THEN IF(UPPER(TRIM(detail.day21)) IN ('N', 'D', 'LN', 'TC', 'X'), UPPER(TRIM(detail.day21)), NULL)
            WHEN 22 THEN IF(UPPER(TRIM(detail.day22)) IN ('N', 'D', 'LN', 'TC', 'X'), UPPER(TRIM(detail.day22)), NULL)
            WHEN 23 THEN IF(UPPER(TRIM(detail.day23)) IN ('N', 'D', 'LN', 'TC', 'X'), UPPER(TRIM(detail.day23)), NULL)
            WHEN 24 THEN IF(UPPER(TRIM(detail.day24)) IN ('N', 'D', 'LN', 'TC', 'X'), UPPER(TRIM(detail.day24)), NULL)
            WHEN 25 THEN IF(UPPER(TRIM(detail.day25)) IN ('N', 'D', 'LN', 'TC', 'X'), UPPER(TRIM(detail.day25)), NULL)
            WHEN 26 THEN IF(UPPER(TRIM(detail.day26)) IN ('N', 'D', 'LN', 'TC', 'X'), UPPER(TRIM(detail.day26)), NULL)
            WHEN 27 THEN IF(UPPER(TRIM(detail.day27)) IN ('N', 'D', 'LN', 'TC', 'X'), UPPER(TRIM(detail.day27)), NULL)
            WHEN 28 THEN IF(UPPER(TRIM(detail.day28)) IN ('N', 'D', 'LN', 'TC', 'X'), UPPER(TRIM(detail.day28)), NULL)
            WHEN 29 THEN IF(UPPER(TRIM(detail.day29)) IN ('N', 'D', 'LN', 'TC', 'X'), UPPER(TRIM(detail.day29)), NULL)
            WHEN 30 THEN IF(UPPER(TRIM(detail.day30)) IN ('N', 'D', 'LN', 'TC', 'X'), UPPER(TRIM(detail.day30)), NULL)
            WHEN 31 THEN IF(UPPER(TRIM(detail.day31)) IN ('N', 'D', 'LN', 'TC', 'X'), UPPER(TRIM(detail.day31)), NULL)
        END as hnhc,
        NOW(),
        NOW()
    FROM celender_detail_hnhc detail
    LEFT JOIN celenders c ON detail.celender_id = c.id
    CROSS JOIN (
        SELECT 1 as day_num UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL
        SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9 UNION ALL SELECT 10 UNION ALL
        SELECT 11 UNION ALL SELECT 12 UNION ALL SELECT 13 UNION ALL SELECT 14 UNION ALL SELECT 15 UNION ALL
        SELECT 16 UNION ALL SELECT 17 UNION ALL SELECT 18 UNION ALL SELECT 19 UNION ALL SELECT 20 UNION ALL
        SELECT 21 UNION ALL SELECT 22 UNION ALL SELECT 23 UNION ALL SELECT 24 UNION ALL SELECT 25 UNION ALL
        SELECT 26 UNION ALL SELECT 27 UNION ALL SELECT 28 UNION ALL SELECT 29 UNION ALL SELECT 30 UNION ALL
        SELECT 31
    ) days
    WHERE detail.employee_id = p_employee_id 
      AND detail.celender_id = p_celender_id
      AND YEAR(c.date) = YEAR(DATE_ADD(c.date, INTERVAL (days.day_num - 1) DAY))
      AND MONTH(c.date) = MONTH(DATE_ADD(c.date, INTERVAL (days.day_num - 1) DAY))
    ON DUPLICATE KEY
        UPDATE hnhc = VALUES(hnhc),
               updated_at = NOW();
END;
