/*-----------------------------------------------------------------------------
-- VIEW名           ：COMPANIES_V
-- 作成者           ：zenk
-- 作成日           ：2012-02-28
-- 更新履歴         ：
-----------------------------------------------------------------------------*/
CREATE OR REPLACE VIEW `COMPANIES_V` AS
	SELECT
			co.`DATA_ID`
		   ,co.`COMPANY_ID`
		   ,co.`COMPANY_CODE`
		   ,co.`COMPANY_NAME`
		   ,co.`COMP_CLASS`
		   ,cs1.`CODE_VALUE` AS `COMP_CLASS_NAME`
		   ,co.`WELL_SET_DAY`
		   ,co.`PAYMENT_DAY`
		   ,co.`REMARKS`
		   ,co.`VALIDITY_FLAG`
		   ,co.`REGISTRATION_DATET`
		   ,co.`REGISTRATION_USER_ID`
		   ,co.`LAST_UPDATE_DATET`
		   ,co.`LAST_UPDATE_USER_ID`
	FROM
			`COMPANIES`		   co,
			`COMPANIES_SUB1_V` cs1
	WHERE
			cs1.COMPANY_ID			= co.COMPANY_ID
;
