/*-----------------------------------------------------------------------------
-- VIEW名			：WORKSITUATION_V
-- 作成者			：zenk
-- 作成日			：2012-02-28
-- 更新履歴			：
-----------------------------------------------------------------------------*/
CREATE OR REPLACE VIEW `WORKSITUATION_V` AS
	SELECT
			ws.`DATA_ID`						AS `DATA_ID`
		   ,es.`ESTIMATE_ID`					AS `ESTIMATE_ID`
		   ,es.`ESTIMATE_CODE`					AS `ESTIMATE_CODE`
		   ,es.`WORK_NAME`						AS `WORK_NAME`
		   ,wc.`WORK_CONTENT_ID`				AS `WORK_CONTENT_ID`
		   ,wc.`WORK_DATE`						AS `WORK_DATE`
		   ,wc.`WORK_STATUS`					AS `WORK_STATUS`
		   ,wcwk1.`CODE_VALUE`					AS `WORK_STATUS_NAME`
		   ,ws.`WORK_STAFF_ID`					AS `WORK_STAFF_ID`
		   ,ws.`APPROVAL_DIVISION`				AS `APPROVAL_DIVISION`
		   ,wscm1.`CODE_VALUE`					AS `APPROVAL_DIVISION_NAME`
		   ,ws.`CANCEL_DIVISION`				AS `CANCEL_DIVISION`
		   ,wscm3.`CODE_VALUE`					AS `CANCEL_DIVISION_NAME`
		   ,ws.`WORK_USER_ID`					AS `WORK_USER_ID`
		   ,us.`NAME`							AS `WORK_STAFF_NAME`
		   ,ws.`WORK_BASE_ID`					AS `WORK_BASE_ID`
		   ,ba.`BASE_NAME`						AS `WORK_BASE_NAME`
		   ,date_format(ws.`DISPATCH_SCHEDULE_TIMET`, '%H:%i')
												AS `DISPATCH_SCHEDULE_TIMET`
		   ,date_format(ws.`DISPATCH_STAFF_TIMET`, '%H:%i')
												AS `DISPATCH_STAFF_TIMET`
		   ,date_format(ws.`ENTERING_SCHEDULE_TIMET`, '%H:%i')
												AS `ENTERING_SCHEDULE_TIMET`
		   ,date_format(ws.`ENTERING_STAFF_TIMET`, '%H:%i')
												AS `ENTERING_STAFF_TIMET`
		   ,date_format(ws.`ENTERING_MANAGE_TIMET`, '%H:%i')
												AS `ENTERING_MANAGE_TIMET`
		   ,date_format(ws.`LEAVE_SCHEDULE_TIMET`, '%H:%i')
												AS `LEAVE_SCHEDULE_TIMET`
		   ,date_format(ws.`LEAVE_STAFF_TIMET`, '%H:%i')
												AS `LEAVE_STAFF_TIMET`
		   ,date_format(ws.`LEAVE_MANAGE_TIMET`, '%H:%i')
												AS `LEAVE_MANAGE_TIMET`
		   ,es.`VALIDITY_FLAG`					AS `ESTIMATES_VALIDITY_FLAG`
		   ,wc.`VALIDITY_FLAG`					AS `CONTENTS_VALIDITY_FLAG`
		   ,ws.`VALIDITY_FLAG`					AS `VALIDITY_FLAG`
		   ,ws.`REGISTRATION_DATET`				AS `REGISTRATION_DATET`
		   ,ws.`REGISTRATION_USER_ID`			AS `REGISTRATION_USER_ID`
		   ,ws.`LAST_UPDATE_DATET`				AS `LAST_UPDATE_DATET`
		   ,ws.`LAST_UPDATE_USER_ID`			AS `LAST_UPDATE_USER_ID`
	FROM	
		((((`WORK_STAFF`			ws	LEFT JOIN `WORK_CONTENTS` wc  ON ws.`WORK_CONTENT_ID` = wc.`WORK_CONTENT_ID`)
												 LEFT JOIN `ESTIMATES`	   es  ON wc.`ESTIMATE_ID`	   = es.`ESTIMATE_ID`)
												 LEFT JOIN `USERS`		   us  ON ws.`WORK_USER_ID`	   = us.`USER_ID`)
												 LEFT JOIN `BASES`		   ba  ON ws.`WORK_BASE_ID`	   = ba.`BASE_ID`)
		   ,`WORKSTAFF_SUB1_V`	   wscm1
		   ,`WORKSTAFF_SUB3_V`	   wscm3
		   ,`WORKCONTENTS_SUB3_V`  wcwk1
	WHERE	ws.`WORK_STAFF_ID`	 = wscm1.`WORK_STAFF_ID`
	AND		ws.`WORK_STAFF_ID`	 = wscm3.`WORK_STAFF_ID`
	AND		wc.`WORK_CONTENT_ID` = wcwk1.`WORK_CONTENT_ID`
	AND		wc.`VALIDITY_FLAG`	 = 'Y'
	AND		es.`VALIDITY_FLAG`	 = 'Y'
;