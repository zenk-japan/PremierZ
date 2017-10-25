/*-----------------------------------------------------------------------------
-- VIEW名           ：GROUPS_SUB1_V
-- 作成者           ：zenk
-- 作成日           ：2012-02-28
-- 更新履歴         ：2012-05-15 結合条件見直し
-----------------------------------------------------------------------------*/
CREATE OR REPLACE VIEW `GROUPS_SUB1_V` AS
	SELECT 
		   gp1.`DATA_ID`
		  ,gp1.`GROUP_ID`
		  ,gp1.`CLASSIFICATION_DIVISION`
		  ,cm.`CODE_VALUE`
		  ,cm.`VALIDITY_FLAG`
		  ,cm.`VALIDATION_START_DATE`
		  ,cm.`VALIDATION_END_DATE`
	FROM   `GROUPS` gp1 LEFT JOIN `COMMON_MASTER` cm
		ON (	gp1.`CLASSIFICATION_DIVISION` = cm.`CODE_NAME`
			AND	gp1.`DATA_ID` = cm.`DATA_ID`
			AND	cm.`CODE_SET` = 'CLASSIFICATION_DIVISION')
;