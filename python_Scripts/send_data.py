#!/usr/bin/env python3
#Read about cURL: https://curl.haxx.se/docs/manual.html
import os
from ultra import ultraSonic
from example import scale

def main():
	sonic = ultraSonic() #Get the value from the ultrasonic funtcion (the string value)
	weight = scale() #Get the value from the scale function (the string value)
	
	customer = "Galactic Empire"
	stationName = "Death Station"
	material = "Papper"
	os.system('curl -d "customer='+customer+'&station='+stationName+'&material='+material+'&weight='+weight+'&fill='+sonic+'&post=Send" "10.50.1.4:81/wordpress/?page_id=468"') #The last stuff is the IP-address, or the URL to the website, where the form is
	pass
if __name__ == '__main__':
	main()
	print("Sending...")