/*-----------------------------------------------------------------------------
-- TABLE名          ：値リスト定義
-- 作成者           ：zenk
-- 作成日           ：2009-07-14
-- 更新履歴         ：2010-01-19
--                    VALUE_DEST_ITEM_ID,ID_DEST_ITEM_ID追加
--                  ：2010-02-02
--                    GROUP_BY_PHRASE追加
  -----------------------------------------------------------------------------*/
CREATE TABLE  `VALUE_LIST_DEFINES` (
    `DATA_ID`                 BIGINT (8)     NOT NULL                  COMMENT 'データID',
    `DEFINE_ID`               BIGINT (8)     NOT NULL  AUTO_INCREMENT  COMMENT '定義ID',
    `DEFINE_CODE`             VARCHAR (150)  NOT NULL                  COMMENT '定義コード',
    `USE_PAGE`                VARCHAR (150)            DEFAULT NULL    COMMENT '使用画面',
    `USE_ITEM`                VARCHAR (150)            DEFAULT NULL    COMMENT '使用項目',
    `SELECT_PHRASE`           LONGTEXT                 DEFAULT NULL    COMMENT 'SELECT句',
    `OPTION_WHERE_1`          VARCHAR (500)            DEFAULT NULL    COMMENT '追加WHERE句1',
    `OPTION_WHERE_2`          VARCHAR (500)            DEFAULT NULL    COMMENT '追加WHERE句2',
    `OPTION_WHERE_3`          VARCHAR (500)            DEFAULT NULL    COMMENT '追加WHERE句3',
    `OPTION_WHERE_4`          VARCHAR (500)            DEFAULT NULL    COMMENT '追加WHERE句4',
    `OPTION_WHERE_5`          VARCHAR (500)            DEFAULT NULL    COMMENT '追加WHERE句5',
    `OPTION_WHERE_6`          VARCHAR (500)            DEFAULT NULL    COMMENT '追加WHERE句6',
    `OPTION_WHERE_7`          VARCHAR (500)            DEFAULT NULL    COMMENT '追加WHERE句7',
    `OPTION_WHERE_8`          VARCHAR (500)            DEFAULT NULL    COMMENT '追加WHERE句8',
    `OPTION_WHERE_9`          VARCHAR (500)            DEFAULT NULL    COMMENT '追加WHERE句9',
    `OPTION_WHERE_10`         VARCHAR (500)            DEFAULT NULL    COMMENT '追加WHERE句10',
    `GROUP_BY_PHRASE`         VARCHAR (2000)           DEFAULT NULL    COMMENT 'GROUP BY句',
    `ORDER_BY_PHRASE`         VARCHAR (2000)           DEFAULT NULL    COMMENT 'ORDER BY句',
    `VALUE_DEST_ITEM_ID`      VARCHAR (2000)           DEFAULT NULL    COMMENT '値セット先ID',
    `ID_DEST_ITEM_ID`         VARCHAR (2000)           DEFAULT NULL    COMMENT 'IDセット先ID',
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
    PRIMARY KEY  (`DEFINE_ID`),
    KEY `UI_VALUE_LIST_DEFINES_01` (`DATA_ID`,`DEFINE_CODE`)
) ENGINE=innoDB DEFAULT CHARSET=utf8 COMMENT='値リスト定義';
