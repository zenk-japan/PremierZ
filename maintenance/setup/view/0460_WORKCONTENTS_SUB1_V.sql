/*-----------------------------------------------------------------------------
-- VIEW名			：WORKCONTENTS_SUB1_V
-- 作成者			：zenk
-- 作成日			：2012-02-28
-- 更新履歴			：
-----------------------------------------------------------------------------*/
CREATE OR REPLACE VIEW `WORKCONTENTS_SUB1_V` AS
	SELECT 
		   wc1.`DATA_ID`
		  ,wc1.`WORK_CONTENT_ID`
		  ,wc1.`EXCESS_LIQUIDATION_FLAG`
		  ,cm.`CODE_VALUE`
		  ,cm.`VALIDITY_FLAG`
		  ,cm.`VALIDATION_START_DATE`
		  ,cm.`VALIDATION_END_DATE`
	FROM   `WORK_CONTENTS` wc1 LEFT JOIN `COMMON_MASTER` cm
	    ON (	wc1.`EXCESS_LIQUIDATION_FLAG` = cm.`CODE_NAME`
			AND	wc1.`DATA_ID` = cm.`DATA_ID`
			AND	cm.`CODE_SET` = 'EXCESS_LIQUIDATION_FLAG')
;