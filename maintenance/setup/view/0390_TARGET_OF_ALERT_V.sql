/*-----------------------------------------------------------------------------
-- VIEW名			：TARGET_OF_ALERT_V
-- 作成者			：zenk
-- 作成日			：2012-02-28
-- 更新履歴			：2012-11-01 IS NULL,IS NOT NULL判定を!='',=''に変更
-----------------------------------------------------------------------------*/
CREATE OR REPLACE VIEW `TARGET_OF_ALERT_V` AS
	SELECT
			ws.`DATA_ID`						AS `DATA_ID`
		   ,ws.`WORK_STAFF_ID`					AS `WORK_STAFF_ID`
		   ,ws.`WORK_CONTENT_ID`				AS `WORK_CONTENT_ID`
		   ,es.`WORK_NAME`						AS `WORK_NAME`
		   ,wc.`WORK_DATE`						AS `WORK_DATE`
		   ,DATE_FORMAT(wc.`WORK_DATE`, '%c/%d')
												AS `MAIL_WORK_DATE_SHORT`
		   ,DATE_FORMAT(wc.`WORK_DATE`, '%c/%d(%a)')
												AS `MAIL_WORK_DATE`
		   ,DATE_FORMAT(wc.`AGGREGATE_TIMET`, '%H:%i')
												AS `AGGREGATE_TIMET`
		   ,wc.`AGGREGATE_POINT`				AS `AGGREGATE_POINT`
		   ,wc.`WORK_CONTENT_DETAILS`			AS `WORK_CONTENT_DETAILS`
		   ,ws.`WORK_BASE_ID`					AS `WORK_BASE_ID`
		   ,ba.`BASE_CODE`						AS `WORK_BASE_CODE`
		   ,ba.`BASE_NAME`						AS `WORK_BASE_NAME`
		   ,wsus2.`USER_CODE`					AS `WORK_USER_CODE`
		   ,wsus2.`NAME`						AS `WORK_USER_NAME`
		   ,wsus2.`KANA`						AS `WORK_USER_KANA`
		   ,wsus2.`CLOSEST_STATION`				AS `CLOSEST_STATION`
		   ,wsus2.`HOME_PHONE`					AS `WORK_HOME_PHONE`
		   ,wsus2.`HOME_MAIL`					AS `WORK_HOME_MAIL`
		   ,wsus2.`MOBILE_PHONE`				AS `WORK_MOBILE_PHONE`
		   ,wsus2.`MOBILE_PHONE_MAIL`			AS `WORK_MOBILE_PHONE_MAIL`
		   ,ws.`APPROVAL_DIVISION`				AS `APPROVAL_DIVISION`
		   ,wscm1.`CODE_VALUE`					AS `APPROVAL_DIVISION_NAME`
		   ,ws.`TRANSMISSION_FLAG`				AS `TRANSMISSION_FLAG`
		   ,wscm2.`CODE_VALUE`					AS `TRANSMISSION_FLAG_NAME`
		   ,ws.`CANCEL_DIVISION`				AS `CANCEL_DIVISION`
		   ,wscm3.`CODE_VALUE`					AS `CANCEL_DIVISION_NAME`
		   ,CASE
			  WHEN (ws.`DISPATCH_SCHEDULE_TIMET` IS NOT NULL AND ws.`DISPATCH_SCHEDULE_TIMET` != '')
				THEN date_format(ws.`DISPATCH_SCHEDULE_TIMET`, '%Y%m%d%H%i')
			  ELSE ws.`DISPATCH_SCHEDULE_TIMET`
			END									AS `DISPATCH_SCHEDULE_TIMET`
		   ,CASE
			  WHEN (ws.`DISPATCH_STAFF_TIMET` IS NOT NULL AND ws.`DISPATCH_STAFF_TIMET` != '')
				THEN date_format(ws.`DISPATCH_STAFF_TIMET`, '%Y%m%d%H%i')
			  ELSE ws.`DISPATCH_STAFF_TIMET`
			END									AS `DISPATCH_STAFF_TIMET`
		   ,cm1.`CODE_VALUE`					AS `DISPATCH_DELAY_TOLERANCE`
		   ,CASE
			  WHEN (ws.`DISPATCH_STAFF_TIMET` IS NULL OR ws.`DISPATCH_STAFF_TIMET` = '')
			   AND date_format(now(), '%Y%m%d%H%i') >= date_format(ws.`DISPATCH_SCHEDULE_TIMET` + INTERVAL cm1.`CODE_VALUE` MINUTE, '%Y%m%d%H%i')
			   AND ws.`DISPATCH_DELAY_NOTIFIED` = 'N'
				THEN 'Y'
			  ELSE 'N'
			END									AS `DISPATCH_DELAY_FLAG`
		   ,ws.`DISPATCH_DELAY_NOTIFIED`		AS `DISPATCH_DELAY_NOTIFIED`
		   ,CASE
			  WHEN (ws.`ENTERING_SCHEDULE_TIMET` IS NOT NULL AND ws.`ENTERING_SCHEDULE_TIMET` != '')
				THEN date_format(ws.`ENTERING_SCHEDULE_TIMET`, '%Y%m%d%H%i')
			  ELSE ws.`ENTERING_SCHEDULE_TIMET`
			END									AS `ENTERING_SCHEDULE_TIMET`
		   ,CASE
			  WHEN (ws.`ENTERING_STAFF_TIMET` IS NOT NULL AND ws.`ENTERING_STAFF_TIMET` != '')
				THEN date_format(ws.`ENTERING_STAFF_TIMET`, '%Y%m%d%H%i')
			  ELSE ws.`ENTERING_STAFF_TIMET`
			END									AS `ENTERING_STAFF_TIMET`
		   ,cm2.`CODE_VALUE`					AS `ENTERING_DELAY_TOLERANCE`
		   ,CASE
			  WHEN (ws.`ENTERING_STAFF_TIMET` IS NULL OR ws.`ENTERING_STAFF_TIMET` = '')
			   AND date_format(now(), '%Y%m%d%H%i') >= date_format(ws.`ENTERING_SCHEDULE_TIMET` + INTERVAL cm2.`CODE_VALUE` MINUTE, '%Y%m%d%H%i')
			   AND ws.`ENTERING_DELAY_NOTIFIED` = 'N'
				THEN 'Y'
			  ELSE 'N'
			END									AS `ENTERING_DELAY_FLAG`
		   ,ws.`ENTERING_DELAY_NOTIFIED`		AS `ENTERING_DELAY_NOTIFIED`
		   ,CASE
			  WHEN (ws.`LEAVE_SCHEDULE_TIMET` IS NOT NULL AND ws.`LEAVE_SCHEDULE_TIMET` != '')
				THEN date_format(ws.`LEAVE_SCHEDULE_TIMET`, '%Y%m%d%H%i')
			  ELSE ws.`LEAVE_SCHEDULE_TIMET`
			END									AS `LEAVE_SCHEDULE_TIMET`
		   ,CASE
			  WHEN (ws.`LEAVE_STAFF_TIMET` IS NOT NULL AND ws.`LEAVE_STAFF_TIMET` != '')
				THEN date_format(ws.`LEAVE_STAFF_TIMET`, '%Y%m%d%H%i')
			  ELSE ws.`LEAVE_STAFF_TIMET`
			END									AS `LEAVE_STAFF_TIMET`
		   ,cm3.`CODE_VALUE`					AS `LEAVE_DELAY_TOLERANCE`
		   ,CASE
			  WHEN (ws.`LEAVE_STAFF_TIMET` IS NULL OR ws.`LEAVE_STAFF_TIMET` = '')
			   AND date_format(now(), '%Y%m%d%H%i') >= date_format(ws.`LEAVE_SCHEDULE_TIMET` + INTERVAL cm3.`CODE_VALUE` MINUTE, '%Y%m%d%H%i')
			   AND ws.`LEAVE_DELAY_NOTIFIED` = 'N'
				THEN 'Y'
			  ELSE 'N'
			END									AS `LEAVE_DELAY_FLAG`
		   ,ws.`LEAVE_DELAY_NOTIFIED`			AS `LEAVE_DELAY_NOTIFIED`
		   ,wc.`WORK_STATUS`					AS `WORK_STATUS`
		   ,ws.`VALIDITY_FLAG`					AS `VALIDITY_FLAG_STAFF`
		   ,wc.`VALIDITY_FLAG`					AS `VALIDITY_FLAG_CONTENT`
		   ,usr.`ALERT_PERMISSION_FLAG`			AS `ALERT_PERMISSION_FLAG`
		   ,ws.`REGISTRATION_DATET`				AS `REGISTRATION_DATET`
		   ,ws.`REGISTRATION_USER_ID`			AS `REGISTRATION_USER_ID`
		   ,ws.`LAST_UPDATE_DATET`				AS `LAST_UPDATE_DATET`
		   ,ws.`LAST_UPDATE_USER_ID`			AS `LAST_UPDATE_USER_ID`
	FROM	
		 (((`WORK_STAFF`			ws	LEFT JOIN `BASES`		  ba  ON ws.`WORK_BASE_ID`	  = ba.`BASE_ID`)
										LEFT JOIN `WORK_CONTENTS` wc  ON ws.`WORK_CONTENT_ID` = wc.`WORK_CONTENT_ID`)
										LEFT JOIN `ESTIMATES`	  es  ON wc.`ESTIMATE_ID`	  = es.`ESTIMATE_ID`)
		   ,`WORKSTAFF_SUB1_V`		wscm1
		   ,`WORKSTAFF_SUB2_V`		wscm2
		   ,`WORKSTAFF_SUB3_V`		wscm3
		   ,`WORKSTAFF_SUB5_V`		wsus2
		   ,`USERS`					usr
		   ,`COMMON_MASTER`			cm1
		   ,`COMMON_MASTER`			cm2
		   ,`COMMON_MASTER`			cm3
	WHERE	ws.`WORK_STAFF_ID`	  = wscm1.`WORK_STAFF_ID`
	AND		ws.`WORK_STAFF_ID`	  = wscm2.`WORK_STAFF_ID`
	AND		ws.`WORK_STAFF_ID`	  = wscm3.`WORK_STAFF_ID`
	AND		ws.`WORK_STAFF_ID`	  = wsus2.`WORK_STAFF_ID`
	AND		ws.`WORK_USER_ID`	  = usr.`USER_ID`
	/* 有効な作業 */
	AND		ws.`VALIDITY_FLAG`	  = 'Y'
	AND		wc.`VALIDITY_FLAG`	  = 'Y'
	/* ユーザーが有効 */
	AND		usr.`VALIDITY_FLAG`	  = 'Y'
	/* ユーザーが警告メール発信許可 */
	AND		usr.`ALERT_PERMISSION_FLAG` = 'Y'
	/* 作業中の作業 */
	AND		wc.`WORK_STATUS`	  = 'NW'
	/* 承認区分が承認 */
	AND		ws.`APPROVAL_DIVISION` = 'AP'
	/* キャンセル区分が作業依頼 */
	AND		ws.`CANCEL_DIVISION`  = 'WR'
	/* 共通マスタ結合 */
	AND		ws.`DATA_ID`		  = cm1.`DATA_ID`
	AND		cm1.`CODE_SET`		  = 'DELAY_TOLERANCE'
	AND		cm1.`CODE_NAME`		  = 'DISPATCH'
	AND		ws.`DATA_ID`		  = cm2.`DATA_ID`
	AND		cm2.`CODE_SET`		  = 'DELAY_TOLERANCE'
	AND		cm2.`CODE_NAME`		  = 'ENTERING'
	AND		ws.`DATA_ID`		  = cm3.`DATA_ID`
	AND		cm3.`CODE_SET`		  = 'DELAY_TOLERANCE'
	AND		cm3.`CODE_NAME`		  = 'LEAVE'
	ORDER BY ws.`DATA_ID`
			,wc.`WORK_DATE`
			,ws.`WORK_CONTENT_ID`
			,wsus2.`NAME`
;