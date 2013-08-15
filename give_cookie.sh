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
		if [ "$cookie" == "A" ]; then
			echo ok lets move the right motor!
			/root/atosdv2/pi-blaster/pi-blaster --pcm &
			sleep 0.1
			echo 5=0.2 > /dev/pi-blaster
			sleep 1.5
			echo 5=0.063 > /dev/pi-blaster
			sleep 1.5
			echo 5=0 > /dev/pi-blaster
			killall pi-blaster
		elif [ "$cookie" == "B" ] ; then
			/root/atosdv2/single_cookie/single_cookie
		fi
		lockfile-remove /srv/http/writeable/cookie_lock
	else
		echo sorry no cookie
	fi
	
fi

