drop procedure if exists delete_calendar_schedule_details;
CREATE PROCEDURE delete_calendar_schedule_details(IN p_celender_id BIGINT)
BEGIN
    -- Delete all schedule_details records for a specific calendar
    DELETE FROM schedule_details 
    WHERE schedule_id = p_celender_id;
END;
