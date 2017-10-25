/*-----------------------------------------------------------------------------
-- VIEW名           ：AUTHORITY_V
-- 作成者           ：zenk
-- 作成日           ：2012-02-28
-- 更新履歴         ：
-----------------------------------------------------------------------------*/
CREATE OR REPLACE VIEW `AUTHORITY_V` AS
	SELECT
			au.`DATA_ID`						AS `DATA_ID`
		   ,au.`AUTHORITY_ID`					AS `AUTHORITY_ID`
		   ,au.`AUTHORITY_CODE`					AS `AUTHORITY_CODE`
		   ,au.`AUTHORITY_NAME`					AS `AUTHORITY_NAME`
		   ,au.`TERMINAL_DIVISION`				AS `TERMINAL_DIVISION`
		   ,aucm1.`CODE_VALUE`					AS `TERMINAL_DIVISION_NAME`
		   ,au.`SCREEN_NAME`					AS `SCREEN_NAME`
		   ,au.`ADMITTED_OPERATION_FLAG`		AS `ADMITTED_OPERATION_FLAG`
		   ,aucm2.`CODE_VALUE`					AS `ADMITTED_OPERATION_FLAG_NAME`
		   ,au.`REMARKS`						AS `REMARKS`
		   ,au.`VALIDITY_FLAG`					AS `VALIDITY_FLAG`
		   ,au.`REGISTRATION_DATET`				AS `REGISTRATION_DATET`
		   ,au.`REGISTRATION_USER_ID`			AS `REGISTRATION_USER_ID`
		   ,au.`LAST_UPDATE_DATET`				AS `LAST_UPDATE_DATET`
		   ,au.`LAST_UPDATE_USER_ID`			AS `LAST_UPDATE_USER_ID`
	FROM	
			`AUTHORITY`				au
		   ,`AUTHORITY_SUB1_V`		aucm1
		   ,`AUTHORITY_SUB2_V`		aucm2
	WHERE	au.`AUTHORITY_ID`	= aucm1.`AUTHORITY_ID`
	  AND	au.`AUTHORITY_ID`	= aucm2.`AUTHORITY_ID`
;
