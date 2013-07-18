#!/usr/bin/bash


if [ $# -ne 1 ]; then
	echo $0 U/D
	exit
fi

direction=$1

inc=0
if [ "$direction" == "U" ]; then
	inc=+7
elif [ "$direction" == "D" ]; then
	inc=-7
else
	echo "WRONG PARAM"
	exit
fi	

max=246
reset=180
min=65


last=`cat /srv/http/camera_position`

if [ -z "$last" ]; then
	echo Setting to mean
	last=reset
fi 

#get the lock
lockfile-create --retry 1 /srv/http/writeable/cookie_lock
if [ $? -eq 0 ]; then
	new=`echo "" | awk -v last=$last -v max=$max -v min=$min -v inc=$inc '{ new=last+inc; if (new<min) {new=min} ; if (new>max) {new=max}; print new}'`
	echo Moving camera to $new
	echo ok lets move the motor!
	/usr/local/bin/servod &
	sleep 0.1
	echo 7=$new > /dev/servoblaster
	sleep 0.2
	echo 7=0 > /dev/servoblaster
	echo $new > /srv/http/camera_position
	killall servod
	lockfile-remove /srv/http/writeable/cookie_lock
fi
	
