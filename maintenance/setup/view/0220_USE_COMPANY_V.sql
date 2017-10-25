/*-----------------------------------------------------------------------------
-- VIEW名           ：USE_COMPANY_V
-- 作成者           ：zenk
-- 作成日           ：2012-02-28
-- 更新履歴         ：
-----------------------------------------------------------------------------*/
CREATE OR REPLACE VIEW `USE_COMPANY_V` AS
	SELECT
			uc.`DATA_ID`						AS `DATA_ID`
		   ,uc.`USE_COMPANY_CODE`				AS `USE_COMPANY_CODE`
		   ,uc.`USE_COMPANY_NAME`				AS `USE_COMPANY_NAME`
		   ,uc.`REMARKS`						AS `REMARKS`
		   ,uc.`RESERVE_1`						AS `RESERVE_1`
		   ,uc.`RESERVE_2`						AS `RESERVE_2`
		   ,uc.`RESERVE_3`						AS `RESERVE_3`
		   ,uc.`RESERVE_4`						AS `RESERVE_4`
		   ,uc.`RESERVE_5`						AS `RESERVE_5`
		   ,uc.`RESERVE_6`						AS `RESERVE_6`
		   ,uc.`RESERVE_7`						AS `RESERVE_7`
		   ,uc.`RESERVE_8`						AS `RESERVE_8`
		   ,uc.`RESERVE_9`						AS `RESERVE_9`
		   ,uc.`RESERVE_10`						AS `RESERVE_10`
		   ,uc.`VALIDITY_FLAG`					AS `VALIDITY_FLAG`
		   ,uc.`REGISTRATION_DATET`				AS `REGISTRATION_DATET`
		   ,uc.`REGISTRATION_USER_ID`			AS `REGISTRATION_USER_ID`
		   ,uc.`LAST_UPDATE_DATET`				AS `LAST_UPDATE_DATET`
		   ,uc.`LAST_UPDATE_USER_ID`			AS `LAST_UPDATE_USER_ID`
	FROM
		   `USE_COMPANY` uc
;