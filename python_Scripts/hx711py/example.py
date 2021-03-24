# For the non-changed example, go here and get it: https://github.com/tatobari/hx711py
def scale():
    import RPi.GPIO as GPIO
    import time
    import sys
    import numpy as np
    from hx711 import HX711

    def cleanAndExit(): #Cleans the GPIO-pins
        print ("Cleaning...")
        GPIO.cleanup()
        #print ("Bye!")
        #sys.exit()

    hx = HX711(5, 6)
    weightArray = []

    # I've found out that, for some reason, the order of the bytes is not always the same between versions of python, numpy and the hx711 itself.
    # Still need to figure out why does it change.
    # If you're experiencing super random values, change these values to MSB or LSB until to get more stable values.
    # There is some code below to debug and log the order of the bits and the bytes.
    # The first parameter is the order in which the bytes are used to build the "long" value.
    # The second paramter is the order of the bits inside each byte.
    # According to the HX711 Datasheet, the second parameter is MSB so you shouldn't need to modify it.
    hx.set_reading_format("LSB", "MSB")

    # HOW TO CALCULATE THE REFFERENCE UNIT
    # To set the reference unit to 1. Put 1kg on your sensor or anything you have and know exactly how much it weights.
    # In this case, 92 is 1 gram because, with 1 as a reference unit I got numbers near 0 without any weight
    # and I got numbers around 184000 when I added 2kg. So, according to the rule of thirds:
    # If 2000 grams is 184000 then 1000 grams is 184000 / 2000 = 92.
    hx.set_reference_unit(214)

    hx.reset()
    #hx.tare() #This tares the scale, aka making it zero when the script is being executed, we obviously don't want that

    for x in range(0, 12):
            # These three lines are usefull to debug wether to use MSB or LSB in the reading formats
            # for the first parameter of "hx.set_reading_format("LSB", "MSB")".
            # Comment the two lines "val = hx.get_weight(5)" and "print val" and uncomment the three lines to see what it prints.
            #np_arr8_string = hx.get_np_arr8_string()
            #binary_string = hx.get_binary_string()
            #print binary_string + " " + np_arr8_string
            
            val = hx.get_weight(1) 
            kiloVal = val/1000 #Because it gets grams, and we want kilograms
            roundVal = round(kiloVal-41.3, 1) #Rounding the weight to kilograms with one decimal. Reason for high minus is because it prints a high value once the tare is gone.
            positiveVal = max(0.0000, roundVal)#Using max() between zero and the weight to get ge biggest value between them, that way it ignores anything below 0
            weightArray.append(positiveVal)#Send value to back of an array

            hx.reset()
            time.sleep(0.5) #Sleep-time to allow the HX711 to rest

    weightArray.remove(max(weightArray)) #Remove the highest value in the array
    weightArray.remove(min(weightArray)) #Remove the lowest value in the array

    averageWeight = np.average(weightArray) #Take an average of the values in the array
    averageWeight = round(averageWeight, 1) #Round the average to 1 decimal
    
    cleanAndExit()
    return (str(averageWeight)) #cURL-script expects strings, so we return a string
#scale() #This is here for testing of this individual script