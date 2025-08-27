drop procedure if exists trg_delete_eat_room_from_schedule_details;
CREATE PROCEDURE trg_delete_eat_room_from_schedule_details(IN p_employee_id BIGINT, IN p_celender_id BIGINT)
BEGIN
    UPDATE schedule_details 
    SET is_eat_room = 0,
        updated_at = NOW()
    WHERE employee_id = p_employee_id 
      AND schedule_id = p_celender_id
      AND is_eat_room = 1;
END;
