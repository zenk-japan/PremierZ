#! /bin/sh
# 遅延警告のログをバックアップし、10日前のものは圧縮ファイルに追加する
# 定数
logdir="/home/AAAA/cron/delaycheck/"


filename="delaychecklog"
bkfilename="delaychecklog_`date +"%Y-%m-%d"`"
tarfilename="delaychklog_pack.tar"
packlimit=10
findcommand="eval find ${logdir} -daystart -type f -name '${filename}\*' -mtime +${packlimit}"

# 昨日の分をバックアップ
if test -e ${logdir}${filename};then
    mv ${logdir}${filename} ${logdir}${bkfilename}
fi

# findcommandの実行結果を配列に格納
findresult=`eval ${findcommand} | tr "[:cntrl:]" " "`
findarray=($findresult)
#teststrings="x"`eval ${findcommand} | tr "[:cntrl:]" "-"`
#echo ${teststrings}
#echo "num0" ${findarray[0]}
#echo "num1" ${findarray[1]}
#echo "num2" ${findarray[2]}
#echo ${#findarray[*]}
#exit

# 10日前のものは圧縮ファイルに追加
if [ ${#findarray[*]} -ne 0 ]
then
	for trgtfile in ${findarray[@]}
	do
		if test -e ${logdir}${zipfilename};then
			tar rf ${logdir}${tarfilename} $trgtfile
		else
			tar cf ${logdir}${tarfilename} $trgtfile
		fi

		if [ $? = 0 ]
		then
		# 圧縮に成功したら元のファイルを削除
			rm $trgtfile
		fi
	done
fi

