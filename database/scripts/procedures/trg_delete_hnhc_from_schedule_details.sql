drop procedure if exists trg_delete_hnhc_from_schedule_details;
CREATE PROCEDURE trg_delete_hnhc_from_schedule_details(IN p_employee_id BIGINT, IN p_celender_id BIGINT)
BEGIN
    UPDATE schedule_details 
    SET hnhc = NULL,
        updated_at = NOW()
    WHERE employee_id = p_employee_id 
      AND schedule_id = p_celender_id
      AND hnhc IS NOT NULL;
END;
