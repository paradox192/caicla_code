#Several videos helped with this: 
#Distance sensor: https://www.youtube.com/watch?v=kqJ8WYQu68w
#Percentage: https://www.youtube.com/watch?v=7fIY_5m1yH0

def ultraSonic():
    import RPi.GPIO as GPIO
    import time
    import numpy as np
    import sys

    def cleanAndExit():
        #print ("Cleaning...")
        GPIO.cleanup()
        #print ("Bye!")
        #sys.exit()

    GPIO.setmode(GPIO.BCM)
    TRIG = 3
    ECHO = 2
    percentArray = [] #Array to send percentages into
    sensorToTopOfBin = 20#In centimeters, this is the space between the sensor and the top of the bin
    sensorToBottomOfBin = 59 #in centimeters, this is the distance from the sensor to the bottom of the bin
    
    containerDepth = sensorToBottomOfBin - sensorToTopOfBin #This calculates the depth of the bin
    GPIO.setup(TRIG,GPIO.OUT) #Says that TRIG will use its number as a GPIO OUT-pin
    GPIO.setup(ECHO,GPIO.IN) #Says that ECHO will use its number as a GPIO IN-pin

    for x in range(0, 22):
        GPIO.output(TRIG, True)
        time.sleep(0.05)
        GPIO.output(TRIG, False)

        while GPIO.input(ECHO) == 0:
                start = time.time()

        while GPIO.input(ECHO) == 1:
                end = time.time()

        signal_time = end-start

        #CM:
        currentDepth = int(signal_time / 0.000058) - sensorToTopOfBin #The current depth minus the space between sensor and bin
        percentage = (((currentDepth - containerDepth)/(containerDepth)) * 100) *-1 #Calculates percentage
        if(percentage <= 0): #if percent is less or equal to zero, set it as zero
            percentage = 0
        percentArray.append(percentage) #Push values to the back of an array

    percentArray.remove(max(percentArray))#Remove the biggest value of the array
    percentArray.remove(min(percentArray))#Remove the smallest value of the array

    averagePercentage = np.average(percentArray)#Take an average of the values in the array
    averagePercentage = int(averagePercentage)#Set the average as an int, so that there are no decimals
    #print(str(averagePercentage)+'%')

    cleanAndExit()
    return(str(averagePercentage)) #cURL-script expects a string, so we return the value as a string

#ultraSonic() #This is here for testing this individual script
