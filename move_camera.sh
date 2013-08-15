#!/usr/bin/bash


if [ $# -ne 1 ]; then
	echo $0 U/D
	exit
fi

direction=$1

inc=0.005
if [ "$direction" == "U" ]; then
	inc=$inc
elif [ "$direction" == "D" ]; then
	inc=-$inc
else
	echo "WRONG PARAM"
	exit
fi	

max=0.21
reset=0.11
min=0.01


last=`cat /srv/http/camera_position`

if [ -z "$last" ]; then
	echo Setting to mean
	last=reset
fi 

#get the lock
lockfile-create --retry 1 /srv/http/writeable/cookie_lock
if [ $? -eq 0 ]; then
	new=`awk -v last=$last -v max=$max -v min=$min -v inc=$inc 'BEGIN { new=last+inc; if (new<min) {new=min} ; if (new>max) {new=max}; print new}'`
	echo Moving camera to $new
	echo ok lets move the motor!
	echo $new > /srv/http/camera_position
	/root/atosdv2/pi-blaster/pi-blaster --pcm &
	sleep 0.1
	echo 6=$new > /dev/pi-blaster
	sleep 0.2
	//echo 6=0 > /dev/pi-blaster
	killall pi-blaster
	sleep 0.2
	lockfile-remove /srv/http/writeable/cookie_lock
fi
	
