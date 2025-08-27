drop procedure if exists reset_hnhc_schedule_details;
CREATE PROCEDURE reset_hnhc_schedule_details(IN p_celender_id BIGINT)
BEGIN
    -- Reset HNHC field to NULL for all employees in a specific calendar
    UPDATE schedule_details 
    SET hnhc = NULL,
        updated_at = NOW()
    WHERE schedule_id = p_celender_id
      AND hnhc IS NOT NULL;
END;
