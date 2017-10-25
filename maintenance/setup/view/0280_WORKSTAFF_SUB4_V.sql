/*-----------------------------------------------------------------------------
-- VIEW名           ：WORKSTAFF_SUB4_V
-- 作成者           ：zenk
-- 作成日           ：2012-02-28
-- 更新履歴         ：
-----------------------------------------------------------------------------*/
CREATE OR REPLACE VIEW `WORKSTAFF_SUB4_V` AS
	SELECT 
			 ws1.`DATA_ID`
			,ws1.`WORK_STAFF_ID`
			,ws1.`WORK_CONTENT_ID`
			,wc1.`WORK_ARRANGEMENT_ID`
			,us1.`USER_CODE`
			,us1.`NAME`
			,us1.`MOBILE_PHONE`
			,ws1.`VALIDITY_FLAG`
	FROM   ((`WORK_STAFF` ws1 LEFT JOIN `WORK_CONTENTS` wc1 ON ws1.`WORK_CONTENT_ID`	 = wc1.`WORK_CONTENT_ID`)
									   LEFT JOIN `USERS`		 us1 ON wc1.`WORK_ARRANGEMENT_ID` = us1.`USER_ID`)
;