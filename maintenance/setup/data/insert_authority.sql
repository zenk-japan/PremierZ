INSERT INTO `AUTHORITY` 
VALUES
 (%%data_id%%,NULL,'ADMIN','管理者','PM','PAGE','W','PC・Mobileユーザ',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Y',now(),-1,now(),-1)
,(%%data_id%%,NULL,'GENERAL4','社員（SALES）','PM','PAGE','W','PC・Mobileユーザ',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Y',now(),-1,now(),-1)
,(%%data_id%%,NULL,'GENERAL1','社員（MANAGEMENT）','PM','PAGE','W','PC・Mobileユーザ',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Y',now(),-1,now(),-1)
,(%%data_id%%,NULL,'GENERAL2','社員（FEG）','PM','PAGE','W','PC・Mobileユーザ',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Y',now(),-1,now(),-1)
,(%%data_id%%,NULL,'GENERAL3','社員（OTHERS）','PM','PAGE','W','PC・Mobileユーザ',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Y',now(),-1,now(),-1)
,(%%data_id%%,NULL,'GENERAL','一般','PM','PAGE','W','PC・Mobileユーザ',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Y',now(),-1,now(),-1)
,(%%data_id%%,NULL,'MO_GUEST','Mobileユーザ','MO','PAGE','W','Mobileユーザ',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Y',now(),-1,now(),-1)
,(%%data_id%%,NULL,'PC_GUEST','PCユーザ','PC','PAGE','W','PCユーザ',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Y',now(),-1,now(),-1)
,(%%data_id%%,NULL,'ISOLATED','権限無','IM','PAGE','D','閲覧不可ユーザ',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Y',now(),-1,now(),-1)
;
