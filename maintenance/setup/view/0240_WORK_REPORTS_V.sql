/*-----------------------------------------------------------------------------
-- VIEW名           ：WORK_REPORTS_V
-- 作成者           ：zenk
-- 作成日           ：2012-02-28
-- 更新履歴         ：
-----------------------------------------------------------------------------*/
CREATE OR REPLACE VIEW `WORK_REPORTS_V` AS
SELECT
			wr.`DATA_ID`
		   ,wr.`REPORT_ID`
		   ,wr.`WORK_STAFF_ID`
		   ,wr.`REPORT_DIVISION`
		   ,wr.`REPORT_TIME`
		   ,wr.`VALIDITY_FLAG`
		   ,wr.`REGISTRATION_DATET`
		   ,wr.`REGISTRATION_USER_ID`
		   ,wr.`LAST_UPDATE_DATET`
		   ,wr.`LAST_UPDATE_USER_ID`
	FROM	`WORK_REPORTS` wr
;