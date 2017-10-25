/*-----------------------------------------------------------------------------
-- VIEW名           ：ESTIMATES_SUB1_V
-- 作成者           ：zenk
-- 作成日           ：2012-02-28
-- 更新履歴         ：2012-05-15 結合条件見直し
-----------------------------------------------------------------------------*/
CREATE OR REPLACE VIEW `ESTIMATES_SUB1_V` AS
	SELECT 
		   es1.`DATA_ID`
		  ,es1.`ESTIMATE_ID`
		  ,es1.`ORDER_DIVISION`
		  ,cm.`CODE_VALUE`
		  ,cm.`VALIDITY_FLAG`
		  ,cm.`VALIDATION_START_DATE`
		  ,cm.`VALIDATION_END_DATE`
	FROM   `ESTIMATES` es1 LEFT JOIN `COMMON_MASTER` cm
		ON (	es1.`ORDER_DIVISION` = cm.`CODE_NAME`
		 	AND	es1.`DATA_ID` = cm.`DATA_ID`
		 	AND cm.`CODE_SET` = 'ORDER_DIVISION')
;
