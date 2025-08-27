drop procedure if exists trg_insert_eat_room_to_schedule_details;
CREATE PROCEDURE trg_insert_eat_room_to_schedule_details(IN p_employee_id BIGINT, IN p_celender_id BIGINT)
BEGIN
    -- Use a single query with UNION to handle all days at once without dynamic SQL
    INSERT INTO schedule_details (employee_id, schedule_id, date, is_eat_room, created_at, updated_at)
    SELECT 
        detail.employee_id,
        detail.celender_id,
        DATE_ADD(c.date, INTERVAL (days.day_num - 1) DAY) as date,
        CASE days.day_num
            WHEN 1 THEN IF(detail.day1 IS NOT NULL AND detail.day1 != '', 1, 0)
            WHEN 2 THEN IF(detail.day2 IS NOT NULL AND detail.day2 != '', 1, 0)
            WHEN 3 THEN IF(detail.day3 IS NOT NULL AND detail.day3 != '', 1, 0)
            WHEN 4 THEN IF(detail.day4 IS NOT NULL AND detail.day4 != '', 1, 0)
            WHEN 5 THEN IF(detail.day5 IS NOT NULL AND detail.day5 != '', 1, 0)
            WHEN 6 THEN IF(detail.day6 IS NOT NULL AND detail.day6 != '', 1, 0)
            WHEN 7 THEN IF(detail.day7 IS NOT NULL AND detail.day7 != '', 1, 0)
            WHEN 8 THEN IF(detail.day8 IS NOT NULL AND detail.day8 != '', 1, 0)
            WHEN 9 THEN IF(detail.day9 IS NOT NULL AND detail.day9 != '', 1, 0)
            WHEN 10 THEN IF(detail.day10 IS NOT NULL AND detail.day10 != '', 1, 0)
            WHEN 11 THEN IF(detail.day11 IS NOT NULL AND detail.day11 != '', 1, 0)
            WHEN 12 THEN IF(detail.day12 IS NOT NULL AND detail.day12 != '', 1, 0)
            WHEN 13 THEN IF(detail.day13 IS NOT NULL AND detail.day13 != '', 1, 0)
            WHEN 14 THEN IF(detail.day14 IS NOT NULL AND detail.day14 != '', 1, 0)
            WHEN 15 THEN IF(detail.day15 IS NOT NULL AND detail.day15 != '', 1, 0)
            WHEN 16 THEN IF(detail.day16 IS NOT NULL AND detail.day16 != '', 1, 0)
            WHEN 17 THEN IF(detail.day17 IS NOT NULL AND detail.day17 != '', 1, 0)
            WHEN 18 THEN IF(detail.day18 IS NOT NULL AND detail.day18 != '', 1, 0)
            WHEN 19 THEN IF(detail.day19 IS NOT NULL AND detail.day19 != '', 1, 0)
            WHEN 20 THEN IF(detail.day20 IS NOT NULL AND detail.day20 != '', 1, 0)
            WHEN 21 THEN IF(detail.day21 IS NOT NULL AND detail.day21 != '', 1, 0)
            WHEN 22 THEN IF(detail.day22 IS NOT NULL AND detail.day22 != '', 1, 0)
            WHEN 23 THEN IF(detail.day23 IS NOT NULL AND detail.day23 != '', 1, 0)
            WHEN 24 THEN IF(detail.day24 IS NOT NULL AND detail.day24 != '', 1, 0)
            WHEN 25 THEN IF(detail.day25 IS NOT NULL AND detail.day25 != '', 1, 0)
            WHEN 26 THEN IF(detail.day26 IS NOT NULL AND detail.day26 != '', 1, 0)
            WHEN 27 THEN IF(detail.day27 IS NOT NULL AND detail.day27 != '', 1, 0)
            WHEN 28 THEN IF(detail.day28 IS NOT NULL AND detail.day28 != '', 1, 0)
            WHEN 29 THEN IF(detail.day29 IS NOT NULL AND detail.day29 != '', 1, 0)
            WHEN 30 THEN IF(detail.day30 IS NOT NULL AND detail.day30 != '', 1, 0)
            WHEN 31 THEN IF(detail.day31 IS NOT NULL AND detail.day31 != '', 1, 0)
        END as is_eat_room,
        NOW(),
        NOW()
    FROM celender_detail_eatroom detail
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
        UPDATE is_eat_room = VALUES(is_eat_room),
               updated_at = NOW();
END;
