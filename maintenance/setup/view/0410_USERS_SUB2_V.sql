/*-----------------------------------------------------------------------------
-- VIEW名			：USERS_SUB2_V
-- 作成者			：zenk
-- 作成日			：2012-02-28
-- 更新履歴			：2012-05-15 結合条件見直し
-----------------------------------------------------------------------------*/
CREATE OR REPLACE VIEW `USERS_SUB2_V` AS
	SELECT 
		   us1.`DATA_ID`
		  ,us1.`USER_ID`
		  ,us1.`SEX`
		  ,cm.`CODE_VALUE`
		  ,cm.`VALIDITY_FLAG`
		  ,cm.`VALIDATION_START_DATE`
		  ,cm.`VALIDATION_END_DATE`
	FROM   `USERS` us1 LEFT JOIN `COMMON_MASTER` cm
		ON (	us1.`SEX` = cm.`CODE_NAME`
			AND	us1.`DATA_ID` = cm.`DATA_ID`
			AND	cm.`CODE_SET` = 'SEX')
;