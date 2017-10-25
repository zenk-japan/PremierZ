/*-----------------------------------------------------------------------------
-- TABLE名          ：ログインログ
-- 作成者           ：zenk
-- 作成日           ：2011-08-15
-- 更新履歴         ：
  -----------------------------------------------------------------------------*/
CREATE TABLE  `LOGIN_LOG` (
    `LOGIN_LOG_ID`            BIGINT (8)     NOT NULL  AUTO_INCREMENT  COMMENT 'ログインログID',
    `USED_USER_CODE`          TEXT           NOT NULL                  COMMENT '使用ユーザーコード',
    `USED_PASSWORD`           TEXT                     DEFAULT NULL    COMMENT '使用パスワード',
    `USED_COMPANY_CODE`       TEXT                     DEFAULT NULL    COMMENT '使用利用会社コード',
    `CERTIFICATION_RESULT`    VARCHAR (2)              DEFAULT NULL    COMMENT '認証結果',
    `SPG_REFERER`             TEXT                     DEFAULT NULL    COMMENT '移動元画面',
    `SPG_REMORT_ADDR`         TEXT                     DEFAULT NULL    COMMENT 'ユーザーIPアドレス',
    `SPG_SERVER`              TEXT                     DEFAULT NULL    COMMENT '$_SERVER',
    `SPG_REQUEST`             TEXT                     DEFAULT NULL    COMMENT '$_REQUEST',
    `REMARK`                  TEXT                     DEFAULT NULL    COMMENT '備考',
    `VALIDITY_FLAG`           VARCHAR (1)    NOT NULL  DEFAULT 'Y'     COMMENT '有効フラグ',
    `REGISTRATION_DATET`      DATETIME       NOT NULL                  COMMENT '新規登録日',
    `REGISTRATION_USER_ID`    BIGINT (8)     NOT NULL                  COMMENT '新規登録者ID',
    `LAST_UPDATE_DATET`       DATETIME       NOT NULL                  COMMENT '最終更新日',
    `LAST_UPDATE_USER_ID`     BIGINT (8)     NOT NULL                  COMMENT '最終更新者ID',
    PRIMARY KEY  (`LOGIN_LOG_ID`)
) ENGINE=innoDB DEFAULT CHARSET=utf8 COMMENT='ログインログ';
