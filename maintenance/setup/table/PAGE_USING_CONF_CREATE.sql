/*-----------------------------------------------------------------------------
-- TABLE名          ：画面使用設定
-- 作成者           ：zenk
-- 作成日           ：2010-04-09
-- 更新履歴         ：
  -----------------------------------------------------------------------------*/
CREATE TABLE  `PAGE_USING_CONF` (
    `DATA_ID`               BIGINT (8)      NOT NULL                    COMMENT 'データID',
    `PAGE_USING_CONF_ID`    BIGINT (8)      NOT NULL    AUTO_INCREMENT  COMMENT '画面使用設定ID',
    `PAGE_CODE`             VARCHAR (150)   NOT NULL                    COMMENT '画面コード',
    `PAGE_NAME`             VARCHAR (150)   DEFAULT NULL                COMMENT '画面名',
    `ALLOWED_AUTHCODE`      TEXT  DEFAULT   NULL                        COMMENT '使用許可権限コード',
    `REMARKS`               TEXT  DEFAULT   NULL                        COMMENT '備考',
    `RESERVE_1`             VARCHAR (150)   DEFAULT NULL                COMMENT '予備1',
    `RESERVE_2`             VARCHAR (150)   DEFAULT NULL                COMMENT '予備2',
    `RESERVE_3`             VARCHAR (150)   DEFAULT NULL                COMMENT '予備3',
    `RESERVE_4`             VARCHAR (150)   DEFAULT NULL                COMMENT '予備4',
    `RESERVE_5`             VARCHAR (150)   DEFAULT NULL                COMMENT '予備5',
    `RESERVE_6`             VARCHAR (150)   DEFAULT NULL                COMMENT '予備6',
    `RESERVE_7`             VARCHAR (150)   DEFAULT NULL                COMMENT '予備7',
    `RESERVE_8`             VARCHAR (150)   DEFAULT NULL                COMMENT '予備8',
    `RESERVE_9`             VARCHAR (150)   DEFAULT NULL                COMMENT '予備9',
    `RESERVE_10`            VARCHAR (150)   DEFAULT NULL                COMMENT '予備10',
    `VALIDITY_FLAG`         VARCHAR (1)     NOT NULL    DEFAULT 'Y'     COMMENT '有効フラグ',
    `REGISTRATION_DATET`    DATETIME        NOT NULL                    COMMENT '新規登録日',
    `REGISTRATION_USER_ID`  BIGINT (8)      NOT NULL                    COMMENT '新規登録者ID',
    `LAST_UPDATE_DATET`     DATETIME        NOT NULL                    COMMENT '最終更新日',
    `LAST_UPDATE_USER_ID`   BIGINT (8)      NOT NULL                    COMMENT '最終更新者ID',
    PRIMARY KEY  (`PAGE_USING_CONF_ID`),
    UNIQUE KEY `UI_PAGE_USING_CONF_01` (`DATA_ID`,`PAGE_CODE`)
) ENGINE=innoDB DEFAULT CHARSET=utf8 COMMENT='画面使用設定';
