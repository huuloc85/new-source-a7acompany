DROP PROCEDURE IF EXISTS insert_wc_clean_men_to_schedule_details;

CREATE PROCEDURE insert_wc_clean_men_to_schedule_details(IN in_employee_id bigint, IN in_schedule_id bigint)
BEGIN
  DECLARE base_date DATE;
  DECLARE task_day1 VARCHAR(10);
  DECLARE task_day2 VARCHAR(10);
  DECLARE task_day3 VARCHAR(10);
  DECLARE task_day4 VARCHAR(10);
  DECLARE task_day5 VARCHAR(10);
  DECLARE task_day6 VARCHAR(10);
  DECLARE task_day7 VARCHAR(10);
  DECLARE task_day8 VARCHAR(10);
  DECLARE task_day9 VARCHAR(10);
  DECLARE task_day10 VARCHAR(10);
  DECLARE task_day11 VARCHAR(10);
  DECLARE task_day12 VARCHAR(10);
  DECLARE task_day13 VARCHAR(10);
  DECLARE task_day14 VARCHAR(10);
  DECLARE task_day15 VARCHAR(10);
  DECLARE task_day16 VARCHAR(10);
  DECLARE task_day17 VARCHAR(10);
  DECLARE task_day18 VARCHAR(10);
  DECLARE task_day19 VARCHAR(10);
  DECLARE task_day20 VARCHAR(10);
  DECLARE task_day21 VARCHAR(10);
  DECLARE task_day22 VARCHAR(10);
  DECLARE task_day23 VARCHAR(10);
  DECLARE task_day24 VARCHAR(10);
  DECLARE task_day25 VARCHAR(10);
  DECLARE task_day26 VARCHAR(10);
  DECLARE task_day27 VARCHAR(10);
  DECLARE task_day28 VARCHAR(10);
  DECLARE task_day29 VARCHAR(10);
  DECLARE task_day30 VARCHAR(10);
  DECLARE task_day31 VARCHAR(10);

  -- Get base date
  SELECT `date` INTO base_date FROM celenders WHERE id = in_schedule_id;

  -- Day 1
  SELECT day1 INTO task_day1 FROM celender_detail_wc_clean_men WHERE employee_id = in_employee_id AND celender_id = in_schedule_id;
  INSERT INTO schedule_details (
    employee_id, schedule_id, date,
    is_wc_clean_men, created_at, updated_at
  )
  VALUES (
    in_employee_id, in_schedule_id, DATE_ADD(base_date, INTERVAL 0 DAY),
    IF(task_day1 IS NOT NULL AND task_day1 != '', 1, 0), NOW(), NOW()
  )
  ON DUPLICATE KEY UPDATE
    is_wc_clean_men = VALUES(is_wc_clean_men),
    updated_at = VALUES(updated_at);

  -- Day 2
  SELECT day2 INTO task_day2 FROM celender_detail_wc_clean_men WHERE employee_id = in_employee_id AND celender_id = in_schedule_id;
  INSERT INTO schedule_details (
    employee_id, schedule_id, date,
    is_wc_clean_men, created_at, updated_at
  )
  VALUES (
    in_employee_id, in_schedule_id, DATE_ADD(base_date, INTERVAL 1 DAY),
    IF(task_day2 IS NOT NULL AND task_day2 != '', 1, 0), NOW(), NOW()
  )
  ON DUPLICATE KEY UPDATE
    is_wc_clean_men = VALUES(is_wc_clean_men),
    updated_at = VALUES(updated_at);

  -- Day 3
  SELECT day3 INTO task_day3 FROM celender_detail_wc_clean_men WHERE employee_id = in_employee_id AND celender_id = in_schedule_id;
  INSERT INTO schedule_details (
    employee_id, schedule_id, date,
    is_wc_clean_men, created_at, updated_at
  )
  VALUES (
    in_employee_id, in_schedule_id, DATE_ADD(base_date, INTERVAL 2 DAY),
    IF(task_day3 IS NOT NULL AND task_day3 != '', 1, 0), NOW(), NOW()
  )
  ON DUPLICATE KEY UPDATE
    is_wc_clean_men = VALUES(is_wc_clean_men),
    updated_at = VALUES(updated_at);

  -- Day 4
  SELECT day4 INTO task_day4 FROM celender_detail_wc_clean_men WHERE employee_id = in_employee_id AND celender_id = in_schedule_id;
  INSERT INTO schedule_details (
    employee_id, schedule_id, date,
    is_wc_clean_men, created_at, updated_at
  )
  VALUES (
    in_employee_id, in_schedule_id, DATE_ADD(base_date, INTERVAL 3 DAY),
    IF(task_day4 IS NOT NULL AND task_day4 != '', 1, 0), NOW(), NOW()
  )
  ON DUPLICATE KEY UPDATE
    is_wc_clean_men = VALUES(is_wc_clean_men),
    updated_at = VALUES(updated_at);

  -- Day 5
  SELECT day5 INTO task_day5 FROM celender_detail_wc_clean_men WHERE employee_id = in_employee_id AND celender_id = in_schedule_id;
  INSERT INTO schedule_details (
    employee_id, schedule_id, date,
    is_wc_clean_men, created_at, updated_at
  )
  VALUES (
    in_employee_id, in_schedule_id, DATE_ADD(base_date, INTERVAL 4 DAY),
    IF(task_day5 IS NOT NULL AND task_day5 != '', 1, 0), NOW(), NOW()
  )
  ON DUPLICATE KEY UPDATE
    is_wc_clean_men = VALUES(is_wc_clean_men),
    updated_at = VALUES(updated_at);

  -- Day 6
  SELECT day6 INTO task_day6 FROM celender_detail_wc_clean_men WHERE employee_id = in_employee_id AND celender_id = in_schedule_id;
  INSERT INTO schedule_details (
    employee_id, schedule_id, date,
    is_wc_clean_men, created_at, updated_at
  )
  VALUES (
    in_employee_id, in_schedule_id, DATE_ADD(base_date, INTERVAL 5 DAY),
    IF(task_day6 IS NOT NULL AND task_day6 != '', 1, 0), NOW(), NOW()
  )
  ON DUPLICATE KEY UPDATE
    is_wc_clean_men = VALUES(is_wc_clean_men),
    updated_at = VALUES(updated_at);

  -- Day 7
  SELECT day7 INTO task_day7 FROM celender_detail_wc_clean_men WHERE employee_id = in_employee_id AND celender_id = in_schedule_id;
  INSERT INTO schedule_details (
    employee_id, schedule_id, date,
    is_wc_clean_men, created_at, updated_at
  )
  VALUES (
    in_employee_id, in_schedule_id, DATE_ADD(base_date, INTERVAL 6 DAY),
    IF(task_day7 IS NOT NULL AND task_day7 != '', 1, 0), NOW(), NOW()
  )
  ON DUPLICATE KEY UPDATE
    is_wc_clean_men = VALUES(is_wc_clean_men),
    updated_at = VALUES(updated_at);

  -- Day 8
  SELECT day8 INTO task_day8 FROM celender_detail_wc_clean_men WHERE employee_id = in_employee_id AND celender_id = in_schedule_id;
  INSERT INTO schedule_details (
    employee_id, schedule_id, date,
    is_wc_clean_men, created_at, updated_at
  )
  VALUES (
    in_employee_id, in_schedule_id, DATE_ADD(base_date, INTERVAL 7 DAY),
    IF(task_day8 IS NOT NULL AND task_day8 != '', 1, 0), NOW(), NOW()
  )
  ON DUPLICATE KEY UPDATE
    is_wc_clean_men = VALUES(is_wc_clean_men),
    updated_at = VALUES(updated_at);

  -- Day 9
  SELECT day9 INTO task_day9 FROM celender_detail_wc_clean_men WHERE employee_id = in_employee_id AND celender_id = in_schedule_id;
  INSERT INTO schedule_details (
    employee_id, schedule_id, date,
    is_wc_clean_men, created_at, updated_at
  )
  VALUES (
    in_employee_id, in_schedule_id, DATE_ADD(base_date, INTERVAL 8 DAY),
    IF(task_day9 IS NOT NULL AND task_day9 != '', 1, 0), NOW(), NOW()
  )
  ON DUPLICATE KEY UPDATE
    is_wc_clean_men = VALUES(is_wc_clean_men),
    updated_at = VALUES(updated_at);

  -- Day 10
  SELECT day10 INTO task_day10 FROM celender_detail_wc_clean_men WHERE employee_id = in_employee_id AND celender_id = in_schedule_id;
  INSERT INTO schedule_details (
    employee_id, schedule_id, date,
    is_wc_clean_men, created_at, updated_at
  )
  VALUES (
    in_employee_id, in_schedule_id, DATE_ADD(base_date, INTERVAL 9 DAY),
    IF(task_day10 IS NOT NULL AND task_day10 != '', 1, 0), NOW(), NOW()
  )
  ON DUPLICATE KEY UPDATE
    is_wc_clean_men = VALUES(is_wc_clean_men),
    updated_at = VALUES(updated_at);

  -- Day 11
  SELECT day11 INTO task_day11 FROM celender_detail_wc_clean_men WHERE employee_id = in_employee_id AND celender_id = in_schedule_id;
  INSERT INTO schedule_details (
    employee_id, schedule_id, date,
    is_wc_clean_men, created_at, updated_at
  )
  VALUES (
    in_employee_id, in_schedule_id, DATE_ADD(base_date, INTERVAL 10 DAY),
    IF(task_day11 IS NOT NULL AND task_day11 != '', 1, 0), NOW(), NOW()
  )
  ON DUPLICATE KEY UPDATE
    is_wc_clean_men = VALUES(is_wc_clean_men),
    updated_at = VALUES(updated_at);

  -- Day 12
  SELECT day12 INTO task_day12 FROM celender_detail_wc_clean_men WHERE employee_id = in_employee_id AND celender_id = in_schedule_id;
  INSERT INTO schedule_details (
    employee_id, schedule_id, date,
    is_wc_clean_men, created_at, updated_at
  )
  VALUES (
    in_employee_id, in_schedule_id, DATE_ADD(base_date, INTERVAL 11 DAY),
    IF(task_day12 IS NOT NULL AND task_day12 != '', 1, 0), NOW(), NOW()
  )
  ON DUPLICATE KEY UPDATE
    is_wc_clean_men = VALUES(is_wc_clean_men),
    updated_at = VALUES(updated_at);

  -- Day 13
  SELECT day13 INTO task_day13 FROM celender_detail_wc_clean_men WHERE employee_id = in_employee_id AND celender_id = in_schedule_id;
  INSERT INTO schedule_details (
    employee_id, schedule_id, date,
    is_wc_clean_men, created_at, updated_at
  )
  VALUES (
    in_employee_id, in_schedule_id, DATE_ADD(base_date, INTERVAL 12 DAY),
    IF(task_day13 IS NOT NULL AND task_day13 != '', 1, 0), NOW(), NOW()
  )
  ON DUPLICATE KEY UPDATE
    is_wc_clean_men = VALUES(is_wc_clean_men),
    updated_at = VALUES(updated_at);

  -- Day 14
  SELECT day14 INTO task_day14 FROM celender_detail_wc_clean_men WHERE employee_id = in_employee_id AND celender_id = in_schedule_id;
  INSERT INTO schedule_details (
    employee_id, schedule_id, date,
    is_wc_clean_men, created_at, updated_at
  )
  VALUES (
    in_employee_id, in_schedule_id, DATE_ADD(base_date, INTERVAL 13 DAY),
    IF(task_day14 IS NOT NULL AND task_day14 != '', 1, 0), NOW(), NOW()
  )
  ON DUPLICATE KEY UPDATE
    is_wc_clean_men = VALUES(is_wc_clean_men),
    updated_at = VALUES(updated_at);

  -- Day 15
  SELECT day15 INTO task_day15 FROM celender_detail_wc_clean_men WHERE employee_id = in_employee_id AND celender_id = in_schedule_id;
  INSERT INTO schedule_details (
    employee_id, schedule_id, date,
    is_wc_clean_men, created_at, updated_at
  )
  VALUES (
    in_employee_id, in_schedule_id, DATE_ADD(base_date, INTERVAL 14 DAY),
    IF(task_day15 IS NOT NULL AND task_day15 != '', 1, 0), NOW(), NOW()
  )
  ON DUPLICATE KEY UPDATE
    is_wc_clean_men = VALUES(is_wc_clean_men),
    updated_at = VALUES(updated_at);

  -- Day 16
  SELECT day16 INTO task_day16 FROM celender_detail_wc_clean_men WHERE employee_id = in_employee_id AND celender_id = in_schedule_id;
  INSERT INTO schedule_details (
    employee_id, schedule_id, date,
    is_wc_clean_men, created_at, updated_at
  )
  VALUES (
    in_employee_id, in_schedule_id, DATE_ADD(base_date, INTERVAL 15 DAY),
    IF(task_day16 IS NOT NULL AND task_day16 != '', 1, 0), NOW(), NOW()
  )
  ON DUPLICATE KEY UPDATE
    is_wc_clean_men = VALUES(is_wc_clean_men),
    updated_at = VALUES(updated_at);

  -- Day 17
  SELECT day17 INTO task_day17 FROM celender_detail_wc_clean_men WHERE employee_id = in_employee_id AND celender_id = in_schedule_id;
  INSERT INTO schedule_details (
    employee_id, schedule_id, date,
    is_wc_clean_men, created_at, updated_at
  )
  VALUES (
    in_employee_id, in_schedule_id, DATE_ADD(base_date, INTERVAL 16 DAY),
    IF(task_day17 IS NOT NULL AND task_day17 != '', 1, 0), NOW(), NOW()
  )
  ON DUPLICATE KEY UPDATE
    is_wc_clean_men = VALUES(is_wc_clean_men),
    updated_at = VALUES(updated_at);

  -- Day 18
  SELECT day18 INTO task_day18 FROM celender_detail_wc_clean_men WHERE employee_id = in_employee_id AND celender_id = in_schedule_id;
  INSERT INTO schedule_details (
    employee_id, schedule_id, date,
    is_wc_clean_men, created_at, updated_at
  )
  VALUES (
    in_employee_id, in_schedule_id, DATE_ADD(base_date, INTERVAL 17 DAY),
    IF(task_day18 IS NOT NULL AND task_day18 != '', 1, 0), NOW(), NOW()
  )
  ON DUPLICATE KEY UPDATE
    is_wc_clean_men = VALUES(is_wc_clean_men),
    updated_at = VALUES(updated_at);

  -- Day 19
  SELECT day19 INTO task_day19 FROM celender_detail_wc_clean_men WHERE employee_id = in_employee_id AND celender_id = in_schedule_id;
  INSERT INTO schedule_details (
    employee_id, schedule_id, date,
    is_wc_clean_men, created_at, updated_at
  )
  VALUES (
    in_employee_id, in_schedule_id, DATE_ADD(base_date, INTERVAL 18 DAY),
    IF(task_day19 IS NOT NULL AND task_day19 != '', 1, 0), NOW(), NOW()
  )
  ON DUPLICATE KEY UPDATE
    is_wc_clean_men = VALUES(is_wc_clean_men),
    updated_at = VALUES(updated_at);

  -- Day 20
  SELECT day20 INTO task_day20 FROM celender_detail_wc_clean_men WHERE employee_id = in_employee_id AND celender_id = in_schedule_id;
  INSERT INTO schedule_details (
    employee_id, schedule_id, date,
    is_wc_clean_men, created_at, updated_at
  )
  VALUES (
    in_employee_id, in_schedule_id, DATE_ADD(base_date, INTERVAL 19 DAY),
    IF(task_day20 IS NOT NULL AND task_day20 != '', 1, 0), NOW(), NOW()
  )
  ON DUPLICATE KEY UPDATE
    is_wc_clean_men = VALUES(is_wc_clean_men),
    updated_at = VALUES(updated_at);

  -- Day 21
  SELECT day21 INTO task_day21 FROM celender_detail_wc_clean_men WHERE employee_id = in_employee_id AND celender_id = in_schedule_id;
  INSERT INTO schedule_details (
    employee_id, schedule_id, date,
    is_wc_clean_men, created_at, updated_at
  )
  VALUES (
    in_employee_id, in_schedule_id, DATE_ADD(base_date, INTERVAL 20 DAY),
    IF(task_day21 IS NOT NULL AND task_day21 != '', 1, 0), NOW(), NOW()
  )
  ON DUPLICATE KEY UPDATE
    is_wc_clean_men = VALUES(is_wc_clean_men),
    updated_at = VALUES(updated_at);

  -- Day 22
  SELECT day22 INTO task_day22 FROM celender_detail_wc_clean_men WHERE employee_id = in_employee_id AND celender_id = in_schedule_id;
  INSERT INTO schedule_details (
    employee_id, schedule_id, date,
    is_wc_clean_men, created_at, updated_at
  )
  VALUES (
    in_employee_id, in_schedule_id, DATE_ADD(base_date, INTERVAL 21 DAY),
    IF(task_day22 IS NOT NULL AND task_day22 != '', 1, 0), NOW(), NOW()
  )
  ON DUPLICATE KEY UPDATE
    is_wc_clean_men = VALUES(is_wc_clean_men),
    updated_at = VALUES(updated_at);

  -- Day 23
  SELECT day23 INTO task_day23 FROM celender_detail_wc_clean_men WHERE employee_id = in_employee_id AND celender_id = in_schedule_id;
  INSERT INTO schedule_details (
    employee_id, schedule_id, date,
    is_wc_clean_men, created_at, updated_at
  )
  VALUES (
    in_employee_id, in_schedule_id, DATE_ADD(base_date, INTERVAL 22 DAY),
    IF(task_day23 IS NOT NULL AND task_day23 != '', 1, 0), NOW(), NOW()
  )
  ON DUPLICATE KEY UPDATE
    is_wc_clean_men = VALUES(is_wc_clean_men),
    updated_at = VALUES(updated_at);

  -- Day 24
  SELECT day24 INTO task_day24 FROM celender_detail_wc_clean_men WHERE employee_id = in_employee_id AND celender_id = in_schedule_id;
  INSERT INTO schedule_details (
    employee_id, schedule_id, date,
    is_wc_clean_men, created_at, updated_at
  )
  VALUES (
    in_employee_id, in_schedule_id, DATE_ADD(base_date, INTERVAL 23 DAY),
    IF(task_day24 IS NOT NULL AND task_day24 != '', 1, 0), NOW(), NOW()
  )
  ON DUPLICATE KEY UPDATE
    is_wc_clean_men = VALUES(is_wc_clean_men),
    updated_at = VALUES(updated_at);

  -- Day 25
  SELECT day25 INTO task_day25 FROM celender_detail_wc_clean_men WHERE employee_id = in_employee_id AND celender_id = in_schedule_id;
  INSERT INTO schedule_details (
    employee_id, schedule_id, date,
    is_wc_clean_men, created_at, updated_at
  )
  VALUES (
    in_employee_id, in_schedule_id, DATE_ADD(base_date, INTERVAL 24 DAY),
    IF(task_day25 IS NOT NULL AND task_day25 != '', 1, 0), NOW(), NOW()
  )
  ON DUPLICATE KEY UPDATE
    is_wc_clean_men = VALUES(is_wc_clean_men),
    updated_at = VALUES(updated_at);

  -- Day 26
  SELECT day26 INTO task_day26 FROM celender_detail_wc_clean_men WHERE employee_id = in_employee_id AND celender_id = in_schedule_id;
  INSERT INTO schedule_details (
    employee_id, schedule_id, date,
    is_wc_clean_men, created_at, updated_at
  )
  VALUES (
    in_employee_id, in_schedule_id, DATE_ADD(base_date, INTERVAL 25 DAY),
    IF(task_day26 IS NOT NULL AND task_day26 != '', 1, 0), NOW(), NOW()
  )
  ON DUPLICATE KEY UPDATE
    is_wc_clean_men = VALUES(is_wc_clean_men),
    updated_at = VALUES(updated_at);

  -- Day 27
  SELECT day27 INTO task_day27 FROM celender_detail_wc_clean_men WHERE employee_id = in_employee_id AND celender_id = in_schedule_id;
  INSERT INTO schedule_details (
    employee_id, schedule_id, date,
    is_wc_clean_men, created_at, updated_at
  )
  VALUES (
    in_employee_id, in_schedule_id, DATE_ADD(base_date, INTERVAL 26 DAY),
    IF(task_day27 IS NOT NULL AND task_day27 != '', 1, 0), NOW(), NOW()
  )
  ON DUPLICATE KEY UPDATE
    is_wc_clean_men = VALUES(is_wc_clean_men),
    updated_at = VALUES(updated_at);

  -- Day 28
  SELECT day28 INTO task_day28 FROM celender_detail_wc_clean_men WHERE employee_id = in_employee_id AND celender_id = in_schedule_id;
  INSERT INTO schedule_details (
    employee_id, schedule_id, date,
    is_wc_clean_men, created_at, updated_at
  )
  VALUES (
    in_employee_id, in_schedule_id, DATE_ADD(base_date, INTERVAL 27 DAY),
    IF(task_day28 IS NOT NULL AND task_day28 != '', 1, 0), NOW(), NOW()
  )
  ON DUPLICATE KEY UPDATE
    is_wc_clean_men = VALUES(is_wc_clean_men),
    updated_at = VALUES(updated_at);

  -- Day 29
  SELECT day29 INTO task_day29 FROM celender_detail_wc_clean_men WHERE employee_id = in_employee_id AND celender_id = in_schedule_id;
  INSERT INTO schedule_details (
    employee_id, schedule_id, date,
    is_wc_clean_men, created_at, updated_at
  )
  VALUES (
    in_employee_id, in_schedule_id, DATE_ADD(base_date, INTERVAL 28 DAY),
    IF(task_day29 IS NOT NULL AND task_day29 != '', 1, 0), NOW(), NOW()
  )
  ON DUPLICATE KEY UPDATE
    is_wc_clean_men = VALUES(is_wc_clean_men),
    updated_at = VALUES(updated_at);

  -- Day 30
  SELECT day30 INTO task_day30 FROM celender_detail_wc_clean_men WHERE employee_id = in_employee_id AND celender_id = in_schedule_id;
  INSERT INTO schedule_details (
    employee_id, schedule_id, date,
    is_wc_clean_men, created_at, updated_at
  )
  VALUES (
    in_employee_id, in_schedule_id, DATE_ADD(base_date, INTERVAL 29 DAY),
    IF(task_day30 IS NOT NULL AND task_day30 != '', 1, 0), NOW(), NOW()
  )
  ON DUPLICATE KEY UPDATE
    is_wc_clean_men = VALUES(is_wc_clean_men),
    updated_at = VALUES(updated_at);

  -- Day 31
  SELECT day31 INTO task_day31 FROM celender_detail_wc_clean_men WHERE employee_id = in_employee_id AND celender_id = in_schedule_id;
  INSERT INTO schedule_details (
    employee_id, schedule_id, date,
    is_wc_clean_men, created_at, updated_at
  )
  VALUES (
    in_employee_id, in_schedule_id, DATE_ADD(base_date, INTERVAL 30 DAY),
    IF(task_day31 IS NOT NULL AND task_day31 != '', 1, 0), NOW(), NOW()
  )
  ON DUPLICATE KEY UPDATE
    is_wc_clean_men = VALUES(is_wc_clean_men),
    updated_at = VALUES(updated_at);

END;

