/*-----------------------------------------------------------------------------
-- TABLE名          ：メッセージテンプレート
-- 作成者           ：zenk
-- 作成日           ：2012-03-30
-- 更新履歴         ：
  -----------------------------------------------------------------------------*/
CREATE TABLE  `MESSAGE_TEMPLATE` (
    `DATA_ID`                 BIGINT (8)     NOT NULL                  COMMENT 'データID',
    `MESSAGE_TEMPLATE_ID`     BIGINT (8)     NOT NULL  AUTO_INCREMENT  COMMENT 'メッセージテンプレートID',
    `MESSAGE_TEMPLATE_NAME`   VARCHAR (150)  NOT NULL                  COMMENT 'メッセージテンプレート名',
    `MESSAGE_CATEGORY`        VARCHAR (150)  NOT NULL                  COMMENT 'メッセージカテゴリ',
    `MESSAGE`                 TEXT                     DEFAULT NULL    COMMENT 'メッセージ',
    `RESERVE_1`               VARCHAR (150)            DEFAULT NULL    COMMENT '予備1',
    `RESERVE_2`               VARCHAR (150)            DEFAULT NULL    COMMENT '予備2',
    `RESERVE_3`               VARCHAR (150)            DEFAULT NULL    COMMENT '予備3',
    `RESERVE_4`               VARCHAR (150)            DEFAULT NULL    COMMENT '予備4',
    `RESERVE_5`               VARCHAR (150)            DEFAULT NULL    COMMENT '予備5',
    `RESERVE_6`               VARCHAR (150)            DEFAULT NULL    COMMENT '予備6',
    `RESERVE_7`               VARCHAR (150)            DEFAULT NULL    COMMENT '予備7',
    `RESERVE_8`               VARCHAR (150)            DEFAULT NULL    COMMENT '予備8',
    `RESERVE_9`               VARCHAR (150)            DEFAULT NULL    COMMENT '予備9',
    `RESERVE_10`              VARCHAR (150)            DEFAULT NULL    COMMENT '予備10',
    `VALIDITY_FLAG`           VARCHAR (1)    NOT NULL  DEFAULT 'Y'     COMMENT '有効フラグ',
    `REGISTRATION_DATET`      DATETIME       NOT NULL                  COMMENT '新規登録日',
    `REGISTRATION_USER_ID`    BIGINT (8)     NOT NULL                  COMMENT '新規登録者ID',
    `LAST_UPDATE_DATET`       DATETIME       NOT NULL                  COMMENT '最終更新日',
    `LAST_UPDATE_USER_ID`     BIGINT (8)     NOT NULL                  COMMENT '最終更新者ID',
    PRIMARY KEY  (`MESSAGE_TEMPLATE_ID`),
    UNIQUE KEY `UI_MESSAGE_TEMPLATE_01` (`DATA_ID`,`MESSAGE_TEMPLATE_NAME`)
) ENGINE=innoDB DEFAULT CHARSET=utf8 COMMENT='メッセージテンプレート';
