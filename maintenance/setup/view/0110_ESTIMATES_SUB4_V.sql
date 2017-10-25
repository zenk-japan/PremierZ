/*-----------------------------------------------------------------------------
-- VIEW名           ：ESTIMATES_SUB4_V
-- 作成者           ：zenk
-- 作成日           ：2012-02-28
-- 更新履歴         ：
-----------------------------------------------------------------------------*/
CREATE OR REPLACE VIEW `ESTIMATES_SUB4_V` AS
	SELECT 
		   es1.`DATA_ID`
		  ,es1.`ESTIMATE_ID`
		  ,co.`COMPANY_ID`
		  ,co.`COMPANY_CODE`
		  ,co.`COMPANY_NAME`
	FROM   `ESTIMATES` es1 LEFT JOIN `COMPANIES` co	 ON es1.`REQUEST_COMPANY_ID` = co.`COMPANY_ID`
;
