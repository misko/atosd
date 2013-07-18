#!/usr/bin/python

import time
import RPi.GPIO as GPIO
import sys
GPIO.setmode(GPIO.BCM)


sf='/dev/servoblaster'
#set motor m to x
def set_pos(m,x):
	print("set %d to %d" % (m,x))
	f=open(sf,'w')
	f.write("%d=%d\n" % (m,x))
	f.close()

def cookie_drop(pin,tout):
	slept=0.0
	res=0.03
	last_angle=-1
	while slept<tout:
		cookie_dropped=GPIO.input(pin)==0
		current_angle=readadc(adcnum, SPICLK, SPIMOSI, SPIMISO, SPICS)
		if cookie_dropped:
			print("DROPED COOKIE")
			return True
		#if voltage of motor position has not changed return
		if abs(current_angle-last_angle)<1:
			print("JAMMED %f vs %f " % (current_angle,last_angle))
			return False
		last_angle=current_angle
		time.sleep(res)
		slept+=res	 
	return False

#bit bang method from online, should use SPIDEV!!
#TODO : USE SPIDEV!
# change these as desired
SPICLK = 11
SPIMOSI = 9
SPIMISO = 10
SPICS = 18
 
# set up the SPI interface pins
GPIO.setup(SPIMOSI, GPIO.OUT)
GPIO.setup(SPIMISO, GPIO.IN)
GPIO.setup(SPICLK, GPIO.OUT)
GPIO.setup(SPICS, GPIO.OUT)
adcnum = 0
# read SPI data from MCP3002 chip, 2 possible adc's (0 thru 1)
def readadc(adcnum, clockpin, mosipin, misopin, cspin):
	if ((adcnum > 1) or (adcnum < 0)):
		return -1
	if (adcnum == 0):
		commandout = 0x6
	else:
		commandout = 0x7
	GPIO.output(cspin, True)
 
	GPIO.output(clockpin, False)  # start clock low
	GPIO.output(cspin, False)	 # bring CS low
 
	#commandout = 0x6  #start bit and 1, 0 to select single ended ch0
	commandout <<= 5	# we only need to send 3 bits here
	for i in range(3):
		if (commandout & 0x80):
			GPIO.output(mosipin, True)
		else:
			GPIO.output(mosipin, False)
			commandout <<= 1
			GPIO.output(clockpin, True)
			GPIO.output(clockpin, False)
 
	adcout = 0
	# read in one empty bit, one null bit and 10 ADC bits
	for i in range(12):
		GPIO.output(clockpin, True)
		GPIO.output(clockpin, False)
		adcout <<= 1
		if (GPIO.input(misopin)):
			adcout |= 0x1
 
	GPIO.output(cspin, True)
 
	adcout /= 2	   # first bit is 'null' so drop it
	return adcout



def cookie_check():
	if cookie_drop(PHOTOPIN,5):
		time.sleep(0.1) #time to drop
		#check if clogged
		if GPIO.input(PHOTOPIN)==0:
			return False
		else:
			return True
	return False



if len(sys.argv)!=3:
	print("%s cookies timeout" % (sys.argv[0]))
	sys.exit(1)

def blink(pin,t):
	GPIO.output(pin,True)
	time.sleep(t)
	GPIO.output(pin,False)
	time.sleep(t)

to_drop=int(sys.argv[1])
timeout=int(sys.argv[2])

PHOTOPIN=17
LEDPIN=14
GPIO.setup(LEDPIN, GPIO.OUT)
GPIO.setup(PHOTOPIN,GPIO.IN)
GPIO.output(LEDPIN,False)



last_drop=time.time()
while to_drop>0:
	if time.time()-last_drop>timeout:
		set_pos(0,0)
		blink(LEDPIN,0.15)
		blink(LEDPIN,0.15)
		blink(LEDPIN,0.15)
		print("time out")
		sys.exit(1)
	set_pos(0,40)
	if cookie_check():
		last_drop=time.time()
		set_pos(0,0)
		blink(LEDPIN,0.8)
		to_drop-=1
	set_pos(0,200)
	if cookie_check():
		last_drop=time.time()
		set_pos(0,0)
		blink(LEDPIN,0.8)
		to_drop-=1

set_pos(0,0)
	
