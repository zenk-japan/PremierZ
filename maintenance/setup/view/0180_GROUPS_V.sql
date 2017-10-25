/*-----------------------------------------------------------------------------
-- VIEW名           ：GROUPS_V
-- 作成者           ：zenk
-- 作成日           ：2012-02-28
-- 更新履歴         ：
-----------------------------------------------------------------------------*/
CREATE OR REPLACE VIEW `GROUPS_V` AS
	SELECT
			gp.`DATA_ID`				  AS `DATA_ID`
		   ,gp.`COMPANY_ID`				  AS `COMPANY_ID`
		   ,cp.`COMPANY_CODE`			  AS `COMPANY_CODE`
		   ,cp.`COMPANY_NAME`			  AS `COMPANY_NAME`
		   ,gp.`GROUP_ID`				  AS `GROUP_ID`
		   ,gp.`GROUP_CODE`				  AS `GROUP_CODE`
		   ,gp.`GROUP_NAME`				  AS `GROUP_NAME`
		   ,gp.`REMARKS`				  AS `REMARKS`
		   ,gp.`CLASSIFICATION_DIVISION`  AS `CLASSIFICATION_DIVISION`
		   ,gpcm.`CODE_VALUE`			  AS `CLASSIFICATION_DIVISION_NAME`
		   ,gp.`VALIDITY_FLAG`			  AS `VALIDITY_FLAG`
		   ,gp.`REGISTRATION_DATET`		  AS `REGISTRATION_DATET`
		   ,gp.`REGISTRATION_USER_ID`	  AS `REGISTRATION_USER_ID`
		   ,gp.`LAST_UPDATE_DATET`		  AS `LAST_UPDATE_DATET`
		   ,gp.`LAST_UPDATE_USER_ID`	  AS `LAST_UPDATE_USER_ID`
	FROM	
		  (`GROUPS` gp LEFT JOIN `COMPANIES` cp	 ON gp.`COMPANY_ID` = cp.`COMPANY_ID`)
		  ,`GROUPS_SUB1_V`	  gpcm
	WHERE	gp.`GROUP_ID`	= gpcm.`GROUP_ID`
;