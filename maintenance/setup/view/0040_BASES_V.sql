/*-----------------------------------------------------------------------------
-- VIEW名           ：BASES_V
-- 作成者           ：zenk
-- 作成日           ：2012-02-28
-- 更新履歴         ：
-----------------------------------------------------------------------------*/
CREATE OR REPLACE VIEW `BASES_V` AS
	SELECT
			ba.`DATA_ID`
		   ,ba.`COMPANY_ID`
		   ,cp.`COMPANY_CODE`
		   ,cp.`COMPANY_NAME`
		   ,ba.`BASE_ID`
		   ,ba.`BASE_CODE`
		   ,ba.`BASE_NAME`
		   ,ba.`ZIP_CODE`
		   ,ba.`ADDRESS`
		   ,ba.`TELEPHONE`
		   ,ba.`CLOSEST_STATION`
		   ,ba.`REMARKS`
		   ,ba.`VALIDITY_FLAG`
		   ,ba.`REGISTRATION_DATET`
		   ,ba.`REGISTRATION_USER_ID`
		   ,ba.`LAST_UPDATE_DATET`
		   ,ba.`LAST_UPDATE_USER_ID`
	FROM   (`BASES` ba LEFT JOIN `COMPANIES` cp	 ON ba.`COMPANY_ID` = cp.`COMPANY_ID`)
;
