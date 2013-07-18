#!/bin/bash

echo playing...
if [ $# -ne 1 ] ; then 
	echo $0 number
	exit
fi


x=$1
sounds_list="sounds/sounds_list"


a=0
while read line; do 
	if [ $a -eq "$x" ]; then
		echo $line
		lockfile-create --retry 1 /srv/http/writeable/cookie_lock
		if [ $? -eq 0 ]; then
			#/usr/bin/systemctl stop servod
			#echo stopped servo
			killall servod
			/usr/bin/mpg123 "$line"
			#/usr/bin/systemctl start servod
			lockfile-remove /srv/http/writeable/cookie_lock
		else 
			echo no lock 
		fi
		exit
	fi
	a=`expr $a + 1`
done < ${sounds_list}


