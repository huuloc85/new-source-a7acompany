drop procedure if exists reset_eat_room_schedule_details;
CREATE PROCEDURE reset_eat_room_schedule_details(IN p_celender_id BIGINT)
BEGIN
    -- Reset eat room assignments for all employees in a specific calendar
    UPDATE schedule_details 
    SET is_eat_room = 0,
        updated_at = NOW()
    WHERE schedule_id = p_celender_id
      AND is_eat_room = 1;
END;
