DROP PROCEDURE IF EXISTS run_all_insert_wc_clean_men_to_schedule_details;

create procedure run_all_insert_wc_clean_men_to_schedule_details()
BEGIN
  DECLARE done INT DEFAULT FALSE;
  DECLARE v_emp_id BIGINT;
  DECLARE v_cal_id BIGINT;
  DECLARE cur CURSOR FOR
    SELECT employee_id, celender_id
    FROM celender_detail_wc_clean_men
    GROUP BY employee_id, celender_id;

  DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

  OPEN cur;

  read_loop: LOOP
    FETCH cur INTO v_emp_id, v_cal_id;
    IF done THEN
      LEAVE read_loop;
    END IF;

    CALL insert_wc_clean_men_to_schedule_details(v_emp_id, v_cal_id);
  END LOOP;

  CLOSE cur;
END;

