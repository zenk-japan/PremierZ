/*-----------------------------------------------------------------------------
-- TABLE名          ：セッション
-- 作成者           ：zenk
-- 作成日           ：2009-05-20
-- 更新履歴         ：
  -----------------------------------------------------------------------------*/
CREATE TABLE  `SESSIONS` (
    `DATA_ID`                     BIGINT(8)    NOT NULL                  COMMENT 'データID',
    `SESSION_ID`                  BIGINT(8)    NOT NULL  AUTO_INCREMENT  COMMENT 'セッションID',
    `SESSID`                      VARCHAR(32)            DEFAULT NULL    COMMENT 'セッション値',
    `SESS_TOKEN`                  VARCHAR (32)           DEFAULT NULL    COMMENT 'セッショントークン値',
    `USER_ID`                     BIGINT(8)    NOT NULL                  COMMENT 'ユーザID',
    `LOGIN_FLAG`                  VARCHAR(1)             DEFAULT 'Y'     COMMENT 'ログインフラグ',
    `LOGIN_DATET`                 DATETIME     NOT NULL                  COMMENT 'ログイン時間',
    `RESERVE_1`                   VARCHAR(150)           DEFAULT NULL    COMMENT '予備1',
    `RESERVE_2`                   VARCHAR(150)           DEFAULT NULL    COMMENT '予備2',
    `RESERVE_3`                   VARCHAR(150)           DEFAULT NULL    COMMENT '予備3',
    `RESERVE_4`                   VARCHAR(150)           DEFAULT NULL    COMMENT '予備4',
    `RESERVE_5`                   VARCHAR(150)           DEFAULT NULL    COMMENT '予備5',
    `RESERVE_6`                   VARCHAR(150)           DEFAULT NULL    COMMENT '予備6',
    `RESERVE_7`                   VARCHAR(150)           DEFAULT NULL    COMMENT '予備7',
    `RESERVE_8`                   VARCHAR(150)           DEFAULT NULL    COMMENT '予備8',
    `RESERVE_9`                   VARCHAR(150)           DEFAULT NULL    COMMENT '予備9',
    `RESERVE_10`                  VARCHAR(150)           DEFAULT NULL    COMMENT '予備10',
    `VALIDITY_FLAG`               VARCHAR(1)   NOT NULL  DEFAULT 'Y'     COMMENT '有効フラグ',
    `REGISTRATION_DATET`          DATETIME     NOT NULL                  COMMENT '新規登録日',
    `REGISTRATION_USER_ID`        BIGINT(8)    NOT NULL                  COMMENT '新規登録者ID',
    `LAST_UPDATE_DATET`           DATETIME     NOT NULL                  COMMENT '最終更新日',
    `LAST_UPDATE_USER_ID`         BIGINT(8)    NOT NULL                  COMMENT '最終更新者ID',
    PRIMARY KEY  (`SESSION_ID`),
    KEY `UI_SESSIONS_01` (`DATA_ID`,`USER_ID`)
) ENGINE=innoDB DEFAULT CHARSET=utf8 COMMENT='セッション管理';
