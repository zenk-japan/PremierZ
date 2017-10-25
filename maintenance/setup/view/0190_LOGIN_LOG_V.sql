/*-----------------------------------------------------------------------------
-- VIEW名           ：LOGIN_LOG_V
-- 作成者           ：zenk
-- 作成日           ：2012-02-28
-- 更新履歴         ：
-----------------------------------------------------------------------------*/
CREATE OR REPLACE VIEW `LOGIN_LOG_V` AS
	SELECT
			ll.`LOGIN_LOG_ID`			AS `LOGIN_LOG_ID`
		   ,ll.`USED_USER_CODE`			AS `USED_USER_CODE`
		   ,ll.`USED_PASSWORD`			AS `USED_PASSWORD`
		   ,ll.`USED_COMPANY_CODE`		AS `USED_COMPANY_CODE`
		   ,ll.`CERTIFICATION_RESULT`	AS `CERTIFICATION_RESULT`
		   ,ll.`SPG_REFERER`			AS `SPG_REFERER`
		   ,ll.`SPG_REMORT_ADDR`		AS `SPG_REMORT_ADDR`
		   ,ll.`SPG_SERVER`				AS `SPG_SERVER`
		   ,ll.`SPG_REQUEST`			AS `SPG_REQUEST`
		   ,ll.`REMARK`					AS `REMARK`
		   ,ll.`VALIDITY_FLAG`			AS `VALIDITY_FLAG`
		   ,ll.`REGISTRATION_DATET`		AS `REGISTRATION_DATET`
		   ,ll.`REGISTRATION_USER_ID`	AS `REGISTRATION_USER_ID`
		   ,ll.`LAST_UPDATE_DATET`		AS `LAST_UPDATE_DATET`
		   ,ll.`LAST_UPDATE_USER_ID`	AS `LAST_UPDATE_USER_ID`
	FROM
			LOGIN_LOG ll
;