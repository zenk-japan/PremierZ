/*-----------------------------------------------------------------------------
-- VIEW名			：WORKCONTENTS_SUB3_V
-- 作成者			：zenk
-- 作成日			：2012-02-28
-- 更新履歴			：2012-05-15 結合条件見直し
-----------------------------------------------------------------------------*/
CREATE OR REPLACE VIEW `WORKCONTENTS_SUB3_V` AS
	SELECT 
		   wc3.`DATA_ID`
		  ,wc3.`WORK_CONTENT_ID`
		  ,wc3.`WORK_STATUS`
		  ,cm.`CODE_VALUE`
		  ,cm.`VALIDITY_FLAG`
		  ,cm.`VALIDATION_START_DATE`
		  ,cm.`VALIDATION_END_DATE`
	FROM   `WORK_CONTENTS` wc3 LEFT JOIN `COMMON_MASTER` cm
		ON (	wc3.`WORK_STATUS` = cm.`CODE_NAME`
			AND	wc3.`DATA_ID` = cm.`DATA_ID`
			AND	cm.`CODE_SET` = 'WORK_STATUS')
;