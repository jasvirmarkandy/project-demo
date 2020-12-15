# project-demo
Fristly Install mysql database which is in Install_Sql.sql file

Then just change in db_connect.php according to your localhost sql username and password

----------------------------------------------------------------------------------------
For Step 7: Delete all URLs older than 30 days that aren’t linked to a user account

Answer -> i will use mysql event scheduler
Firstly, make sure the Event Scheduler is enabled. To enable it use

SET GLOBAL event_scheduler = ON;

Then Script

delimiter |
CREATE EVENT cleaning ON SCHEDULE EVERY 1 MONTH ENABLE
  DO
  BEGIN
    DECLARE MaxTime TIMESTAMP;
    SET MaxTime = CURRENT_TIMESTAMP - INTERVAL 1 MONTH;
    DELETE FROM url_Shorten
    WHERE id IN (SELECT url_id FROM id_info WHERE created_at < MaxTime);
    DELETE FROM users
    WHERE id IN (SELECT user_id FROM id_info WHERE user_id = NULL)
    DELETE FROM id_info
    WHERE id_info.Created_at < MaxTime;
  END |
delimiter ;


CREATE EVENT cleaning ON SCHEDULE EVERY 1 MONTH ENABLE
  DO 
  DELETE FROM MyTable
  WHERE `timestamp_column` < CURRENT_TIMESTAMP - INTERVAL 1 MONTH;
----------------------------------------------------------------------------------------

----------------------------------------------------------------------------------------
For Step 8 : Question: How can the deletion script be automated to run once per day?

Answer -> Scrpit -> CREATE EVENT delete_all_expired 
			ON SCHEDULE EVERY DAY DO
				DELETE FROM my_table WHERE expiry < NOW();
----------------------------------------------------------------------------------------
Thanks & Regards,
Jasvir Markandy
