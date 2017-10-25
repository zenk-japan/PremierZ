/*-----------------------------------------------------------------------------
-- VIEW名           ：MAIL_LOG_V
-- 作成者           ：zenk
-- 作成日           ：2012-02-28
-- 更新履歴         ：
-----------------------------------------------------------------------------*/
CREATE OR REPLACE VIEW `MAIL_LOG_V` AS
	SELECT
			ml.`DATA_ID`						AS `DATA_ID`
		   ,ml.`MAIL_LOG_ID`					AS `MAIL_LOG_ID`
		   ,ml.`SEND_USER_ID`					AS `SEND_USER_ID`
		   ,coalesce(usr.`NAME`, "(システム)")	AS `SEND_USER_NAME`
		   ,grp.`GROUP_NAME`					AS `SEND_USER_GROUP`
		   ,cmp.`COMPANY_NAME`					AS `SEND_USER_COMPANY`
		   ,ml.`FROM_ADDRESS`					AS `FROM_ADDRESS`
		   ,ml.`TO_ADDRESS`						AS `TO_ADDRESS`
		   ,ml.`CC_ADDRESS`						AS `CC_ADDRESS`
		   ,ml.`BCC_ADDRESS`					AS `BCC_ADDRESS` 
		   ,ml.`MAIL_TITLE`						AS `MAIL_TITLE` 
		   ,ml.`MAIL_BODY`						AS `MAIL_BODY` 
		   ,ml.`SEND_PURPOSE`					AS `SEND_PURPOSE` 
		   ,ml.`VALIDITY_FLAG`					AS `VALIDITY_FLAG`
		   ,ml.`REGISTRATION_DATET`				AS `REGISTRATION_DATET`
		   ,ml.`REGISTRATION_USER_ID`			AS `REGISTRATION_USER_ID`
		   ,ml.`LAST_UPDATE_DATET`				AS `LAST_UPDATE_DATET`
		   ,ml.`LAST_UPDATE_USER_ID`			AS `LAST_UPDATE_USER_ID`
	FROM
		 (((`MAIL_LOG` ml LEFT JOIN `USERS`		usr	  ON ml.`SEND_USER_ID` = usr.`USER_ID`)
						  LEFT JOIN `GROUPS`	grp	  ON usr.`GROUP_ID`	   = grp.`GROUP_ID`)
						  LEFT JOIN `COMPANIES` cmp	  ON grp.`COMPANY_ID`  = cmp.`COMPANY_ID`)
;