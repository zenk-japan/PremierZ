/*-----------------------------------------------------------------------------
-- VIEW名			：USERS_V
-- 作成者			：zenk
-- 作成日			：2012-02-28
-- 更新履歴			：
-----------------------------------------------------------------------------*/
CREATE OR REPLACE VIEW `USERS_V` AS
	SELECT
			us.`DATA_ID`						 AS `DATA_ID`
		   ,ucv.`USE_COMPANY_CODE`				 AS `USE_COMPANY_CODE`
		   ,ucv.`USE_COMPANY_NAME`				 AS `USE_COMPANY_NAME`
		   ,us.`COMPANY_ID`						 AS `COMPANY_ID`
		   ,cp.`COMPANY_CODE`					 AS `COMPANY_CODE`
		   ,cp.`COMPANY_NAME`					 AS `COMPANY_NAME`
		   ,cp.`COMP_CLASS`						 AS `COMP_CLASS`
		   ,cp.`COMP_CLASS_NAME`				 AS `COMP_CLASS_NAME`
		   ,us.`GROUP_ID`						 AS `GROUP_ID`
		   ,gp.`GROUP_CODE`						 AS `GROUP_CODE`
		   ,gp.`GROUP_NAME`						 AS `GROUP_NAME`
		   ,gp.`CLASSIFICATION_DIVISION`		 AS `CLASSIFICATION_DIVISION`
		   ,gpcm1.`CODE_VALUE`					 AS `CLASSIFICATION_DIVISION_NAME`
		   ,us.`USER_ID`						 AS `USER_ID`
		   ,us.`USER_CODE`						 AS `USER_CODE`
		   ,us.`PASSWORD`						 AS `PASSWORD`
		   ,us.`ENCRYPTION_PASSWORD`			 AS `ENCRYPTION_PASSWORD`
		   ,us.`AUTHORITY_ID`					 AS `AUTHORITY_ID`
		   ,au.`AUTHORITY_CODE`					 AS `AUTHORITY_CODE`
		   ,au.`AUTHORITY_NAME`					 AS `AUTHORITY_NAME`
		   ,au.`TERMINAL_DIVISION`				 AS `TERMINAL_DIVISION`
		   ,au.`SCREEN_NAME`					 AS `SCREEN_NAME`
		   ,au.`ADMITTED_OPERATION_FLAG`		 AS `ADMITTED_OPERATION_FLAG`
		   ,us.`NAME`							 AS `NAME`
		   ,us.`KANA`							 AS `KANA`
		   ,us.`PAYMENT_DIVISION`				 AS `PAYMENT_DIVISION`
		   ,uscm1.`CODE_VALUE`					 AS `PAYMENT_DIVISION_NAME`
		   ,us.`SEX`							 AS `SEX`
		   ,uscm2.`CODE_VALUE`					 AS `SEX_NAME`
		   ,DATE_FORMAT(us.`BIRTHDATE`, '%Y/%m/%d')
												 AS `BIRTHDATE`
		   ,CASE
				WHEN date_format(BIRTHDATE, '%m%d') <= date_format(now(), '%m%d')
					THEN date_format(now(), '%Y') - date_format(BIRTHDATE, '%Y')
				ELSE date_format(now(), '%Y') - date_format(BIRTHDATE, '%Y') - 1
			END									 AS `AGE`
		   ,us.`ZIP_CODE`						 AS `ZIP_CODE`
		   ,us.`ADDRESS`						 AS `ADDRESS`
		   ,us.`CLOSEST_STATION`				 AS `CLOSEST_STATION`
		   ,us.`HOME_PHONE`						 AS `HOME_PHONE`
		   ,us.`HOME_MAIL`						 AS `HOME_MAIL`
		   ,us.`MOBILE_PHONE`					 AS `MOBILE_PHONE`
		   ,us.`MOBILE_PHONE_MAIL`				 AS `MOBILE_PHONE_MAIL`
		   ,us.`IDENTIFICATION_ID`				 AS `IDENTIFICATION_ID`
		   ,us.`IDENTIFICATION_FLAG`			 AS `IDENTIFICATION_FLAG`
		   ,us.`ALERT_PERMISSION_FLAG`			 AS `ALERT_PERMISSION_FLAG`
		   ,uscm3.`CODE_VALUE`					 AS `ALERT_PERMISSION_FLAG_NAME`
		   ,us.`UNIT_PRICE`						 AS `UNIT_PRICE`
		   ,us.`BANK_NAME`						 AS `BANK_NAME`
		   ,us.`BRANCH_NAME`					 AS `BRANCH_NAME`
		   ,us.`ACCOUNT_NUMBER`					 AS `ACCOUNT_NUMBER`
		   ,us.`REMARKS`						 AS `REMARKS`
		   ,us.`RESERVE_1`						 AS `RESERVE_1`
		   ,us.`VALIDITY_FLAG`					 AS `VALIDITY_FLAG`
		   ,us.`REGISTRATION_DATET`				 AS `REGISTRATION_DATET`
		   ,us.`REGISTRATION_USER_ID`			 AS `REGISTRATION_USER_ID`
		   ,us.`LAST_UPDATE_DATET`				 AS `LAST_UPDATE_DATET`
		   ,us.`LAST_UPDATE_USER_ID`			 AS `LAST_UPDATE_USER_ID`
	FROM
		((((`USERS`			   us LEFT JOIN `GROUPS`		gp	  ON us.`GROUP_ID`	   = gp.`GROUP_ID`)
								  LEFT JOIN `COMPANIES_V`	cp	  ON us.`COMPANY_ID`   = cp.`COMPANY_ID`)
								  LEFT JOIN `AUTHORITY`		au	  ON us.`AUTHORITY_ID` = au.`AUTHORITY_ID`)
								  LEFT JOIN `GROUPS_SUB1_V` gpcm1 ON us.`GROUP_ID`	   = gpcm1.`GROUP_ID`)
		   ,`USERS_SUB1_V`	   uscm1
		   ,`USERS_SUB2_V`	   uscm2
		   ,`USERS_SUB3_V`	   uscm3
		   ,`USE_COMPANY_V`	   ucv
	WHERE	us.`USER_ID`	  = uscm1.`USER_ID`
	AND		us.`USER_ID`	  = uscm2.`USER_ID`
	AND		us.`USER_ID`	  = uscm3.`USER_ID`
	AND		us.`DATA_ID`	  = ucv.`DATA_ID`
;