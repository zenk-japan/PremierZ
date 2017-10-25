/*-----------------------------------------------------------------------------
-- TABLE名          ：拠点
-- 作成者           ：zenk
-- 作成日           ：2009-02-12
-- 更新履歴         ：
  -----------------------------------------------------------------------------*/
CREATE TABLE  `BASES` (
    `DATA_ID`               BIGINT(8)       NOT NULL                    COMMENT 'データID',
    `COMPANY_ID`            BIGINT(8)       NOT NULL    DEFAULT '0'     COMMENT '会社ID',
    `BASE_ID`               BIGINT(8)       NOT NULL    AUTO_INCREMENT  COMMENT '拠点ID',
    `BASE_CODE`             VARCHAR(8)      NOT NULL                    COMMENT '拠点コード',
    `BASE_NAME`             VARCHAR(150)    NOT NULL                    COMMENT '拠点名',
    `ZIP_CODE`              VARCHAR(10)     DEFAULT NULL                COMMENT '郵便番号',
    `ADDRESS`               VARCHAR(240)    DEFAULT NULL                COMMENT '住所',
    `TELEPHONE`             VARCHAR(20)     DEFAULT NULL                COMMENT '電話番号',
    `CLOSEST_STATION`       VARCHAR(50)     DEFAULT NULL                COMMENT '最寄駅',
    `REMARKS`               VARCHAR(150)    DEFAULT NULL                COMMENT '備考',
    `RESERVE_1`             VARCHAR(150)    DEFAULT NULL                COMMENT '予備1',
    `RESERVE_2`             VARCHAR(150)    DEFAULT NULL                COMMENT '予備2',
    `RESERVE_3`             VARCHAR(150)    DEFAULT NULL                COMMENT '予備3',
    `RESERVE_4`             VARCHAR(150)    DEFAULT NULL                COMMENT '予備4',
    `RESERVE_5`             VARCHAR(150)    DEFAULT NULL                COMMENT '予備5',
    `RESERVE_6`             VARCHAR(150)    DEFAULT NULL                COMMENT '予備6',
    `RESERVE_7`             VARCHAR(150)    DEFAULT NULL                COMMENT '予備7',
    `RESERVE_8`             VARCHAR(150)    DEFAULT NULL                COMMENT '予備8',
    `RESERVE_9`             VARCHAR(150)    DEFAULT NULL                COMMENT '予備9',
    `RESERVE_10`            VARCHAR(150)    DEFAULT NULL                COMMENT '予備10',
    `VALIDITY_FLAG`         VARCHAR(1)      NOT NULL    DEFAULT 'Y'     COMMENT '有効フラグ',
    `REGISTRATION_DATET`    DATETIME        NOT NULL                    COMMENT '新規登録日',
    `REGISTRATION_USER_ID`  BIGINT(8)       NOT NULL                    COMMENT '新規登録者ID',
    `LAST_UPDATE_DATET`     DATETIME        NOT NULL                    COMMENT '最終更新日',
    `LAST_UPDATE_USER_ID`   BIGINT(8)       NOT NULL                    COMMENT '最終更新者ID',
    PRIMARY KEY  (`BASE_ID`),
    UNIQUE KEY `UI_BASES_01` (`DATA_ID`,`COMPANY_ID`,`BASE_CODE`),
    KEY `NI_BASES_02` (`DATA_ID`,`COMPANY_ID`,`BASE_NAME`)
) ENGINE=innoDB DEFAULT CHARSET=utf8 COMMENT='拠点';
