#!/bin/bash

echo playing...
if [ $# -ne 1 ] ; then 
	echo $0 number
	exit
fi


x=$1
sounds_list="sounds/sounds_list"

ls /srv/http/writeable/ > /srv/http/writeable/testq
echo test >> /srv/http/writeable/testq

a=0
while read line; do 
	if [ $a -eq "$x" ]; then
		echo going to play $line
		lockfile-create --retry 1 /srv/http/writeable/cookie_lock
		if [ $? -eq 0 ]; then
			echo got lock!
			#/usr/bin/systemctl stop servod
			#echo stopped servo
			#killall servod
			/usr/bin/mpg123 "$line" 2>> /srv/http/writeable/testq
			#/usr/bin/systemctl start servod
			lockfile-remove /srv/http/writeable/cookie_lock
		else 
			echo no lock 
		fi
		exit
	fi
	a=`expr $a + 1`
done < ${sounds_list}


