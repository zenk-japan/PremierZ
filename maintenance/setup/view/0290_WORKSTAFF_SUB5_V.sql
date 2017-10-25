/*-----------------------------------------------------------------------------
-- VIEW名           ：WORKSTAFF_SUB5_V
-- 作成者           ：zenk
-- 作成日           ：2012-02-28
-- 更新履歴         ：
-----------------------------------------------------------------------------*/
CREATE OR REPLACE VIEW `WORKSTAFF_SUB5_V` AS
	SELECT 
			 ws1.`DATA_ID`
			,ws1.`WORK_STAFF_ID`
			,co1.`COMPANY_ID`
			,co1.`COMPANY_CODE`
			,co1.`COMPANY_NAME`
			,gr1.`GROUP_ID`
			,gr1.`GROUP_CODE`
			,gr1.`GROUP_NAME`
			,gr1.`CLASSIFICATION_DIVISION`
			,ws1.`WORK_USER_ID`
			,us1.`USER_CODE`
			,us1.`NAME`
			,us1.`KANA`
			,us1.`PAYMENT_DIVISION`
			,us1.`CLOSEST_STATION`
			,us1.`HOME_PHONE`
			,us1.`HOME_MAIL`
			,us1.`MOBILE_PHONE`
			,us1.`MOBILE_PHONE_MAIL`
			,us1.`UNIT_PRICE`
			,ws1.`VALIDITY_FLAG`
	FROM  (((`WORK_STAFF` ws1 LEFT JOIN `USERS`			us1 ON ws1.`WORK_USER_ID`			 = us1.`USER_ID`)
									   LEFT JOIN `GROUPS`		 gr1 ON us1.`GROUP_ID`				  = gr1.`GROUP_ID`)
									   LEFT JOIN `COMPANIES`	 co1 ON gr1.`COMPANY_ID`			  = co1.`COMPANY_ID`)
;