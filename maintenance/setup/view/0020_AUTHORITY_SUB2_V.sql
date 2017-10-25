/*-----------------------------------------------------------------------------
-- VIEW名           ：AUTHORITY_SUB2_V
-- 作成者           ：zenk
-- 作成日           ：2012-02-28
-- 更新履歴         ：2012-05-15 結合条件見直し
-----------------------------------------------------------------------------*/
CREATE OR REPLACE VIEW `AUTHORITY_SUB2_V` AS
	SELECT 
		   au1.`DATA_ID`
		  ,au1.`AUTHORITY_ID`
		  ,au1.`ADMITTED_OPERATION_FLAG`
		  ,cm.`CODE_VALUE`
		  ,cm.`VALIDITY_FLAG`
		  ,cm.`VALIDATION_START_DATE`
		  ,cm.`VALIDATION_END_DATE`
	FROM   `AUTHORITY` au1 LEFT JOIN `COMMON_MASTER` cm
		ON (	au1.`ADMITTED_OPERATION_FLAG` = cm.`CODE_NAME`
			AND	au1.`DATA_ID` = cm.`DATA_ID`
			AND	cm.`CODE_SET` = 'ADMITTED_OPERATION_FLAG')
;
