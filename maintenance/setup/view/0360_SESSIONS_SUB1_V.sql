/*-----------------------------------------------------------------------------
-- VIEW名           ：SESSIONS_SUB1_V
-- 作成者           ：zenk
-- 作成日           ：2012-02-28
-- 更新履歴         ：
-----------------------------------------------------------------------------*/
CREATE OR REPLACE VIEW `SESSIONS_SUB1_V` AS
	SELECT 
		   se1.`DATA_ID`
		  ,se1.`SESSION_ID`
		  ,se1.`LOGIN_FLAG`
		  ,cm.`CODE_VALUE`
		  ,cm.`VALIDITY_FLAG`
		  ,cm.`VALIDATION_START_DATE`
		  ,cm.`VALIDATION_END_DATE`
	FROM   `SESSIONS` se1 LEFT JOIN `COMMON_MASTER` cm	ON se1.`LOGIN_FLAG` = cm.`CODE_NAME`
	WHERE ((cm.`CODE_ID` IS NULL)
		   OR
		  (cm.`CODE_ID` IS NOT NULL
	AND	   cm.`CODE_SET` = 'LOGIN_FLAG'
	AND	   cm.`DATA_ID` = se1.`DATA_ID`))
;