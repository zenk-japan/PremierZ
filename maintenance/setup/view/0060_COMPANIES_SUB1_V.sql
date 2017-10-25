/*-----------------------------------------------------------------------------
-- VIEW名           ：COMPANIES_SUB1_V
-- 作成者           ：zenk
-- 作成日           ：2012-02-28
-- 更新履歴         ：2012-05-15 結合条件見直し
-----------------------------------------------------------------------------*/
CREATE OR REPLACE VIEW `COMPANIES_SUB1_V` AS
	SELECT 
		   co1.`DATA_ID`
		  ,co1.`COMPANY_ID`
		  ,co1.`COMP_CLASS`
		  ,cm.`CODE_VALUE`
		  ,cm.`VALIDITY_FLAG`
		  ,cm.`VALIDATION_START_DATE`
		  ,cm.`VALIDATION_END_DATE`
	FROM   `COMPANIES` co1 LEFT JOIN `COMMON_MASTER` cm
		ON (	co1.`COMP_CLASS` = cm.`CODE_NAME`
			AND	co1.`DATA_ID` = cm.`DATA_ID`
			AND	cm.`CODE_SET` = 'COMP_CLASS')
;
