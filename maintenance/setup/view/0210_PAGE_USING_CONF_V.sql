/*-----------------------------------------------------------------------------
-- VIEW名           ：PAGE_USING_CONF_V
-- 作成者           ：zenk
-- 作成日           ：2012-02-28
-- 更新履歴         ：
-----------------------------------------------------------------------------*/
CREATE OR REPLACE VIEW `PAGE_USING_CONF_V` AS
	SELECT
			puc.`DATA_ID`						AS `DATA_ID`
		   ,puc.`PAGE_USING_CONF_ID`			AS `PAGE_USING_CONF_ID`
		   ,puc.`PAGE_CODE`						AS `PAGE_CODE`
		   ,puc.`PAGE_NAME`						AS `PAGE_NAME`
		   ,puc.`ALLOWED_AUTHCODE`				AS `ALLOWED_AUTHCODE`
		   ,puc.`REMARKS`						AS `REMARKS`
		   ,puc.`RESERVE_1`						AS `RESERVE_1`
		   ,puc.`RESERVE_2`						AS `RESERVE_2`
		   ,puc.`RESERVE_3`						AS `RESERVE_3`
		   ,puc.`RESERVE_4`						AS `RESERVE_4`
		   ,puc.`RESERVE_5`						AS `RESERVE_5`
		   ,puc.`RESERVE_6`						AS `RESERVE_6`
		   ,puc.`RESERVE_7`						AS `RESERVE_7`
		   ,puc.`RESERVE_8`						AS `RESERVE_8`
		   ,puc.`RESERVE_9`						AS `RESERVE_9`
		   ,puc.`RESERVE_10`					AS `RESERVE_10`
		   ,puc.`VALIDITY_FLAG`					AS `VALIDITY_FLAG`
		   ,puc.`REGISTRATION_DATET`			AS `REGISTRATION_DATET`
		   ,puc.`REGISTRATION_USER_ID`			AS `REGISTRATION_USER_ID`
		   ,puc.`LAST_UPDATE_DATET`				AS `LAST_UPDATE_DATET`
		   ,puc.`LAST_UPDATE_USER_ID`			AS `LAST_UPDATE_USER_ID`
	FROM
		   `PAGE_USING_CONF` puc
;