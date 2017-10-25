#! /bin/sh
# 出発、入店、退店の遅延メールを送信するPHPモジュールを起動する

logdir="/home/XXXX/cron/delaycheck/"
webhomedir="/var/www/XXXX/"
phpdir="/usr/bin/"

echo -e "\n" >> ${logdir}delaychecklog
echo "[`date +"%Y-%m-%d_%k%M"`]" >> ${logdir}delaychecklog

cd ${webhomedir}mdl
${phpdir}php -q ${webhomedir}mdl/m_alertmail.php >> ${logdir}delaychecklog
chmod 755 ${logdir}delaychecklog
