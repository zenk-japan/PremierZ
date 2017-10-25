/*-----------------------------------------------------------------------------
-- TABLE名          ：作業内容
-- 作成者           ：zenk
-- 作成日           ：2009-03-04
-- 更新履歴         ：2009-06-03 作業ステータス追加
--                  ：2011-07-15 作業コード追加
--                               ALTER TABLE WORK_CONTENTS ADD WORK_CONTENT_CODE VARCHAR(50) NOT NULL COMMENT '作業コード';
--                               見積ID、作業日、作業コードで一意になる様に制約を追加
--                               ALTER TABLE WORK_CONTENTS ADD UNIQUE `UI_CONTENT_TABLE_01` (`ESTIMATE_ID`,`WORK_DATE`,`WORK_CONTENT_CODE`);
  -----------------------------------------------------------------------------*/
CREATE TABLE  `WORK_CONTENTS` (
    `DATA_ID`                           BIGINT(8)     NOT NULL                      COMMENT 'データID',
    `WORK_CONTENT_ID`                   BIGINT(8)     NOT NULL      AUTO_INCREMENT  COMMENT '作業内容ID',
    `WORK_CONTENT_CODE`                 VARCHAR(50)   NOT NULL                      COMMENT '作業コード',
    `ESTIMATE_ID`                       BIGINT(8)     NOT NULL                      COMMENT '見積ID',
    `WORK_DATE`                         DATE          NOT NULL                      COMMENT '作業日',
    `DEFAULT_ENTERING_SCHEDULE_TIMET`   VARCHAR(30)   DEFAULT NULL                  COMMENT '入店予定時刻',
    `DEFAULT_LEAVE_SCHEDULE_TIMET`      VARCHAR(30)   DEFAULT NULL                  COMMENT '退店予定時刻',
    `DEFAULT_WORKING_TIME`              DOUBLE(5,2)   DEFAULT '8.0'                 COMMENT '規定実働時間',
    `DEFAULT_BREAK_TIME`                DOUBLE(5,2)   DEFAULT '1.0'                 COMMENT '規定休憩時間',
    `AGGREGATE_TIMET`                   TIME          DEFAULT NULL                  COMMENT '集合時間',
    `AGGREGATE_POINT`                   VARCHAR(150)  DEFAULT NULL                  COMMENT '集合場所',
    `WORK_ARRANGEMENT_ID`               BIGINT(8)     DEFAULT NULL                  COMMENT '作業纏め者ID',
    `WORK_CONTENT_DETAILS`              TEXT          DEFAULT NULL                  COMMENT '作業内容詳細',
    `BRINGING_GOODS`                    VARCHAR(150)  DEFAULT NULL                  COMMENT '持参品',
    `CLOTHES`                           VARCHAR(150)  DEFAULT NULL                  COMMENT '服装',
    `INTRODUCE`                         VARCHAR(150)  DEFAULT NULL                  COMMENT '名乗り',
    `TRANSPORT_AMOUNT_REMARKS`          VARCHAR(150)  DEFAULT NULL                  COMMENT '交通費備考',
    `OTHER_REMARKS`                     VARCHAR(150)  DEFAULT NULL                  COMMENT 'その他備考',
    `OTHER_COST`                        INTEGER       DEFAULT NULL                  COMMENT 'その他費用',
    `EXCESS_AMOUNT`                     INTEGER       DEFAULT NULL                  COMMENT '超過金額',
    `EXCESS_LIQUIDATION_FLAG`           VARCHAR(1)    DEFAULT 'N'                   COMMENT '超過精算',
    `CANCEL_CHARGE`                     INTEGER       DEFAULT NULL                  COMMENT 'キャンセル料',
    `TOTAL_SALES`                       INTEGER       DEFAULT NULL                  COMMENT '総売上',
    `GROSS_MARGIN`                      INTEGER       DEFAULT NULL                  COMMENT '粗利益',
    `GROSS_MARGIN_RATE`                 DOUBLE(5,2)   DEFAULT NULL                  COMMENT '粗利益率',
    `WORK_STATUS`                       VARCHAR(2)    DEFAULT 'NW'                  COMMENT '作業ステータス',
    `RESERVE_1`                         VARCHAR(150)  DEFAULT NULL                  COMMENT '予備1',
    `RESERVE_2`                         VARCHAR(150)  DEFAULT NULL                  COMMENT '予備2',
    `RESERVE_3`                         VARCHAR(150)  DEFAULT NULL                  COMMENT '予備3',
    `RESERVE_4`                         VARCHAR(150)  DEFAULT NULL                  COMMENT '予備4',
    `RESERVE_5`                         VARCHAR(150)  DEFAULT NULL                  COMMENT '予備5',
    `RESERVE_6`                         VARCHAR(150)  DEFAULT NULL                  COMMENT '予備6',
    `RESERVE_7`                         VARCHAR(150)  DEFAULT NULL                  COMMENT '予備7',
    `RESERVE_8`                         VARCHAR(150)  DEFAULT NULL                  COMMENT '予備8',
    `RESERVE_9`                         VARCHAR(150)  DEFAULT NULL                  COMMENT '予備9',
    `RESERVE_10`                        VARCHAR(150)  DEFAULT NULL                  COMMENT '予備10',
    `VALIDITY_FLAG`                     VARCHAR(1)    NOT NULL      DEFAULT 'Y'     COMMENT '有効フラグ',
    `REGISTRATION_DATET`                DATETIME      NOT NULL                      COMMENT '新規登録日',
    `REGISTRATION_USER_ID`              BIGINT(8)     NOT NULL                      COMMENT '新規登録者ID',
    `LAST_UPDATE_DATET`                 DATETIME      NOT NULL                      COMMENT '最終更新日',
    `LAST_UPDATE_USER_ID`               BIGINT(8)     NOT NULL                      COMMENT '最終更新者ID',
    PRIMARY KEY  (`WORK_CONTENT_ID`),
    KEY `NI_CONTENT_TABLE_01` (`DATA_ID`,`ESTIMATE_ID`,`WORK_DATE`),
    UNIQUE `UI_CONTENT_TABLE_01` (`ESTIMATE_ID`,`WORK_DATE`,`WORK_CONTENT_CODE`)
) ENGINE=innoDB DEFAULT CHARSET=utf8 COMMENT='作業内容';
