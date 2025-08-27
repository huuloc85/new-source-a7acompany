drop procedure if exists trg_delete_wc_clean_men_from_schedule_details;
CREATE PROCEDURE trg_delete_wc_clean_men_from_schedule_details(IN p_employee_id BIGINT, IN p_celender_id BIGINT)
BEGIN
    UPDATE schedule_details 
    SET is_wc_clean_men = 0,
        updated_at = NOW()
    WHERE employee_id = p_employee_id 
      AND schedule_id = p_celender_id
      AND is_wc_clean_men = 1;
END;
