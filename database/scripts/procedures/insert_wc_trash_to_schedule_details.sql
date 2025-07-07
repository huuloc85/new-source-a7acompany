Drop PROCEDURE IF EXISTS insert_wc_trash_to_schedule_details;

CREATE PROCEDURE insert_wc_trash_to_schedule_details(IN in_employee_id bigint, IN in_schedule_id bigint)
BEGIN
  DECLARE base_date DATE;
  DECLARE task_day1 VARCHAR(10);
  DECLARE task_day2 VARCHAR(10);
  DECLARE task_day3 VARCHAR(10);
  DECLARE task_day4 VARCHAR(10);
  DECLARE task_day5 VARCHAR(10);

  -- Get base date
  SELECT `date` INTO base_date FROM celenders WHERE id = in_schedule_id;

  -- Day 1
  SELECT day1 INTO task_day1 FROM celender_detail_wc WHERE employee_id = in_employee_id AND celender_id = in_schedule_id;
  INSERT INTO schedule_details (
    employee_id, schedule_id, date,
    is_wc_trash, created_at, updated_at
  )
  VALUES (
    in_employee_id, in_schedule_id, DATE_ADD(base_date, INTERVAL ((7 - DAYOFWEEK(base_date) + 1) % 7 + 0 * 7)-1  DAY),
    IF(task_day1 IS NOT NULL AND task_day1 != '', 1, 0), NOW(), NOW()
  )
  ON DUPLICATE KEY UPDATE
    is_wc_trash = VALUES(is_wc_trash),
    updated_at = VALUES(updated_at);

  -- Day 2
  SELECT day2 INTO task_day2 FROM celender_detail_wc WHERE employee_id = in_employee_id AND celender_id = in_schedule_id;
  INSERT INTO schedule_details (
    employee_id, schedule_id, date,
    is_wc_trash, created_at, updated_at
  )
  VALUES (
    in_employee_id, in_schedule_id, DATE_ADD(base_date, INTERVAL ((7 - DAYOFWEEK(base_date) + 1) % 7 + 1 * 7)-1 DAY),
    IF(task_day2 IS NOT NULL AND task_day2 != '', 1, 0), NOW(), NOW()
  )
  ON DUPLICATE KEY UPDATE
    is_wc_trash = VALUES(is_wc_trash),
    updated_at = VALUES(updated_at);

  -- Day 3
  SELECT day3 INTO task_day3 FROM celender_detail_wc WHERE employee_id = in_employee_id AND celender_id = in_schedule_id;
  INSERT INTO schedule_details (
    employee_id, schedule_id, date,
    is_wc_trash, created_at, updated_at
  )
  VALUES (
    in_employee_id, in_schedule_id, DATE_ADD(base_date, INTERVAL ((7 - DAYOFWEEK(base_date) + 1) % 7 + 2 * 7)-1 DAY),
    IF(task_day3 IS NOT NULL AND task_day3 != '', 1, 0), NOW(), NOW()
  )
  ON DUPLICATE KEY UPDATE
    is_wc_trash = VALUES(is_wc_trash),
    updated_at = VALUES(updated_at);

  -- Day 4
  SELECT day4 INTO task_day4 FROM celender_detail_wc WHERE employee_id = in_employee_id AND celender_id = in_schedule_id;
  INSERT INTO schedule_details (
    employee_id, schedule_id, date,
    is_wc_trash, created_at, updated_at
  )
  VALUES (
    in_employee_id, in_schedule_id, DATE_ADD(base_date, INTERVAL ((7 - DAYOFWEEK(base_date) + 1) % 7 + 3 * 7)-1 DAY),
    IF(task_day4 IS NOT NULL AND task_day4 != '', 1, 0), NOW(), NOW()
  )
  ON DUPLICATE KEY UPDATE
    is_wc_trash = VALUES(is_wc_trash),
    updated_at = VALUES(updated_at);

  -- Day 5
  SELECT day5 INTO task_day5 FROM celender_detail_wc WHERE employee_id = in_employee_id AND celender_id = in_schedule_id;
  INSERT INTO schedule_details (
    employee_id, schedule_id, date,
    is_wc_trash, created_at, updated_at
  )
  VALUES (
    in_employee_id, in_schedule_id, DATE_ADD(base_date, INTERVAL ((7 - DAYOFWEEK(base_date) + 1) % 7 + 4 * 7)-1 DAY),
    IF(task_day5 IS NOT NULL AND task_day5 != '', 1, 0), NOW(), NOW()
  )
  ON DUPLICATE KEY UPDATE
    is_wc_trash = VALUES(is_wc_trash),
    updated_at = VALUES(updated_at);
END;

