/*-----------------------------------------------------------------------------
-- TABLE名          ：メールログ
-- 作成者           ：zenk
-- 作成日           ：2009-11-19
-- 更新履歴         ：
  -----------------------------------------------------------------------------*/
CREATE TABLE  `MAIL_LOG` (
    `DATA_ID`                 BIGINT (8)     NOT NULL                  COMMENT 'データID',
    `MAIL_LOG_ID`             BIGINT (8)     NOT NULL  AUTO_INCREMENT  COMMENT 'メールログID',
    `SEND_USER_ID`            BIGINT (8)     NOT NULL                  COMMENT '送信ユーザーID',
    `FROM_ADDRESS`            TEXT                     DEFAULT NULL    COMMENT '送信元メールアドレス',
    `TO_ADDRESS`              TEXT                     DEFAULT NULL    COMMENT '送信先メールアドレス',
    `CC_ADDRESS`              TEXT                     DEFAULT NULL    COMMENT 'CCメールアドレス',
    `BCC_ADDRESS`             TEXT                     DEFAULT NULL    COMMENT 'BCCメールアドレス',
    `MAIL_TITLE`              TEXT                     DEFAULT NULL    COMMENT 'メールタイトル',
    `MAIL_BODY`               TEXT                     DEFAULT NULL    COMMENT 'メール本文',
    `SEND_PURPOSE`            TEXT                     DEFAULT NULL    COMMENT '送信目的',
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
    PRIMARY KEY  (`MAIL_LOG_ID`)
) ENGINE=innoDB DEFAULT CHARSET=utf8 COMMENT='メールログ';
