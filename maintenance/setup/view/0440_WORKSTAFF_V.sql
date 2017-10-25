/*-----------------------------------------------------------------------------
-- VIEW名           ：WORKSTAFF_V
-- 作成者           ：zenk
-- 作成日           ：2012-02-28
-- 更新履歴         ：
-----------------------------------------------------------------------------*/
CREATE OR REPLACE VIEW `WORKSTAFF_V` AS
	SELECT 
			ws.`DATA_ID`						AS `DATA_ID`
		   ,ws.`WORK_STAFF_ID`					AS `WORK_STAFF_ID`
		   ,es.`ESTIMATE_ID`					AS `ESTIMATE_ID`
		   ,ws.`WORK_CONTENT_ID`				AS `WORK_CONTENT_ID`
		   ,wc.`WORK_STATUS`					AS `WORK_STATUS`
		   ,es.`WORK_NAME`						AS `WORK_NAME`
		   ,wc.`WORK_DATE`						AS `WORK_DATE`
		   ,DATE_FORMAT(wc.`WORK_DATE`, '%c/%d(%a)')
												AS `MAIL_WORK_DATE`
		   ,DATE_FORMAT(wc.`AGGREGATE_TIMET`, '%H:%i')
												AS `AGGREGATE_TIMET`
		   ,wc.`AGGREGATE_POINT`				AS `AGGREGATE_POINT`
		   ,wc.`WORK_ARRANGEMENT_ID`			AS `WORK_ARRANGEMENT_ID`
		   ,wsus1.`NAME`						AS `WORK_ARRANGEMENT_NAME`
		   ,wsus1.`MOBILE_PHONE`				AS `WORK_ARRANGEMENT_MOBILE_PHONE`
		   ,ws.`WORK_BASE_ID`					AS `WORK_BASE_ID`
		   ,co1.`COMPANY_ID`					AS `COMPANY_ID`
		   ,co1.`COMPANY_CODE`					AS `COMPANY_CODE`
		   ,co1.`COMPANY_NAME`					AS `COMPANY_NAME`
		   ,co1.`COMP_CLASS`					AS `COMP_CLASS`
		   ,ba.`BASE_CODE`						AS `WORK_BASE_CODE`
		   ,ba.`BASE_NAME`						AS `WORK_BASE_NAME`
		   ,ba.`ADDRESS`						AS `WORK_ADDRESS`
		   ,ba.`CLOSEST_STATION`				AS `WORK_CLOSEST_STATION`
		   ,wc.`WORK_CONTENT_DETAILS`			AS `WORK_CONTENT_DETAILS`
		   ,wc.`BRINGING_GOODS`					AS `BRINGING_GOODS`
		   ,wc.`CLOTHES`						AS `CLOTHES`
		   ,wc.`INTRODUCE`						AS `INTRODUCE`
		   ,wc.`TRANSPORT_AMOUNT_REMARKS`		AS `TRANSPORT_AMOUNT_REMARKS`
		   ,wc.`OTHER_REMARKS`					AS `OTHER_REMARKS`
		   ,ws.`WORK_USER_ID`					AS `WORK_USER_ID`
		   ,usco.`COMPANY_ID`					AS `WORK_COMPANY_ID`
		   ,usco.`COMPANY_CODE`					AS `WORK_COMPANY_CODE`
		   ,usco.`COMPANY_NAME`					AS `WORK_COMPANY_NAME`
		   ,usco.`COMP_CLASS`					AS `WORK_COMP_CLASS`
		   ,us.`GROUP_ID`						AS `WORK_GROUP_ID`
		   ,usgr.`GROUP_CODE`					AS `WORK_GROUP_CODE`
		   ,usgr.`GROUP_NAME`					AS `WORK_GROUP_NAME`
		   ,usgr.`CLASSIFICATION_DIVISION`		AS `WORK_CLASSIFICATION_DIVISION`
		   ,grcm1.`CODE_VALUE`					AS `WORK_CLASSIFICATION_DIVISION_NAME`
		   ,us.`USER_CODE`						AS `WORK_USER_CODE`
		   ,us.`NAME`							AS `WORK_USER_NAME`
		   ,us.`KANA`							AS `WORK_USER_KANA`
		   ,us.`PAYMENT_DIVISION`				AS `WORK_PAYMENT_DIVISION`
		   ,uscm1.`CODE_VALUE`					AS `WORK_PAYMENT_DIVISION_NAME`
		   ,us.`CLOSEST_STATION`				AS `CLOSEST_STATION`
		   ,us.`HOME_PHONE`						AS `WORK_HOME_PHONE`
		   ,us.`HOME_MAIL`						AS `WORK_HOME_MAIL`
		   ,us.`MOBILE_PHONE`					AS `WORK_MOBILE_PHONE`
		   ,us.`MOBILE_PHONE_MAIL`				AS `WORK_MOBILE_PHONE_MAIL`
		   ,ws.`APPROVAL_DIVISION`				AS `APPROVAL_DIVISION`
		   ,wscm1.`CODE_VALUE`					AS `APPROVAL_DIVISION_NAME`
		   ,ws.`TRANSMISSION_FLAG`				AS `TRANSMISSION_FLAG`
		   ,wscm2.`CODE_VALUE`					AS `TRANSMISSION_FLAG_NAME`
		   ,ws.`CANCEL_DIVISION`				AS `CANCEL_DIVISION`
		   ,wscm3.`CODE_VALUE`					AS `CANCEL_DIVISION_NAME`
		   ,ws.`WORK_UNIT_PRICE`				AS `WORK_UNIT_PRICE_ORIG`
		   ,IFNULL(ws.`WORK_UNIT_PRICE`,us.`UNIT_PRICE`) 
												AS `WORK_UNIT_PRICE`
/* 超過単価 */
		   ,ws.`EXCESS_AMOUNT`					AS `EXCESS_AMOUNT`
		   ,IFNULL(ws.`BASIC_TIME`, wc.`DEFAULT_WORKING_TIME`)
												AS `BASIC_TIME`
		   ,IFNULL(IFNULL(ws.`BREAK_TIME`, wc.`DEFAULT_BREAK_TIME`),0)
												AS `BREAK_TIME`
		   ,ws.`DISPATCH_SCHEDULE_TIMET`		AS `DISPATCH_SCHEDULE_TIMET`
		   ,ws.`DISPATCH_STAFF_TIMET`			AS `DISPATCH_STAFF_TIMET`
		   ,ws.`ENTERING_SCHEDULE_TIMET`		AS `ENTERING_SCHEDULE_TIMET`
		   ,ws.`ENTERING_STAFF_TIMET`			AS `ENTERING_STAFF_TIMET`
		   ,ws.`ENTERING_MANAGE_TIMET`			AS `ENTERING_MANAGE_TIMET`
		   ,ws.`LEAVE_SCHEDULE_TIMET`			AS `LEAVE_SCHEDULE_TIMET`
		   ,ws.`LEAVE_STAFF_TIMET`				AS `LEAVE_STAFF_TIMET`
		   ,ws.`LEAVE_MANAGE_TIMET`				AS `LEAVE_MANAGE_TIMET`
		   ,IFNULL(ws.`TRANSPORT_AMOUNT`,0)		AS `TRANSPORT_AMOUNT`
		   ,IFNULL(ws.`OTHER_AMOUNT`,0)			AS `OTHER_AMOUNT`
		   ,ws.`REMARKS`						AS `REMARKS`
/* 残業代 */
		   ,IFNULL((
			  CASE
				WHEN us.`PAYMENT_DIVISION` = "HP"
				  THEN TRUNCATE((ws.`WORK_UNIT_PRICE` * 1.25) + .5 ,0)
				WHEN us.`PAYMENT_DIVISION` = "DP"
				  THEN TRUNCATE(((ws.`WORK_UNIT_PRICE` / ws.`BASIC_TIME`) * 1.25) + .5 ,0)
				ELSE 0
			  END),0)
			* IF(IFNULL(
			hour(timediff(CAST(LEAVE_MANAGE_TIMET as datetime),CAST(ENTERING_MANAGE_TIMET as datetime))) + TRUNCATE((minute(timediff(CAST(LEAVE_MANAGE_TIMET as datetime),CAST(ENTERING_MANAGE_TIMET as datetime))) / 60) + .5, 0)
			- IFNULL(ws.`BREAK_TIME`,wc.`DEFAULT_BREAK_TIME`),0) > 8,
			   IFNULL(
			hour(timediff(CAST(LEAVE_MANAGE_TIMET as datetime),CAST(ENTERING_MANAGE_TIMET as datetime))) + TRUNCATE((minute(timediff(CAST(LEAVE_MANAGE_TIMET as datetime),CAST(ENTERING_MANAGE_TIMET as datetime))) / 60) + .5, 0)
			- IFNULL(ws.`BREAK_TIME`,wc.`DEFAULT_BREAK_TIME`),0) - 8,
			  0)
												AS `OVERTIME_WORK_AMOUNT`
/* 作業費合計 */
		   ,((CASE
				WHEN us.`PAYMENT_DIVISION` = "DP"
				  THEN IFNULL(ws.`WORK_UNIT_PRICE`,us.`UNIT_PRICE`)
				WHEN us.`PAYMENT_DIVISION` = "HP"
				  THEN IF(IFNULL(
			hour(timediff(CAST(LEAVE_MANAGE_TIMET as datetime),CAST(ENTERING_MANAGE_TIMET as datetime))) + TRUNCATE((minute(timediff(CAST(LEAVE_MANAGE_TIMET as datetime),CAST(ENTERING_MANAGE_TIMET as datetime))) / 60) + .5, 0)
			- IFNULL(ws.`BREAK_TIME`,wc.`DEFAULT_BREAK_TIME`),0) > 8
						  ,IFNULL(ws.`WORK_UNIT_PRICE`,us.`UNIT_PRICE`) * 8
						  ,IF(IFNULL(
			hour(timediff(CAST(LEAVE_MANAGE_TIMET as datetime),CAST(ENTERING_MANAGE_TIMET as datetime))) + TRUNCATE((minute(timediff(CAST(LEAVE_MANAGE_TIMET as datetime),CAST(ENTERING_MANAGE_TIMET as datetime))) / 60) + .5, 0)
			- IFNULL(ws.`BREAK_TIME`,wc.`DEFAULT_BREAK_TIME`),0) < 5
							  ,IFNULL(ws.`WORK_UNIT_PRICE`,us.`UNIT_PRICE`) * 5
							  ,IFNULL(ws.`WORK_UNIT_PRICE`,us.`UNIT_PRICE`) * IFNULL(
			hour(timediff(CAST(LEAVE_MANAGE_TIMET as datetime),CAST(ENTERING_MANAGE_TIMET as datetime))) + TRUNCATE((minute(timediff(CAST(LEAVE_MANAGE_TIMET as datetime),CAST(ENTERING_MANAGE_TIMET as datetime))) / 60) + .5, 0)
			- IFNULL(ws.`BREAK_TIME`,wc.`DEFAULT_BREAK_TIME`),0)))
				ELSE 0
			 END)
		  + (IFNULL((
			  CASE
				WHEN us.`PAYMENT_DIVISION` = "HP"
				  THEN TRUNCATE((ws.`WORK_UNIT_PRICE` * 1.25) + .5 ,0)
				WHEN us.`PAYMENT_DIVISION` = "DP"
				  THEN TRUNCATE(((ws.`WORK_UNIT_PRICE` / ws.`BASIC_TIME`) * 1.25) + .5 ,0)
				ELSE 0
			  END),0)
			* IF(IFNULL(
			hour(timediff(CAST(LEAVE_MANAGE_TIMET as datetime),CAST(ENTERING_MANAGE_TIMET as datetime))) + TRUNCATE((minute(timediff(CAST(LEAVE_MANAGE_TIMET as datetime),CAST(ENTERING_MANAGE_TIMET as datetime))) / 60) + .5, 0)
			- IFNULL(ws.`BREAK_TIME`,wc.`DEFAULT_BREAK_TIME`),0) > 8,
			   IFNULL(
			hour(timediff(CAST(LEAVE_MANAGE_TIMET as datetime),CAST(ENTERING_MANAGE_TIMET as datetime))) + TRUNCATE((minute(timediff(CAST(LEAVE_MANAGE_TIMET as datetime),CAST(ENTERING_MANAGE_TIMET as datetime))) / 60) + .5, 0)
			- IFNULL(ws.`BREAK_TIME`,wc.`DEFAULT_BREAK_TIME`),0) - 8,
			  0)))
		  * (CASE
			  WHEN usgr.`CLASSIFICATION_DIVISION` = "CC" THEN 1.05
			  ELSE 1
			END)
												AS `WORK_EXPENSE_AMOUNT_TOTAL`
/* 出金合計 */
		   ,TRUNCATE(((CASE
				WHEN us.`PAYMENT_DIVISION` = "DP"
				  THEN IFNULL(ws.`WORK_UNIT_PRICE`,us.`UNIT_PRICE`)
				WHEN us.`PAYMENT_DIVISION` = "HP"
				  THEN IF(IFNULL(
			hour(timediff(CAST(LEAVE_MANAGE_TIMET as datetime),CAST(ENTERING_MANAGE_TIMET as datetime))) + TRUNCATE((minute(timediff(CAST(LEAVE_MANAGE_TIMET as datetime),CAST(ENTERING_MANAGE_TIMET as datetime))) / 60) + .5, 0)
			- IFNULL(ws.`BREAK_TIME`,wc.`DEFAULT_BREAK_TIME`),0) > 8
						  ,IFNULL(ws.`WORK_UNIT_PRICE`,us.`UNIT_PRICE`) * 8
						  ,IF(IFNULL(
			hour(timediff(CAST(LEAVE_MANAGE_TIMET as datetime),CAST(ENTERING_MANAGE_TIMET as datetime))) + TRUNCATE((minute(timediff(CAST(LEAVE_MANAGE_TIMET as datetime),CAST(ENTERING_MANAGE_TIMET as datetime))) / 60) + .5, 0)
			- IFNULL(ws.`BREAK_TIME`,wc.`DEFAULT_BREAK_TIME`),0) < 5
							  ,IFNULL(ws.`WORK_UNIT_PRICE`,us.`UNIT_PRICE`) * 5
							  ,IFNULL(ws.`WORK_UNIT_PRICE`,us.`UNIT_PRICE`) * IFNULL(
			hour(timediff(CAST(LEAVE_MANAGE_TIMET as datetime),CAST(ENTERING_MANAGE_TIMET as datetime))) + TRUNCATE((minute(timediff(CAST(LEAVE_MANAGE_TIMET as datetime),CAST(ENTERING_MANAGE_TIMET as datetime))) / 60) + .5, 0)
			- IFNULL(ws.`BREAK_TIME`,wc.`DEFAULT_BREAK_TIME`),0)))
				ELSE 0
			 END)
		  + (IFNULL((
			  CASE
				WHEN us.`PAYMENT_DIVISION` = "HP"
				  THEN TRUNCATE((ws.`WORK_UNIT_PRICE` * 1.25) + .5 ,0)
				WHEN us.`PAYMENT_DIVISION` = "DP"
				  THEN TRUNCATE(((ws.`WORK_UNIT_PRICE` / ws.`BASIC_TIME`) * 1.25) + .5 ,0)
				ELSE 0
			  END),0)
			* IF(hour(timediff(CAST(LEAVE_MANAGE_TIMET as datetime),CAST(ENTERING_MANAGE_TIMET as datetime))) + TRUNCATE((minute(timediff(CAST(LEAVE_MANAGE_TIMET as datetime),CAST(ENTERING_MANAGE_TIMET as datetime))) / 60) + .5, 0)
			- IFNULL(ws.`BREAK_TIME`,wc.`DEFAULT_BREAK_TIME`) > 8,
			hour(timediff(CAST(LEAVE_MANAGE_TIMET as datetime),CAST(ENTERING_MANAGE_TIMET as datetime))) + TRUNCATE((minute(timediff(CAST(LEAVE_MANAGE_TIMET as datetime),CAST(ENTERING_MANAGE_TIMET as datetime))) / 60) + .5, 0)
			- IFNULL(ws.`BREAK_TIME`,wc.`DEFAULT_BREAK_TIME`) - 8,
			0)))
		  * (CASE
			  WHEN usgr.`CLASSIFICATION_DIVISION` = "CC" THEN 1.05
			  ELSE 1
			END)
			+ IFNULL(ws.`TRANSPORT_AMOUNT`,0)
			+ IFNULL(ws.`OTHER_AMOUNT`,0) + .99 ,0)
												AS `PAYMENT_AMOUNT_TOTAL`
/* 実作業時間 */
		   ,IFNULL(hour(timediff(CAST(LEAVE_MANAGE_TIMET as datetime),CAST(ENTERING_MANAGE_TIMET as datetime))) + TRUNCATE((minute(timediff(CAST(LEAVE_MANAGE_TIMET as datetime),CAST(ENTERING_MANAGE_TIMET as datetime))) / 60) + .005, 2),0) - IFNULL(ws.`BREAK_TIME`,wc.`DEFAULT_BREAK_TIME`)
												AS `REAL_WORKING_HOURS`
/* 実作業時間(休憩時間減算後) */
		   ,IFNULL(
			hour(timediff(CAST(LEAVE_MANAGE_TIMET as datetime),CAST(ENTERING_MANAGE_TIMET as datetime))) + TRUNCATE((minute(timediff(CAST(LEAVE_MANAGE_TIMET as datetime),CAST(ENTERING_MANAGE_TIMET as datetime))) / 60) + .005, 2)
			- IFNULL(ws.`BREAK_TIME`,wc.`DEFAULT_BREAK_TIME`),0)
												AS `REAL_LABOR_HOURS`
/* 実残業時間 */
		   ,IF(hour(timediff(CAST(LEAVE_MANAGE_TIMET as datetime),CAST(ENTERING_MANAGE_TIMET as datetime))) + TRUNCATE((minute(timediff(CAST(LEAVE_MANAGE_TIMET as datetime),CAST(ENTERING_MANAGE_TIMET as datetime))) / 60) + .005, 2)
			- IFNULL(ws.`BREAK_TIME`,wc.`DEFAULT_BREAK_TIME`) > 8,
			hour(timediff(CAST(LEAVE_MANAGE_TIMET as datetime),CAST(ENTERING_MANAGE_TIMET as datetime))) + TRUNCATE((minute(timediff(CAST(LEAVE_MANAGE_TIMET as datetime),CAST(ENTERING_MANAGE_TIMET as datetime))) / 60) + .005, 2)
			- IFNULL(ws.`BREAK_TIME`,wc.`DEFAULT_BREAK_TIME`) - 8,
			0)
												AS `REAL_OVERTIME_HOURS`
/* 差引支給額 */
		   ,IFNULL(ws.`SUPPLIED_AMOUNT_TOTAL`,0)
												AS `SUPPLIED_AMOUNT_TOTAL`
		   ,ws.`STAFF_STATUS`					AS `STAFF_STATUS`
		   ,wsss1.`CODE_VALUE`					AS `STAFF_STATUS_NAME`
		   ,ws.`DISPATCH_DELAY_NOTIFIED`		AS `DISPATCH_DELAY_NOTIFIED`
		   ,ws.`ENTERING_DELAY_NOTIFIED`		AS `ENTERING_DELAY_NOTIFIED`
		   ,ws.`LEAVE_DELAY_NOTIFIED`			AS `LEAVE_DELAY_NOTIFIED`
		   ,ws.`WORK_UNIT_PRICE_DISPLAY_FLAG`	AS `WORK_UNIT_PRICE_DISPLAY_FLAG`
		   ,wscm4.`CODE_VALUE`					AS `WORK_UNIT_PRICE_DISPLAY_FLAG_NAME`
		   ,ws.`VALIDITY_FLAG`					AS `VALIDITY_FLAG`
		   ,ws.`REGISTRATION_DATET`				AS `REGISTRATION_DATET`
		   ,ws.`REGISTRATION_USER_ID`			AS `REGISTRATION_USER_ID`
		   ,ws.`LAST_UPDATE_DATET`				AS `LAST_UPDATE_DATET`
		   ,ws.`LAST_UPDATE_USER_ID`			AS `LAST_UPDATE_USER_ID`
	FROM	
   (((((((((`WORK_STAFF`			ws	LEFT JOIN `BASES`		   ba	  ON ws.`WORK_BASE_ID`	   = ba.`BASE_ID`)
										LEFT JOIN `COMPANIES`	   co1	  ON ba.`COMPANY_ID`	   = co1.`COMPANY_ID`)
										LEFT JOIN `WORK_CONTENTS`  wc	  ON ws.`WORK_CONTENT_ID`  = wc.`WORK_CONTENT_ID`)
										LEFT JOIN `ESTIMATES`	   es	  ON wc.`ESTIMATE_ID`	   = es.`ESTIMATE_ID`)
										LEFT JOIN `USERS`		   us	  ON ws.`WORK_USER_ID`	   = us.`USER_ID`)
										LEFT JOIN `USERS_SUB1_V`   uscm1  ON ws.`WORK_USER_ID`	   = uscm1.`USER_ID`)
										LEFT JOIN `GROUPS`		   usgr	  ON us.`GROUP_ID`		   = usgr.`GROUP_ID`)
										LEFT JOIN `GROUPS_SUB1_V`  grcm1  ON usgr.`GROUP_ID`	   = grcm1.`GROUP_ID`)
										LEFT JOIN `COMPANIES`	   usco	  ON usgr.`COMPANY_ID`	   = usco.`COMPANY_ID`)
		   ,`WORKSTAFF_SUB1_V`		wscm1
		   ,`WORKSTAFF_SUB2_V`		wscm2
		   ,`WORKSTAFF_SUB3_V`		wscm3
		   ,`WORKSTAFF_SUB4_V`		wsus1
		   ,`WORKSTAFF_SUB6_V`		wsss1
		   ,`WORKSTAFF_SUB7_V`		wscm4
	WHERE	ws.`WORK_STAFF_ID`	  = wscm1.`WORK_STAFF_ID`
	AND		ws.`WORK_STAFF_ID`	  = wscm2.`WORK_STAFF_ID`
	AND		ws.`WORK_STAFF_ID`	  = wscm3.`WORK_STAFF_ID`
	AND		ws.`WORK_STAFF_ID`	  = wsus1.`WORK_STAFF_ID`
	AND		ws.`WORK_STAFF_ID`	  = wsss1.`WORK_STAFF_ID`
	AND		ws.`WORK_STAFF_ID`	  = wscm4.`WORK_STAFF_ID`
;