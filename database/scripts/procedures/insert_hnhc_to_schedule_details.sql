DROP PROCEDURE IF EXISTS insert_hnhc_to_schedule_details;

CREATE PROCEDURE insert_hnhc_to_schedule_details(IN in_employee_id bigint, IN in_schedule_id bigint)
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

  -- Get calendar base date
  SELECT date INTO base_date FROM celenders WHERE id = in_schedule_id;

  -- Day 1
  SELECT TRIM(UPPER(day1)) INTO task_day1 FROM celender_detail_hnhc
  WHERE employee_id = in_employee_id AND celender_id = in_schedule_id;

  INSERT INTO schedule_details (
    employee_id, schedule_id, date,
    hnhc,
    created_at, updated_at
  )
  VALUES (
    in_employee_id, in_schedule_id, DATE_ADD(base_date, INTERVAL 0 DAY),
    IF(task_day1 IN ('N', 'D', 'X', 'TC', 'LN'), task_day1, NULL),
    NOW(), NOW()
  )
  ON DUPLICATE KEY UPDATE hnhc = VALUES(hnhc), updated_at = VALUES(updated_at);

  -- Day 2
  SELECT TRIM(UPPER(day2)) INTO task_day2 FROM celender_detail_hnhc
  WHERE employee_id = in_employee_id AND celender_id = in_schedule_id;

  INSERT INTO schedule_details (
    employee_id, schedule_id, date,
    hnhc, created_at, updated_at
  )
  VALUES (
    in_employee_id, in_schedule_id, DATE_ADD(base_date, INTERVAL 1 DAY),
    IF(task_day2 IN ('N', 'D', 'X', 'TC', 'LN'), task_day2, NULL),
    NOW(), NOW()
  )
  ON DUPLICATE KEY UPDATE hnhc = VALUES(hnhc), updated_at = VALUES(updated_at);

  -- Day 3
  SELECT TRIM(UPPER(day3)) INTO task_day3 FROM celender_detail_hnhc
  WHERE employee_id = in_employee_id AND celender_id = in_schedule_id;

  INSERT INTO schedule_details (
    employee_id, schedule_id, date,
    hnhc, created_at, updated_at
  )
  VALUES (
    in_employee_id, in_schedule_id, DATE_ADD(base_date, INTERVAL 2 DAY),
    IF(task_day3 IN ('N', 'D', 'X', 'TC', 'LN'), task_day3, NULL),
    NOW(), NOW()
  )
  ON DUPLICATE KEY UPDATE hnhc = VALUES(hnhc), updated_at = VALUES(updated_at);

  -- Day 4
  SELECT TRIM(UPPER(day4)) INTO task_day4 FROM celender_detail_hnhc
  WHERE employee_id = in_employee_id AND celender_id = in_schedule_id;
  INSERT INTO schedule_details (
    employee_id, schedule_id, date,
    hnhc, created_at, updated_at
  )
  VALUES (
    in_employee_id, in_schedule_id, DATE_ADD(base_date, INTERVAL 3 DAY),
    IF(task_day4 IN ('N', 'D', 'X', 'TC', 'LN'), task_day4, NULL),
    NOW(), NOW()
  )
  ON DUPLICATE KEY UPDATE hnhc = VALUES(hnhc), updated_at = VALUES(updated_at);

  -- Day 5
  SELECT TRIM(UPPER(day5)) INTO task_day5 FROM celender_detail_hnhc
  WHERE employee_id = in_employee_id AND celender_id = in_schedule_id;
  INSERT INTO schedule_details (
    employee_id, schedule_id, date,
    hnhc, created_at, updated_at
  )
  VALUES (
    in_employee_id, in_schedule_id, DATE_ADD(base_date, INTERVAL 4 DAY),
    IF(task_day5 IN ('N', 'D', 'X', 'TC', 'LN'), task_day5, NULL),
    NOW(), NOW()
  )
  ON DUPLICATE KEY UPDATE hnhc = VALUES(hnhc), updated_at = VALUES(updated_at);

  -- Day 6
  SELECT TRIM(UPPER(day6)) INTO task_day6 FROM celender_detail_hnhc
  WHERE employee_id = in_employee_id AND celender_id = in_schedule_id;

  INSERT INTO schedule_details (
    employee_id, schedule_id, date,
    hnhc, created_at, updated_at
  )
  VALUES (
    in_employee_id, in_schedule_id, DATE_ADD(base_date, INTERVAL 5 DAY),
    IF(task_day6 IN ('N', 'D', 'X', 'TC', 'LN'), task_day6, NULL),
    NOW(), NOW()
  )
  ON DUPLICATE KEY UPDATE hnhc = VALUES(hnhc), updated_at = VALUES(updated_at);

  -- Day 7
  SELECT TRIM(UPPER(day7)) INTO task_day7 FROM celender_detail_hnhc
  WHERE employee_id = in_employee_id AND celender_id = in_schedule_id;
  INSERT INTO schedule_details (
    employee_id, schedule_id, date,
    hnhc, created_at, updated_at
  )
  VALUES (
    in_employee_id, in_schedule_id, DATE_ADD(base_date, INTERVAL 6 DAY),
    IF(task_day7 IN ('N', 'D', 'X', 'TC', 'LN'), task_day7, NULL),
    NOW(), NOW()
  )
  ON DUPLICATE KEY UPDATE hnhc = VALUES(hnhc), updated_at = VALUES(updated_at);

  -- Day 8
  SELECT TRIM(UPPER(day8)) INTO task_day8 FROM celender_detail_hnhc
  WHERE employee_id = in_employee_id AND celender_id = in_schedule_id;
  INSERT INTO schedule_details (
    employee_id, schedule_id, date,
    hnhc, created_at, updated_at
  )
  VALUES (
    in_employee_id, in_schedule_id, DATE_ADD(base_date, INTERVAL 7 DAY),
    IF(task_day8 IN ('N', 'D', 'X', 'TC', 'LN'), task_day8, NULL),
    NOW(), NOW()
  )
  ON DUPLICATE KEY UPDATE hnhc = VALUES(hnhc), updated_at = VALUES(updated_at);

  -- Day 9
  SELECT TRIM(UPPER(day9)) INTO task_day9 FROM celender_detail_hnhc
  WHERE employee_id = in_employee_id AND celender_id = in_schedule_id;
  INSERT INTO schedule_details (
    employee_id, schedule_id, date,
    hnhc, created_at, updated_at
  )
  VALUES (
    in_employee_id, in_schedule_id, DATE_ADD(base_date, INTERVAL 8 DAY),
    IF(task_day9 IN ('N', 'D', 'X', 'TC', 'LN'), task_day9, NULL),
    NOW(), NOW()
  )
  ON DUPLICATE KEY UPDATE hnhc = VALUES(hnhc), updated_at = VALUES(updated_at);

  -- Day 10
  SELECT TRIM(UPPER(day10)) INTO task_day10 FROM celender_detail_hnhc
  WHERE employee_id = in_employee_id AND celender_id = in_schedule_id;
  INSERT INTO schedule_details (
    employee_id, schedule_id, date,
    hnhc, created_at, updated_at
  )
  VALUES (
    in_employee_id, in_schedule_id, DATE_ADD(base_date, INTERVAL 9 DAY),
    IF(task_day10 IN ('N', 'D', 'X', 'TC', 'LN'), task_day10, NULL),
    NOW(), NOW()
  )
  ON DUPLICATE KEY UPDATE hnhc = VALUES(hnhc), updated_at = VALUES(updated_at);

  -- Day 11
  SELECT TRIM(UPPER(day11)) INTO task_day11 FROM celender_detail_hnhc
  WHERE employee_id = in_employee_id AND celender_id = in_schedule_id;
  INSERT INTO schedule_details (
    employee_id, schedule_id, date,
    hnhc, created_at, updated_at
  )
  VALUES (
    in_employee_id, in_schedule_id, DATE_ADD(base_date, INTERVAL 10 DAY),
    IF(task_day11 IN ('N', 'D', 'X', 'TC', 'LN'), task_day11, NULL),
    NOW(), NOW()
  )
  ON DUPLICATE KEY UPDATE hnhc = VALUES(hnhc), updated_at = VALUES(updated_at);

  -- Day 12
  SELECT TRIM(UPPER(day12)) INTO task_day12 FROM celender_detail_hnhc
  WHERE employee_id = in_employee_id AND celender_id = in_schedule_id;
  INSERT INTO schedule_details (
    employee_id, schedule_id, date,
    hnhc, created_at, updated_at
  )
  VALUES (
    in_employee_id, in_schedule_id, DATE_ADD(base_date, INTERVAL 11 DAY),
    IF(task_day12 IN ('N', 'D', 'X', 'TC', 'LN'), task_day12, NULL),
    NOW(), NOW()
  )
  ON DUPLICATE KEY UPDATE hnhc = VALUES(hnhc), updated_at = VALUES(updated_at);

  -- Day 13
  SELECT TRIM(UPPER(day13)) INTO task_day13 FROM celender_detail_hnhc
  WHERE employee_id = in_employee_id AND celender_id = in_schedule_id;
  INSERT INTO schedule_details (
    employee_id, schedule_id, date,
    hnhc, created_at, updated_at
  )
  VALUES (
    in_employee_id, in_schedule_id, DATE_ADD(base_date, INTERVAL 12 DAY),
    IF(task_day13 IN ('N', 'D', 'X', 'TC', 'LN'), task_day13, NULL),
    NOW(), NOW()
  )
  ON DUPLICATE KEY UPDATE hnhc = VALUES(hnhc), updated_at = VALUES(updated_at);

  -- Day 14
  SELECT TRIM(UPPER(day14)) INTO task_day14 FROM celender_detail_hnhc
  WHERE employee_id = in_employee_id AND celender_id = in_schedule_id;
  INSERT INTO schedule_details (
    employee_id, schedule_id, date,
    hnhc, created_at, updated_at
  )
  VALUES (
    in_employee_id, in_schedule_id, DATE_ADD(base_date, INTERVAL 13 DAY),
    IF(task_day14 IN ('N', 'D', 'X', 'TC', 'LN'), task_day14, NULL),
    NOW(), NOW()
  )
  ON DUPLICATE KEY UPDATE hnhc = VALUES(hnhc), updated_at = VALUES(updated_at);

  -- Day 15
  SELECT TRIM(UPPER(day15)) INTO task_day15 FROM celender_detail_hnhc
  WHERE employee_id = in_employee_id AND celender_id = in_schedule_id;
  INSERT INTO schedule_details (
    employee_id, schedule_id, date,
    hnhc, created_at, updated_at
  )
  VALUES (
    in_employee_id, in_schedule_id, DATE_ADD(base_date, INTERVAL 14 DAY),
    IF(task_day15 IN ('N', 'D', 'X', 'TC', 'LN'), task_day15, NULL),
    NOW(), NOW()
  )
  ON DUPLICATE KEY UPDATE hnhc = VALUES(hnhc), updated_at = VALUES(updated_at);

  -- Day 16
  SELECT TRIM(UPPER(day16)) INTO task_day16 FROM celender_detail_hnhc
  WHERE employee_id = in_employee_id AND celender_id = in_schedule_id;
  INSERT INTO schedule_details (
    employee_id, schedule_id, date,
    hnhc, created_at, updated_at
  )
  VALUES (
    in_employee_id, in_schedule_id, DATE_ADD(base_date, INTERVAL 15 DAY),
    IF(task_day16 IN ('N', 'D', 'X', 'TC', 'LN'), task_day16, NULL),
    NOW(), NOW()
  )
  ON DUPLICATE KEY UPDATE hnhc = VALUES(hnhc), updated_at = VALUES(updated_at);

  -- Day 17
  SELECT TRIM(UPPER(day17)) INTO task_day17 FROM celender_detail_hnhc
  WHERE employee_id = in_employee_id AND celender_id = in_schedule_id;
  INSERT INTO schedule_details (
    employee_id, schedule_id, date,
    hnhc, created_at, updated_at
  )
  VALUES (
    in_employee_id, in_schedule_id, DATE_ADD(base_date, INTERVAL 16 DAY),
    IF(task_day17 IN ('N', 'D', 'X', 'TC', 'LN'), task_day17, NULL),
    NOW(), NOW()
  )
  ON DUPLICATE KEY UPDATE hnhc = VALUES(hnhc), updated_at = VALUES(updated_at);

  -- Day 18
  SELECT TRIM(UPPER(day18)) INTO task_day18 FROM celender_detail_hnhc
  WHERE employee_id = in_employee_id AND celender_id = in_schedule_id;
  INSERT INTO schedule_details (
    employee_id, schedule_id, date,
    hnhc, created_at, updated_at
  )
  VALUES (
    in_employee_id, in_schedule_id, DATE_ADD(base_date, INTERVAL 17 DAY),
    IF(task_day18 IN ('N', 'D', 'X', 'TC', 'LN'), task_day18, NULL),
    NOW(), NOW()
  )
  ON DUPLICATE KEY UPDATE hnhc = VALUES(hnhc), updated_at = VALUES(updated_at);

  -- Day 19
  SELECT TRIM(UPPER(day19)) INTO task_day19 FROM celender_detail_hnhc
  WHERE employee_id = in_employee_id AND celender_id = in_schedule_id;
  INSERT INTO schedule_details (
    employee_id, schedule_id, date,
    hnhc, created_at, updated_at
  )
  VALUES (
    in_employee_id, in_schedule_id, DATE_ADD(base_date, INTERVAL 18 DAY),
    IF(task_day19 IN ('N', 'D', 'X', 'TC', 'LN'), task_day19, NULL),
    NOW(), NOW()
  )
  ON DUPLICATE KEY UPDATE hnhc = VALUES(hnhc), updated_at = VALUES(updated_at);

  -- Day 20
  SELECT TRIM(UPPER(day20)) INTO task_day20 FROM celender_detail_hnhc
  WHERE employee_id = in_employee_id AND celender_id = in_schedule_id;
  INSERT INTO schedule_details (
    employee_id, schedule_id, date,
    hnhc, created_at, updated_at
  )
  VALUES (
    in_employee_id, in_schedule_id, DATE_ADD(base_date, INTERVAL 19 DAY),
    IF(task_day20 IN ('N', 'D', 'X', 'TC', 'LN'), task_day20, NULL),
    NOW(), NOW()
  )
  ON DUPLICATE KEY UPDATE hnhc = VALUES(hnhc), updated_at = VALUES(updated_at);

  -- Day 21
  SELECT TRIM(UPPER(day21)) INTO task_day21 FROM celender_detail_hnhc
  WHERE employee_id = in_employee_id AND celender_id = in_schedule_id;
  INSERT INTO schedule_details (
    employee_id, schedule_id, date,
    hnhc, created_at, updated_at
  )
  VALUES (
    in_employee_id, in_schedule_id, DATE_ADD(base_date, INTERVAL 20 DAY),
    IF(task_day21 IN ('N', 'D', 'X', 'TC', 'LN'), task_day21, NULL),
    NOW(), NOW()
  )
  ON DUPLICATE KEY UPDATE hnhc = VALUES(hnhc), updated_at = VALUES(updated_at);

  -- Day 22
  SELECT TRIM(UPPER(day22)) INTO task_day22 FROM celender_detail_hnhc
  WHERE employee_id = in_employee_id AND celender_id = in_schedule_id;
  INSERT INTO schedule_details (
    employee_id, schedule_id, date,
    hnhc, created_at, updated_at
  )
  VALUES (
    in_employee_id, in_schedule_id, DATE_ADD(base_date, INTERVAL 21 DAY),
    IF(task_day22 IN ('N', 'D', 'X', 'TC', 'LN'), task_day22, NULL),
    NOW(), NOW()
  )
  ON DUPLICATE KEY UPDATE hnhc = VALUES(hnhc), updated_at = VALUES(updated_at);

  -- Day 23
  SELECT TRIM(UPPER(day23)) INTO task_day23 FROM celender_detail_hnhc
  WHERE employee_id = in_employee_id AND celender_id = in_schedule_id;
  INSERT INTO schedule_details (
    employee_id, schedule_id, date,
    hnhc, created_at, updated_at
  )
  VALUES (
    in_employee_id, in_schedule_id, DATE_ADD(base_date, INTERVAL 22 DAY),
    IF(task_day23 IN ('N', 'D', 'X', 'TC', 'LN'), task_day23, NULL),
    NOW(), NOW()
  )
  ON DUPLICATE KEY UPDATE hnhc = VALUES(hnhc), updated_at = VALUES(updated_at);

  -- Day 24
  SELECT TRIM(UPPER(day24)) INTO task_day24 FROM celender_detail_hnhc
  WHERE employee_id = in_employee_id AND celender_id = in_schedule_id;
  INSERT INTO schedule_details (
    employee_id, schedule_id, date,
    hnhc, created_at, updated_at
  )
  VALUES (
    in_employee_id, in_schedule_id, DATE_ADD(base_date, INTERVAL 23 DAY),
    IF(task_day24 IN ('N', 'D', 'X', 'TC', 'LN'), task_day24, NULL),
    NOW(), NOW()
  )
  ON DUPLICATE KEY UPDATE hnhc = VALUES(hnhc), updated_at = VALUES(updated_at);

  -- Day 25
  SELECT TRIM(UPPER(day25)) INTO task_day25 FROM celender_detail_hnhc
  WHERE employee_id = in_employee_id AND celender_id = in_schedule_id;
  INSERT INTO schedule_details (
    employee_id, schedule_id, date,
    hnhc, created_at, updated_at
  )
  VALUES (
    in_employee_id, in_schedule_id, DATE_ADD(base_date, INTERVAL 24 DAY),
    IF(task_day25 IN ('N', 'D', 'X', 'TC', 'LN'), task_day25, NULL),
    NOW(), NOW()
  )
  ON DUPLICATE KEY UPDATE hnhc = VALUES(hnhc), updated_at = VALUES(updated_at);

  -- Day 26
  SELECT TRIM(UPPER(day26)) INTO task_day26 FROM celender_detail_hnhc
  WHERE employee_id = in_employee_id AND celender_id = in_schedule_id;
  INSERT INTO schedule_details (
    employee_id, schedule_id, date,
    hnhc, created_at, updated_at
  )
  VALUES (
    in_employee_id, in_schedule_id, DATE_ADD(base_date, INTERVAL 25 DAY),
    IF(task_day26 IN ('N', 'D', 'X', 'TC', 'LN'), task_day26, NULL),
    NOW(), NOW()
  )
  ON DUPLICATE KEY UPDATE hnhc = VALUES(hnhc), updated_at = VALUES(updated_at);

  -- Day 27
  SELECT TRIM(UPPER(day27)) INTO task_day27 FROM celender_detail_hnhc
  WHERE employee_id = in_employee_id AND celender_id = in_schedule_id;
  INSERT INTO schedule_details (
    employee_id, schedule_id, date,
    hnhc, created_at, updated_at
  )
  VALUES (
    in_employee_id, in_schedule_id, DATE_ADD(base_date, INTERVAL 26 DAY),
    IF(task_day27 IN ('N', 'D', 'X', 'TC', 'LN'), task_day27, NULL),
    NOW(), NOW()
  )
  ON DUPLICATE KEY UPDATE hnhc = VALUES(hnhc), updated_at = VALUES(updated_at);

  -- Day 28
  SELECT TRIM(UPPER(day28)) INTO task_day28 FROM celender_detail_hnhc
  WHERE employee_id = in_employee_id AND celender_id = in_schedule_id;
  INSERT INTO schedule_details (
    employee_id, schedule_id, date,
    hnhc, created_at, updated_at
  )
  VALUES (
    in_employee_id, in_schedule_id, DATE_ADD(base_date, INTERVAL 27 DAY),
    IF(task_day28 IN ('N', 'D', 'X', 'TC', 'LN'), task_day28, NULL),
    NOW(), NOW()
  )
  ON DUPLICATE KEY UPDATE hnhc = VALUES(hnhc), updated_at = VALUES(updated_at);

  -- Day 29
  SELECT TRIM(UPPER(day29)) INTO task_day29 FROM celender_detail_hnhc
  WHERE employee_id = in_employee_id AND celender_id = in_schedule_id;
  INSERT INTO schedule_details (
    employee_id, schedule_id, date,
    hnhc, created_at, updated_at
  )
  VALUES (
    in_employee_id, in_schedule_id, DATE_ADD(base_date, INTERVAL 28 DAY),
    IF(task_day29 IN ('N', 'D', 'X', 'TC', 'LN'), task_day29, NULL),
    NOW(), NOW()
  )
  ON DUPLICATE KEY UPDATE hnhc = VALUES(hnhc), updated_at = VALUES(updated_at);

  -- Day 30
  SELECT TRIM(UPPER(day30)) INTO task_day30 FROM celender_detail_hnhc
  WHERE employee_id = in_employee_id AND celender_id = in_schedule_id;
  INSERT INTO schedule_details (
    employee_id, schedule_id, date,
    hnhc, created_at, updated_at
  )
  VALUES (
    in_employee_id, in_schedule_id, DATE_ADD(base_date, INTERVAL 29 DAY),
    IF(task_day30 IN ('N', 'D', 'X', 'TC', 'LN'), task_day30, NULL),
    NOW(), NOW()
  )
  ON DUPLICATE KEY UPDATE hnhc = VALUES(hnhc), updated_at = VALUES(updated_at);

  -- Day 31
  SELECT TRIM(UPPER(day31)) INTO task_day31 FROM celender_detail_hnhc
  WHERE employee_id = in_employee_id AND celender_id = in_schedule_id;
  INSERT INTO schedule_details (
    employee_id, schedule_id, date,
    hnhc, created_at, updated_at
  )
  VALUES (
    in_employee_id, in_schedule_id, DATE_ADD(base_date, INTERVAL 30 DAY),
    IF(task_day31 IN ('N', 'D', 'X', 'TC', 'LN'), task_day31, NULL),
    NOW(), NOW()
  )
  ON DUPLICATE KEY UPDATE hnhc = VALUES(hnhc), updated_at = VALUES(updated_at);

END;

