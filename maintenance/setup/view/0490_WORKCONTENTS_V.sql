/*-----------------------------------------------------------------------------
-- VIEW名			：WORKCONTENTS_V
-- 作成者			：zenk
-- 作成日			：2012-02-28
-- 更新履歴			：
-----------------------------------------------------------------------------*/
CREATE OR REPLACE VIEW `WORKCONTENTS_V`						AS 
select `wc`.`DATA_ID`										AS `DATA_ID`,
	   `wc`.`WORK_CONTENT_ID`								AS `WORK_CONTENT_ID`,
	   `wc`.`WORK_CONTENT_CODE`								AS `WORK_CONTENT_CODE`,
	   `wc`.`ESTIMATE_ID`									AS `ESTIMATE_ID`,
	   `es`.`ESTIMATE_CODE`									AS `ESTIMATE_CODE`,
	   `es`.`SUB_NUMBER`									AS `SUB_NUMBER`,
	   `es`.`SCHEDULE_FROM_DATE`							AS `SCHEDULE_FROM_DATE`,
	   `es`.`SCHEDULE_TO_DATE`								AS `SCHEDULE_TO_DATE`,
	   `es`.`WORK_NAME`										AS `WORK_NAME`,
	   `es`.`ENDUSER_COMPANY_ID`							AS `ENDUSER_COMPANY_ID`,
	   `esco1`.`COMPANY_CODE`								AS `ENDUSER_COMPANY_CODE`,
	   `esco1`.`COMPANY_NAME`								AS `ENDUSER_COMPANY_NAME`,
	   `es`.`REQUEST_COMPANY_ID`							AS `REQUEST_COMPANY_ID`,
	   `esco2`.`COMPANY_CODE`								AS `REQUEST_COMPANY_CODE`,
	   `esco2`.`COMPANY_NAME`								AS `REQUEST_COMPANY_NAME`,
	   `wc`.`WORK_DATE`										AS `WORK_DATE`,
	   convert(date_format(`wc`.`DEFAULT_ENTERING_SCHEDULE_TIMET`,_sjis'%H:%i') using utf8)		AS `DEFAULT_ENTERING_SCHEDULE_TIMET`,
	   convert((CASE
	   WHEN datediff(date_format(wc.`DEFAULT_LEAVE_SCHEDULE_TIMET`,_sjis'%Y-%m-%d'),WORK_DATE) = 1
	   THEN concat(date_format(wc.`DEFAULT_LEAVE_SCHEDULE_TIMET`,_sjis'%H')+24,':',date_format(wc.`DEFAULT_LEAVE_SCHEDULE_TIMET`,_sjis'%i'))
	   ELSE date_format(`wc`.`DEFAULT_LEAVE_SCHEDULE_TIMET`,_sjis'%H:%i')
	   END)	 using utf8)									AS `DEFAULT_LEAVE_SCHEDULE_TIMET`,
	   `wc`.`DEFAULT_WORKING_TIME`							AS `DEFAULT_WORKING_TIME`,
	   `wc`.`DEFAULT_BREAK_TIME`							AS `DEFAULT_BREAK_TIME`,
	   convert(date_format(`wc`.`AGGREGATE_TIMET`,_sjis'%H:%i')	 using utf8)	 AS `AGGREGATE_TIMET`,
	   `wc`.`AGGREGATE_POINT`								AS `AGGREGATE_POINT`,
	   `cs`.`COMPANY_NAME`									AS `WORK_ARRANGEMENT_COMPANY_NAME`,
	   `wc`.`WORK_ARRANGEMENT_ID`							AS `WORK_ARRANGEMENT_ID`,
	   `us`.`USER_CODE`										AS `WORK_ARRANGEMENT_USER_CODE`,
	   `us`.`NAME`											AS `WORK_ARRANGEMENT_USER_NAME`,
	   `us`.`MOBILE_PHONE`									AS `WORK_ARRANGEMENT_MOBILE_PHONE`,
	   `wc`.`WORK_CONTENT_DETAILS`							AS `WORK_CONTENT_DETAILS`,
	   `wc`.`BRINGING_GOODS`								AS `BRINGING_GOODS`,
	   `wc`.`CLOTHES`										AS `CLOTHES`,
	   `wc`.`INTRODUCE`										AS `INTRODUCE`,
	   `wc`.`TRANSPORT_AMOUNT_REMARKS`						AS `TRANSPORT_AMOUNT_REMARKS`,
	   `wc`.`OTHER_REMARKS`									AS `OTHER_REMARKS`,
	   `wc`.`OTHER_COST`									AS `OTHER_COST`,
	   `wc`.`EXCESS_AMOUNT`									AS `EXCESS_AMOUNT`,
	   `wc`.`EXCESS_LIQUIDATION_FLAG`						AS `EXCESS_LIQUIDATION_FLAG`,
	   `wccm1`.`CODE_VALUE`									AS `EXCESS_LIQUIDATION_FLAG_NAME`,
	   `wc`.`CANCEL_CHARGE`									AS `CANCEL_CHARGE`,
	   `wc`.`TOTAL_SALES`									AS `TOTAL_SALES`,
	   `wc`.`GROSS_MARGIN`									AS `GROSS_MARGIN`,
	   `wc`.`GROSS_MARGIN_RATE`								AS `GROSS_MARGIN_RATE`,
	   `wc`.`WORK_STATUS`									AS `WORK_STATUS`,
	   `wcwk1`.`CODE_VALUE`									AS `WORK_STATUS_NAME`,
	   `wcws1`.`BASIC_TIME_SUM`								AS `BASIC_TIME_SUM`,
	   `wcws1`.`BREAK_TIME_SUM`								AS `BREAK_TIME_SUM`,
	   `wcws1`.`TRANSPORT_AMOUNT_SUM`						AS `TRANSPORT_AMOUNT_SUM`,
	   `wcws1`.`OTHER_AMOUNT_SUM`							AS `OTHER_AMOUNT_SUM`,
	   `wcws1`.`OVERTIME_WORK_AMOUNT_SUM`					AS `OVERTIME_WORK_AMOUNT_SUM`,
	   `wcws1`.`WORK_EXPENSE_AMOUNT_TOTAL_SUM`				AS `WORK_EXPENSE_AMOUNT_TOTAL_SUM`,
	   `wcws1`.`PAYMENT_AMOUNT_TOTAL_SUM`					AS `PAYMENT_AMOUNT_TOTAL_SUM`,
	   `wcws1`.`REAL_WORKING_HOURS_SUM`						AS `REAL_WORKING_HOURS_SUM`,
	   `wcws1`.`REAL_OVERTIME_HOURS_SUM`					AS `REAL_OVERTIME_HOURS_SUM`,
	   `wcws1`.`SUPPLIED_AMOUNT_TOTAL_SUM`					AS `SUPPLIED_AMOUNT_TOTAL_SUM`,
	   `wc`.`VALIDITY_FLAG`									AS `VALIDITY_FLAG`,
	   `wc`.`REGISTRATION_DATET`							AS `REGISTRATION_DATET`,
	   `wc`.`REGISTRATION_USER_ID`							AS `REGISTRATION_USER_ID`,
	   `wc`.`LAST_UPDATE_DATET`								AS `LAST_UPDATE_DATET`,
	   `wc`.`LAST_UPDATE_USER_ID`							AS `LAST_UPDATE_USER_ID` 
from (((((((((`WORK_CONTENTS` `wc` left join `ESTIMATES` `es` on((`wc`.`ESTIMATE_ID` = `es`.`ESTIMATE_ID`))) 
								   left join `USERS` `us` on((`wc`.`WORK_ARRANGEMENT_ID` = `us`.`USER_ID`))) 
								   left join `WORKCONTENTS_SUB2_V` `wcws1` on((`wc`.`WORK_CONTENT_ID` = `wcws1`.`WORK_CONTENT_ID`))) 
								   left join `GROUPS` `gp` on((`us`.`GROUP_ID` = `gp`.`GROUP_ID`))) 
								   left join `COMPANIES` `cs` on((`gp`.`COMPANY_ID` = `cs`.`COMPANY_ID`))) 
										join `WORKCONTENTS_SUB1_V` `wccm1`) join `WORKCONTENTS_SUB3_V` `wcwk1`) 
										join `ESTIMATES_SUB3_V` `esco1`) join `ESTIMATES_SUB4_V` `esco2`) 
where ((`wc`.`ESTIMATE_ID` = `es`.`ESTIMATE_ID`) 
and	   (`wc`.`WORK_CONTENT_ID` = `wccm1`.`WORK_CONTENT_ID`) 
and	   (`wc`.`WORK_CONTENT_ID` = `wcwk1`.`WORK_CONTENT_ID`) 
and	   (`wc`.`ESTIMATE_ID` = `esco1`.`ESTIMATE_ID`) 
and	   (`wc`.`ESTIMATE_ID` = `esco2`.`ESTIMATE_ID`))
;