/*-----------------------------------------------------------------------------
-- VIEW名           ：WORKSTAFF_SUB2_V
-- 作成者           ：zenk
-- 作成日           ：2012-02-28
-- 更新履歴         ：2012-05-15 結合条件見直し
-----------------------------------------------------------------------------*/
CREATE OR REPLACE VIEW `WORKSTAFF_SUB2_V` AS
	SELECT 
		   ws1.`DATA_ID`
		  ,ws1.`WORK_STAFF_ID`
		  ,ws1.`TRANSMISSION_FLAG`
		  ,cm.`CODE_VALUE`
		  ,cm.`VALIDITY_FLAG`
		  ,cm.`VALIDATION_START_DATE`
		  ,cm.`VALIDATION_END_DATE`
	FROM   `WORK_STAFF` ws1 LEFT JOIN `COMMON_MASTER` cm
    	ON (	ws1.`TRANSMISSION_FLAG` = cm.`CODE_NAME`
			AND	ws1.`DATA_ID` = cm.`DATA_ID`
			AND	cm.`CODE_SET` = 'TRANSMISSION_FLAG')
;