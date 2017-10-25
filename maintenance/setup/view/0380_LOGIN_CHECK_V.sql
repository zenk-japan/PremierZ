/*-----------------------------------------------------------------------------
-- VIEW名           ：LOGIN_CHECK_V
-- 作成者           ：zenk
-- 作成日           ：2012-02-28
-- 更新履歴         ：
-----------------------------------------------------------------------------*/
CREATE OR REPLACE VIEW `LOGIN_CHECK_V` AS
	SELECT
			se.`DATA_ID`						AS `DATA_ID`
		   ,se.`SESSION_ID`						AS `SESSION_ID`
		   ,se.`SESSID`							AS `SESSID`
		   ,se.`SESS_TOKEN`						AS `SESS_TOKEN`
		   ,se.`USER_ID`						AS `USER_ID`
		   ,us.`USER_CODE`						AS `USER_CODE`
		   ,us.`NAME`							AS `NAME`
		   ,us.`AUTHORITY_ID`					AS `AUTHORITY_ID`
		   ,au.`AUTHORITY_CODE`					AS `AUTHORITY_CODE`
		   ,au.`AUTHORITY_NAME`					AS `AUTHORITY_NAME`
		   ,au.`TERMINAL_DIVISION`				AS `TERMINAL_DIVISION`
		   ,aucm1.`CODE_VALUE`					AS `TERMINAL_DIVISION_NAME`
		   ,se.`LOGIN_FLAG`						AS `LOGIN_FLAG`
		   ,secm1.`CODE_VALUE`					AS `LOGIN_FLAG_NAME`
		   ,se.`LOGIN_DATET`					AS `LOGIN_DATET`
		   ,se.`VALIDITY_FLAG`					AS `VALIDITY_FLAG`
		   ,se.`REGISTRATION_DATET`				AS `REGISTRATION_DATET`
		   ,se.`REGISTRATION_USER_ID`			AS `REGISTRATION_USER_ID`
		   ,se.`LAST_UPDATE_DATET`				AS `LAST_UPDATE_DATET`
		   ,se.`LAST_UPDATE_USER_ID`			AS `LAST_UPDATE_USER_ID`
	FROM
		  ((`SESSIONS` se LEFT JOIN `USERS`		us	ON se.`USER_ID`		 = us.`USER_ID`)
								   LEFT JOIN `AUTHORITY` au	 ON us.`AUTHORITY_ID` = au.`AUTHORITY_ID`)
		   ,`SESSIONS_SUB1_V`		secm1
		   ,`AUTHORITY_SUB1_V`		aucm1
	WHERE  (se.`SESSID` IS NOT NULL OR se.`LOGIN_FLAG` = 'Y')
    /* 最終操作（画面表示から30分経過時）*/
	AND		se.`LAST_UPDATE_DATET` < now() - INTERVAL 30 MINUTE /* ( 1 MINUTE, 1 HOUR, 1 DAY）*/
	AND		se.`SESSION_ID`		= secm1.`SESSION_ID`
	AND		au.`AUTHORITY_ID`	= aucm1.`AUTHORITY_ID`
	ORDER BY se.`LAST_UPDATE_DATET` DESC
;