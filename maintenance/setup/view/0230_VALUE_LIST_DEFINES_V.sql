/*-----------------------------------------------------------------------------
-- VIEW名           ：VALUE_LIST_DEFINES_V
-- 作成者           ：zenk
-- 作成日           ：2012-02-28
-- 更新履歴         ：
-----------------------------------------------------------------------------*/
CREATE OR REPLACE VIEW `VALUE_LIST_DEFINES_V` AS
	SELECT
			vld.`DATA_ID`				AS `DATA_ID`
		   ,vld.`DEFINE_ID`				AS `DEFINE_ID`
		   ,vld.`DEFINE_CODE`			AS `DEFINE_CODE`
		   ,vld.`USE_PAGE`				AS `USE_PAGE`
		   ,vld.`USE_ITEM`				AS `USE_ITEM`
		   ,vld.`SELECT_PHRASE`			AS `SELECT_PHRASE`
		   ,vld.`OPTION_WHERE_1`		AS `OPTION_WHERE_1` 
		   ,vld.`OPTION_WHERE_2`		AS `OPTION_WHERE_2` 
		   ,vld.`OPTION_WHERE_3`		AS `OPTION_WHERE_3` 
		   ,vld.`OPTION_WHERE_4`		AS `OPTION_WHERE_4` 
		   ,vld.`OPTION_WHERE_5`		AS `OPTION_WHERE_5` 
		   ,vld.`OPTION_WHERE_6`		AS `OPTION_WHERE_6` 
		   ,vld.`OPTION_WHERE_7`		AS `OPTION_WHERE_7` 
		   ,vld.`OPTION_WHERE_8`		AS `OPTION_WHERE_8` 
		   ,vld.`OPTION_WHERE_9`		AS `OPTION_WHERE_9` 
		   ,vld.`OPTION_WHERE_10`		AS `OPTION_WHERE_10`
		   ,vld.`GROUP_BY_PHRASE`		AS `GROUP_BY_PHRASE`
		   ,vld.`ORDER_BY_PHRASE`		AS `ORDER_BY_PHRASE`
		   ,vld.`VALUE_DEST_ITEM_ID`	AS `VALUE_DEST_ITEM_ID`
		   ,vld.`ID_DEST_ITEM_ID`		AS `ID_DEST_ITEM_ID`
		   ,vld.`VALIDITY_FLAG`			AS `VALIDITY_FLAG`
		   ,vld.`REGISTRATION_DATET`	AS `REGISTRATION_DATET`
		   ,vld.`REGISTRATION_USER_ID`	AS `REGISTRATION_USER_ID`
		   ,vld.`LAST_UPDATE_DATET`		AS `LAST_UPDATE_DATET`
		   ,vld.`LAST_UPDATE_USER_ID`	AS `LAST_UPDATE_USER_ID`
	FROM
			`VALUE_LIST_DEFINES`	vld
;