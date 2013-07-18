#!/usr/bin/bash


if [ $# -ne 1 ]; then
	echo $0 cookieAorB
	exit
fi

cookie=$1

if [ "$cookie" == "A" -o "$cookie" == "B" ]; then
	#only one cookie servo at a time!
	lockfile-create --retry 1 /srv/http/writeable/cookie_lock
	if [ $? -eq 0 ]; then
		echo got the lock send away!
		motor=0
		if [ "$cookie" == "A" ]; then
			motor=6
		elif [ "$cookie" == "B" ] ; then
			motor=5
		fi
		if [ $motor -ne 0 ]; then
			echo ok lets move the right motor!
			/usr/local/bin/servod &
			sleep 0.1
			echo ${motor}=249 > /dev/servoblaster
			sleep 1.5
			echo ${motor}=63 > /dev/servoblaster
			sleep 1.5
			echo ${motor}=0 > /dev/servoblaster
			killall servod
		fi
		lockfile-remove /srv/http/writeable/cookie_lock
	else
		echo sorry no cookie
	fi
	
fi

