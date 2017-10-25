/*-----------------------------------------------------------------------------
-- TABLE名          ：共通マスタ
-- 作成者           ：zenk
-- 作成日           ：2009-01-29
-- 更新履歴         ：2009-07-02 CODE_VALUEのサイズを50->150に変更
  -----------------------------------------------------------------------------*/
CREATE TABLE  `COMMON_MASTER` (
    `DATA_ID`               BIGINT(8)       NOT NULL                    COMMENT 'データID',
    `CODE_ID`               BIGINT(8)       NOT NULL     AUTO_INCREMENT COMMENT 'コードID',
    `CODE_SET`              VARCHAR(50)     NOT NULL                    COMMENT 'コードセット',
    `CODE_NAME`             VARCHAR(50)     NOT NULL                    COMMENT 'コード名',
    `CODE_VALUE`            VARCHAR(150)    DEFAULT NULL                COMMENT 'コード値',
    `REMARKS`               VARCHAR(150)    DEFAULT NULL                COMMENT '備考',
    `VALIDATION_START_DATE` DATE            DEFAULT NULL                COMMENT '有効化開始日',
    `VALIDATION_END_DATE`   DATE            DEFAULT NULL                COMMENT '有効化終了日',
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
    `VALIDITY_FLAG`         VARCHAR(1)      NOT NULL     DEFAULT 'Y'    COMMENT '有効フラグ',
    `REGISTRATION_DATET`    DATETIME        NOT NULL                    COMMENT '新規登録日',
    `REGISTRATION_USER_ID`  BIGINT(8)       NOT NULL                    COMMENT '新規登録者ID',
    `LAST_UPDATE_DATET`     DATETIME        NOT NULL                    COMMENT '最終更新日',
    `LAST_UPDATE_USER_ID`   BIGINT(8)       NOT NULL                    COMMENT '最終更新者ID',
    PRIMARY KEY  (`CODE_ID`),
    UNIQUE KEY `UI_COMMON_MASTER_01` USING BTREE (`DATA_ID`,`CODE_SET`,`CODE_NAME`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='共通マスタ';
