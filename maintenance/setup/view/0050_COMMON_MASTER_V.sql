/*-----------------------------------------------------------------------------
-- VIEW名           ：COMMON_MASTER_V
-- 作成者           ：zenk
-- 作成日           ：2012-02-28
-- 更新履歴         ：
-----------------------------------------------------------------------------*/
CREATE OR REPLACE VIEW `COMMON_MASTER_V` AS
	SELECT
			cm.`DATA_ID`			   AS `DATA_ID`
		   ,cm.`CODE_ID`			   AS `CODE_ID`
		   ,cm.`CODE_SET`			   AS `CODE_SET`
		   ,cm.`CODE_NAME`			   AS `CODE_NAME`
		   ,cm.`CODE_VALUE`			   AS `CODE_VALUE`
		   ,cm.`REMARKS`			   AS `REMARKS`
		   ,cm.`VALIDATION_START_DATE` AS `VALIDATION_START_DATE`
		   ,cm.`VALIDATION_END_DATE`   AS `VALIDATION_END_DATE`
		   ,cm.`VALIDITY_FLAG`		   AS `VALIDITY_FLAG`
		   ,cm.`REGISTRATION_DATET`	   AS `REGISTRATION_DATET`
		   ,cm.`REGISTRATION_USER_ID`  AS `REGISTRATION_USER_ID`
		   ,cm.`LAST_UPDATE_DATET`	   AS `LAST_UPDATE_DATET`
		   ,cm.`LAST_UPDATE_USER_ID`   AS `LAST_UPDATE_USER_ID`
	FROM	
			`COMMON_MASTER`	   cm
;
