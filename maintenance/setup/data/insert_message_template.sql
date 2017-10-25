INSERT INTO `MESSAGE_TEMPLATE` 
VALUES
 (%%data_id%%,NULL,'ユーザー通知用本文','ユーザー通知','%%USER_NAME%% 様

★今回より作業詳細のメール送信及び作業への出発・開始・終了の時間連絡につきまして、弊社システムを使用する事となりました。
下記手順に従い、事前の登録及び当日の作業連絡を忘れない様お願いいたします。

--------------------

1.作業依頼のメールが、後ほど送信されます。
2.作業依頼のメール本文に記載されているURLへアクセスし、本メールにて送付しております[USER]、[パスワード]、[利用会社]でログインしてください。
3.ログイン後、作業内容一覧／詳細から、『作業内容承認』及び『出発予定時間』の登録をお願いいたします。
4.作業当日は、ログイン後、出発・入店・退店登録をお願いいたします。

※セキュリティ向上のため初回ログイン時にパスワードの変更をお願いいたします。
※作業当日は、承認時に設定した『出発予定時間』までに出発登録を押してください。
（出発登録がない場合は、管理担当者から連絡が入る事がございます。）

[USER]:%%USER_CODE%%    
[パスワード]:%%USER_DEFAULT_PASS%%
[利用会社]:%%USE_COMPANY_CODE%%

[PC URL]:%%PC_URL%%
[Mobile URL]:%%MOBILE_URL%%

--------------------

以上、ご協力のほど宜しくお願い申し上げます。',null,null,null,null,null,null,null,null,null,null,'Y',now(),-1,now(),-1)
,(%%data_id%%,NULL,'ユーザー通知用件名','ユーザー通知','【%%USER_NAME%%様】%%SYS_NAME%%についてのお知らせ',null,null,null,null,null,null,null,null,null,null,'Y',now(),-1,now(),-1)
,(%%data_id%%,NULL,'作業通知用本文','作業通知','%各ユーザ名% 様

★体調不良等による休みの申請は当日の朝6:30までに%%ATTEND_ADDR%%宛にメールにてご連絡頂くか、
または、作業纏め者に電話にて直接ご連絡頂きます様お願いいたします。

--------------------
【作業日時】
  %%WORK_DATE%%  予定時間：%%SCHEDULE_TIMET_FROM%%～%%SCHEDULE_TIMET_TO%%
【集合場所】
  %%AGGREGATE_POINT%% (%%AGGREGATE_TIMET%%集合)
【作業場所】
  %%WORK_BASE_NAME%%
  住所：%%WORK_ADDRESS%%
  最寄駅：%%WORK_CLOSEST_STATION%%
【作業纏め者】
  %%WORK_ARRANGEMENT_NAME%%
  携帯：%%WORK_ARRG_MOBILE_PHONE%%
【作業内容詳細】
  %%WORK_CONTENT_DETAILS%%
【持参品】
  %%BRINGING_GOODS%%
【名乗り】
  %%INTRODUCE%%
【服装】
  %%CLOTHES%%
【作業費】
  ￥%各ユーザ－作業費%

以下のURLよりアクセスしてください。
[PC URL]:%%PC_URL%%
[Mobile URL]:%%MOBILE_URL%%

--------------------
※作業内容のご質問・不明点につきましては、%%MANAGE_ADDR%%宛てにご連絡ください。
※%%SYS_NAME%%による入退店等の報告は全て弊社宛ての連絡となります。
依頼元・お客様宛てへ入退店連絡報告の指示が別途ありましたら、指定先へのご連絡も必ず実施願います。',null,null,null,null,null,null,null,null,null,null,'Y',now(),-1,now(),-1)
,(%%data_id%%,NULL,'作業通知用件名','作業通知','【%各ユーザ名%様】%%WORK_NAME%% - 作業詳細',null,null,null,null,null,null,null,null,null,null,'Y',now(),-1,now(),-1)
,(%%data_id%%,NULL,'パスワードリセット用本文','パスワードリセット','%%USER_NAME%% 様

パスワードリセットを行うには、下記URLにアクセスをして下さい。
%%URL_FOR_PASSRESET%%

このアドレスにアクセスすると、パスワードがリセットされ、
画面上に新しいパスワードが表示されます。

パスワードがリセットされると、古いパスワードではログインできなくなりますので、
御注意下さい。

また、このメールの配信以降に正常にログインが行われた場合、
上記のURLは使用できなくなります。

★当メールはパスワード変更依頼画面からパスワードの変更を依頼された方に配信しています。
★心当たりのない場合は、当メールを破棄し、管理者にご連絡下さい。

',null,null,null,null,null,null,null,null,null,null,'Y',now(),-1,now(),-1)
,(%%data_id%%,NULL,'パスワードリセット用件名','パスワードリセット','【%%USER_NAME%%様】パスワードリセット用URLのお知らせ',null,null,null,null,null,null,null,null,null,null,'Y',now(),-1,now(),-1)
,(%%data_id%%,NULL,'遅延通知用本文','遅延通知','%%USER_NAME%% 様

%%WORK_DATE_SHORT%% 実施の「%%WORK_NAME%%」について、

%%DELAY_MESSAGE%%

至急連絡をお願いいたします。

下記URLよりアクセスして下さい。 
[PC URL]:%%PC_URL%%
[Mobile URL]:%%MOBILE_URL%%

',null,null,null,null,null,null,null,null,null,null,'Y',now(),-1,now(),-1)
,(%%data_id%%,NULL,'遅延通知用件名','遅延通知','【連絡依頼】%%WORK_DATE_SHORT%%「%%WORK_NAME%%」についてご連絡下さい',null,null,null,null,null,null,null,null,null,null,'Y',now(),-1,now(),-1)
;