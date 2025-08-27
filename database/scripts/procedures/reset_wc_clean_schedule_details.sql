drop procedure if exists reset_wc_clean_schedule_details;
CREATE PROCEDURE reset_wc_clean_schedule_details(IN p_celender_id BIGINT)
BEGIN
    -- Reset all WC cleaning assignments for all employees in a specific calendar
    UPDATE schedule_details 
    SET is_wc_clean_men = 0,
        is_wc_clean_women = 0,
        is_wc_trash = 0,
        updated_at = NOW()
    WHERE schedule_id = p_celender_id
      AND (is_wc_clean_men = 1 OR is_wc_clean_women = 1 OR is_wc_trash = 1);
END;
