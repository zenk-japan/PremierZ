/*-----------------------------------------------------------------------------
-- TABLE名          ：作業報告
-- 作成者           ：zenk
-- 作成日           ：2009-03-04
-- 更新履歴         ：
  -----------------------------------------------------------------------------*/
CREATE TABLE  `WORK_REPORTS` (
    `DATA_ID`               BIGINT(8)     NOT NULL                     COMMENT 'データID',
    `REPORT_ID`             BIGINT(8)     NOT NULL     AUTO_INCREMENT  COMMENT '報告ID',
    `WORK_STAFF_ID`         BIGINT(8)     NOT NULL                     COMMENT '作業人員ID',
    `REPORT_DIVISION`       VARCHAR(2)    NOT NULL                     COMMENT '報告区分',
    `REPORT_TIME`           DATETIME      NOT NULL                     COMMENT '報告時間',
    `RESERVE_1`             VARCHAR(150)  DEFAULT NULL                 COMMENT '予備1',
    `RESERVE_2`             VARCHAR(150)  DEFAULT NULL                 COMMENT '予備2',
    `RESERVE_3`             VARCHAR(150)  DEFAULT NULL                 COMMENT '予備3',
    `RESERVE_4`             VARCHAR(150)  DEFAULT NULL                 COMMENT '予備4',
    `RESERVE_5`             VARCHAR(150)  DEFAULT NULL                 COMMENT '予備5',
    `RESERVE_6`             VARCHAR(150)  DEFAULT NULL                 COMMENT '予備6',
    `RESERVE_7`             VARCHAR(150)  DEFAULT NULL                 COMMENT '予備7',
    `RESERVE_8`             VARCHAR(150)  DEFAULT NULL                 COMMENT '予備8',
    `RESERVE_9`             VARCHAR(150)  DEFAULT NULL                 COMMENT '予備9',
    `RESERVE_10`            VARCHAR(150)  DEFAULT NULL                 COMMENT '予備10',
    `VALIDITY_FLAG`         VARCHAR(1)    NOT NULL     DEFAULT 'Y'     COMMENT '有効フラグ',
    `REGISTRATION_DATET`    DATETIME      NOT NULL                     COMMENT '新規登録日',
    `REGISTRATION_USER_ID`  BIGINT(8)     NOT NULL                     COMMENT '新規登録者ID',
    `LAST_UPDATE_DATET`     DATETIME      NOT NULL                     COMMENT '最終更新日',
    `LAST_UPDATE_USER_ID`   BIGINT(8)     NOT NULL                     COMMENT '最終更新者ID',
    PRIMARY KEY  (`REPORT_ID`),
    KEY `UI_WORK_REPORTS_01` (`DATA_ID`,`WORK_STAFF_ID`,`REPORT_DIVISION`)
) ENGINE=innoDB DEFAULT CHARSET=utf8 COMMENT='作業報告';
