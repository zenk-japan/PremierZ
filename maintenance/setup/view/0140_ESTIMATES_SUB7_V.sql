/*-----------------------------------------------------------------------------
-- VIEW名           ：ESTIMATES_SUB7_V
-- 作成者           ：zenk
-- 作成日           ：2012-02-28
-- 更新履歴         ：
-----------------------------------------------------------------------------*/
CREATE OR REPLACE VIEW `ESTIMATES_SUB7_V` AS
	SELECT 
		   es1.`DATA_ID`
		  ,es1.`ESTIMATE_ID`
		  ,us.`USER_ID`
		  ,us.`USER_CODE`
		  ,us.`NAME`
	FROM   `ESTIMATES` es1 LEFT JOIN `USERS` us	 ON es1.`ENDUSER_USER_ID` = us.`USER_ID`
;