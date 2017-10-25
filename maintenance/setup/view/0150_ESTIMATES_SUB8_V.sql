/*-----------------------------------------------------------------------------
-- VIEW名           ：ESTIMATES_SUB8_V
-- 作成者           ：zenk
-- 作成日           ：2012-02-28
-- 更新履歴         ：
-----------------------------------------------------------------------------*/
CREATE OR REPLACE VIEW `ESTIMATES_SUB8_V` AS
	SELECT
		  es1.`DATA_ID`							AS `DATA_ID`
		 ,CASE
			WHEN LEFT(MAX(es1.`ESTIMATE_CODE`),4 ) = DATE_FORMAT(DATE_SUB(now(), interval 1 month),'%y%m')
			  THEN CONCAT(DATE_FORMAT(NOW(), '%y%m') ,'-001')
			WHEN LEFT(MAX(es1.`ESTIMATE_CODE`),4 ) = DATE_FORMAT(NOW(), '%y%m')
			  THEN CONCAT(DATE_FORMAT(NOW(), '%y%m') ,'-', LPAD(RIGHT(MAX(es1.`ESTIMATE_CODE`) ,3 ) +1 ,3 ,0 ))
			WHEN LEFT(MAX(es1.`ESTIMATE_CODE`),4 ) = DATE_FORMAT(DATE_ADD(now(), interval 1 month),'%y%m')
			  THEN IFNULL(CONCAT(DATE_FORMAT(NOW(), '%y%m') ,'-', 
				  (SELECT LPAD(RIGHT(MAX(es2.`ESTIMATE_CODE`) ,3 ) +1 ,3 ,0 )
				   FROM	  `ESTIMATES` es2
				   WHERE  LEFT(es2.`ESTIMATE_CODE`,4 ) = DATE_FORMAT(NOW(), '%y%m')))
				   ,CONCAT(DATE_FORMAT(NOW(), '%y%m') ,'-001')
				   )
			ELSE
			  CONCAT(DATE_FORMAT(NOW(), '%y%m') ,'-001')
		  END									AS `W_ESTIMATE_CODE`
	FROM `ESTIMATES` es1
	GROUP BY es1.`DATA_ID`
;